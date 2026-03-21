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

//$materials = get_training_materials_by_user($_SESSION['_id']);
if ($isAdmin) {
    $materials = get_all_training_materials($search_name);
} else {
    $materials = get_training_materials_by_user($_SESSION['_id'], $search_name);
}

$pageTitle = $isAdmin ? 'Training Documents' : 'My Training Documents';
$pageSubtitle = $isAdmin
    ? 'View all uploaded training documents across events.'
    : 'Training documents for events you are signed up for.';
if ($search_name !== '') {
    $emptyMessage = 'No training documents matched your search.';
} else {
    $emptyMessage = $isAdmin
        ? 'No training documents have been uploaded yet.'
        : 'You do not have any training documents for your events yet.';
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
            max-width: 900px;
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

        .filter-bar input[type="text"] {
            flex: 1;
            min-width: 280px;
            padding: 10px 16px;
            border: 1px solid #e0e0e0;
            border-radius: 50px;
            font-family: Quicksand, sans-serif;
            font-size: 14px;
            background-color: #f8f8f8;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .filter-bar input[type="text"]:focus {
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

        @media (max-width: 640px) {
            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-bar input[type="text"],
            .filter-bar button,
            .filter-bar .clear-btn {
                width: 100%;
                text-align: center;
            }
        }

        .empty-msg {
            background: #f8f8f8;
            color: #444;
            padding: 16px 20px;
            border-radius: 10px;
            border: 1px solid #e4e4e4;
            margin-bottom: 20px;
        }

        .material-card {
            background: #fff;
            border: 1px solid #e8e8e8;
            border-radius: 14px;
            padding: 22px;
            margin-bottom: 18px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .material-event {
            font-size: 22px;
            font-weight: 700;
            color: #314767;
            margin-bottom: 6px;
        }

        .material-date {
            font-size: 15px;
            color: #777;
            margin-bottom: 14px;
        }

        .file-badge {
            display: inline-block;
            background-color: #eef3f8;
            color: #314767;
            font-size: 12px;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 999px;
            margin-bottom: 12px;
            letter-spacing: 0.4px;
        }

        .material-title {
            font-size: 18px;
            font-weight: 700;
            color: #222;
            margin-bottom: 8px;
        }

        .material-description {
            font-size: 15px;
            color: #555;
            margin-bottom: 18px;
            line-height: 1.5;
        }

        .button-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-primary {
            display: inline-block;
            background-color: #314767;
            color: white;
            padding: 10px 24px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            transition: background 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #263955;
        }

        .btn-secondary {
            display: inline-block;
            background-color: #f0f0f0;
            color: #333;
            padding: 10px 24px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            transition: background 0.2s ease;
        }

        .btn-secondary:hover {
            background-color: #e0e0e0;
        }

        .bottom-actions {
            margin-top: 26px;
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
                <button type="submit">Search</button>

                <?php if ($search_name !== ''): ?>
                    <a href="myTrainingMaterials.php" class="clear-btn">Clear</a>
                <?php endif; ?>
            </div>
        </form>

        <?php if (empty($materials)): ?>
            <div class="empty-msg"><?php echo htmlspecialchars($emptyMessage); ?></div>
        <?php else: ?>
            <?php foreach ($materials as $material): ?>
                <div class="material-card">
                    <div class="material-event">
                        <?php echo htmlspecialchars_decode($material['event_name']); ?>
                    </div>
                    
                    <div class="material-date">
                        <?php echo date('l, F j, Y', strtotime($material['event_date'])); ?>
                    </div>

                    <div class="file-badge">
                        <?php echo htmlspecialchars(getDocumentTypeLabel($material['file_name'])); ?>
                    </div>

                    <div class="material-title">
                        <?php echo htmlspecialchars($material['title']); ?>
                    </div>

                    <?php if (!empty($material['description'])): ?>
                        <div class="material-description">
                            <?php echo htmlspecialchars($material['description']); ?>
                        </div>
                    <?php endif; ?>

                    <div class="button-row">
                        <a class="btn-primary" href="<?php echo htmlspecialchars($material['file_path']); ?>" target="_blank">
                            Open Document
                        </a>
                        <a class="btn-secondary" href="event.php?id=<?php echo urlencode($material['eventID']); ?>">
                            Go to Event
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="bottom-actions">
            <a class="btn-secondary" href="index.php">Return to Dashboard</a>
        </div>
    </div>

</body>

</html>