<?php
session_cache_expire(30);
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

if (!isset($_SESSION['_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$userID = $_SESSION['_id'];

$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['event_ids']) || !is_array($input['event_ids'])) {
    echo json_encode(['error' => 'Invalid request body']);
    exit;
}

require_once('database/dbinfo.php');
require_once('database/dbMessages.php');

$eventIDs = array_map('intval', $input['event_ids']);
$eventIDs = array_filter($eventIDs, function ($id) { return $id > 0; });

if (empty($eventIDs)) {
    echo json_encode(['error' => 'No valid event IDs']);
    exit;
}

$connection = connect();
$safeUser = mysqli_real_escape_string($connection, $userID);

// Batch fetch all requested events in one query
$idList = implode(',', $eventIDs);
$eventsResult = mysqli_query($connection, "SELECT id, name, type, capacity FROM dbevents WHERE id IN ($idList)");
$events = [];
while ($row = mysqli_fetch_assoc($eventsResult)) {
    $events[$row['id']] = $row;
}

// Batch fetch signup counts for these events in one query
$countsResult = mysqli_query($connection, "SELECT eventID, COUNT(*) as cnt FROM dbeventpersons WHERE eventID IN ($idList) GROUP BY eventID");
$signupCounts = [];
while ($row = mysqli_fetch_assoc($countsResult)) {
    $signupCounts[$row['eventID']] = intval($row['cnt']);
}

// Batch check which events user is already signed up for
$alreadyResult = mysqli_query($connection, "SELECT eventID FROM dbeventpersons WHERE eventID IN ($idList) AND userID = '$safeUser'");
$alreadySignedUp = [];
while ($row = mysqli_fetch_assoc($alreadyResult)) {
    $alreadySignedUp[$row['eventID']] = true;
}

$success = [];
$failed = [];
$names = [];

foreach ($eventIDs as $eid) {
    // Event must exist
    if (!isset($events[$eid])) {
        $failed[] = $eid;
        continue;
    }

    $event = $events[$eid];

    // Skip Retreat events
    if ($event['type'] === 'Retreat') {
        $failed[] = $eid;
        continue;
    }

    // Skip if already signed up
    if (isset($alreadySignedUp[$eid])) {
        $failed[] = $eid;
        continue;
    }

    // Check capacity
    $capacity = intval($event['capacity']);
    $currentSignups = isset($signupCounts[$eid]) ? $signupCounts[$eid] : 0;
    if ($capacity > 0 && $currentSignups >= $capacity) {
        $failed[] = $eid;
        continue;
    }

    // Insert signup
    $safeEid = mysqli_real_escape_string($connection, $eid);
    $result = mysqli_query($connection, "INSERT INTO dbeventpersons (eventID, userID, notes) VALUES ('$safeEid', '$safeUser', '')");
    if ($result) {
        $success[] = $eid;
        $names[] = $event['name'] ?? ('Event #' . $eid);
    } else {
        $failed[] = $eid;
    }
}

mysqli_close($connection);

// Send a single notification for all successful signups
if (count($success) > 0) {
    if (count($names) === 1) {
        $title = "You are now signed up for " . $names[0] . "!";
        $body = "Thank you for signing up for " . $names[0] . "!";
    } else {
        $nameList = implode(', ', $names);
        $title = "You are now signed up for " . count($names) . " events!";
        $body = "Thank you for signing up for: " . $nameList;
    }
    send_system_message($userID, $title, $body);
}

echo json_encode([
    'success' => $success,
    'failed' => $failed,
    'message' => count($success) . ' event(s) signed up successfully'
]);
