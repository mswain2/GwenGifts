<?php
    // ── SESSION SETUP ──
    // Cache session data for 30 minutes before it expires
    session_cache_expire(30);
    // Start (or resume) the user's session so we can track login status
    session_start();

    // Set the timezone so all date/time functions use Eastern Time
    date_default_timezone_set("America/New_York");

    // ── ACCESS CONTROL ──
    // Ensure user is logged in (access_level >= 1 means authenticated)
    // Currently disabled — uncomment the two lines below to enforce login
    if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 1) {
        //header('Location: login.php');
        //die();
    }

    // ── DETERMINE WHICH MONTH TO DISPLAY ──
    // If no "month" query parameter is provided, default to the current month
    if (!isset($_GET['month'])) {
        $month = date("Y-m-d"); // e.g. "2026-02-25"
    } else {
        $month = $_GET['month'];
    }
    
    // Extract the 4-digit year and 2-digit month from the date string
    $year = substr($month, 0, 4);        // e.g. "2026"
    $month2digit = substr($month, 5, 2); // e.g. "02"

    // Get today's date as a Unix timestamp (used to highlight "today" on the calendar)
    $today = strtotime(date("Y-m-d"));

    // ── CALCULATE KEY CALENDAR DATES ──
    // Convert the month string to a Unix timestamp for date arithmetic
    $month = strtotime($month);
    // Build the first day of the selected month (e.g. "2026-02-01") from the timestamp
    $first = strtotime(date('Y-m-01', $month));
    // Extract the current day number for the month-jumper input
    $day = date('j', $month);
    // Calculate timestamps for the previous and next months (for navigation arrows)
    $previousMonth = strtotime(date('Y-m-d', $month) . ' -1 month');
    $nextMonth = strtotime(date('Y-m-d', $month) . ' +1 month');
    // If the month parameter was invalid (couldn't be parsed), redirect to today's month
    if (!$month) {
        header('Location: calendar.php?month=' . date("Y-m-d"));
        die();
    }
    // ── BUILD THE CALENDAR GRID BOUNDARIES ──
    // Start from the 1st of the month
    $calendarStart = $first;
    // Walk backwards to find the nearest Sunday so the grid always starts on Sunday
    // date('w') returns 0 for Sunday, 1 for Monday, etc.
    while (date('w', $calendarStart) > 0) {
        $calendarStart = strtotime(date('Y-m-d', $calendarStart) . ' -1 day');
    }
    // A 5-week calendar grid = 35 days (5 rows × 7 columns), so end is start + 34 days
    $calendarEnd = date('Y-m-d', strtotime(date('Y-m-d', $calendarStart) . ' +34 day'));
    $calendarEndEpoch = strtotime($calendarEnd);
    $weeks = 5; // Default to 5 rows
    // If the day after the 5th row still falls in the same month, we need a 6th row
    if (date('m', strtotime($calendarEnd . ' +1 day')) == date('m', $first)) {
        $calendarEnd = date('Y-m-d', strtotime($calendarEnd . ' +7 day'));
        $calendarEndEpoch = strtotime($calendarEnd);
        $weeks = 6;
    }
