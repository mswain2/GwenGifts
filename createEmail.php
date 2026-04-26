<?php
session_cache_expire(30);
session_start();
ini_set("display_errors",1);
error_reporting(E_ALL);

// Admin check
if(!isset($_SESSION['_id'])) {
    header('Location: login.php');
    exit;
}

require_once(__DIR__ . '/database/dbinfo.php');
require_once(__DIR__ . '/database/dbPersons.php');
include_once(__DIR__ . '/email.php');

// Manual PHPMailer include
require_once __DIR__ . '/email/PHPMailer/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/email/PHPMailer/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/email/PHPMailer/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$allMembers = getUsersAndEmails();

// Load .env file
$env = loadEnv(__DIR__ . '/email/.env');
$missingEnvKeys = validateSmtpEnv($env);

// ------------------------
// Form handling
// ------------------------
$isEventManager = $_SESSION['access_level'] >= 2;
$submissionMessage = '';

if ($isEventManager && $_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST['action'] ?? 'send';
    $subject = trim($_POST['subject'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $sendNowStr = $_POST['scheduled'] ?? 'true';
    $sendDate = $_POST['sendTime'] ?? '';
    $recipientsType = $_POST['recipients'] ?? 'all';
    $recipientID = $_POST['recipientID'] ?? '';

    $sendNow = ($sendNowStr === 'true');

    // Collect recipient IDs
    $recipientIDs = [];
    if ($recipientsType === 'specific' && !empty($recipientID)) {
        $recipientIDs = [$recipientID];
    }

    // ------------------------------------------------------
    // ACTION: SAVE DRAFT
    // ------------------------------------------------------
    if ($action === 'draft') {

        $conn = connect();
        $stmt = $conn->prepare("
            INSERT INTO dbdrafts (userID, subject, body, recipientID)
            VALUES (?, ?, ?, ?)
        ");

        $uid = (string)$_SESSION['_id'];

        // use the real recipientID selected from the form
        // If no recipient selected, store "all"
        $rid = $recipientID !== '' ? $recipientID : $recipientsType;


        $stmt->bind_param("ssss", 
            $uid, 
            $subject, 
            $content, 
            $rid
        );

        if (!$stmt->execute()) {
            $submissionMessage = "<div class='error-toast'>Failed to save draft: {$stmt->error}</div>";
        } else {
            $submissionMessage = "<div class='happy-toast'>Draft saved!</div>";
        }

        $stmt->close();
    }


    // ------------------------------------------------------
    // ACTION: SEND (NOW / SCHEDULE)
    // ------------------------------------------------------
    else if ($action === 'send') {

        if (empty($subject)) {
            $submissionMessage = "<div class='error-toast'>Email Subject is required.</div>";
        } else if ($recipientsType === 'specific' && empty($recipientID)) {
            $submissionMessage = "<div class='error-toast'>Please select a specific recipient.</div>";
        } else {

            $result = submitEmail($recipientIDs, 0, $subject, $content, $sendNow, $sendDate, $recipientsType);

            if ($result['success']) {
                $submissionMessage = "<div class='happy-toast'>Email successfully sent/scheduled!</div>";
            } else {
                $submissionMessage = "<div class='error-toast'>Errors:<br>" . implode("<br>", $result['errors']) . "</div>";
            }
        }
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once('universal.inc'); ?>
    <title>Gwyneth's Gift | Create Email</title>
    <link rel="stylesheet" href="css/base.css">
</head>
<body>
<?php require_once('header.php'); ?>
<h1>Create Email</h1>

<?php if (!$isEventManager): ?>
    <div class='error-toast'>You do not have permission to view this page.</div>
<?php else: ?>

<main class="date">
<?=$submissionMessage ?>
<?php if (!empty($missingEnvKeys)): ?>
    <div class='error-toast'>Missing SMTP configuration: <?= htmlspecialchars(implode(', ', $missingEnvKeys)) ?>. Create email/.env with these keys.</div>
<?php endif; ?>

    <form method="POST">
        <label for="subject">* Email Subject</label>
        <input type="text" id="subject" name="subject" required>

        <label for="content">Email Body</label>
        <textarea id="content" name="content" rows="10"></textarea>

        <label for="scheduled">Send Now?</label>
        <select name="scheduled" id="scheduled">
            <option value="true">Yes</option>
            <option value="false">No (Schedule)</option>
        </select>

        <div id="selectorTime" style="display:none;">
            <label for="sendTime">Send Date</label>
            <input type="date" id="sendTime" name="sendTime">
        </div>

        <label for="recipients">Recipients (only those consenting to email)</label>
        <select name="recipients" id="recipients">
            <option value="all">All Gwyneth's Gift Members</option>
            <option value="vols">Volunteers</option>
            <option value="ems">Event Managers</option>
            <option value="bms">Board Members</option>
            <option value="admin">Administrators</option>
            <option value="specific">Specific User</option>
        </select>

        <div id="selectorRecipients" style="display:none;">
            <label for="recipientID">Select Member (only members consenting to email will appear)</label>
            <select id="recipientID" name="recipientID">
                <option value="">-- Select a Member or Type to Search --</option>
                <?php foreach ($allMembers as $m): ?>
                    <option value="<?= htmlspecialchars($m['value']) ?>"><?= htmlspecialchars($m['label']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" name="action" value="send" class="submit-btn">Send Email</button>
        <button type="submit" name="action" value="draft" class="draft-btn">Save Draft</button>

    </form>

    <a class="button cancel" href="index.php">Return to Dashboard</a>
</main>

<script>
const scheduledSelect = document.getElementById('scheduled');
const timeDiv = document.getElementById('selectorTime');
const sendTimeInput = document.getElementById('sendTime');
const recipientsSelect = document.getElementById('recipients');
const recipientsDiv = document.getElementById('selectorRecipients');
const recipientID = document.getElementById('recipientID');

function toggleTime() {
    const sendNow = scheduledSelect.value === 'true';
    timeDiv.style.display = sendNow ? 'none' : 'block';
    sendTimeInput.required = !sendNow;
}

function toggleRecipients() {
    recipientsDiv.style.display = recipientsSelect.value === 'specific' ? 'block' : 'none';
    recipientID.required = recipientsSelect.value === 'specific';
}

scheduledSelect.addEventListener('change', toggleTime);
recipientsSelect.addEventListener('change', toggleRecipients);
document.addEventListener('DOMContentLoaded', () => { toggleTime(); toggleRecipients(); });
</script>

<?php endif; ?>
</body>
</html>




