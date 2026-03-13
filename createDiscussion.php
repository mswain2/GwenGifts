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

if ($accessLevel < 2) {
    header('Location: index.php');
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

        if (discussion_exists($title)) {
            $error = "Error: A discussion with this title already exists.";
        } else {
            $discussion = new Discussion($userID, $title, $body, $time);
            if (add_discussion($discussion, 'general')) {
                $from = "vmsroot";
                $msgTitle = "A new discussion has been created. View under discussions page.";
                $msgBody = "New Discussion";
                message_all_users($from, $msgTitle, $msgBody);
                header("Location: viewDiscussions.php");
                exit();
            } else {
                $error = "Error: Failed to add discussion. A discussion with this title and author might already exist.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gwyneth's Gift | Create Discussion</title>
    <link rel="stylesheet" href="css/normal_tw.css">
    <?php require('header.php'); ?>
    <h1>Create a New Discussion</h1>
</head>
<body>


<main>
    <div class="main-content-box">
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="POST" action="createDiscussion.php">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="body">Body:</label>
            <textarea id="body" name="body" required></textarea>

            <button type="submit" class="btn btn-submit">Create Discussion</button>
        </form>

        <a href="viewDiscussions.php" class="btn btn-back">Back to Discussions</a>
    </div>
</main>
</body>
</html>
<?php ob_end_flush(); ?>