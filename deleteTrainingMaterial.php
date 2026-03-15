<?php
session_cache_expire(30);
session_start();

if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 2) {
    header('Location: login.php');
    die();
}

require_once('database/dbTrainingMaterials.php');
require_once('include/input-validation.php');

$args = sanitize($_GET);

$id = $args['id'] ?? null;
$eventID = $args['eventID'] ?? null;

if (!$id || !$eventID) {
    die('Missing required parameters.');
}

$ok = delete_training_material($id);

if ($ok) {
    header("Location: event.php?id=$eventID&trainingDeleteSuccess=1");
    exit();
} else {
    die('Failed to delete training material.');
}
?>