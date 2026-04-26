<?php
$sessionName = session_name();
$sessionId = isset($_COOKIE[$sessionName]) ? $_COOKIE[$sessionName] : '';
if ($sessionId) {
    session_id($sessionId);
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set("America/New_York");

if (isset($_GET['month']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['month'])) {
    $dayStr = $_GET['month'];
} else {
    $dayStr = date('Y-m-d');
}

$dayEpoch = strtotime($dayStr);
if (!$dayEpoch) {
    header('Location: calendar.php?month=' . date("Y-m-d"));
    exit;
}

$today = strtotime(date("Y-m-d"));
$previousWeek = strtotime(date('Y-m-d', $dayEpoch) . ' -7 days');
$nextWeek = strtotime(date('Y-m-d', $dayEpoch) . ' +7 days');
?>
<table id="calendar"
       data-current-month="<?php echo date('Y-m-d', $dayEpoch); ?>"
       data-prev-month="<?php echo date('Y-m-d', $previousWeek); ?>"
       data-next-month="<?php echo date('Y-m-d', $nextWeek); ?>">
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
    $calendarStart = $dayEpoch;
    while (date('w', $calendarStart) > 0) {
        $calendarStart = strtotime(date('Y-m-d', $calendarStart) . ' -1 day');
    }
    $start = date('Y-m-d', $calendarStart);

    $calendarEndEpoch = $calendarStart;
    while (date('w', $calendarEndEpoch) < 6) {
        $calendarEndEpoch = strtotime(date('Y-m-d', $calendarEndEpoch) . ' +1 day');
    }
    $end = date('Y-m-d', $calendarEndEpoch);

    require_once('database/dbEvents.php');
    $events = fetch_events_in_date_range($start, $end);

    echo '<tr class="calendar-week">';
    $date = $calendarStart;
    for ($day = 0; $day < 7; $day++) {
        $extraAttributes = '';
        $extraClasses = '';
        if (date('Y-m-d', $date) == date('Y-m-d', $today)) {
            $extraClasses = ' today';
        }
        if (date('m', $date) != date('m', $dayEpoch)) {
            $extraClasses .= ' other-month';
            $extraAttributes .= ' data-month="' . date('Y-m-d', $date) . '"';
        }
        $eventsStr = '';
        $e = date('Y-m-d', $date);

        if (isset($events[$e])) {
            $dayEvents = $events[$e];
            $maxEvents = 2;
            $eventCount = count($dayEvents);
            $displayEvents = array_slice($dayEvents, 0, $maxEvents);

            $uid = isset($_GET['uid']) ? $_GET['uid'] : (isset($_SESSION['_id']) ? $_SESSION['_id'] : '');
            $al  = isset($_GET['al'])  ? (int)$_GET['al']  : (isset($_SESSION['access_level']) ? (int)$_SESSION['access_level'] : 0);

            foreach ($displayEvents as $info) {
                $backgroundCol = 'var(--calendar-event-color)';
                if ($uid) {
                    if (is_archived($info['id'])) {
                        if ($al < 2) continue;
                        $backgroundCol = '#b0b0b0';
                    } elseif (!empty($info['board_event']) && $info['board_event'] == 1) {
                        $backgroundCol = '#1a3a6b';
                        if (check_if_signed_up($info['id'], $uid)) {
                            $backgroundCol = '#4CAF50';
                        }
                    } elseif (check_if_signed_up($info['id'], $uid)) {
                        $backgroundCol = '#4CAF50';
                    }
                    $eventsStr .= '<a class="calendar-event" style="background-color: ' . $backgroundCol . '" href="event.php?id=' . $info['id'] . '&user_id=' . htmlspecialchars($uid) . '">' . htmlspecialchars_decode($info['abbr_name']) . '</a>';
                } else {
                    $eventsStr .= '<a class="calendar-event" style="background-color: ' . $backgroundCol . '" href="event.php?id=' . $info['id'] . '&user_id=guest">' . htmlspecialchars_decode($info['abbr_name']) . '</a>';
                }
            }
            if ($eventCount > $maxEvents) {
                $remaining = $eventCount - $maxEvents;
                $eventsStr .= '<a class="calendar-event" style="background-color:#6b8caf;text-align:center;" href="viewAllEvents.php?date=' . $e . '">+ ' . $remaining . ' more</a>';
            }
        }
        echo '<td class="calendar-day' . $extraClasses . '" ' . $extraAttributes . ' data-date="' . date('Y-m-d', $date) . '">
            <div class="calendar-day-wrapper">
                <p class="calendar-day-number">' . date('j', $date) . '</p>
                ' . $eventsStr . '
            </div>
        </td>';
        $date = strtotime(date('Y-m-d', $date) . ' +1 day');
    }
    echo '</tr>';
    ?>
    </tbody>
</table>