?>
<!-- ── HTML DOCUMENT START ── -->
<!DOCTYPE html>
<html>
    <head>
        <!-- Load shared site-wide styles, meta tags, and configuration -->
        <?php require('universal.inc'); ?>
        <!-- Load the site header (navigation bar, logo, etc.) -->
        <?php require('header.php'); ?>
        <!-- jQuery library for DOM manipulation and AJAX -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Custom JS that handles rendering the calendar grid dynamically -->
        <script src="js/calendar.js"></script>
        <!-- JS for switching between Month / Week / Day views (deferred until DOM is ready) -->
        <script src="js/view-switcher.js" defer></script>
        <title>Gwyneth's Gift | Events Calendar</title>
        <style>.happy-toast { margin: 0 1rem 1rem 1rem; }</style>
    </head>
    <body>
        <!-- ── MONTH JUMPER DIALOG ──
             A hidden popup form that lets users quickly jump to a specific month/year.
             Toggled visible via JavaScript when the user clicks the calendar heading. -->
        <div id="month-jumper-wrapper" class="hidden"> 
            <form id="month-jumper">
                <p>Choose a month to jump to</p>
                <!-- Dropdown for selecting the month, plus a year and day input -->
                <div>
                    <select id="jumper-month">
                        <?php
                            // Generate <option> tags for each month (January=01 … December=12)
                            $months = [
                                'January', 'February', 'March', 'April',
                                'May', 'June', 'July', 'August',
                                'September', 'October', 'November', 'December'
                            ];
                            $digit = 1;
                            foreach ($months as $m) {
                                // Zero-pad to 2 digits (e.g. 1 → "01")
                                $month_digits = str_pad($digit, 2, '0', STR_PAD_LEFT);
                                // Pre-select the option that matches the currently viewed month
                                if ($month_digits == $month2digit) {
                                    echo "<option value='$month_digits' selected>$m</option>";
                                } else {
                                    echo "<option value='$month_digits'>$m</option>";
                                }
                                $digit++;
                            }
                        ?>
                    </select>
                    <input id="jumper-year" type="number" value="<?php echo $year ?>" required min="2023">
                    
                    <?php
                    // Calculate the last day of the selected month (e.g. 28, 30, or 31)
                    // Used as the "max" attribute on the day input to prevent invalid dates
                    $finalDayofMonth = date("t", $month);
                    ?>
                    <!-- Day input — constrained between 1 and the last valid day of the month -->
                    <input id="jumper-day" type="number" value="<?php echo $day?>" required min="1" max="<?php echo $finalDayofMonth?>" >
                </div>
                <!-- Hidden input that JS populates with the final date value before submitting -->
                <input type="hidden" id="jumper-value" name="month" value="<?php echo 'test' ?>">
                <input type="submit" value="View">
                <button id="jumper-cancel" class="cancel" type="button">Cancel</button>
            </form>
        </div>
        
        <!-- ── VIEW FILTER DIALOG ──
             Hidden popup that lets users switch between Month, Week, and Day views.
             TODO: Wire up filtering logic for weekly/daily calendar views. -->
        <div id="view-filter-wrapper" class="hidden"> 
            <form id="filter-view">
                <p>View by month, week, or day?</p>
                <div>
                    <select id="views">
                        <?php
                        // Generate dropdown options for the three calendar view modes
                        $views = ['Month', 'Week', 'day'];
                        $digit = 1;
                            foreach ($views as $m) {
                                $view_digits = str_pad($digit, 2, '0', STR_PAD_LEFT);
                                // Mark the currently active view as "selected"
                                if ($view_digits == $view2digit) {
                                    echo "<option value='$view_digits' selected>$m</option>";
                                } else {
                                    echo "<option value='$view_digits'>$m</option>";
                                }
                                $digit++;
                            }
                        ?>
                    </select>
                    <!-- TODO: Make this show view. Might edit this to use icons?-->
                    <input id="calendar-view" type="number" value="<?php echo $year ?>" required min="2023">
                </div>
                <input type="hidden" id="jumper-value" name="month" value="<?php echo 'VIEW FILTER TEST' ?>">
                <input type="submit" value="View"> 
                <button id="filter-cancel" class="cancel" type="button">Cancel</button>
            </form> 

        </div>
        <!-- ── MAIN CALENDAR CONTENT ── -->
        <main class="calendar-view">
            
            <!-- Calendar header with left/right arrows for month navigation and the current month label -->
            <h1 class='calendar-header' style="height: 75px;">
                <!-- Arrow to navigate to the previous month -->
                <img id="previous-month-button" src="images/arrow-back.png" data-month="<?php echo date("Y-m-d", $previousMonth); ?>">
                <!-- Display the currently viewed month and year (e.g. "Events - February 2026") -->
                <span id="calendar-heading-month" style="font-weight: 700; font-size: 32px; color: white; letter-spacing: 0.02em;">Events &mdash; <?php echo date('F Y', $month); ?></span>
                <!-- Arrow to navigate to the next month -->
                <img id="next-month-button" src="images/arrow-forward.png" data-month="<?php echo date("Y-m-d", $nextMonth); ?>">
            </h1>

            <!-- ── VIEW SWITCHER TOOLBAR ──
                 Hamburger menu with icon buttons to switch between different calendar views:
                 List view, Monthly grid, Weekly view, and Daily view.
                 The checkbox acts as a CSS toggle to show/hide the menu. -->
            <div class="filter-wrapper">
                <div class="filter-menu-wrapper">
                    <input type="checkbox" /> <!-- Checkbox toggle: when checked, the filter menu is visible -->
                    <div class="filter-menu"><img class="filter-menu-icon" src="./images/menu.png" style="filter: invert(1);"></div>
                    <div class="calendar-filter" style="height: 3rem;">
                        <!-- Each icon triggers a JS handler to swap the calendar display mode -->
                        <img id="list-view-button" class="filter-button" src="images/list-solid.svg" alt="List view">
                        <img id="calendar-view-button" class="filter-button" src="images/view-calendar.png" alt="Calendar view">
                        <img id="calendar-weekly-view-button" class="filter-button" src="images/new-event.png" alt="Calendar view: Weekly">
                        <img id="calendar-day-view-button" class="filter-button" src="images/day-sunny-svgrepo-com.svg" alt="Calendar view: Day">
                    </div>
                </div>
                <!-- <div class="time-filter" class="hidden">  will later be used for week<->month --
                    <img id="day-view-button" class="filter-button" class="hidden" src="images/day-view.png" alt="Day view">
                    <img id="week-view-button" class="filter-button" class="hidden" src="images/week-view.png" alt="week view">
                    <img id="month-view-button" class="filter-button" class="hidden" src="images/month-view.png" alt="month view">
                </div> -->
            </div>

            <!-- <input type="date" id="month-jumper" value="<?php echo date('Y-m-d', $month); ?>" min="2023-01-01"> -->
            <!-- ── SUCCESS TOAST MESSAGES ──
                 Show a green confirmation banner when the user is redirected here
                 after successfully deleting, completing, or canceling an event.
                 The message type is determined by query-string parameters. -->
            <?php if (isset($_GET['deleteSuccess'])) : ?>
                <div class="happy-toast">Event deleted successfully.</div>
            <?php elseif (isset($_GET['completeSuccess'])) : ?>
                <div class="happy-toast">Event completed successfully.</div>
            <?php elseif (isset($_GET['cancelSuccess'])) : ?>
                <div class="happy-toast">Event canceled successfully.</div>
                <?php elseif (isset($_GET['cancelSuccess'])) : ?>
                <div class="happy-toast">Event canceled successfully.</div>
            <?php endif ?>
                <!--Here we lay out the week. Table for view. Will likely need to switch this out for each view.-->

                <!-- to be replaced -Blue -->

            <!-- ── CALENDAR GRID CONTAINER ──
                 This div is the target where JS (calendar.js) dynamically renders
                 the monthly calendar table via AJAX. The commented-out table below
                 is the original server-side PHP rendering (kept for reference). -->
            <div class="table-wrapper" id="event-viewer">
                <!-- <table id="calendar"-->

                <!-- to be replaced -Blue -->

            <div class="table-wrapper" id="event-viewer">
                <!-- <table id="calendar">
                    <thead>
                        <tr>
                            <th>Sunday</th>
                            <th>Monday</th>
                            <th>Tuesday</th>
                            <th>Wednesday</th>
                            <th>Thursday</th>
                            <th>Friday</th>
                            <th>Saturday</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        // ── RENDER CALENDAR ROWS (old server-side approach, now commented out) ──
                        // Set the starting date to the first Sunday of the grid
                        $date = $calendarStart;
                        $start = date('Y-m-d', $calendarStart);
                        $end = date('Y-m-d', $calendarEndEpoch);
                        // Include database helper and fetch all events within the visible date range
                        require_once('database/dbEvents.php');
                        $events = fetch_events_in_date_range($start, $end/*, $loggedIn*/);
                        // Loop through each week row
                        for ($week = 0; $week < $weeks; $week++) {
                            echo '
                                <tr class="calendar-week">
                            ';
                            // Loop through each day of the week (Sun=0 … Sat=6)
                            for ($day = 0; $day < 7; $day++) {
                                $extraAttributes = '';
                                $extraClasses = '';
                                // Highlight today's date with a special CSS class
                                if ($date == $today) {
                                    $extraClasses = ' today';
                                }
                                // Grey out days that belong to the previous or next month
                                if (date('m', $date) != date('m', $month)) {
                                    $extraClasses .= ' other-month';
                                    $extraAttributes .= ' data-month="' . date('Y-m-d', $date) . '"';
                                }
                                // Build the HTML string for all events on this day
                                $eventsStr = '';
                                $e = date('Y-m-d', $date);

                                // If there are events on this date, render each one
                                if (isset($events[$e])) {
                                    $dayEvents = $events[$e];
                                    foreach ($dayEvents as $info) {

                                        $backgroundCol = 'var(--calendar-event-color)'; // default color

                                        // ── COLOR-CODE EVENTS BASED ON STATUS ──
                                        if(isset($_SESSION['access_level'])) {
                                            // Grey out archived events; hide them entirely from regular users
                                            if (is_archived($info['id'])) { // archived event
                                                if ($_SESSION['access_level'] < 2) {
                                                    continue; // regular users cannot see archived events
                                                }
                                                $backgroundCol = 'rgba(170, 170, 170, 1)'; // grey for archived
                                        if(isset($_SESSION['access_level'])) {
                                            if (is_archived($info['id'])) { // archived event
                                                if ($_SESSION['access_level'] < 2) {
                                                    continue; // users cannot see archived events
                                                }
                                                $backgroundCol = 'rgba(170, 170, 170, 1)'; // grey for archived

                                            // Green if the logged-in user has signed up for this event
                                            } elseif (check_if_signed_up($info['id'], $_SESSION['_id'])) {// user is signed-up for event
                                                $backgroundCol = '#4CAF50';
                                            }
                                            // Render event link for logged-in users (includes their user ID)
                                            $eventsStr .= '<a class="calendar-event" style="background-color: ' . $backgroundCol . '" href="event.php?id=' . $info['id'] . '&user_id=' . $_SESSION['_id'] . '">' . htmlspecialchars_decode($info['name']) . '</a>';

                                        } else {
                                            // Render event link for guests (no session, user_id = "guest")
                                            $eventsStr .= '<a class="calendar-event" style="background-color: ' . $backgroundCol . '" href="event.php?id=' . $info['id'] . '&user_id=guest' . '">' . htmlspecialchars_decode($info['name']) . '</a>';
                                            }
                                            $eventsStr .= '<a class="calendar-event" style="background-color: ' . $backgroundCol . '" href="event.php?id=' . $info['id'] . '&user_id=' . $_SESSION['_id'] . '">' . htmlspecialchars_decode($info['name']) . '</a>';

                                        } else {
                                            $eventsStr .= '<a class="calendar-event" style="background-color: ' . $backgroundCol . '" href="event.php?id=' . $info['id'] . '&user_id=guest' . '">' . htmlspecialchars_decode($info['name']) . '</a>';
                                        }
                                        
                                        
                                    }
                                }
                                // Output the table cell for this calendar day
                                // Contains the day number and any event links
                                echo '<td class="calendar-day' . $extraClasses . '" ' . $extraAttributes . ' data-date="' . date('Y-m-d', $date) . '">
                                    <div class="calendar-day-wrapper">
                                        <p class="calendar-day-number">' . date('j', $date) . '</p>
                                        ' . $eventsStr . '
                                    </div>
                                </td>';
                                // Advance to the next day
                                $date = strtotime(date('Y-m-d', $date) . ' +1 day');
                            }
                            echo '
                                </tr>';
                        }}
                    ?>
                    </tbody>
                </table>-->
                
            </div>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">            
            
            <?php
            // ── EVENT COLOR LEGEND ──
            // Grey   = Archived event (only visible to admins)
            // Red    = Restricted event (requires special access)
            // Green  = User is signed up for this event
            // Blue   = Open / unrestricted event (anyone can sign up)
            ?>
            <!--<center>
            <p></p>
            <i class="fa-solid fa-circle legend-dot accent"></i>
                <span class="legend-label">Open Event</span>
            <i class="fa-solid fa-circle legend-dot green"></i>
                <span class="legend-label">Signed-Up</span>
            <i class="fa-solid fa-circle legend-dot gray"></i>
                <span class="legend-label">Archived Event</span>
            </center>
                            <p></p>-->
        
<!-- ── BACK TO DASHBOARD BUTTON ──
     Centered button that takes the user back to the main dashboard page. -->
<div style="display: flex; justify-content: center; align-items: center;">
<div style="margin-top: 1.5rem; margin-bottom: 1.5rem;">
    <a href="index.php" class="btn-muted">
    Return to Dashboard
  </a>
</div>
</div>

        </main>
    </body>
</html>