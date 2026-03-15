<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_cache_expire(30);
session_start();

date_default_timezone_set("America/New_York");

if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 1) {
    if (isset($_SESSION['change-password'])) {
        header('Location: changePassword.php');
    } else {
        header('Location: login.php');
    }
    die();
}

require_once('database/dbTrainingMaterials.php');
require_once('database/dbPersons.php');

$person = retrieve_person($_SESSION['_id']);
$materials = get_training_materials_by_user($_SESSION['_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="./css/base.css" rel="stylesheet">
    <title>My Training Materials</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Quicksand, sans-serif; background-color: #ffffff; }

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
    <h2><b>My Training Materials</b></h2>
    <div class="subtitle">Training documents for events you are signed up for.</div>

    <?php if (empty($materials)): ?>
        <div class="empty-msg">You do not have any training materials for your events yet.</div>
    <?php else: ?>
        <?php foreach ($materials as $material): ?>
            <div class="material-card">
                <div class="material-event">
                    <?php echo htmlspecialchars_decode($material['event_name']); ?>
                </div>

                <div class="material-date">
                    <?php echo date('l, F j, Y', strtotime($material['event_date'])); ?>
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