<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_cache_expire(30);
session_start();

date_default_timezone_set("America/New_York");

$type = strtolower($_SESSION['type'] ?? 'guest');

if (($_SESSION['_id'] ?? '') === 'vmsroot') {
    $type = 'admin';
}

if (!in_array($type, ['admin', 'volunteer'], true)) {
    if (isset($_SESSION['change-password'])) {
        header('Location: changePassword.php');
    } else {
        header('Location: login.php');
    }
    die();
}

$isAdmin = ($type === 'admin');

require_once('database/dbTrainingMaterials.php');
require_once('database/dbPersons.php');

$person = retrieve_person($_SESSION['_id']);

$search_name = isset($_GET['search_name']) ? trim($_GET['search_name']) : '';
$view_scope = $isAdmin ? trim($_GET['view_scope'] ?? 'all') : 'mine';
$filter_type = isset($_GET['filter_type']) ? trim($_GET['filter_type']) : '';
$filter_event = isset($_GET['filter_event']) ? trim($_GET['filter_event']) : '';
$filter_sort = isset($_GET['filter_sort']) ? trim($_GET['filter_sort']) : 'date_desc';

if (!in_array($view_scope, ['all', 'mine'], true)) {
    $view_scope = 'all';
}

if (!in_array($filter_sort, ['date_desc', 'date_asc', 'name_asc', 'name_desc', 'type_asc'], true)) {
    $filter_sort = 'date_desc';
}

if ($isAdmin && $view_scope === 'mine') {
    $materials = get_training_materials_by_user($_SESSION['_id'], $search_name);
} elseif ($isAdmin) {
    $materials = get_all_training_materials($search_name);
} else {
    $materials = get_training_materials_by_user($_SESSION['_id'], $search_name);
}

$pageTitle = ($isAdmin && $view_scope === 'all') ? 'Training Materials' : 'My Training Materials';

if ($isAdmin && $view_scope === 'all') {
    $pageSubtitle = 'View all uploaded training materials across events.';
} else {
    $pageSubtitle = 'Training materials for events you are signed up for.';
}

function getDocumentTypeLabel($fileName)
{
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    switch ($ext) {
        case 'pdf':
            return 'PDF';
        case 'doc':
        case 'docx':
            return 'DOC/DOCX';
        case 'ppt':
        case 'pptx':
            return 'PPT/PPTX';
        case 'xls':
        case 'xlsx':
            return 'XLS/XLSX';
        case 'jpg':
        case 'jpeg':
        case 'png':
            return 'IMAGE';
        default:
            return strtoupper($ext ?: 'FILE');
    }
}

function formatDisplayDate($dateValue)
{
    $timestamp = strtotime((string)$dateValue);
    return $timestamp ? date('M j, Y', $timestamp) : 'Unknown date';
}

$eventOptions = [];
$typeOptions = [];

foreach ($materials as $material) {
    $eventName = trim((string)($material['event_name'] ?? ''));
    $typeLabel = getDocumentTypeLabel($material['file_name'] ?? '');

    if ($eventName !== '') {
        $eventOptions[$eventName] = $eventName;
    }

    if ($typeLabel !== '') {
        $typeOptions[$typeLabel] = $typeLabel;
    }
}

natcasesort($eventOptions);
natcasesort($typeOptions);

if ($filter_event !== '') {
    $materials = array_values(array_filter($materials, function ($material) use ($filter_event) {
        return (string)($material['event_name'] ?? '') === $filter_event;
    }));
}

if ($filter_type !== '') {
    $materials = array_values(array_filter($materials, function ($material) use ($filter_type) {
        return getDocumentTypeLabel($material['file_name'] ?? '') === $filter_type;
    }));
}

