<?php
session_start();

ini_set("display_errors", 1);
error_reporting(E_ALL);

// Check access levels and initialize user data
$loggedIn = false;
$accessLevel = 0;
$userID = null;
$userType = 'volunteer';
if (isset($_SESSION['_id'])) {
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
}

require_once('include/input-validation.php');
require_once('database/dbEvents.php');
require_once('database/dbPersons.php');
require_once('include/output.php');
require_once('domain/Person.php');

// Gets user type
if (isset($_SESSION['_id'])) {
    if ($_SESSION['_id'] === 'vmsroot') {
        $userType = 'superadmin';
    } else {
        $person = retrieve_person($_SESSION['_id']);
        if ($person) $userType = $person->get_type();
    }
}

if (!$loggedIn) {
    header('Location: login.php');
    die();
}

$isManager = in_array($userType, ['event_manager', 'board_member', 'admin', 'superadmin']);

//Approve or reject hours if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isManager) {
    $action     = $_POST['action']     ?? null;
    $personID   = $_POST['personID']   ?? null;
    $eventID    = $_POST['eventID']    ?? null;
    $startTime  = $_POST['start_time'] ?? null;

    if ($action && $personID && $eventID && $startTime) {
        $newStatus = 'approved';
        $connection = connect(); 

        $personID  = mysqli_real_escape_string($connection, $personID);
        $eventID   = mysqli_real_escape_string($connection, $eventID);
        $startTime = mysqli_real_escape_string($connection, $startTime);

        $query = "UPDATE dbpersonhours 
                  SET status = '$newStatus'
                  WHERE personID = '$personID' 
                  AND eventID = '$eventID' 
                  AND start_time = '$startTime'
                  LIMIT 1";
        mysqli_query($connection, $query);

        if ($newStatus === 'approved') {
            $durQuery = "SELECT start_time, end_time FROM dbpersonhours 
                         WHERE personID = '$personID' 
                         AND eventID = '$eventID' 
                         AND start_time = '$startTime'
                         LIMIT 1";

            $durResult = mysqli_query($connection, $durQuery);
            $durRow = mysqli_fetch_assoc($durResult);

            if ($durRow && $durRow['end_time']) {
                $hours = (strtotime($durRow['end_time']) - strtotime($durRow['start_time'])) / 3600;

                if ($hours > 0) {
                    add_hours_to_person($personID, $hours);
                }
            }
        }

        mysqli_close($connection);

        header('Location: eventList.php?username=' . urlencode($personID));
        exit();
    }
}

if ($isManager) {
    if (isset($_GET['username'])) {
        $username = $_GET['username'];
    } else {
        header('Location: editHours.php');
        die();
    }
} else {
    // Volunteers only see their own hours
    $username = $_SESSION['_id'];
}

// Fetch eventIDs attended by the user
$event_ids = get_attended_event_ids($username);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once('universal.inc'); ?>
    <link rel="stylesheet" href="css/editprofile.css" type="text/css" />
    <title>Gwyneth's Gift | User Events</title>
