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
 
include_once 'database/dbDiscussions.php';
include_once 'domain/Discussion.php';

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
 
$title = $_GET['title'] ?? null;
$category = $_GET['category'] ?? null;
 
if (!$title) {
    die("Error: Missing discussion title.");
}
 
$discussion = get_discussion($title, $category);
if (!$discussion) {
    die("Error: Discussion not found.");
}

if (!in_array($userType, ['admin', 'superadmin']) && $userID !== $discussion['author_id']) {
    header('Location: index.php');
    die();
}
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newBody = trim($_POST['body'] ?? '');
 
    if (empty($newBody)) {
        $error = "Error: Body cannot be empty.";
    } else {
        $result = update_discussion($discussion['author_id'], $title, $newBody, $userID, $category);
        if ($result) {
            header("Location: discussionContent.php?author=" . urlencode($discussion['author_id']) . "&title=" . urlencode($title) . "&category=" . urlencode($category));
            exit();
        } else {
            $error = "Error: Failed to update discussion.";
        }
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gwyneth's Gift | Edit Discussion</title>
    <?php require_once('universal.inc') ?>
</head>
<body>
<?php require_once('header.php'); ?>
<h1 style="color: white;">Edit Discussion</h1>
 
<main class="date">
    <h2>Edit Discussion</h2>
 
    <?php if (!empty($error)) echo "<p style='color: red;'>$error</p>"; ?>
 
    <form method="POST">
        <div class="event-sect">
            <label for="title">Title</label>
            <input type="text" id="title" value="<?php echo htmlspecialchars($discussion['title']); ?>" disabled>
        </div>
 
        <div class="event-sect">
            <label for="body">* Body</label>
            <textarea id="body" name="body" required><?php echo htmlspecialchars($discussion['body']); ?></textarea>
        </div>
 
        <input type="submit" value="Save Changes" style="width:100%;">
    </form>
 
    <a class="button cancel" href="discussionContent.php?author=<?php echo urlencode($discussion['author_id']); ?>&title=<?php echo urlencode($title); ?>&category=<?php echo urlencode($category); ?>" style="margin-top: -.5rem">Cancel</a>
</main>
</body>
</html>
<?php ob_end_flush(); ?>