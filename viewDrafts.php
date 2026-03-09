<?php
session_cache_expire(30);
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/database/dbinfo.php');
require_once('include/input-validation.php');

if (!isset($_SESSION['_id'])) {
    header('Location: login.php');
    exit;
}

$isAdmin = $_SESSION['access_level'] >= 2;
$userId = $_SESSION['_id'];
$message = '';

if (!$isAdmin) {
    echo "<div class='error-toast'>You do not have permission to view this page.</div>";
    exit();
}

$connection = connect();

// === Delete Draft (if requested) ===
if (isset($_GET['delete'])) {
    $draftId = intval($_GET['delete']);
    $stmt = $connection->prepare("DELETE FROM dbdrafts WHERE draftID = ? AND userID = ?");
    $stmt->bind_param("is", $draftId, $userId);
    $stmt->execute();
    $stmt->close();
    $message = "<div class='success-toast'>Draft deleted successfully!</div>";
}

// === Retrieve Drafts for Current User ===
$query = "SELECT draftID, subject, recipientID, scheduledSend
          FROM dbdrafts
          WHERE userID = ?
          ORDER BY scheduledSend DESC, subject ASC";

$stmt = $connection->prepare($query);
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

$drafts = [];
while ($row = $result->fetch_assoc()) {
    $drafts[] = $row;
}

$stmt->close();
mysqli_close($connection);
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once('universal.inc'); ?>
    <title>Gwyneth's Gift | Email Drafts</title>
    <link href="css/base.css" rel="stylesheet">
</head>
<body>
    <?php require_once('header.php'); ?>
    <h1>Your Drafts</h1>

    <div class="drafts-container">
        
        <?php echo $message; ?>

        <?php if (empty($drafts)): ?>
            <p>No drafts found.</p>
        <?php else: ?>
            <table class="drafts-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Recipients</th>
                        <th>Scheduled Send</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($drafts as $draft): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($draft['subject']); ?></td>
                            <td><?php echo htmlspecialchars($draft['recipientID']); ?></td>
                            <td><?php echo htmlspecialchars($draft['scheduledSend'] ?? '—'); ?></td>
                            <td class="actions">
                                <a class="btn-edit" href="editDrafts.php?id=<?php echo $draft['draftID']; ?>">Edit</a>
                                <a class="btn-send" href="sendDraft.php?id=<?php echo $draft['draftID']; ?>" onclick="return confirm('Send this draft now?');">Send</a>
                                <a class="btn-delete" href="?delete=<?php echo $draft['draftID']; ?>" onclick="return confirm('Delete this draft?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <button class="button cancel" href="index.php">Return to Dashboard</a>
    </div>
</body>
</html>


