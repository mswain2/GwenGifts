<?php
/*
 * processAttendees.php
 * Handles POST from logAttendance.php
 * Marks checked attendees as present, all others as absent.
 */

session_start();

// Auth check — event_manager and above
$userType = 'volunteer';
if (isset($_SESSION['_id'])) {
    if ($_SESSION['_id'] === 'vmsroot') {
        $userType = 'superadmin';
    } else {
        include_once('database/dbPersons.php');
        $person = retrieve_person($_SESSION['_id']);
        if ($person) $userType = $person->get_type();
    }
}

if (!in_array($userType, ['event_manager', 'board_member', 'admin', 'superadmin'])) {
    header('Location: index.php');
    die();
}

include_once('database/dbEvents.php');
include_once('database/dbinfo.php');
require_once('include/input-validation.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['log'])) {
    header('Location: index.php');
    die();
}

$args       = sanitize($_POST);
$eventId    = $args['event_id'] ?? null;
$loggingId  = $_SESSION['_id'];

if (!$eventId) {
    header('Location: index.php');
    die();
}


$presentIds = isset($_POST['attendee']) ? $_POST['attendee'] : [];


$notes = isset($_POST['attendee_notes']) ? $_POST['attendee_notes'] : [];


$allSignups = fetch_event_signups($eventId); 
$allUserIds = array_column($allSignups, 'userID');

$success = true;
foreach ($allUserIds as $uid) {
    $isPresent = in_array($uid, $presentIds);
    $comment   = isset($notes[$uid]) ? $notes[$uid] : '';
    $ok = logAttendance($eventId, $loggingId, $uid, $isPresent, $comment);
    if (!$ok) $success = false;
}

header('Location: viewEventSignUps.php?id=' . urlencode($eventId) . ($success ? '' : '&error=partial'));
die();

function logAttendance($eventId, $loggingId, $userId, bool $present, $comments): bool
{
    $connection = connect();

    $safe_event   = mysqli_real_escape_string($connection, (string)$eventId);
    $safe_user    = mysqli_real_escape_string($connection, (string)$userId);
    $safe_logger  = mysqli_real_escape_string($connection, (string)$loggingId);
    $safe_comment = mysqli_real_escape_string($connection, (string)$comments);
    $present_int  = $present ? 1 : 0;

    $checkQ = "SELECT id FROM dbattendance
               WHERE eventId = '$safe_event' AND userId = '$safe_user'";
    $checkR = mysqli_query($connection, $checkQ);

    if (!$checkR) {
        mysqli_close($connection);
        return false;
    }

    if (mysqli_num_rows($checkR) === 0) {
        $q = "INSERT INTO dbattendance (eventId, userId, loggedById, attended, attendanceNote)
            VALUES ('$safe_event', '$safe_user', '$safe_logger', $present_int, '$safe_comment')";
    } elseif (mysqli_num_rows($checkR) === 1) {
        $row = mysqli_fetch_assoc($checkR);
        $rid = (int)$row['id'];
        $q = "UPDATE dbattendance
            SET attended = $present_int, attendanceNote = '$safe_comment', loggedById = '$safe_logger'
            WHERE id = $rid";
    } else {
        mysqli_close($connection);
        return false;
    }

    $result = mysqli_query($connection, $q);
    mysqli_close($connection);
    return (bool)$result;
}

function logAllNotPresent($eventId, $loggingId, $comments = ''): bool
{
    $allSignups = fetch_event_signups($eventId);
    $success    = true;

    foreach ($allSignups as $signup) {
        $uid = $signup['userID'];
        $ok = logAttendance($eventId, $loggingId, $uid, false, $comments);
        if (!$ok) $success = false;
    }

    return $success;
}

function get_attendance_statuses_for_event($eventId): array
{
    $connection = connect();
    $safe_event = mysqli_real_escape_string($connection, (string)$eventId);

    $result   = mysqli_query($connection, "SELECT userId, attended FROM dbattendance WHERE eventId = '$safe_event'");
    $statuses = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $statuses[$row['userId']] = ((string)$row['attended'] === '1') ? 'Present' : 'Absent';
        }
    }

    mysqli_close($connection);
    return $statuses;
}