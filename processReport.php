<?php
session_cache_expire(30);
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('America/New_York');

if (isset($_SESSION['access_level']) && $_SESSION['access_level'] >= 2) {
    $isEventManager = true;
} else {
    header('Location: index.php');
    die();  
}

require_once('database/dbinfo.php');

// --- Input ---
$type        = $_POST['type']        ?? '';
$timePeriod  = $_POST['time_period'] ?? 'monthly';
$dateFrom    = $_POST['date_from']   ?? '';
$dateTo      = $_POST['date_to']     ?? '';
$userStatus  = $_POST['user_status'] ?? 'all';
$eventId     = $_POST['event_id']    ?? '';
$volunteer   = $_POST['volunteer']   ?? '';
// Empty string (blank field = no filter) is normalized to the existing 'all' sentinel
if ($eventId   === '') $eventId   = 'all';
if ($volunteer === '') $volunteer = 'all';
$topN        = intval($_POST['top_n'] ?? 10);
$format      = $_POST['format']      ?? 'csv';

// Save filters to session for persistence
$_SESSION['report_filters'] = [
    'type'        => $type,
    'time_period' => $timePeriod,
    'date_from'   => $dateFrom,
    'date_to'     => $dateTo,
    'user_status' => $userStatus,
    'event_id'    => $eventId,
    'volunteer'   => $volunteer,
    'top_n'       => strval($topN),
    'format'      => $format,
];

// check valid report type, valid time period, and valid report format
$validTypes   = ['volunteer_hours', 'volunteer_participation', 'volunteer_growth', 'top_volunteers'];
$validPeriods = ['weekly', 'monthly', 'yearly'];
$validFormats = ['csv', 'pdf'];

$errors = [];

if (!in_array($type, $validTypes)) {
    $errors[] = 'Invalid report type given.';
}
if (!in_array($timePeriod, $validPeriods)) {
    $errors[] = 'Invalid time period for report given.';
}
if (!in_array($format, $validFormats)) {
    $errors[] = 'Invalid report format given.';
}
if ($dateFrom && $dateTo && $dateFrom >= $dateTo) {
    $errors[] = 'Start date must be before end date.';
}

if (!empty($errors)) {
    $_SESSION['report_errors'] = $errors;
    header('Location: generateReport.php');
    exit();
}

$con = connect();
if (!$con) {
    die('Database connection failed.');
}

// --- Time Period SQL expressions ---
function period_expr($col, $timePeriod) {
    if ($timePeriod === 'weekly') {
        // Returns the Monday of the week as a date string
        return "DATE_FORMAT(DATE_SUB($col, INTERVAL WEEKDAY($col) DAY), '%Y-%m-%d')";
    } elseif ($timePeriod === 'monthly') {
        return "DATE_FORMAT($col, '%Y-%m')";
    } elseif ($timePeriod === 'yearly') {
        return "YEAR($col)";
    }
}

function period_label($raw, $timePeriod) {
    if ($timePeriod === 'weekly') {
        // raw = "2026-03-09" (Monday)
        $mon = new DateTime($raw);
        $sun = clone $mon;
        $sun->modify('+6 days');
        return $mon->format('M j') . ' – ' . $sun->format('M j, Y');
    } elseif ($timePeriod === 'monthly') {
        // raw = "2026-04"
        $d = DateTime::createFromFormat('Y-m', $raw);
        return $d ? $d->format('F Y') : $raw;
    } elseif ($timePeriod === 'yearly') {
        return "Year " . $raw;
    }
    return $raw;
}

// --- Build report data ---
$title = '';
$headers = [];
$rows = [];

