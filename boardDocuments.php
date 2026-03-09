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
    $connection = connect();

    // Fetch all board documents
    $query = "SELECT * FROM boarddocuments ORDER BY uploaded_at DESC";
    $result = mysqli_query($connection, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="./css/base.css" rel="stylesheet">
    <title>Board Documents</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Quicksand, sans-serif; background-color: #ffffff; }

        .page-container {
            max-width: 900px;
            margin: 120px auto 60px auto;
            padding: 0 20px;
        }

        h2 { font-weight: normal; font-size: 30px; margin-bottom: 20px; }

        .add-btn {
            display: inline-block;
            background-color: #6b8caf;
            color: white;
            padding: 10px 24px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 30px;
            transition: background 0.2s ease;
        }
        .add-btn:hover { background-color: #57789a; }

        .doc-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 16px;
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
        }
        .doc-table tr:hover td { background-color: #f5f5f5; }

        .doc-table a {
            color: #6b8caf;
            font-weight: 700;
            text-decoration: none;
        }
        .doc-table a:hover { text-decoration: underline; }

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
    <h2><b>Board Documents</b></h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="happy-toast">Document uploaded successfully!</div>
    <?php endif; ?>

    <a class="add-btn" href="addBoardDocument.php">+ Add Document</a>

    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <table class="doc-table">
            <thead>
                <tr>
                    <th>Document Name</th>
                    <th>Uploaded At</th>
                    <th>Download</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['doc_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['uploaded_at']); ?></td>
                    <td>
                        <a href="<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank">View / Download</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="empty-msg">No documents have been uploaded yet.</p>
    <?php endif; ?>
</div>

</body>
</html>
