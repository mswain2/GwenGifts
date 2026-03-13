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
 
include_once "database/dbDiscussions.php";
include_once "domain/Discussion.php";
include_once "database/dbPersons.php";
 
$discussions = get_board_discussions();
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Gwyneth's Gift | Board Discussions</title>
    <link href="css/normal_tw.css" rel="stylesheet">
<?php
$tailwind_mode = true;
require_once('header.php');
?>
<h1>Board Discussions</h1>
</head>
<body>
 
<main>
    <div class="main-content-box w-[90%] p-8">
 
        <div class="top-bar">
            <a href="createBoardDiscussion.php" class="blue-button">+ New Discussion</a>
        </div>
 
        <table>
            <thead>
                <tr>
                    <th>Author</th>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($discussions): ?>
                    <?php foreach ($discussions as $discussion): 
                        $person = get_user_from_author($discussion['author_id']);
                        $author_name = $person ? $person->get_first_name() . ' ' . $person->get_last_name() : 'Unknown';
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($author_name); ?></td>
                            <td><?php echo htmlspecialchars($discussion['title']); ?></td>
                            <td><?php echo htmlspecialchars($discussion['time']); ?></td>
                            <td>
                                <a href="discussionContent.php?author=<?php echo urlencode($discussion['author_id']); ?>&title=<?php echo urlencode($discussion['title']); ?>" class="blue-button">View</a>
 
                                <?php if ($accessLevel > 2): ?>
                                    <form action="deleteDiscussion.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="author_id" value="<?php echo htmlspecialchars($discussion['author_id']); ?>">
                                        <input type="hidden" name="title" value="<?php echo htmlspecialchars($discussion['title']); ?>">
                                        <button type="submit" class="delete-button" onclick="return confirm('Are you sure you want to delete this discussion?');">Delete</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">No board discussions found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
 
    </div>
 
    <div class="text-center mt-6">
        <a href="index.php" class="return-button">Return to Dashboard</a>
    </div>
</main>
</body>
</html>
<?php ob_end_flush(); ?>