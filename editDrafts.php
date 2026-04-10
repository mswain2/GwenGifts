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

$isEventManager = $_SESSION['access_level'] >= 2;
if (!$isEventManager) {
    echo "<div class='error-toast'>You do not have permission to edit drafts.</div>";
    exit;
}

function getUsersAndEmails() {
    $conn = connect();
    $members = [];
    $res = $conn->query("SELECT id, CONCAT(first_name,' ',last_name,' (',email,')') as label FROM dbpersons ORDER BY first_name");
    while ($row = $res->fetch_assoc()) {
        $members[] = ['label' => $row['label'], 'value' => $row['id']];
    }
    return $members;
}

$allMembers = getUsersAndEmails();

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

// Determine recipients value based on existing draft
$recipientsValue = ($draft['recipientID'] === 'all') ? 'all' : 'specific';

// Determine send type based on existing draft
$sendTypeValue = empty($draft['scheduledSend']) ? 'draft' : 'schedule';
// === Handle Form Submission ===
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $subject = $_POST['subject'] ?? '';
    $body = $_POST['body'] ?? '';
    $recipients = $_POST['recipients'] ?? 'all';
    $recipientID = ($recipients === 'all') ? 'all' : ($_POST['recipientID'] ?? '');
    $sendType = $_POST['sendType'] ?? 'draft';
    $scheduledSend = ($sendType === 'schedule') ? ($_POST['scheduledSend'] ?? null) : null;

    // Convert datetime-local to date for DB
    $sendDate = !empty($scheduledSend) ? date('Y-m-d', strtotime($scheduledSend)) : null;

    $update = $conn->prepare("
        UPDATE dbdrafts
        SET subject = ?, body = ?, recipientID = ?, scheduledSend = ?
        WHERE draftID = ?
    ");
    $update->bind_param("ssssi", $subject, $body, $recipientID, $sendDate, $draftID);

    if ($update->execute()) {
        $message = "<div class='happy-toast'>Draft updated successfully!</div>";
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

// Recalculate recipients value after potential update
$recipientsValue = ($draft['recipientID'] === 'all') ? 'all' : 'specific';

// Recalculate send type value after potential update
$sendTypeValue = empty($draft['scheduledSend']) ? 'draft' : 'schedule';

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
        <!--
        <label for="recipientID">Recipients:</label>
        <input type="text" id="recipientID" name="recipientID" value="<?php echo htmlspecialchars($draft['recipientID']); ?>">
        -->
        <label for="recipients">Recipients</label>
        <select name="recipients" id="recipients">
            <option value="all" <?php if ($recipientsValue == 'all') echo 'selected'; ?>>All Gwyneth's Gift Members</option>
            <option value="specific" <?php if ($recipientsValue == 'specific') echo 'selected'; ?>>Specific Users</option>
        </select>

        <div id="selectorRecipients" style="display:none;">
            <label for="recipientID">Select Member</label>
            <select id="recipientID" name="recipientID">
                <option value="">-- Select a Member --</option>
                <?php foreach ($allMembers as $m): ?>
                    <option value="<?= htmlspecialchars($m['value']) ?>" <?php if ($m['value'] == $draft['recipientID']) echo 'selected'; ?>><?= htmlspecialchars($m['label']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <label for="sendType">Send Type</label>
        <select name="sendType" id="sendType">
            <option value="draft" <?php if ($sendTypeValue == 'draft') echo 'selected'; ?>>Leave as Draft</option>
            <option value="schedule" <?php if ($sendTypeValue == 'schedule') echo 'selected'; ?>>Schedule Send</option>
        </select>

        <div id="scheduleDateDiv" style="display:none;">
            <label for="scheduledSend">Scheduled Date:</label>
            <input type="date" id="scheduledSend" name="scheduledSend" value="<?php echo htmlspecialchars($draft['scheduledSend']); ?>">
        </div>

        <button type="submit" class="submit-btn">Save Changes</button>
        <a class="button cancel" href="viewDrafts.php">Return to Drafts</a>
          
    </form>
</main>
<script>
    const recipientsSelect = document.getElementById('recipients');
    const recipientsDiv = document.getElementById('selectorRecipients');
    const sendTypeSelect = document.getElementById('sendType');
    const scheduleDateDiv = document.getElementById('scheduleDateDiv');

    function toggleRecipients() {
        recipientsDiv.style.display = recipientsSelect.value === 'specific' ? 'block' : 'none';
    }

    function toggleSchedule() {
        scheduleDateDiv.style.display = sendTypeSelect.value === 'schedule' ? 'block' : 'none';
    }

    recipientsSelect.addEventListener('change', toggleRecipients);
    sendTypeSelect.addEventListener('change', toggleSchedule);
    document.addEventListener('DOMContentLoaded', () => { 
        toggleRecipients(); 
        toggleSchedule(); 
    });
</script>
</body>
</html>
