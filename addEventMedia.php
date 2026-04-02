<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_cache_expire(30);
session_start();

date_default_timezone_set("America/New_York");

require_once('include/input-validation.php');
require_once('database/dbEventMedia.php');

$error = null;
$eventID = $_GET['eventID'] ?? $_POST['eventID'] ?? '';

if (!$eventID) {
    die("Missing event ID.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $args = sanitize($_POST, null);
    $required = [
        'url',
        'description',
        'format',
        'eventID'
    ];
    if (!wereRequiredFieldsSubmitted($args, $required)) {
        echo "dude, args missing";
        die();
    }
    $type = 'post';
    $format = $args['format'];
    $url = $args['url'];
    if ($format == 'video') {
        $url = convertYouTubeURLToEmbedLink($url);
        if (!$url) {
            echo "bad video link";
            die();
        }
    } else if (!validateURL($url)) {
        echo "bad url";
        die();
    }
    $eid = $args['eventID'];
    $description = $args['description'];
    $uploaded_by = $_SESSION['_id'];
    if (!valueConstrainedTo($format, ['link', 'video', 'picture'])) {
        echo "bad format";
        die();
    }
    add_event_media($eid, $url, $format, $description, $uploaded_by);
    header('Location: event.php?id=' . $eid . '&attachSuccess');
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
    <title>Gwyneth's Gift | Add Event Media
    </title>
</head>

<body>
    <?php require 'header.php'; ?>
    <h1>Add Event Media</h1>
    <main class="event_media">

        <?php if ($error): ?>
            <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="eventID" value="<?php echo htmlspecialchars($eventID); ?>">

            <label for="post-url">URL</label>
            <input type="text" id="post-url" name="url" placeholder="Paste link to media" required>
            <p class="error hidden" id="post-url-error">Please enter a valid URL.</p>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" placeholder="Short description of the event media" required></textarea>
            </div>

            <label for="post-format">Format</label>
            <select id="post-format" name="format">
                <option value="link">Link</option>
                <option value="video">YouTube video (embeds video)</option>
                <option value="picture">Picture (embeds picture)</option>
            </select>

            <button type="submit" class="btn-submit">Upload Media</button>
            <a href="event.php?id=<?php echo urlencode($eventID); ?>" class="button cancel">Cancel</a>
        </form>
    </main>

</body>

</html>