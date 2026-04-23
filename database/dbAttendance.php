<?php

include_once("database/dbinfo.php");

/**
 * Creates an insert for the user which attended/did not attend the event.
 * @param mixed $eventId The id for the event in which we are tallying attendance for.
 * @param mixed $loggingId The username of the user which logged the $userId's attendance.
 * @param mixed $userId The username of the user which we are marking as present.
 * @param bool $present True if present, false if absent.
 * @param mixed $comments Any comments the organizer/admin may have.
 * @return bool Returns whether or not the function was successful.
 */
function logAttendance($eventId, $loggingId, $userId, bool $present, $comments): bool
{
    $connection = connect();

    $safe_event   = mysqli_real_escape_string($connection, (string)$eventId);
    $safe_user    = mysqli_real_escape_string($connection, (string)$userId);
    $safe_logger  = mysqli_real_escape_string($connection, (string)$loggingId);
    $safe_comment = mysqli_real_escape_string($connection, (string)$comments);
    $present_int  = $present ? 1 : 0;

    $verificationQuery = "SELECT id FROM dbattendance 
                          WHERE eventId = '$safe_event' AND userId = '$safe_user'";
    $verifyResult = mysqli_query($connection, $verificationQuery);

    if (!$verifyResult || mysqli_num_rows($verifyResult) === 0) {
        $q = "INSERT INTO dbattendance (eventId, userId, loggedById, attended, attendanceNote)
              VALUES ('$safe_event', '$safe_user', '$safe_logger', $present_int, '$safe_comment')";
    } elseif (mysqli_num_rows($verifyResult) === 1) {
        $row = mysqli_fetch_assoc($verifyResult);
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

/**
 * Marks all signed-up users with no attendance record yet as absent.
 * @param mixed $eventId The id for the event.
 * @param mixed $loggingId The username of whoever is closing attendance.
 * @param mixed $comments Any notes.
 * @return bool Returns whether all operations succeeded.
 */
function logAllNotPresent($eventId, $loggingId, $comments = ''): bool
{
    require_once('database/dbEvents.php');
    $signups = fetch_event_signups($eventId);
    $success = true;

    foreach ($signups as $signup) {
        $ok = logAttendance($eventId, $loggingId, $signup['userID'], false, $comments);
        if (!$ok) $success = false;
    }

    return $success;
}

function clear_attendance_for_user($eventId, $userId): bool
{
    $connection = connect();
    $safe_event = mysqli_real_escape_string($connection, (string)$eventId);
    $safe_user  = mysqli_real_escape_string($connection, (string)$userId);

    $result = mysqli_query($connection, 
        "DELETE FROM dbattendance WHERE eventId = '$safe_event' AND userId = '$safe_user'"
    );

    mysqli_close($connection);
    return (bool)$result;
}

function get_attendance_statuses_for_event($eventId): array
{
    $connection = connect();
    $safe_event = mysqli_real_escape_string($connection, (string)$eventId);

    $query = "SELECT userId, attended FROM dbattendance WHERE eventId = '$safe_event'";
    $result = mysqli_query($connection, $query);
    $statuses = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $statuses[$row['userId']] = ((string)$row['attended'] === '1')
                ? 'Present'
                : 'Absent';
        }
    }

    mysqli_close($connection);
    return $statuses;
}
?>