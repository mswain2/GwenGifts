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

    $error = null;

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $connection = connect();
        $doc_name = mysqli_real_escape_string($connection, trim($_POST['doc_name']));
        $uploaded_by = $_SESSION['_id'];

        if (isset($_FILES['doc_file']) && $_FILES['doc_file']['error'] === 0) {
            $allowed_types = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'text/plain'
            ];

            $file_type = $_FILES['doc_file']['type'];
            $file_name = basename($_FILES['doc_file']['name']);
            $upload_dir = 'board_docs/';
            $unique_name = time() . '_' . $file_name;
            $target_path = $upload_dir . $unique_name;

            if (in_array($file_type, $allowed_types)) {
                if (move_uploaded_file($_FILES['doc_file']['tmp_name'], $target_path)) {
                    $insertQuery = "INSERT INTO boarddocuments (doc_name, file_path, uploaded_by) 
                                    VALUES ('$doc_name', '$target_path', '$uploaded_by')";
                    mysqli_query($connection, $insertQuery);
                    header("Location: boardDocuments.php?success=1");
                    exit();
                } else {
                    $error = "Failed to save file. Make sure the board_docs/ folder exists in your project root.";
                }
            } else {
                $error = "Invalid file type. Please upload a PDF, Word, Excel, or plain text file.";
            }
        } else {
            $error = "No file uploaded or an upload error occurred.";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="./css/base.css" rel="stylesheet">
    <title>Add Board Document</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Quicksand, sans-serif; background-color: #ffffff; }

        .page-container {
            max-width: 600px;
            margin: 120px auto 60px auto;
            padding: 0 20px;
        }

        h2 { font-weight: normal; font-size: 30px; margin-bottom: 30px; }

        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: 700;
            margin-bottom: 8px;
            font-size: 16px;
        }
        .form-group input[type="text"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-family: Quicksand, sans-serif;
            font-size: 15px;
            background-color: #f8f8f8;
        }
        .form-group input[type="text"]:focus {
            outline: none;
            border-color: #6b8caf;
            background-color: #fff;
        }

        .btn-submit {
            background-color: #6b8caf;
            color: white;
            padding: 10px 28px;
            border: none;
            border-radius: 50px;
            font-family: Quicksand, sans-serif;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s ease;
            margin-right: 10px;
        }
        .btn-submit:hover { background-color: #57789a; }

        .btn-cancel {
            display: inline-block;
            background-color: #f0f0f0;
            color: #333;
            padding: 10px 28px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 16px;
            transition: background 0.2s ease;
        }
        .btn-cancel:hover { background-color: #e0e0e0; }

        .error-msg {
            background: #f8d7da;
            color: #721c24;
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
    <h2><b>Add Board Document</b></h2>

    <?php if ($error): ?>
        <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="doc_name">Document Name:</label>
            <input type="text" id="doc_name" name="doc_name" placeholder="e.g. Board Minutes – March 2025" required>
        </div>

        <div class="form-group">
            <label for="doc_file">Attach File (PDF, Word, Excel, or TXT):</label>
            <input type="file" id="doc_file" name="doc_file" accept=".pdf,.doc,.docx,.xls,.xlsx,.txt" required>
        </div>

        <button type="submit" class="btn-submit">Upload Document</button>
        <a href="boardDocuments.php" class="btn-cancel">Cancel</a>
    </form>
</div>

</body>
</html>
