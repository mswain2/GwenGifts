<?php
ob_start();
session_cache_expire(30);
session_start();
 
$loggedIn = false;
$accessLevel = 0;
$userID = null;
if (isset($_SESSION['_id'])) {
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
}
 
if ($accessLevel < 1) {
    header('Location: login.php');
    die();
}
 
include_once 'database/dbDiscussions.php';
include_once 'domain/Discussion.php';
include_once 'database/dbMessages.php';
 
$error = "";
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['title']) || empty($_POST['body'])) {
        $error = "Error: Title and Body are required fields.";
    } else {
        $title = trim($_POST['title']);
        $body = trim($_POST['body']);
        $time = date("Y-m-d-H:i");
 
        if (discussion_exists($title,'board')) {
            $error = "Error: A discussion with this title already exists.";
        } else {
            $discussion = new Discussion($userID, $title, $body, $time);
            if (add_discussion($discussion, 'board')) {
                header("Location: viewBoardDiscussions.php");
                exit();
            } else {
                $error = "Error: Failed to add discussion. A discussion with this title might already exist.";
            }
        }
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gwyneth's Gift | Create Board Discussion</title>
    <?php require_once('universal.inc') ?>
</head>
<body>
<?php require_once('header.php'); ?>
<h1 style="color: white;">Create Board Discussion</h1>
 
<main class="date">
    <h2>New Board Discussion</h2>
 
    <?php if (!empty($error)) echo "<p style='color: red;'>$error</p>"; ?>
 
    <form method="POST" action="createBoardDiscussion.php">
 
        <div class="event-sect">
            <label for="title">* Title</label>
            <input type="text" id="title" name="title" required placeholder="Discussion title">
        </div>
 
        <div class="event-sect">
            <label for="body">* Body</label>
            <textarea id="body" name="body" required placeholder="Write your discussion here..."></textarea>
        </div>
 
        <input type="submit" value="Post Discussion" style="width:100%;">
    </form>
 
    <a class="button cancel" href="viewBoardDiscussions.php" style="margin-top: -.5rem">Cancel</a>
</main>
</body>
</html>
<?php ob_end_flush(); ?>