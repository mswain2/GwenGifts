<!--Looks like a combo of editHours.php and editEvent.php - Kenzie-->

<?php
    session_cache_expire(30);
    session_start();
    date_default_timezone_set("America/New_York");

    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    $userType = 'volunteer';
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
        $accessLevel = $_SESSION['access_level'];
        $userID = $_SESSION['_id'];
    }

    require_once('database/dbPersons.php');
    require_once('database/dbEvents.php');
    require_once('include/input-validation.php');

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

    $eventId = $_GET['eventId']    ?? null;
    $targetUser = $_GET['user']       ?? null;
    $oldStartTime = $_GET['start_time'] ?? null;
    $oldEndTime = $_GET['end_time']   ?? null;

    // Ensure fallback values or error handling
    if (!$eventId || !$oldStartTime || !$oldEndTime) {
        echo "Missing required data.";
        die(); // Stop further execution
    }

    $tsStart = strtotime($oldStartTime);
    $tsEnd   = strtotime($oldEndTime);
    
    $displayStart    = $tsStart ? date("l, F j, Y, g:i:s A", $tsStart) : "Invalid";
    $displayEnd      = $tsEnd   ? date("l, F j, Y, g:i:s A", $tsEnd)   : "Invalid";
    $sqlStart        = $tsStart ? date("Y-m-d H:i:s", $tsStart) : null;
    $sqlEnd          = $tsEnd   ? date("Y-m-d H:i:s", $tsEnd)   : null;
    $dateOnly        = $tsStart ? date("Y-m-d", $tsStart) : null;
    
    $error = '';
    $success = false;


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newStartRaw = trim($_POST['start-time'] ?? '');
    $newEndRaw   = trim($_POST['end-time']   ?? '');
 
    if (empty($newStartRaw) || empty($newEndRaw)) {
        $error = "Please fill in both start and end times.";
    } else {
        $validated = validate12hTimeRangeAndConvertTo24h($newStartRaw, $newEndRaw);
        if (!$validated) {
            $error = "Invalid time range. Make sure end time is after start time and format is correct (e.g. 12:00 PM).";
        } else {
            $newStart24 = $validated[0];
            $newEnd24   = $validated[1];
 
            $newStartSQL = $dateOnly . ' ' . $newStart24;
            $newEndSQL   = $dateOnly . ' ' . $newEnd24;
 
            $connection = connect();
            $newStartSQL = mysqli_real_escape_string($connection, $newStartSQL);
            $newEndSQL   = mysqli_real_escape_string($connection, $newEndSQL);
            $safeUser    = mysqli_real_escape_string($connection, $targetUser);
            $safeEvent   = mysqli_real_escape_string($connection, $eventId);
            $safeSqlStart = mysqli_real_escape_string($connection, $sqlStart);
            $safeSqlEnd   = mysqli_real_escape_string($connection, $sqlEnd);
 
            $query = "UPDATE dbpersonhours 
                      SET start_time = '$newStartSQL', end_time = '$newEndSQL'
                      WHERE personID = '$safeUser' 
                      AND eventID = '$safeEvent' 
                      AND start_time = '$safeSqlStart' 
                      AND end_time = '$safeSqlEnd'
                      LIMIT 1";
 
            if (mysqli_query($connection, $query)) {
                mysqli_close($connection);
                if ($isManager) {
                    header('Location: eventList.php?username=' . urlencode($targetUser));
                } else {
                    header('Location: eventList.php');
                }
                exit();
            } else {
                $error = "Error updating hours: " . mysqli_error($connection);
                mysqli_close($connection);
            }
        }
    }
}

 ?>



<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>Gwyneth's Gift | Edit Check-In</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1 style="color:white;">Edit Check-In</h1>
        <main class="date">
            <h2>Edit this check-in</h2>            
            <?php if ($error): ?>
            <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
 
        <p><strong>Current Start Time:</strong> <?php echo htmlspecialchars($displayStart); ?></p>
        <p><strong>Current End Time:</strong> <?php echo htmlspecialchars($displayEnd); ?></p>
 
        <form id="edit-times-form" method="POST">
            <label for="start-time">* New Start Time</label>
            <input type="text" id="start-time" name="start-time"
                   pattern="([1-9]|10|11|12):[0-5][0-9] ?([aApP][mM])"
                   required placeholder="e.g. 10:00 AM">
 
            <label for="end-time">* New End Time</label>
            <input type="text" id="end-time" name="end-time"
                   pattern="([1-9]|10|11|12):[0-5][0-9] ?([aApP][mM])"
                   required placeholder="e.g. 2:00 PM">
 
            <input type="submit" value="Save Changes">
        </form>
 
        <a class="button cancel" href="<?php echo $isManager ? 'eventList.php?username=' . urlencode($targetUser) : 'eventList.php'; ?>" style="margin-top: -.5rem">
            Cancel
        </a>
    </main>
</body>
</html>