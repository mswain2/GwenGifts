<?php
session_cache_expire(30);
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('America/New_York');

// check RBAC
if (isset($_SESSION['access_level']) && $_SESSION['access_level'] >= 2) {
    $isEventManager = true;
} else {
    header('Location: index.php');
    die();  
}

// Get current fiscal year
$currentMonth = date("m");
$currentYear = date("Y");
$fiscalYearStart = ($currentMonth >= 10) ? $currentYear : $currentYear - 1;
$fiscalYearEnd = $fiscalYearStart + 1;

// Clear saved filters when arriving fresh from the dashboard
if (isset($_GET['reset'])) {
    unset($_SESSION['report_filters']);
}

// Restore saved filters from session
$rf = $_SESSION['report_filters'] ?? [];
$resetStorage = isset($_GET['reset']);
function old($key, $default = '') {
    global $rf;
    return htmlspecialchars($rf[$key] ?? $default, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gwyneth's Gift | Generate Report</title>
    <?php if ($resetStorage): ?>
    <script>sessionStorage.removeItem('report_filters');</script>
    <?php endif; ?>
    <script src="js/report-filters.js" defer></script>
    <link href="css/normal_tw.css" rel="stylesheet">
<?php
$tailwind_mode = true;
require_once('header.php');
?>

</head>
<body>
    <?php require_once('database/dbEvents.php'); ?>
    <?php require_once('database/dbPersons.php'); ?>

    <!-- Hero Section with Title -->
    <h1 style="color:white;">Generate Report</h1>

    <main>
        <?php
        $events = get_all_events_sorted_by_date_not_archived();
        function format_event_label($event) {
            $name = $event->getName();
            $startDate = $event->getStartDate();
            $ts = $startDate ? strtotime($startDate) : false;
            return $ts ? $name . ' — ' . date('M j, Y', $ts) : $name;
        }
        ?>

        <div class="main-content-box w-[80%] p-8">

            <!-- Form Title -->
            <div class="text-center mb-8">
                <h2>Generate Report</h2>
                <p class="sub-text">Generate reports on volunteer activity filtered by date, event, or volunteer. Reports are available in CSV or PDF format.</p>
            </div>

            <?php if (!empty($_SESSION['report_errors'])): ?>
                <?php foreach ($_SESSION['report_errors'] as $error): ?>
                    <div class="report-error"><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
                <?php unset($_SESSION['report_errors']); ?>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST" action="processReport.php" id="report-form">

                <!-- Report Type -->
                <div class="report-field">
                    <label for="type">Report Type</label>
                    <select id="type" name="type">
                        <option value="" disabled <?= empty($rf['type']) ? 'selected' : '' ?>>-- Select a report type --</option>
                        <option value="volunteer_hours" <?= old('type') === 'volunteer_hours' ? 'selected' : '' ?>>Volunteer Hours</option>
                        <option value="volunteer_participation" <?= old('type') === 'volunteer_participation' ? 'selected' : '' ?>>Volunteer Participation</option>
                        <option value="volunteer_growth" <?= old('type') === 'volunteer_growth' ? 'selected' : '' ?>>Volunteer Growth</option>
                        <option value="top_volunteers" <?= old('type') === 'top_volunteers' ? 'selected' : '' ?>>Top Volunteers</option>
                    </select>
                </div>

                <!-- Report Definitions (shown per report type) -->
                <div id="report-description" class="report-description" style="display:none;"></div>

                <!-- Dynamic Filter Fields -->
                <div id="dynamic-filters" style="display:none;">

                    <hr class="report-divider">
                    <h3 class="report-section-heading">Filters</h3>

                    <!-- Time Period -->
                    <div class="report-field" data-reports="volunteer_hours volunteer_participation volunteer_growth top_volunteers">
                        <label for="time_period">Time Period</label>
                        <select id="time_period" name="time_period">
                            <option value="weekly" <?= old('time_period', 'monthly') === 'weekly' ? 'selected' : '' ?>>Weekly</option>
                            <option value="monthly" <?= old('time_period', 'monthly') === 'monthly' ? 'selected' : '' ?>>Monthly</option>
                            <option value="yearly" <?= old('time_period', 'monthly') === 'yearly' ? 'selected' : '' ?>>Yearly</option>
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div class="report-field-row" data-reports="volunteer_hours volunteer_participation volunteer_growth top_volunteers">
                        <div class="report-field report-field-half">
                            <label for="date_from">Start Date</label>
                            <input type="date" id="date_from" name="date_from" value="<?= old('date_from', $fiscalYearStart . '-10-01') ?>">
                        </div>
                        <div class="report-field report-field-half">
                            <label for="date_to">End Date</label>
                            <input type="date" id="date_to" name="date_to" value="<?= old('date_to', date('Y-m-d')) ?>">
                        </div>
                    </div>

                    <!-- User Status -->
                    <div class="report-field" data-reports="volunteer_hours volunteer_participation volunteer_growth top_volunteers">
                        <label for="user_status">Volunteer Status</label>
                        <select id="user_status" name="user_status">
                            <option value="all" <?= old('user_status', 'Active') === 'all' ? 'selected' : '' ?>>All</option>
                            <option value="Active" <?= old('user_status', 'Active') === 'Active' ? 'selected' : '' ?>>Active</option>
                            <option value="Inactive" <?= old('user_status', 'Active') === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>

                    <!-- Event -->
                    <div class="report-field" data-reports="volunteer_hours volunteer_participation top_volunteers">
                        <label for="event_id_search">Event</label>
                        <div class="autocomplete-wrap">
                            <?php
                                $savedEventId = $rf['event_id'] ?? '';
                                $eventDisplay = '';
                                if (!empty($savedEventId) && $savedEventId !== 'all') {
                                    require_once('database/dbEvents.php');
                                    $savedEvent = retrieve_event($savedEventId);
                                    if ($savedEvent) {
                                        $eventDisplay = format_event_label($savedEvent);
                                    }
                                }
                                $savedEventHidden = (!empty($savedEventId) && $savedEventId !== 'all') ? $savedEventId : '';
                            ?>
                            <input type="text" id="event_id_search" placeholder="Search or select an event..." autocomplete="off"
                                value="<?= htmlspecialchars($eventDisplay) ?>">
                            <input type="hidden" id="event_id" name="event_id" value="<?= htmlspecialchars($savedEventHidden) ?>">
                            <div class="autocomplete-list" id="event_id_list">
                                <?php foreach ($events as $event) {
                                    $eid = htmlspecialchars($event->getID());
                                    $elabel = htmlspecialchars(format_event_label($event));
                                    echo "<div class='autocomplete-item' data-value='$eid'>$elabel</div>";
                                } ?>
                            </div>
                        </div>
                    </div>

                    <!-- Volunteer -->
                    <div class="report-field" data-reports="volunteer_hours volunteer_participation">
                        <label for="volunteer_search">Volunteer</label>
                        <div class="autocomplete-wrap">
                            <?php
                                $savedVolunteer = $rf['volunteer'] ?? '';
                                $volunteerDisplay = (!empty($savedVolunteer) && $savedVolunteer !== 'all')
                                    ? $savedVolunteer
                                    : '';
                            ?>
                            <input type="text" id="volunteer_search" placeholder="Search or select a volunteer..." autocomplete="off"
                                value="<?= htmlspecialchars($volunteerDisplay) ?>">
                            <input type="hidden" id="volunteer" name="volunteer" value="<?= htmlspecialchars($volunteerDisplay) ?>">
                            <div class="autocomplete-list" id="volunteer_list">
                                <?php
                                $volunteers = getall_volunteer_names();
                                if ($volunteers) {
                                    foreach ($volunteers as $name) {
                                        $safe = htmlspecialchars($name);
                                        echo "<div class='autocomplete-item' data-value='$safe'>$safe</div>";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Top N Limit -->
                    <div class="report-field" data-reports="top_volunteers">
                        <label for="top_n">Show Top</label>
                        <select id="top_n" name="top_n">
                            <option value="5" <?= old('top_n', '10') === '5' ? 'selected' : '' ?>>5</option>
                            <option value="10" <?= old('top_n', '10') === '10' ? 'selected' : '' ?>>10</option>
                            <option value="20" <?= old('top_n', '10') === '20' ? 'selected' : '' ?>>20</option>
                            <option value="50" <?= old('top_n', '10') === '50' ? 'selected' : '' ?>>50</option>
                        </select>
                    </div>

                </div>

                <!-- Format -->
                <div id="format-section" class="report-field" style="display:none; margin-top: 1.5rem;">
                    <label for="format">Export Format</label>
                    <select name="format" id="format">
                        <option value="csv" <?= old('format', 'csv') === 'csv' ? 'selected' : '' ?>>CSV (.csv)</option>
                        <option value="pdf" <?= old('format', 'csv') === 'pdf' ? 'selected' : '' ?>>PDF (.pdf)</option>
                    </select>
                </div>

                <!-- Submit -->
                <div id="submit-section" class="text-center pt-4" style="display:none;">
                    <input type="submit" value="Generate Report" class="submit-button">
                </div>

            </form>
        </div>

        <!-- Return to Dashboard -->
        <div class="text-center mb-8">
            <a href="index.php" class="return-button">Return to Dashboard</a>
        </div>

    </main>

</body>
</html>

