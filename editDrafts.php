<?php
session_cache_expire(30);
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/database/dbinfo.php');
require_once(__DIR__ . '/email.php'); // optional, only if you need connect() or helper funcs

if (!isset($_SESSION['_id'])) {
    header('Location: login.php');
    exit;
}

$isAdmin = $_SESSION['access_level'] >= 2;
if (!$isAdmin) {
    echo "<div class='error-toast'>You do not have permission to edit drafts.</div>";
    exit;
}

// === Connect to DB ===
$conn = connect();

// === Fetch draft by ID ===
$draftID = $_GET['id'] ?? null;
if (!$draftID) {
    echo "<div class='error-toast'>No draft ID provided.</div>";
    exit;
}

$query = $conn->prepare("SELECT * FROM dbdrafts WHERE draftID = ?");
$query->bind_param("i", $draftID);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    echo "<div class='error-toast'>Draft not found.</div>";
    exit;
}

$draft = $result->fetch_assoc();
$query->close();

// === Handle Form Submission ===
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $subject = $_POST['subject'] ?? '';
    $body = $_POST['body'] ?? '';
    $recipientID = $_POST['recipientID'] ?? '';
    $scheduledSend = $_POST['scheduledSend'] ?? null;

    // Convert datetime-local to date for DB
    $sendDate = !empty($scheduledSend) ? date('Y-m-d', strtotime($scheduledSend)) : null;

    $update = $conn->prepare("
        UPDATE dbdrafts
        SET subject = ?, body = ?, recipientID = ?, scheduledSend = ?
        WHERE draftID = ?
    ");
    $update->bind_param("ssssi", $subject, $body, $recipientID, $sendDate, $draftID);

    if ($update->execute()) {
        $message = "<div class='success-toast'>Draft updated successfully!</div>";
        // Refresh data
        $draft['subject'] = $subject;
        $draft['body'] = $body;
        $draft['recipientID'] = $recipientID;
        $draft['scheduledSend'] = $sendDate;
    } else {
        $message = "<div class='error-toast'>Error updating draft: " . htmlspecialchars($update->error) . "</div>";
    }

    $update->close();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once('universal.inc'); ?>
    <title>Gwyneth's Gift | Edit Draft</title>
    <link href="css/base.css" rel="stylesheet">
    
</head>
<body>
    <?php require_once('header.php'); ?>

    <h1>Edit Draft</h1>
    <?php echo $message; ?>
    <main class="date">
    <form method="POST" action="">
        <label for="subject">Subject:</label>
        <input type="text" id="subject" name="subject" value="<?php echo htmlspecialchars($draft['subject']); ?>" required>

        <label for="body">Body:</label>
        <textarea id="body" name="body" rows="10"><?php echo htmlspecialchars($draft['body']); ?></textarea>

        <label for="recipientID">Recipients:</label>
        <input type="text" id="recipientID" name="recipientID" value="<?php echo htmlspecialchars($draft['recipientID']); ?>">

        <label for="scheduledSend">Scheduled Send:</label>
        <input type="date" id="scheduledSend" name="scheduledSend" value="<?php echo htmlspecialchars($draft['scheduledSend']); ?>">

        <button type="submit" class="submit-btn">Save Changes</button>
        <a class="button cancel" href="viewDrafts.php">Return to Drafts</a>
          
    </form>
</main>
</body>
</html>