usort($materials, function ($a, $b) use ($filter_sort) {
    $aEventDate = strtotime((string)($a['event_date'] ?? '')) ?: 0;
    $bEventDate = strtotime((string)($b['event_date'] ?? '')) ?: 0;

    $aUploaded = strtotime((string)($a['uploaded_at'] ?? '')) ?: 0;
    $bUploaded = strtotime((string)($b['uploaded_at'] ?? '')) ?: 0;

    $aTitle = strtolower((string)($a['title'] ?? ''));
    $bTitle = strtolower((string)($b['title'] ?? ''));

    $aType = strtolower(getDocumentTypeLabel($a['file_name'] ?? ''));
    $bType = strtolower(getDocumentTypeLabel($b['file_name'] ?? ''));

    switch ($filter_sort) {
        case 'date_asc':
            return $aEventDate <=> $bEventDate;

        case 'name_asc':
            return $aTitle <=> $bTitle;

        case 'name_desc':
            return $bTitle <=> $aTitle;

        case 'type_asc':
            $typeCompare = $aType <=> $bType;
            if ($typeCompare !== 0) {
                return $typeCompare;
            }
            return $aTitle <=> $bTitle;

        case 'date_desc':
        default:
            if ($aEventDate !== $bEventDate) {
                return $bEventDate <=> $aEventDate;
            }
            return $bUploaded <=> $aUploaded;
    }
});

$hasFiltersApplied =
    $search_name !== '' ||
    $filter_event !== '' ||
    $filter_type !== '' ||
    $filter_sort !== 'date_desc' ||
    ($isAdmin && $view_scope !== 'all');

if ($hasFiltersApplied) {
    $emptyMessage = 'No training materials matched your filters.';
} else {
    $emptyMessage = ($isAdmin && $view_scope === 'all')
        ? 'No training materials have been uploaded yet.'
        : 'You do not have any training materials for your events yet.';
}

