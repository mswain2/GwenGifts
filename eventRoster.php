<?php
session_cache_expire(30);
session_start();

if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 2) {
    header('Location: login.php');
    die();
}

require_once('include/input-validation.php');
require_once('include/eventRosterHelpers.php');

$args = sanitize($_GET);
$id = isset($args['id']) ? intval($args['id']) : 0;

if ($id <= 0) {
    header('Location: calendar.php');
    die();
}

$event_info = fetch_event_by_id($id);
if (!$event_info) {
    echo 'Invalid event ID.';
    die();
}

$event_name_display = html_entity_decode($event_info['name'], ENT_QUOTES | ENT_HTML5, 'UTF-8');

$filters = normalize_event_roster_filters($args);
$all_rows = build_event_roster_rows($id);

$filtered_rows = array();
foreach ($all_rows as $row) {
    if (event_roster_matches_filters($row, $filters)) {
        $filtered_rows[] = $row;
    }
}

$mailingList = '';
$notFirstEmail = false;

foreach ($filtered_rows as $row) {
    $rawEmail = trim((string)($row['raw_email'] ?? ''));

    if ($rawEmail === '' || strpos($rawEmail, '@') === false) {
        continue;
    }

    if ($notFirstEmail) {
        $mailingList .= ', ';
    } else {
        $notFirstEmail = true;
    }

    $mailingList .= $rawEmail;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once('universal.inc'); ?>
    <title>Gwyneth's Gift | Event Roster</title>
    <style>
        main.general {
            width: 92%;
            max-width: 1500px;
            margin: 2rem auto;
            padding: 2rem;
            border: 2px solid #314767;
            border-radius: 12px;
            background-color: #fff;
        }

        .roster-controls {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: end;
            margin: 1.5rem 0;
        }

        .roster-controls label {
            font-weight: 700;
            color: #243b5a;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            margin: 1rem 0 1.5rem 0;
        }

        table.general {
            width: 100%;
            min-width: 1000px;
            border-collapse: collapse;
            background: #fff;
            border: 1px solid #c8d1db;
        }

        .mailing-list-block {
            margin: 1rem 0 1.5rem 0;
            padding: 1rem;
            background: #f6f8fb;
            border: 1px solid #c8d1db;
            border-radius: 8px;
        }

        .mailing-list-block label {
            display: block;
            font-weight: 700;
            color: #243b5a;
            margin-bottom: 0.5rem;
        }

        .mailing-list-text {
            margin: 0;
            color: #333;
            line-height: 1.5;
            word-break: break-word;
        }

        table.general thead th {
            background-color: #c7d6ea;
            color: #243b5a;
            font-weight: 700;
            padding: 16px 14px;
            text-align: left;
            border: 1px solid #c8d1db;
        }

        table.general tbody td {
            padding: 16px 14px;
            border: 1px solid #d6dde5;
            vertical-align: middle;
            text-align: left;
        }

        .training-breakdown {
            line-height: 1.45;
        }

        .training-breakdown div+div {
            margin-top: 0.2rem;
        }

        .actions {
            display: flex;
            gap: .75rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <?php require_once('header.php'); ?>

    <h1>Event Roster</h1>

    <main class="general">
        <h2><?php echo htmlspecialchars($event_name_display); ?></h2>
        <p><?php echo count($all_rows); ?> sign-up(s) are on this roster.</p>
        <p>Showing <?php echo count($filtered_rows); ?> row(s) with the current filters.</p>

        <form method="GET" class="roster-controls">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$id); ?>">

            <div>
                <label for="attendance">Attendance</label><br>
                <select name="attendance" id="attendance">
                    <option value="all" <?php echo $filters['attendance'] === 'all' ? 'selected' : ''; ?>>All</option>
                    <option value="present" <?php echo $filters['attendance'] === 'present' ? 'selected' : ''; ?>>Present</option>
                    <option value="absent" <?php echo $filters['attendance'] === 'absent' ? 'selected' : ''; ?>>Absent</option>
                </select>
            </div>

            <div>
                <label for="training">Training</label><br>
                <select name="training" id="training">
                    <option value="all" <?php echo $filters['training'] === 'all' ? 'selected' : ''; ?>>All</option>
                    <option value="none_completed" <?php echo $filters['training'] === 'none_completed' ? 'selected' : ''; ?>>None Completed</option>
                    <option value="cpr_completed" <?php echo $filters['training'] === 'cpr_completed' ? 'selected' : ''; ?>>CPR Completed</option>
                    <option value="aed_completed" <?php echo $filters['training'] === 'aed_completed' ? 'selected' : ''; ?>>AED Completed</option>
                    <option value="all_completed" <?php echo $filters['training'] === 'all_completed' ? 'selected' : ''; ?>>Both Completed</option>
                </select>
            </div>

            <div>
                <button type="submit" class="button signup">Apply Filters</button>
            </div>

            <div>
                <a href="eventRoster.php?id=<?php echo urlencode((string)$id); ?>" class="button cancel">Clear Filters</a>
            </div>

            <div>
                <a href="exportEventRoster.php?id=<?php echo urlencode((string)$id); ?>&attendance=<?php echo urlencode($filters['attendance']); ?>&training=<?php echo urlencode($filters['training']); ?>&format=csv" class="button signup">
                    Export CSV
                </a>
            </div>

            <div>
                <a href="exportEventRoster.php?id=<?php echo urlencode((string)$id); ?>&attendance=<?php echo urlencode($filters['attendance']); ?>&training=<?php echo urlencode($filters['training']); ?>&format=pdf" class="button signup" target="_blank">
                    Export PDF
                </a>
            </div>
        </form>

        <?php if (empty($filtered_rows)): ?>
            <p>No roster entries match the current filters.</p>
        <?php else: ?>
            <div class="table-wrapper">
                <table class="general">
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
                                <td class="training-breakdown">
                                    <div>CPR: <?php echo htmlspecialchars($row['cpr_training_status'] ?? 'Not Done'); ?></div>
                                    <div>AED: <?php echo htmlspecialchars($row['aed_training_status'] ?? 'Not Done'); ?></div>
                                </td>
                                <td><?php echo htmlspecialchars($row['shirt_size']); ?></td>
                                <td>
                                    <a href="viewProfile.php?id=<?php echo urlencode($row['user_id']); ?>">
                                        <?php echo htmlspecialchars($row['user_id']); ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if ($mailingList !== ''): ?>
                <div class="mailing-list-block">
                    <label>Event Email List:</label>
                    <p class="mailing-list-text"><?php echo htmlspecialchars($mailingList); ?></p>
                </div>
            <?php endif; ?>

        <?php endif; ?>

        <div class="actions">
            <a class="button cancel" href="event.php?id=<?php echo urlencode((string)$id); ?>">Return to Event</a>
        </div>
    </main>
</body>

</html>