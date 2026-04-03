<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_cache_expire(30);
session_start();

date_default_timezone_set("America/New_York");

require_once('include/input-validation.php');
require_once('database/dbEvents.php');
$args = sanitize($_GET);

$user = retrieve_person($_SESSION['_id']);
$active = $user->get_status() == 'Active';
$event_id = $args['id'];
$event_info = fetch_event_by_id($event_id);

if (isset($user) && $active && check_if_signed_up($event_id, $user->get_id())){
    $signed_up = true;
} else {
    $signed_up = false;
    header('Location: index.php');
    die();
}

$error = null;
$eventID = $_GET['id'] ?? $_POST['id'] ?? '';

if (!$eventID) {
    die("Missing event ID.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('database/dbEventComments.php');
    $connection = connect();
    $args = sanitize($_GET);
    
    $comment = mysqli_real_escape_string($connection, trim($_POST['comment']));
    $user_id = $_SESSION['_id'];
    $event_id = $args['id'];

    if (isset($comment) && !empty($comment)) {
        $result = add_event_comment($user_id, $event_id, $comment);
        if(!$result){
            $error = "Save error occured";
        }
    } else {
        $error = "No comment entered or an upload error occurred.";
    }

    mysqli_close($connection);
    header('Location: viewEventComments.php?eventID=' . $event_id . '&attachSuccess');
    die();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="./css/base.css" rel="stylesheet">
    <link href="./event.css" rel="stylesheet">
    <title>Gwyneth's Gift | Add Event Comment</title>

</head>

<body>
    <?php require 'header.php'; ?>
    <h1>Add Event Comment</h1>

    <main class="date">
        <?php if ($error): ?>
            <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>


        <?php
        require_once('include/output.php');
        $event_name = $event_info['name'];
        ?>

        <!-- Event Information Table -->
        <h2 class="event-head">
            <?php echo htmlspecialchars_decode($event_name); ?>
        </h2>

        <form method="POST">
            <input type="hidden" name="eventID" value="<?php echo htmlspecialchars($eventID); ?>">

            <div class="form-group">
                <label for="comment">* Comment:</label>
                <textarea id="comment" name="comment" placeholder="Comments, feedback, suggestions about the event!" required></textarea>
            </div>

            <button type="submit" class="btn-submit">Add Comment</button>
            <a href="viewEventComments.php?eventID=<?php echo urlencode($eventID); ?>" class="button cancel">Cancel</a>
        </form>

    </div>

</body>

</html>