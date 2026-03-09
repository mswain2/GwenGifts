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

    // Require login — same pattern as other files
    if ($accessLevel < 1) {
        header('Location: login.php');
        die();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once('include/input-validation.php');
        require_once('database/dbEvents.php');

        $args = sanitize($_POST, null);
        $required = array("name", "date", "start-time", "duration");

        if (!wereRequiredFieldsSubmitted($args, $required)) {
            echo 'bad form data';
            die();
        }

        $date      = validateDate($args['date']);
        $startTime = $args['start-time'];
        $duration  = (int)$args['duration']; // duration in minutes

        if (!$date || !$startTime || $duration < 1) {
            echo 'bad args';
            die();
        }

        // Calculate end time from start time + duration
        $startEpoch = strtotime($date . ' ' . $startTime);
        $endEpoch   = $startEpoch + ($duration * 60);
        $endTime    = date('H:i', $endEpoch);
        $endDate    = date('Y-m-d', $endEpoch); // handles meetings past midnight

        $args['startDate'] = $date;
        $args['endDate']   = $endDate;
        $args['startTime'] = $startTime;
        $args['endTime']   = $endTime;
        $args['end-time']  = $endTime;
        $args['type']      = 'Board Meeting';
        // IMPORTANT: currently, the access level is public but needs to changed to 'Board' once that access level is implemented
        // so for now, it will be visible to all
        $args['access']    = 'Public';
        // The board meeting is an event type and the bits of code below is just using default values to make it work, not relevant
        $args['capacity']  = 999; // capacity does not apply to board meetings here, set to default high value
        $args['is_recurring']             = 0;
        $args['recurrence_type']          = null;
        $args['recurrence_interval_days'] = null;
        $args['series_id']                = bin2hex(random_bytes(16));

        if (!isset($args['description'])) {
            $args['description'] = '';
        }
        if (!isset($args['location'])) {
            $args['location'] = '';
        }

        $id = create_event($args);
        if (!$id) {
            echo 'failed to create event';
            die();
        } else {
            header('Location: index.php');
            exit();
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>Whiskey Valor | Schedule Board Meeting</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1 style="color: white;">Schedule Board Meeting</h1>
        <main class="date">
            <h2>New Board Meeting Form</h2>
            <form id="board-meeting-form" method="POST">

                <div class="event-sect">
                    <label for="name">* Meeting Title</label>
                    <input type="text" id="name" name="name" required placeholder="e.g. Q2 Board Review">
                </div>

                <div class="event-sect">
                    <div class="event-datetime">
                        <div class="event-time">
                            <div class="event-date">
                                <label for="date">* Date</label>
                                <input type="date" id="date" name="date" min="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="event-date">
                                <label for="start-time">* Start Time</label>
                                <input type="time" id="start-time" name="start-time" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="event-sect">
                    <label for="duration">* Duration (minutes)</label>
                    <input type="number" id="duration" name="duration" min="1" required placeholder="e.g. 60">
                </div>

                <div class="event-sect">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" placeholder="e.g. Conference Room A or Zoom link">
                </div>

                <div class="event-sect">
                    <label for="description">Notes / Agenda</label>
                    <input type="text" id="description" name="description" placeholder="Optional agenda or notes">
                </div>

                <input type="submit" value="Schedule Meeting" style="width:100%;">

            </form>
            <a class="button cancel" href="index.php" style="margin-top: -.5rem">Return to Dashboard</a>
        </main>
    </body>
</html>