// =====================================================
// VOLUNTEER HOURS
// =====================================================
if ($type === 'volunteer_hours') {
    $title = 'Volunteer Hours Report';
    $headers = ['Period', 'Volunteer', 'Hours'];
    $periodExpr = period_expr('ph.start_time', $timePeriod);

    $sql = "SELECT {$periodExpr} AS period,
                   CONCAT(p.first_name, ' ', p.last_name) AS volunteer_name,
                   SUM(TIMESTAMPDIFF(SECOND, ph.start_time, ph.end_time)) / 3600.0 AS total_hours
            FROM dbpersonhours ph
            JOIN dbpersons p ON ph.personID = p.id
            WHERE ph.end_time IS NOT NULL
              AND DATE(ph.start_time) >= ?
              AND DATE(ph.start_time) <= ?";
    $params = [$dateFrom, $dateTo];
    $paramTypes = 'ss';

    if ($userStatus !== 'all') {
        $sql .= " AND p.status = ?";
        $params[] = $userStatus;
        $paramTypes .= 's';
    }
    if ($eventId !== 'all') {
        $sql .= " AND ph.eventID = ?";
        $params[] = $eventId;
        $paramTypes .= 's';
    }
    if ($volunteer !== 'all') {
        $sql .= " AND p.id = ?";
        $params[] = $volunteer;
        $paramTypes .= 's';
    }

    $sql .= " GROUP BY period, ph.personID
               ORDER BY period ASC, total_hours DESC";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, $paramTypes, ...$params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $rawRows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rawRows[] = $row;
    }
    mysqli_stmt_close($stmt);

    // Build rows with subtotals per period and total
    $lastPeriod = null;
    $periodHours = 0;
    $grandTotal = 0;

    foreach ($rawRows as $i => $row) {
        $label = period_label($row['period'], $timePeriod);
        $hours = round($row['total_hours'], 2);

        // If period changed, insert subtotal for previous period
        if ($lastPeriod !== null && $row['period'] !== $lastPeriod) {
            $rows[] = ['Subtotal', '', round($periodHours, 2)];
            $rows[] = ['', '', ''];  // blank separator row
            $periodHours = 0;
        }

        $rows[] = [$label, $row['volunteer_name'], $hours];
        $periodHours += $hours;
        $grandTotal += $hours;
        $lastPeriod = $row['period'];
    }

    // Final period subtotal
    if ($lastPeriod !== null) {
        $rows[] = ['Subtotal', '', round($periodHours, 2)];
    }

    // Total
    if (!empty($rawRows)) {
        $rows[] = ['', '', ''];
        $rows[] = ['Total', '', round($grandTotal, 2)];
    }

