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

include_once('database/dbPersons.php');
include_once('domain/Person.php');

if (isset($_SESSION['_id'])) {
    $person = retrieve_person($_SESSION['_id']);
}

$notRoot = $person->get_id() != 'vmsroot';
$type = strtolower($person->get_type());
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="./css/base.css" rel="stylesheet">
    <title>Gwyneth's Gift | Dashboard</title>

    <!--BEGIN TEST, UPLOAD AND NOTIFICATIONS CHANGED-->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelector(".extra-info").style.maxHeight = "0px"; // Ensure proper initialization
        });

        function toggleInfo(event) {
            event.stopPropagation(); // Prevents triggering the main button click
            let info = event.target.nextElementSibling;
            let isVisible = info.style.maxHeight !== "0px";
            info.style.maxHeight = isVisible ? "0px" : "100px";
            event.target.innerText = isVisible ? "↓" : "↑";
        }
    </script>
    <!--END TEST-->
</head>

<!-- load personalized dashboard according to user's role/type -->
<?php
if ($type === 'admin' || $type === 'superadmin') {
    require 'dashboards/admin_dashboard.php';
}
else if ($type === 'board_member') {
    require 'dashboards/board_member_dashboard.php';
}
else if ($type === 'event_manager') {
    require 'dashboards/event_manager_dashboard.php';
}
else if ($type === 'volunteer') {
    require 'dashboards/volunteer_dashboard.php';
}
?>

</html>