$clearUrl = 'myTrainingMaterials.php';
if ($isAdmin && $view_scope === 'mine') {
    $clearUrl .= '?view_scope=mine';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="./css/base.css" rel="stylesheet">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Quicksand, sans-serif;
            background-color: #ffffff;
        }

        .page-container {
            max-width: 1150px;
            margin: 120px auto 60px auto;
            padding: 0 20px;
        }

        h2 {
            font-weight: normal;
            font-size: 30px;
            margin-bottom: 10px;
            color: #2f4665;
        }

        .subtitle {
            color: #666;
            font-size: 16px;
            margin-bottom: 30px;
        }

        .filter-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 24px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-bar input[type="text"],
        .filter-bar select {
            min-width: 170px;
            padding: 10px 16px;
            border: 1px solid #e0e0e0;
            border-radius: 50px;
            font-family: Quicksand, sans-serif;
            font-size: 14px;
            background-color: #f8f8f8;
            outline: none;
        }

        .filter-bar input[type="text"] {
            flex: 1;
            min-width: 260px;
        }

        .filter-bar input[type="text"]:focus,
        .filter-bar select:focus {
            border-color: #314767;
            box-shadow: 0 0 0 3px rgba(49, 71, 103, 0.08);
        }

        .filter-bar button {
            display: inline-block;
            background-color: #314767;
            color: white;
            padding: 10px 22px;
            border-radius: 50px;
            border: none;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            font-family: Quicksand, sans-serif;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .filter-bar button:hover {
            background-color: #263955;
        }

        .filter-bar .clear-btn {
            display: inline-block;
            background-color: #f0f0f0;
            color: #333;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            transition: background 0.2s ease;
        }

        .filter-bar .clear-btn:hover {
            background-color: #e0e0e0;
        }

        .table-wrap {
            overflow-x: auto;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            background: #fff;
        }

        .doc-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
        }

        .doc-table th {
            background-color: #f8f8f8;
            border-bottom: 1px solid #e0e0e0;
            padding: 12px 16px;
            text-align: left;
            font-weight: 700;
            white-space: nowrap;
        }

        .doc-table td {
            border-top: 1px solid #f0f0f0;
            padding: 12px 16px;
            vertical-align: top;
        }

        .doc-table tbody tr:hover td {
            background-color: #fafafa;
        }

        .file-badge {
            display: inline-block;
            background-color: #eef3f8;
            color: #314767;
            font-size: 12px;
            font-weight: 700;
            padding: 5px 10px;
            border-radius: 999px;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }

        .view-link,
        .event-link {
            color: #6b8caf;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .view-link:hover,
        .event-link:hover {
            text-decoration: underline;
        }

        .description-cell {
            min-width: 240px;
            color: #555;
            line-height: 1.45;
        }

        .muted-cell {
            color: #888;
        }

        .empty-msg {
            background: #f8f8f8;
            color: #444;
            padding: 16px 20px;
            border-radius: 10px;
            border: 1px solid #e4e4e4;
            margin-bottom: 20px;
        }

        .bottom-actions {
            margin-top: 26px;
        }

        .dashboard-return {
            display: inline-block;
            padding: 10px 24px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
        }

        @media (max-width: 820px) {
            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-bar input[type="text"],
            .filter-bar select,
            .filter-bar button,
            .filter-bar .clear-btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
<?php require 'header.php'; ?>

<div class="page-container">
    <h2><b><?php echo htmlspecialchars($pageTitle); ?></b></h2>
    <div class="subtitle"><?php echo htmlspecialchars($pageSubtitle); ?></div>

    <form method="GET" action="myTrainingMaterials.php">
        <div class="filter-bar">
            <input
                type="text"
                name="search_name"
                placeholder="Search by event, title, description, or file name..."
                value="<?php echo htmlspecialchars($search_name); ?>">

            <?php if ($isAdmin): ?>
                <select name="view_scope">
                    <option value="all" <?php if ($view_scope === 'all') echo 'selected'; ?>>All Materials</option>
                    <option value="mine" <?php if ($view_scope === 'mine') echo 'selected'; ?>>My Materials</option>
                </select>
            <?php endif; ?>

            <select name="filter_event">
                <option value="">All Events</option>
                <?php foreach ($eventOptions as $eventName): ?>
                    <option value="<?php echo htmlspecialchars($eventName); ?>" <?php if ($filter_event === $eventName) echo 'selected'; ?>>
                        <?php echo htmlspecialchars_decode($eventName); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="filter_type">
                <option value="">All File Types</option>
                <?php foreach ($typeOptions as $typeLabel): ?>
                    <option value="<?php echo htmlspecialchars($typeLabel); ?>" <?php if ($filter_type === $typeLabel) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($typeLabel); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="filter_sort">
                <option value="date_desc" <?php if ($filter_sort === 'date_desc') echo 'selected'; ?>>Newest Event First</option>
                <option value="date_asc" <?php if ($filter_sort === 'date_asc') echo 'selected'; ?>>Oldest Event First</option>
                <option value="name_asc" <?php if ($filter_sort === 'name_asc') echo 'selected'; ?>>Title A–Z</option>
                <option value="name_desc" <?php if ($filter_sort === 'name_desc') echo 'selected'; ?>>Title Z–A</option>
                <option value="type_asc" <?php if ($filter_sort === 'type_asc') echo 'selected'; ?>>File Type</option>
            </select>

            <button type="submit">Search</button>
            <a href="<?php echo htmlspecialchars($clearUrl); ?>" class="clear-btn">Clear</a>
        </div>
    </form>

    <?php if (empty($materials)): ?>
        <div class="empty-msg"><?php echo htmlspecialchars($emptyMessage); ?></div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="doc-table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Event Date</th>
                        <th>File Type</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Download</th>
                        <th>Event</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materials as $material): ?>
                        <tr>
                            <td><?php echo htmlspecialchars_decode($material['event_name']); ?></td>
                            <td><?php echo htmlspecialchars(formatDisplayDate($material['event_date'])); ?></td>
                            <td>
                                <span class="file-badge">
                                    <?php echo htmlspecialchars(getDocumentTypeLabel($material['file_name'])); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($material['title']); ?></td>
                            <td class="description-cell">
                                <?php if (!empty($material['description'])): ?>
                                    <?php echo htmlspecialchars($material['description']); ?>
                                <?php else: ?>
                                    <span class="muted-cell">No description</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a class="view-link" href="<?php echo htmlspecialchars($material['file_path']); ?>" target="_blank" rel="noopener noreferrer">
                                    Open Document
                                </a>
                            </td>
                            <td>
                                <a class="event-link" href="event.php?id=<?php echo urlencode($material['eventID']); ?>">
                                    Go to Event
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="bottom-actions">
        <a class="cancel dashboard-return no-span" href="index.php">Return to Dashboard</a>
    </div>
</div>
</body>
</html>