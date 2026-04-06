<?php
session_cache_expire(30);
session_start();

if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 2) {
    header('Location: login.php');
    die();
}

require_once('database/dbTrainingMaterials.php');

$eventID = isset($_POST['eventID']) ? (int)$_POST['eventID'] : 0;
$selected = isset($_POST['selected_materials']) && is_array($_POST['selected_materials'])
    ? $_POST['selected_materials']
    : array();

$deletedCount = 0;

foreach ($selected as $materialID) {
    $materialID = trim((string)$materialID);

    if ($materialID === '') {
        continue;
    }

    if (delete_training_material($materialID)) {
        $deletedCount++;
    }
}

$success = $deletedCount > 0 ? '1' : '0';

header(
    'Location: manageTrainingMaterials.php?eventID=' . urlencode((string)$eventID) .
        '&bulkDeleteSuccess=' . $success .
        '&deleted=' . $deletedCount
);
die();
