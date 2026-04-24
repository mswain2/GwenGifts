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

$today       = strtotime(date("Y-m-d"));
$previousDay = date('Y-m-d', strtotime($dayStr . ' -1 day'));
$nextDay     = date('Y-m-d', strtotime($dayStr . ' +1 day'));

require_once('database/dbEvents.php');
require_once('database/dbPersons.php');

$uid = isset($_GET['uid']) ? $_GET['uid'] : (isset($_SESSION['_id']) ? $_SESSION['_id'] : '');
$al  = isset($_GET['al'])  ? (int)$_GET['al']  : (isset($_SESSION['access_level']) ? (int)$_SESSION['access_level'] : 0);

$allEvents = fetch_events_in_date_range($dayStr, $dayStr);
$dayEvents = isset($allEvents[$dayStr]) ? $allEvents[$dayStr] : [];

$formattedDate = date('l, F j, Y', $dayEpoch);
?>

<div style="padding: 1rem;">

    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
        <a href="#" onclick="loadDailyView('<?php echo $previousDay; ?>', '<?php echo htmlspecialchars($uid); ?>', <?php echo $al; ?>)" 
           style="color:var(--main-color); font-size:1.5rem; text-decoration:none; padding:0.25rem 0.75rem;">&#8249;</a>
        <h2 style="font-size:1.1rem; font-weight:600; color:var(--main-color);">
            <?php echo htmlspecialchars($formattedDate); ?>
        </h2>
        <a href="#" onclick="loadDailyView('<?php echo $nextDay; ?>', '<?php echo htmlspecialchars($uid); ?>', <?php echo $al; ?>)"
           style="color:var(--main-color); font-size:1.5rem; text-decoration:none; padding:0.25rem 0.75rem;">&#8250;</a>
    </div>

    <?php if (!empty($dayEvents)): ?>
        <table class="general" style="width:100%;">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Time</th>
                    <th>Type</th>
                    <th>Capacity</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dayEvents as $info):
                    $eventID   = $info['id'];
                    $title     = htmlspecialchars($info['name']);
                    $startTime = htmlspecialchars($info['startTime']);
                    $endTime   = htmlspecialchars($info['endTime']);
                    $type      = htmlspecialchars($info['type']);
                    $capacity  = (int)$info['capacity'];
                    $isBoardEvent = !empty($info['board_event']) && $info['board_event'] == 1;

                    if (is_archived($eventID)) {
                        if ($al < 2) continue;
                    }

                    $signups    = fetch_event_signups($eventID);
                    $numSignups = count($signups);
                    $isSignedUp = $uid ? check_if_signed_up($eventID, $uid) : false;

                    $rowStyle   = $isBoardEvent ? 'background-color:#e8eef5;' : '';
                    $titleColor = $isBoardEvent ? 'color:#1a3a6b;font-weight:700;' : '';
                ?>
                <tr style="<?php echo $rowStyle; ?>">
                    <td>
                        <a href="event.php?id=<?php echo $eventID; ?>" 
                           class="event-link" style="<?php echo $titleColor; ?>">
                            <?php echo $title; ?>
                            <?php if ($isBoardEvent): ?>
                                <span style="font-size:11px;background:#1a3a6b;color:white;padding:1px 6px;border-radius:10px;margin-left:4px;">Board</span>
                            <?php endif; ?>
                        </a>
                    </td>
                    <td style="white-space:nowrap;"><?php echo $startTime . ' – ' . $endTime; ?></td>
                    <td><?php echo $type; ?></td>
                    <td>
                        <?php if ($numSignups >= $capacity): ?>
                            <span class="full-capacity">Full</span>
                        <?php else: ?>
                            <?php echo $numSignups . ' / ' . $capacity; ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($uid): ?>
                            <?php if ($isSignedUp): ?>
                                <span style="color:#4CAF50;font-weight:700;">✓ Signed Up</span>
                            <?php elseif ($numSignups < $capacity): ?>
                                <a href="eventSignUp.php?id=<?php echo $eventID; ?>&event_name=<?php echo urlencode($info['name']); ?>"
                                   class="button" style="width:auto;padding:0.3rem 0.8rem;margin:0;font-size:0.85rem;">
                                    Sign Up
                                </a>
                            <?php else: ?>
                                <span style="color:#cc0000;">Full</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="login.php" style="font-size:0.85rem;">Login to Sign Up</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align:center;color:#828282;padding:2rem 0;">No events on this day.</p>
    <?php endif; ?>

</div>

<script>
function loadDailyView(date, uid, al) {
    if (typeof loadView === 'function') {
        let url = 'calendar-view_daily.php?month=' + date;
        if (uid) url += '&uid=' + encodeURIComponent(uid) + '&al=' + al;
        loadView(url);
    }
}
</script>