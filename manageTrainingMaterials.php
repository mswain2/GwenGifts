<?php
session_cache_expire(30);
session_start();

if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 2) {
    header('Location: login.php');
    die();
}

require_once('include/input-validation.php');
require_once('database/dbTrainingMaterials.php');
require_once('database/dbEvents.php');

$args = sanitize($_GET);
$eventID = isset($args['eventID']) ? intval($args['eventID']) : 0;
$search = isset($args['search']) ? trim((string)$args['search']) : '';

if ($eventID <= 0) {
    header('Location: calendar.php');
    die();
}

$event_info = fetch_event_by_id($eventID);
if (!$event_info) {
    echo 'Invalid event ID.';
    die();
}

$materials = get_training_materials_by_event($eventID, $search);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once('universal.inc'); ?>
    <title>Gwyneth's Gift | Manage Training Materials</title>
    <style>
        main.general {
            width: 92%;
            max-width: 1400px;
            margin: 2rem auto;
            padding: 2rem;
            border: 2px solid #314767;
            border-radius: 12px;
            background-color: #fff;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            margin: 1rem 0;
        }

        table.general {
            width: 100%;
            min-width: 950px;
            border-collapse: collapse;
            background: #fff;
            border: 1px solid #c8d1db;
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

        .toolbar {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: end;
            margin: 1.25rem 0;
        }

        .actions {
            display: flex;
            gap: .75rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }
    </style>
    <script>
        function toggleAll(source) {
            const checkboxes = document.querySelectorAll('.material-check');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = source.checked;
            });
        }

        function confirmBulkDelete() {
            const selected = document.querySelectorAll('.material-check:checked');
            if (selected.length === 0) {
                alert('Select at least one training material to delete.');
                return false;
            }

            return confirm('Delete the selected training materials?');
        }
    </script>
</head>

<body>
    <?php require_once('header.php'); ?>

    <h1>Manage Training Materials</h1>

    <main class="general">
        <h2><?php echo htmlspecialchars($event_info['name']); ?></h2>

        <?php if (isset($_GET['bulkDeleteSuccess']) && $_GET['bulkDeleteSuccess'] === '1'): ?>
            <p class="success">
                Selected training material(s) were removed successfully.
                <?php if (isset($_GET['deleted'])): ?>
                    (<?php echo htmlspecialchars((string)$_GET['deleted']); ?> deleted)
                <?php endif; ?>
            </p>
        <?php elseif (isset($_GET['bulkDeleteSuccess']) && $_GET['bulkDeleteSuccess'] === '0'): ?>
            <p class="error">No training materials were deleted.</p>
        <?php endif; ?>

        <form method="GET" class="toolbar">
            <input type="hidden" name="eventID" value="<?php echo htmlspecialchars((string)$eventID); ?>">

            <div>
                <label for="search"><strong>Search</strong></label><br>
                <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search title or file name">
            </div>

            <div>
                <button type="submit" class="button signup">Search</button>
            </div>

            <div>
                <a href="manageTrainingMaterials.php?eventID=<?php echo urlencode((string)$eventID); ?>" class="button cancel">Clear Search</a>
            </div>
        </form>

        <?php if (empty($materials)): ?>
            <p>No training materials were found for this event.</p>
        <?php else: ?>
            <form method="POST" action="bulkDeleteTrainingMaterials.php" onsubmit="return confirmBulkDelete();">
                <input type="hidden" name="eventID" value="<?php echo htmlspecialchars((string)$eventID); ?>">

                <div class="table-wrapper">
                    <table class="general">
                        <thead>
                            <tr>
                                <th><input type="checkbox" onclick="toggleAll(this)"></th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>File</th>
                                <th>Uploaded At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($materials as $material): ?>
                                <tr>
                                    <td>
                                        <input
                                            type="checkbox"
                                            class="material-check"
                                            name="selected_materials[]"
                                            value="<?php echo htmlspecialchars((string)$material['id']); ?>">
                                    </td>
                                    <td><?php echo htmlspecialchars($material['title']); ?></td>
                                    <td><?php echo htmlspecialchars($material['description'] ?? ''); ?></td>
                                    <td>
                                        <a href="<?php echo htmlspecialchars($material['file_path']); ?>" target="_blank">
                                            <?php echo htmlspecialchars($material['file_name']); ?>
                                        </a>
                                    </td>
                                    <td><?php echo htmlspecialchars($material['uploaded_at'] ?? ''); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="actions">
                    <button type="submit" class="button danger">Delete Selected</button>
                    <a href="event.php?id=<?php echo urlencode((string)$eventID); ?>" class="button cancel">Return to Event</a>
                </div>
            </form>
        <?php endif; ?>
    </main>
</body>

</html>