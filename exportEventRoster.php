<?php
session_cache_expire(30);
session_start();

if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 2) {
    header('Location: login.php');
    die();
}

require_once('include/input-validation.php');
require_once('include/eventRosterHelpers.php');
require_once('database/dbinfo.php');
require_once('database/dbLog.php');

function log_roster_export_action($message)
{
    $con = connect();

    if (!$con) {
        return;
    }

    $tableCheck = mysqli_query($con, "SHOW TABLES LIKE 'dbLog'");
    if (!$tableCheck || mysqli_num_rows($tableCheck) === 0) {
        mysqli_close($con);
        return;
    }

    $time = time();
    $safeMessage = mysqli_real_escape_string($con, $message);

    $venueColumnCheck = mysqli_query($con, "SHOW COLUMNS FROM dbLog LIKE 'venue'");
    if ($venueColumnCheck && mysqli_num_rows($venueColumnCheck) > 0) {
        $venue = isset($_SESSION['venue']) ? mysqli_real_escape_string($con, (string)$_SESSION['venue']) : '';
        $query = "INSERT INTO dbLog (time, message, venue) VALUES ('$time', '$safeMessage', '$venue')";
    } else {
        $query = "INSERT INTO dbLog (time, message) VALUES ('$time', '$safeMessage')";
    }

    mysqli_query($con, $query);
    mysqli_close($con);
}

$args = sanitize($_GET);
$id = isset($args['id']) ? intval($args['id']) : 0;
$format = isset($args['format']) ? strtolower(trim((string)$args['format'])) : 'csv';

if ($id <= 0) {
    header('Location: calendar.php');
    die();
}

$event_info = fetch_event_by_id($id);
$event_name_display = html_entity_decode($event_info['name'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
if (!$event_info) {
    echo 'Invalid event ID.';
    die();
}

$filters = normalize_event_roster_filters($args);
$all_rows = build_event_roster_rows($id);

$filtered_rows = array();
foreach ($all_rows as $row) {
    if (event_roster_matches_filters($row, $filters)) {
        $filtered_rows[] = $row;
    }
}

$actor = isset($_SESSION['_id']) ? $_SESSION['_id'] : 'unknown';

$message = $actor .
    ' exported event roster for event #' . $id .
    ' (' . $event_name_display . ')' .
    ' as ' . strtoupper($format) .
    ' with attendance=' . $filters['attendance'] .
    ' and training=' . $filters['training'];

add_log_entry($message);

$filenameBase = 'event-roster-' . $id . '-' . date('Ymd-His');

if ($format === 'csv') {

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filenameBase . '.csv"');

    echo "\xEF\xBB\xBF";

    $output = fopen('php://output', 'w');

    $attendanceLabel = ucfirst($filters['attendance']);
    $trainingLabel = $filters['training'] === 'all' ? 'All' : str_replace('_', ' ', ucwords($filters['training'], '_'));
    $totalResults = count($filtered_rows);

    // compact metadata section
    fputcsv($output, array(
        'Event Name',
        'Attendance Filter',
        'Training Filter',
        'Exported At',
        'Total Results'
    ));

    fputcsv($output, array(
        $event_name_display,
        $attendanceLabel,
        $trainingLabel,
        $exportedAt,
        $totalResults
    ));

    // spacer row
    fputcsv($output, array());

    // roster table headers
    fputcsv($output, array(
        'Volunteer Name',
        'Email',
        'Phone',
        'Attendance',
        'Training',
        'Shirt Size',
        'User ID'
    ));

    // roster rows
    foreach ($filtered_rows as $row) {
        $trainingExport = 'CPR: ' . ($row['cpr_training_status'] ?? 'Not Done')
            . ' | AED: ' . ($row['aed_training_status'] ?? 'Not Done');

        fputcsv($output, array(
            $row['full_name'],
            $row['email'],
            $row['phone'],
            $row['attendance_status'],
            $trainingExport,
            $row['shirt_size'],
            $row['user_id']
        ));
    }

    fclose($output);
    exit();
}

if ($format !== 'pdf') {
    header('Location: eventRoster.php?id=' . urlencode((string)$id));
    die();
}

$trainingLabel = $filters['training'] === 'all' ? 'All' : str_replace('_', ' ', ucwords($filters['training'], '_'));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Event Roster PDF Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 2rem;
            position: relative;
        }

        .watermark {
            position: fixed;
            top: 38%;
            left: 10%;
            transform: rotate(-30deg);
            font-size: 4rem;
            color: rgba(120, 120, 120, 0.12);
            z-index: 0;
            white-space: nowrap;
            pointer-events: none;
        }

        .content {
            position: relative;
            z-index: 1;
        }

        .actions {
            margin-bottom: 1rem;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 1rem;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: .6rem;
            text-align: left;
        }

        th {
            background: #f2f5f8;
        }

        @media print {
            .actions {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="watermark">GWYNETH'S GIFT EVENT ROSTER</div>

    <div class="content">
        <div class="actions">
            <button onclick="window.print()">Print / Save as PDF</button>
        </div>

        <h1>Event Roster Export</h1>
        <p><strong>Event:</strong> <?php echo htmlspecialchars($event_name_display); ?></p>
        <p><strong>Attendance Filter:</strong> <?php echo htmlspecialchars($filters['attendance']); ?></p>
        <p><strong>Training Filter:</strong> <?php echo htmlspecialchars($trainingLabel); ?></p>
        <p><strong>Exported At:</strong> <?php echo htmlspecialchars(date('Y-m-d H:i:s')); ?></p>

        <table>
            <thead>
                <tr>
                    <th>Volunteer Name</th>
                    <th>Attendance</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Training</th>
                    <th>Shirt Size</th>
                    <th>User ID</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filtered_rows as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['attendance_status']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td>
                            <?php echo htmlspecialchars('CPR: ' . ($row['cpr_training_status'] ?? 'Not Done')); ?><br>
                            <?php echo htmlspecialchars('AED: ' . ($row['aed_training_status'] ?? 'Not Done')); ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['shirt_size']); ?></td>
                        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>