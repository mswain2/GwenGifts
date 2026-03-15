<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_cache_expire(30);
    session_start();

    date_default_timezone_set("America/New_York");

    if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 1) {
        if (isset($_SESSION['change-password'])) {
            header('Location: changePassword.php');
        } else {
            header('Location: login.php');
        }
        die();
    }

    include_once('database/dbinfo.php');
    include_once('database/dbPersons.php');
    include_once('domain/Person.php');

    // Only admins and superadmins can access the trash
    $user_type = 'participant';
    if (isset($_SESSION['_id'])) {
        if ($_SESSION['_id'] === 'vmsroot') {
            $user_type = 'superadmin';
        } else {
            $person = retrieve_person($_SESSION['_id']);
            if ($person) $user_type = $person->get_type();
        }
    }

    if (!in_array($user_type, ['admin', 'superadmin'])) {
        header('Location: boardDocuments.php');
        die();
    }

    $connection = connect();

    // Handle restore
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['restore_id'])) {
        $restore_id = (int)$_POST['restore_id'];
        mysqli_query($connection, "UPDATE boarddocuments SET deleted = 0, deleted_at = NULL, deleted_by = NULL WHERE id = $restore_id");
        header('Location: boardDocumentsTrash.php?restored=1');
        exit();
    }

    // Fetch all soft-deleted documents
    $result = mysqli_query($connection, "SELECT * FROM boarddocuments WHERE deleted = 1 ORDER BY deleted_at DESC");

    $clearance_labels = [
        'public'       => 'Public',
        'volunteer'    => 'Volunteer',
        'manager'      => 'Manager',
        'board_member' => 'Board Member',
        'admin'        => 'Admin',
        'superadmin'   => 'Super Admin',
    ];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="./css/base.css" rel="stylesheet">
    <title>Document Trash</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Quicksand, sans-serif; background-color: #ffffff; }

        .page-container {
            max-width: 1000px;
            margin: 120px auto 60px auto;
            padding: 0 20px;
        }

        h2 { font-weight: normal; font-size: 30px; margin-bottom: 10px; }
        .subtitle {
            color: #828282;
            font-size: 15px;
            margin-bottom: 24px;
        }

        .back-btn {
            display: inline-block;
            background-color: #f0f0f0;
            color: #333;
            padding: 10px 24px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 24px;
            transition: background 0.2s ease;
        }
        .back-btn:hover { background-color: #e0e0e0; }

        .doc-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
        }
        .doc-table th {
            background-color: #f8f8f8;
            border: 1px solid #e0e0e0;
            padding: 12px 16px;
            text-align: left;
            font-weight: 700;
        }
        .doc-table td {
            border: 1px solid #e0e0e0;
            padding: 12px 16px;
            vertical-align: middle;
        }
        .doc-table tr:hover td { background-color: #fdf5f5; }

        .clearance-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 700;
        }
        .clearance-badge.public       { background-color: #e8f5e9; color: #2e7d32; }
        .clearance-badge.volunteer    { background-color: #e3f2fd; color: #1565c0; }
        .clearance-badge.manager      { background-color: #fff3e0; color: #e65100; }
        .clearance-badge.board_member { background-color: #f3e5f5; color: #6a1b9a; }
        .clearance-badge.admin        { background-color: #fce4ec; color: #880e4f; }
        .clearance-badge.superadmin   { background-color: #263238; color: #ffffff; }

        .restore-btn {
            background-color: #6b8caf;
            color: white;
            border: none;
            padding: 6px 16px;
            border-radius: 50px;
            font-family: Quicksand, sans-serif;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .restore-btn:hover { background-color: #57789a; }

        .empty-msg {
            color: #828282;
            font-size: 16px;
            margin-top: 20px;
        }

        .happy-toast {
            background: #d4edda;
            color: #155724;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 700;
        }
    </style>
</head>
<body>
<?php require 'header.php'; ?>

<div class="page-container">
    <h2><b>🗑 Document Trash</b></h2>
    <p class="subtitle">Documents moved to trash can be restored by admins. They are not visible to other users.</p>

    <?php if (isset($_GET['restored'])): ?>
        <div class="happy-toast">Document restored successfully!</div>
    <?php endif; ?>

    <a class="back-btn" href="boardDocuments.php">← Back to Documents</a>

    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <table class="doc-table">
            <thead>
                <tr>
                    <th>Document Name</th>
                    <th>Role Access</th>
                    <th>Uploaded At</th>
                    <th>Deleted At</th>
                    <th>Deleted By</th>
                    <th>Restore</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['doc_name']); ?></td>
                    <td>
                        <span class="clearance-badge <?php echo htmlspecialchars($row['clearance_level']); ?>">
                            <?php echo $clearance_labels[$row['clearance_level']] ?? ucfirst($row['clearance_level']); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($row['uploaded_at']); ?></td>
                    <td><?php echo htmlspecialchars($row['deleted_at'] ?? '—'); ?></td>
                    <td><?php echo htmlspecialchars($row['deleted_by'] ?? '—'); ?></td>
                    <td>
                        <form method="POST" action="boardDocumentsTrash.php">
                            <input type="hidden" name="restore_id" value="<?php echo (int)$row['id']; ?>">
                            <button type="submit" class="restore-btn">Restore</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="empty-msg">The trash is empty.</p>
    <?php endif; ?>
</div>

</body>
</html>
