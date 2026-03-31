<?php
    session_cache_expire(30);
    session_start();

    ini_set("display_errors", 1);
    error_reporting(E_ALL);

    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        $accessLevel = $_SESSION['access_level'];
        $userID = $_SESSION['_id'];
    }

    if ($accessLevel < 1) {
        header('Location: login.php');
        die();
    }

    require_once('database/dbinfo.php');
    require_once('database/dbEvents.php');

    // Fetch existing board documents for the document links dropdown
    $con = connect();
    $docResult = mysqli_query($con, "SELECT id, doc_name FROM boarddocuments WHERE deleted = 0 ORDER BY doc_name ASC");
    $boardDocs = [];
    if ($docResult) {
        while ($row = mysqli_fetch_assoc($docResult)) {
            $boardDocs[] = $row;
        }
    }

    $errors = [];
    $formData = []; // preserve form values on error

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once('include/input-validation.php');

        $args = sanitize($_POST, null);
        $required = array("name", "abbr", "date", "start-time", "end-time");

        $formData = $args; // save for re-fill

        if (!wereRequiredFieldsSubmitted($args, $required)) {
            $errors[] = 'Please fill in all required fields.';
        } else {
            $date      = validateDate($args['date']);
            $startTime = $args['start-time'];
            $endTime   = $args['end-time'];
            $timezone  = $args['timezone'] ?? 'America/New_York';

            if (!$date) {
                $errors[] = 'Invalid date.';
            }

            // SCRUM-53: Prevent past date/time
            $nowTz = new DateTime('now', new DateTimeZone($timezone));
            $meetingStart = new DateTime($date . ' ' . $startTime, new DateTimeZone($timezone));

            if ($meetingStart <= $nowTz) {
                $errors[] = 'Meeting start time cannot be in the past. Please choose a future date and time.';
            }

            // Validate end time is after start time
            $meetingEnd = new DateTime($date . ' ' . $endTime, new DateTimeZone($timezone));
            if ($meetingEnd <= $meetingStart) {
                $errors[] = 'End time must be after start time.';
            }

            // SCRUM-53: Check for scheduling conflicts with other board events
            if (empty($errors)) {
                $safeDate = mysqli_real_escape_string($con, $date);
                $safeStart = mysqli_real_escape_string($con, $startTime);
                $safeEnd   = mysqli_real_escape_string($con, $endTime);

                $conflictQuery = "SELECT name, startTime, endTime FROM dbevents
                                  WHERE board_event = 1
                                  AND startDate = '$safeDate'
                                  AND completed = 'N'
                                  AND NOT (endTime <= '$safeStart' OR startTime >= '$safeEnd')";
                $conflictResult = mysqli_query($con, $conflictQuery);

                if ($conflictResult && mysqli_num_rows($conflictResult) > 0) {
                    $conflictRow = mysqli_fetch_assoc($conflictResult);
                    $errors[] = 'Scheduling conflict! "' . htmlspecialchars($conflictRow['name']) . '" is already scheduled on ' . $date . ' from ' . $conflictRow['startTime'] . ' to ' . $conflictRow['endTime'] . '. Please choose a different time.';
                }
            }

            if (empty($errors)) {
                // Build description with timezone note
                $description = $args['description'] ?? '';
                $tzLabel = $timezone;

                // Handle associated document links
                $selectedDocs = isset($_POST['doc_links']) ? (array)$_POST['doc_links'] : [];
                $docUrl = trim($args['doc_url'] ?? '');
                if ($docUrl) {
                    $description .= ($description ? "\n" : '') . 'Related URL: ' . $docUrl;
                }
                if (!empty($selectedDocs)) {
                    $docNames = [];
                    foreach ($boardDocs as $bd) {
                        if (in_array($bd['id'], $selectedDocs)) {
                            $docNames[] = $bd['doc_name'];
                        }
                    }
                    if ($docNames) {
                        $description .= ($description ? "\n" : '') . 'Related Documents: ' . implode(', ', $docNames);
                    }
                }

                $endDate = date('Y-m-d', strtotime($date . ' ' . $endTime));

                $args['startDate']  = $date;
                $args['endDate']    = $endDate;
                $args['startTime']  = $startTime;
                $args['endTime']    = $endTime;
                $args['end-time']   = $endTime;
                $args['type']       = 'Normal';
                $args['access']     = 'Public';
                $args['capacity']   = 999;
                $args['is_recurring']             = 0;
                $args['recurrence_type']          = null;
                $args['recurrence_interval_days'] = null;
                $args['series_id']                = null;
                $args['board_event']              = 1;
                $args['description']              = $description;
                if (!isset($args['location'])) $args['location'] = '';

                $id = create_event($args);
                if (!$id) {
                    $errors[] = 'Failed to create meeting. Please try again.';
                } else {
                    header('Location: eventSuccess.php');
                    exit();
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>Gwyneth's Gift | Schedule Board Meeting</title>
        <style>
            .error-list {
                background: #f8d7da;
                color: #721c24;
                border-radius: 8px;
                padding: 12px 20px;
                margin-bottom: 20px;
                font-weight: 700;
            }
            .error-list ul { margin: 8px 0 0 20px; }
            .hint { font-size: 13px; color: #828282; margin-top: 4px; }
            .doc-multiselect {
                width: 100%;
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                padding: 6px;
                font-size: 14px;
                min-height: 80px;
            }
        </style>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Schedule Board Meeting</h1>
        <main class="date">
            <h2>New Board Meeting Form</h2>

            <?php if (!empty($errors)): ?>
                <div class="error-list">
                    <strong>Please fix the following:</strong>
                    <ul>
                        <?php foreach ($errors as $e): ?>
                            <li><?php echo htmlspecialchars($e); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form id="board-meeting-form" method="POST">

                <div class="event-sect">
                    <label for="name">* Meeting Title</label>
                    <input type="text" id="name" name="name" required
                           placeholder="e.g. Q2 Board Review"
                           value="<?php echo htmlspecialchars($formData['name'] ?? ''); ?>">

                    <label for="abbr">* Abbreviated Name (20 character max)</label>
                    <input type="text" id="abbr" name="abbr" maxlength="20" required
                           placeholder="Appears on calendar"
                           value="<?php echo htmlspecialchars($formData['abbr'] ?? ''); ?>">
                </div>

                <div class="event-sect">
                    <div class="event-datetime">
                        <div class="event-time">
                            <div class="event-date">
                                <label for="date">* Date</label>
                                <input type="date" id="date" name="date"
                                       min="<?php echo date('Y-m-d'); ?>" required
                                       value="<?php echo htmlspecialchars($formData['date'] ?? ''); ?>">
                            </div>
                            <div class="event-date">
                                <label for="start-time">* Start Time</label>
                                <input type="time" id="start-time" name="start-time" required
                                       value="<?php echo htmlspecialchars($formData['start-time'] ?? ''); ?>">
                            </div>
                            <div class="event-date">
                                <label for="end-time">* End Time</label>
                                <input type="time" id="end-time" name="end-time" required
                                       value="<?php echo htmlspecialchars($formData['end-time'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="event-sect">
                    <label for="timezone">* Time Zone</label>
                    <select id="timezone" name="timezone">
                        <?php
                        $timezones = [
                            'America/New_York'   => 'Eastern Time (ET)',
                            'America/Chicago'    => 'Central Time (CT)',
                            'America/Denver'     => 'Mountain Time (MT)',
                            'America/Los_Angeles'=> 'Pacific Time (PT)',
                            'America/Anchorage'  => 'Alaska Time (AKT)',
                            'Pacific/Honolulu'   => 'Hawaii Time (HT)',
                        ];
                        $selectedTz = $formData['timezone'] ?? 'America/New_York';
                        foreach ($timezones as $tz => $label) {
                            $sel = ($tz === $selectedTz) ? 'selected' : '';
                            echo "<option value=\"$tz\" $sel>$label</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="event-sect">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location"
                           placeholder="e.g. Conference Room A or Zoom link"
                           value="<?php echo htmlspecialchars($formData['location'] ?? ''); ?>">
                </div>

                <div class="event-sect">
                    <label for="description">Notes / Agenda</label>
                    <input type="text" id="description" name="description"
                           placeholder="Optional agenda or notes"
                           value="<?php echo htmlspecialchars($formData['description'] ?? ''); ?>">
                </div>

                <?php if (!empty($boardDocs)): ?>
                <div class="event-sect">
                    <label for="doc_links">Associated Documents (optional)</label>
                    <select id="doc_links" name="doc_links[]" multiple class="doc-multiselect">
                        <?php foreach ($boardDocs as $bd): ?>
                            <option value="<?php echo (int)$bd['id']; ?>"
                                <?php echo (!empty($formData['doc_links']) && in_array($bd['id'], (array)$formData['doc_links'])) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($bd['doc_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="hint">Hold Ctrl (Windows) or Cmd (Mac) to select multiple documents.</p>
                </div>
                <?php endif; ?>

                <div class="event-sect">
                    <label for="doc_url">Associated URL (optional)</label>
                    <input type="url" id="doc_url" name="doc_url"
                           placeholder="https://example.com/meeting-agenda"
                           value="<?php echo htmlspecialchars($formData['doc_url'] ?? ''); ?>">
                </div>

                <input type="submit" value="Schedule Meeting" style="width:100%;">

            </form>
            <a class="button cancel" href="calendar.php" style="margin-top: -.5rem">Return to Calendar</a>
        </main>

        <script>
            // Auto-set end time min when start time changes
            document.getElementById('start-time').addEventListener('input', function() {
                const endEl = document.getElementById('end-time');
                if (this.value) {
                    endEl.min = this.value;
                    if (endEl.value && endEl.value <= this.value) {
                        endEl.value = '';
                    }
                }
            });
        </script>
    </body>
</html>
