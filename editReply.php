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
    $userType = 'volunteer';
    $userID = $_SESSION['_id'];
} 
 
include_once 'database/dbDiscussionReplies.php';
include_once 'database/dbDiscussions.php';

include_once 'database/dbPersons.php';
if (isset($_SESSION['_id'])) {
    if ($_SESSION['_id'] === 'vmsroot') {
        $userType = 'superadmin';
    } else {
        $person = retrieve_person($_SESSION['_id']);
        if ($person) $userType = $person->get_type();
    }
}
 
$error = "";
 
$replyID = $_GET['reply_id'] ?? null;
$category = $_GET['category'] ?? null;
$discussionTitle = $_GET['title'] ?? null;
 
if (!$replyID) {
    die("Error: Missing reply ID.");
}
 
$reply = get_reply_by_id($replyID);
if (!$reply) {
    die("Error: Reply not found.");
}

if (!in_array($userType, ['admin', 'superadmin']) && $userID !== $reply['user_reply_id']) {
    header('Location: index.php');
    die();
}
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newBody = trim($_POST['reply_body'] ?? '');
 
    if (empty($newBody)) {
        $error = "Error: Reply cannot be empty.";
    } else {
        $result = update_reply($replyID, $newBody, $userID);
        if ($result) {
            $discussion = get_discussion($discussionTitle, $category);
            header("Location: discussionContent.php?author=" . urlencode($discussion['author_id']) . "&title=" . urlencode($discussionTitle) . "&category=" . urlencode($category));
            exit();
        } else {
            $error = "Error: Failed to update reply.";
        }
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gwyneth's Gift | Edit Reply</title>
    <?php require_once('universal.inc') ?>
</head>
<body>
<?php require_once('header.php'); ?>
<h1 style="color: white;">Edit Reply</h1>
 
<main class="date">
    <h2>Edit Reply</h2>
 
    <?php if (!empty($error)) echo "<p style='color: red;'>$error</p>"; ?>
 
    <form method="POST">
        <div class="event-sect">
            <label for="reply_body">* Reply</label>
            <textarea id="reply_body" name="reply_body" required><?php echo htmlspecialchars($reply['reply_body']); ?></textarea>
        </div>
 
        <input type="submit" value="Save Changes" style="width:100%;">
    </form>
 
    <a class="button cancel" href="discussionContent.php?author=<?php echo urlencode($reply['author_id']); ?>&title=<?php echo urlencode($discussionTitle); ?>&category=<?php echo urlencode($category); ?>" style="margin-top: -.5rem">Cancel</a>
</main>
</body>
</html>
<?php ob_end_flush(); ?>