</head>
<body>
<?php require_once('header.php'); ?>
<?php if ($isManager): ?>
        <?php
            $viewed_person = retrieve_person($username);
            $name = $viewed_person
                ? $viewed_person->get_first_name() . ' ' . $viewed_person->get_last_name() . "'s"
                : htmlspecialchars($username) . "'s";
        ?>
        <h1 style="color:white;"><?php echo $name ?> Event Attendance Log</h1>
    <?php else: ?>
        <h1 style="color:white;">Your Event Attendance Log</h1>
    <?php endif ?>
 
    <main class="general">
        <?php if (!empty($event_ids)): ?>
 
            <?php foreach ($event_ids as $event_id): ?>
 
                <?php $event = retrieve_event2($event_id); ?>
                <?php if (!$event) continue;?>
 
                <fieldset class="section-box">
                    <h2><?php echo htmlspecialchars($event['name']) ?></h2>
 
                    <?php
                        $shifts = get_check_in_outs($username, $event['id']);
                    ?>
 
                    <table class="general">
                        <tr>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Duration (min)</th>
                            <th>Status</th>
                            <?php if ($isManager): ?>
                                <th>Edit</th>
                                <th>Delete</th>
                                <th>Approve</th>
                            <?php endif; ?>
                        </tr>
 
                        <?php foreach ($shifts as $shift): ?>
                            <?php
                                $start_parts = explode(' ', $shift['start_time']);
                                $end_parts   = explode(' ', $shift['end_time']);

                                $start_epoch = strtotime($shift['start_time']);
                                $end_epoch   = strtotime($shift['end_time']);
                                $duration    = ($end_epoch - $start_epoch) / 60;
 
                                $status = $shift['status'] ?? 'pending';
                            ?>
                            <tr>
                                <td><?php echo isset($start_parts[1]) ? htmlspecialchars($start_parts[1]) : ''; ?></td>
                                <td><?php echo isset($end_parts[1])   ? htmlspecialchars($end_parts[1])   : ''; ?></td>
                                <td><?php echo round($duration, 2); ?></td>
                                <td><?php echo htmlspecialchars(ucfirst($status)); ?></td>
 
                                <?php if ($isManager): ?>
                                    <td>
                                        <?php if ($status !== 'approved'): ?>
                                        <form method="GET" action="editTimes.php" style="display:inline;">
                                            <input type="hidden" name="eventId"    value="<?php echo htmlspecialchars($event['id']); ?>" />
                                            <input type="hidden" name="user"       value="<?php echo htmlspecialchars($username); ?>" />
                                            <input type="hidden" name="start_time" value="<?php echo htmlspecialchars($shift['start_time']); ?>" />
                                            <input type="hidden" name="end_time"   value="<?php echo htmlspecialchars($shift['end_time']); ?>" />
                                            <button type="submit" class="button edit-button" style="width:80px; height:36px; line-height:36px; padding:0 12px; display:inline-block;">Edit</button>
                                        </form>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <button class="button danger" style="width:80px; height:36px; line-height:36px; padding:0 12px; display:inline-block;"
                                            onclick="confirmAction('<?php echo $event['id']; ?>', '<?php echo addslashes($shift['start_time']); ?>', '<?php echo addslashes($shift['end_time']); ?>')">
                                            Delete
                                        </button>
                                    </td>
 
                                    <td>
                                        <?php if ($status !== 'approved'): ?>
                                        <form method="POST" style="display:inline;" onsubmit="event.preventDefault(); confirmApprove(this);">
                                            <input type="hidden" name="action"     value="approve">
                                            <input type="hidden" name="personID"   value="<?php echo htmlspecialchars($username); ?>">
                                            <input type="hidden" name="eventID"    value="<?php echo htmlspecialchars($event['id']); ?>">
                                            <input type="hidden" name="start_time" value="<?php echo htmlspecialchars($shift['start_time']); ?>">
                                            <button type="submit" class="button success" style="width:90px; height:36px; line-height:36px; padding:0 12px; display:inline-block;">Approve</button>
                                        </form>
                                        <?php else: ?>
                                            <span style="color:#5cb85c;"> Approved</span>
                                        <?php endif; ?>
                                    </td>
 
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </table>
 
                    <?php if ($isManager): ?>
                        <form method="GET" action="setTimes.php">
                            <input type="hidden" name="eventID"   value="<?php echo htmlspecialchars($event['id']); ?>" />
                            <input type="hidden" name="eventName" value="<?php echo htmlspecialchars($event['name']); ?>" />
                            <input type="hidden" name="userID"    value="<?php echo htmlspecialchars($username); ?>" />
                            <center><button class="button success" style="width: 50%; margin: 25px;">Add a new check-in</button></center>
                        </form>
                    <?php endif; ?>
                    </fieldset>

                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-events-message">No events attended by <?php echo htmlspecialchars($username); ?>.</p>
            <?php endif; ?>
            <a class="button cancel" href="index.php" style="margin-top: -.5rem">Return to Dashboard</a>
        </main>
        <script>
    function confirmAction(eventID, start_time, end_time) {
        if (confirm("Are you sure you want to delete this check-in?")) {
            window.location.href = 'deleteTimes.php?userID=<?php echo htmlspecialchars($username); ?>&eventID=' + eventID + '&start_time=' + encodeURIComponent(start_time) + '&end_time=' + encodeURIComponent(end_time);
        }
    }
    function confirmApprove(formEl) {
        if (confirm("Are you sure you want to approve these hours?")) {
            formEl.submit();
        }
    }
    </script>
</body> -->
</html>