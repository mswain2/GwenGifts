<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_cache_expire(30);
session_start();

date_default_timezone_set("America/New_York");


//check RBAC
if (isset($_SESSION['access_level']) && $_SESSION['access_level'] >= 2) {
    $isEventManager = true;
} else {
    $isEventManager = false; 
}

require_once('include/input-validation.php');
require_once('database/dbEvents.php');
require_once('database/dbEventComments.php');

$args = sanitize($_GET);

$user = retrieve_person($_SESSION['_id']);
$active = $user->get_status() == 'Active';
$event_id = $args['eventID'];
$event_info = fetch_event_by_id($event_id);

if (isset($user) && $active && check_if_signed_up($event_id, $user->get_id()) || $isEventManager){
    $signed_up = true;
} else {
    $signed_up = false;
    header('Location: index.php');
    die();
}

require_once('database/dbTrainingMaterials.php');

$error = null;
$eventID = $_GET['eventID'] ?? $_POST['eventID'] ?? '';

if (!$eventID) {
    die("Missing event ID.");
}

// Pagination settings
$comments_per_page = 10; // Number of comments per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); // Ensure page is at least 1
$offset = ($page - 1) * $comments_per_page;

// Get total comment count for pagination
$total_comments = get_event_comments_count($eventID);
$total_pages = ceil($total_comments / $comments_per_page);

// Get paginated comments
$event_comments = get_event_comments($eventID, $comments_per_page, $offset);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="./css/base.css" rel="stylesheet">
    <link href="./event.css" rel="stylesheet">
    <title>Gwyneth's Gift | View Event Comments</title>

</head>

<body>
    <?php require 'header.php'; ?>
    <h1>View Event Comments</h1>

    <main class="date">
        <?php if (isset($_GET['attachSuccess'])): ?>
            <div class="happy-toast">Event comment added successfully!</div>
        <?php endif ?>
        <?php if (isset($_GET['deleteSuccess'])): ?>
            <div class="happy-toast">Event comment deleted successfully!</div>
        <?php endif ?>
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

        <?php if (empty($event_comments)): ?>
                <p>No comments have been made about this event yet.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($event_comments as $comment): ?>
                        <li>
                            <div class="event_comment">
                                <div class="comment">
                                    <p><?php echo htmlspecialchars_decode($comment['user_id']);?></p>
                                    <?php
                                    if ($isEventManager) {
                                        echo ' <a class="remove-link" href="deleteEventComment.php?eid=' . $comment['event_id'] . '&cid=' . $comment['id'] . '" onclick="return confirm(\'Delete this comment?\');">Remove</a>';
                                    }
                                    ?>
                                </div>
                                <?php echo htmlspecialchars_decode($comment['comment']);?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php if ($i == $page): ?>
                            <span class="current-page"><?= $i ?></span>
                        <?php else: ?>
                            <a href="?eventID=<?= $eventID ?>&page=<?= $i ?>" class="page-link"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>

        <div> 
            <a href="addEventComment.php?id=<?= $eventID?>" class="button signup">Add Comment</a>
            <a href="event.php?id=<?= $eventID?>" class="button cancel">Back to Event</a>
        </div>
    </div>

</body>

</html>