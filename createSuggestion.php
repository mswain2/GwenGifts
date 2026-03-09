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

// Redirect if not logged in (Access level 0 or not set)
if (!$loggedIn || $accessLevel < 1) {
    header('Location: login.php');
    die();
}

include_once 'database/dbSuggestions.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['title']) || empty($_POST['body'])) {
        $error = "Error: Title and Suggestion content are required.";
    } else {
        $title = trim($_POST['title']);
        $body = trim($_POST['body']);

        if (add_suggestion($userID, $title, $body)) {
            $success = "Suggestion submitted successfully!";
        } else {
            $error = "Error: Failed to submit suggestion.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gwyneth's Gift | Submit a Suggestion</title>
    <link rel="stylesheet" href="css/normal_tw.css">
    <?php require('header.php'); ?>
    <h1>Submit a Suggestion</h1>

</head>
<body>

       


<main>
    <div class="main-content-box">
        <?php if (!empty($error)) echo "<div class='message error'>$error</div>"; ?>
        <?php if (!empty($success)) echo "<div class='message success'>$success</div>"; ?>

        <form method="POST" action="createSuggestion.php">
            <label for="title">Subject:</label>
            <input type="text" id="title" name="title" required placeholder="Brief summary of your suggestion">

            <label for="body">Suggestion:</label>
            <textarea id="body" name="body" required placeholder="Describe your suggestion in detail..."></textarea>

            <button type="submit" class="btn btn-submit">Submit Suggestion</button>
        </form>

        <div>
            <a href="index.php" class="btn btn-back">Return to Dashboard</a>
        </div>
    </div>
</main>
</body>
</html>
<?php ob_end_flush(); ?>