// =====================================================
// VOLUNTEER PARTICIPATION
// =====================================================
} elseif ($type === 'volunteer_participation') {
    $title = 'Volunteer Participation Report';
    $headers = ['Period', 'Event', 'Signups', 'Attended', 'No Shows', 'Attendance Rate'];
    $periodExpr = period_expr('e.startDate', $timePeriod);

    $sql = "SELECT {$periodExpr} AS period,
                   e.name AS event_name,
                   COUNT(DISTINCT ep.userID) AS signups,
                   COUNT(DISTINCT CASE WHEN da.attended = 1 THEN ep.userID END) AS attended,
                   COUNT(DISTINCT CASE WHEN da.attended = 0 THEN ep.userID END) AS no_shows
            FROM dbeventpersons ep
            JOIN dbevents e ON ep.eventID = e.id
            JOIN dbpersons p ON ep.userID = p.id
            LEFT JOIN dbattendance da
                ON CAST(da.eventId AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_unicode_ci =
                    CAST(ep.eventID AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_unicode_ci
                AND CAST(da.userId AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_unicode_ci =
                    CAST(ep.userID AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_unicode_ci
            WHERE e.startDate >= ?
              AND e.startDate <= ?";
    $params = [$dateFrom, $dateTo];
    $paramTypes = 'ss';

    if ($userStatus !== 'all') {
        $sql .= " AND p.status = ?";
        $params[] = $userStatus;
        $paramTypes .= 's';
    }
    if ($eventId !== 'all') {
        $sql .= " AND e.id = ?";
        $params[] = $eventId;
        $paramTypes .= 's';
    }
    if ($volunteer !== 'all') {
        $sql .= " AND p.id = ?";
        $params[] = $volunteer;
        $paramTypes .= 's';
    }

    $sql .= " GROUP BY period, e.id
               ORDER BY period ASC, event_name ASC";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, $paramTypes, ...$params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $signups  = intval($row['signups']);
        $attended = intval($row['attended']);
        $noShows  = intval($row['no_shows']);
        $rate     = $signups > 0 ? round(($attended / $signups) * 100, 1) . '%' : 'N/A';
        $rows[] = [
            period_label($row['period'], $timePeriod),
            $row['event_name'],
            $signups,
            $attended,
            $noShows,
            $rate
        ];
    }
    mysqli_stmt_close($stmt);

// =====================================================
// VOLUNTEER GROWTH
// =====================================================
} elseif ($type === 'volunteer_growth') {
    $title = 'Volunteer Growth Report';
    $headers = ['Period', 'New Volunteers', 'Total Active', 'Total Inactive'];
    $periodExpr = period_expr('p.start_date', $timePeriod);

    // Get all periods with new volunteer counts
    $sql = "SELECT {$periodExpr} AS period,
                   COUNT(*) AS new_volunteers
            FROM dbpersons p
            WHERE p.id != 'vmsroot'
              AND p.start_date >= ?
              AND p.start_date <= ?";
    $params = [$dateFrom, $dateTo];
    $paramTypes = 'ss';

    if ($userStatus !== 'all') {
        $sql .= " AND p.status = ?";
        $params[] = $userStatus;
        $paramTypes .= 's';
    }

    $sql .= " GROUP BY period ORDER BY period ASC";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, $paramTypes, ...$params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $periods = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $periods[$row['period']] = intval($row['new_volunteers']);
    }
    mysqli_stmt_close($stmt);

    // For each period, get cumulative active and inactive counts as of the period end
    foreach ($periods as $period => $newCount) {
        // Determine end date of period
        if ($timePeriod === 'weekly') {
            // period = "2026-03-09" (Monday of the week)
            $dt = new DateTime($period);
            $dt->modify('+6 days'); // Sunday
            $periodEnd = $dt->format('Y-m-d');
        } elseif ($timePeriod === 'monthly') {
            $periodEnd = date('Y-m-t', strtotime($period . '-01'));
        } elseif ($timePeriod === 'yearly') {
            $periodEnd = $period . '-12-31';
        }

        // Active count as of period end
        $stmt2 = mysqli_prepare($con,
            "SELECT COUNT(*) AS cnt FROM dbpersons
             WHERE id != 'vmsroot' AND start_date <= ? AND status = 'Active' AND archived = 0");
        mysqli_stmt_bind_param($stmt2, 's', $periodEnd);
        mysqli_stmt_execute($stmt2);
        $r = mysqli_stmt_get_result($stmt2);
        $active = intval(mysqli_fetch_assoc($r)['cnt']);
        mysqli_stmt_close($stmt2);

        // Inactive count as of period end
        $stmt3 = mysqli_prepare($con,
            "SELECT COUNT(*) AS cnt FROM dbpersons
             WHERE id != 'vmsroot' AND start_date <= ? AND status = 'Inactive'");
        mysqli_stmt_bind_param($stmt3, 's', $periodEnd);
        mysqli_stmt_execute($stmt3);
        $r = mysqli_stmt_get_result($stmt3);
        $inactive = intval(mysqli_fetch_assoc($r)['cnt']);
        mysqli_stmt_close($stmt3);

        $rows[] = [
            period_label($period, $timePeriod),
            $newCount,
            $active,
            $inactive
        ];
    }

// =====================================================
// TOP VOLUNTEERS
// =====================================================
} elseif ($type === 'top_volunteers') {
    $title = "Top $topN Volunteers Report";
    $headers = ['Rank', 'Volunteer', 'Total Hours', 'Events Attended'];

    $sql = "SELECT CONCAT(p.first_name, ' ', p.last_name) AS volunteer_name,
                   SUM(TIMESTAMPDIFF(SECOND, ph.start_time, ph.end_time)) / 3600.0 AS total_hours,
                   COUNT(DISTINCT ph.eventID) AS events_attended
            FROM dbpersonhours ph
            JOIN dbpersons p ON ph.personID = p.id
            WHERE ph.end_time IS NOT NULL
              AND DATE(ph.start_time) >= ?
              AND DATE(ph.start_time) <= ?";
    $params = [$dateFrom, $dateTo];
    $paramTypes = 'ss';

    if ($userStatus !== 'all') {
        $sql .= " AND p.status = ?";
        $params[] = $userStatus;
        $paramTypes .= 's';
    }
    if ($eventId !== 'all') {
        $sql .= " AND ph.eventID = ?";
        $params[] = $eventId;
        $paramTypes .= 's';
    }

    $sql .= " GROUP BY ph.personID
               ORDER BY total_hours DESC
               LIMIT ?";
    $params[] = $topN;
    $paramTypes .= 'i';

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, $paramTypes, ...$params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $rank = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = [
            $rank++,
            $row['volunteer_name'],
            round($row['total_hours'], 2),
            intval($row['events_attended'])
        ];
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($con);

// --- Resolve event name for display ---
$eventName = '';
if ($eventId !== 'all') {
    require_once('database/dbEvents.php');
    $eventObj = retrieve_event($eventId);
    $eventName = $eventObj ? $eventObj->getName() : "Event #$eventId";
}

// --- Resolve volunteer display from ID ---
$volunteerName = '';
if ($volunteer !== 'all') {
    require_once('database/dbPersons.php');
    $volunteerObj = retrieve_person($volunteer);
    if ($volunteerObj) {
        $volunteerName = trim($volunteerObj->get_first_name() . ' ' . $volunteerObj->get_last_name());
        $vEmail = $volunteerObj->get_email();
        if (!empty($vEmail)) {
            $volunteerName .= ' (' . $vEmail . ')';
        }
    } else {
        $volunteerName = "Volunteer #$volunteer";
    }
}

// --- Subtitle with filter info ---
$subtitle = ucfirst(str_replace('_', ' ', $timePeriod)) . " | $dateFrom to $dateTo";
$subtitle .= " | Status: " . ($userStatus !== 'all' ? $userStatus : 'All');
$subtitle .= " | Event: " . ($eventId !== 'all' ? $eventName : 'All Events');
$subtitle .= " | Volunteer: " . ($volunteer !== 'all' ? $volunteerName : 'All Volunteers');

// =====================================================
// CSV OUTPUT
// =====================================================
if ($format === 'csv') {
    $filename = $type . '_' . date('Y-m-d') . '.csv';
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    $output = fopen('php://output', 'w');
    fputcsv($output, [$title]);
    fputcsv($output, [$subtitle]);
    fputcsv($output, []);
    fputcsv($output, $headers);

    foreach ($rows as $row) {
        fputcsv($output, $row);
    }

    if (empty($rows)) {
        fputcsv($output, ['No data found for the selected filters.']);
    }

    fclose($output);
    exit();
}

// =====================================================
// PDF OUTPUT (styled HTML for print/save as PDF)
// =====================================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; }
            a[href]:after { content: none !important; }
        }
        body {
            font-family: 'Nunito', 'Segoe UI', sans-serif;
            color: #2f4159;
            max-width: 960px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        h1 {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }
        .subtitle {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
        .timestamp {
            color: #999;
            font-size: 0.8rem;
            margin-bottom: 1rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        thead th {
            background-color: #2f4159;
            color: #fff;
            padding: 0.6rem 0.75rem;
            text-align: left;
            font-weight: 600;
        }
        tbody td {
            padding: 0.5rem 0.75rem;
            border-bottom: 1px solid #e0e0e0;
            text-align: left;
        }
        tbody tr:nth-child(even) {
            background-color: #f7f9fb;
        }
        .period-group td {
            background-color: #eef2f7;
            font-weight: 600;
        }
        tr.subtotal-row td {
            font-weight: 600;
            border-top: 2px solid #2f4159;
            background-color: #eef2f7;
        }
        tr.grand-total-row td {
            font-weight: 700;
            border-top: 3px double #2f4159;
            background-color: #d9e2ec;
            font-size: 0.95rem;
        }
        tr.blank-row td {
            border: none;
            padding: 0.15rem;
        }
        .empty-msg {
            text-align: center;
            padding: 2rem;
            color: #888;
        }
        .actions {
            margin-top: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .actions button, .actions a {
            padding: 0.5rem 1.5rem;
            border-radius: 6px;
            font-size: 0.9rem;
            cursor: pointer;
            text-decoration: none;
            border: none;
        }
        .btn-print {
            background-color: #2f4159;
            color: #fff;
            transition: background-color 0.2s ease;
        }
        .btn-print:hover {
            background-color: #f5c16e;
            color: #fff;
        }
        .btn-back {
            background-color: #f6a4b5;
            color: #fff;
            transition: background-color 0.2s ease;
        }
        .btn-back:hover {
            background-color: #f5c16e;
            color: #fff;
        }
    </style>
</head>
<body>

<h1><?= htmlspecialchars($title) ?></h1>
<div class="subtitle"><?= htmlspecialchars($subtitle) ?></div>
<div class="timestamp">Generated <?= date('F j, Y \a\t g:i A') ?></div>

<?php if (empty($rows)): ?>
    <div class="empty-msg">No data found for the selected filters.</div>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <?php foreach ($headers as $h): ?>
                    <th><?= htmlspecialchars($h) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row):
                // Detect row type
                $isBlank = ($row[0] === '' && $row[1] === '' && $row[2] === '');
                $isSubtotal = ($row[0] === 'Subtotal');
                $isGrandTotal = ($row[0] === 'Total');
                $rowClass = '';
                if ($isBlank) $rowClass = 'blank-row';
                elseif ($isSubtotal) $rowClass = 'subtotal-row';
                elseif ($isGrandTotal) $rowClass = 'grand-total-row';
            ?>
                <tr class="<?= $rowClass ?>">
                    <?php foreach ($row as $cell): ?>
                        <td><?= htmlspecialchars($cell) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<div class="actions no-print">
    <button class="btn-print" onclick="window.print()">Print / Save as PDF</button>
    <a href="generateReport.php" class="btn-back">Back to Reports</a>
</div>

</body>
</html>
