<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_cache_expire(30);
    session_start();

    date_default_timezone_set("America/New_York");

    // Only admins / managers can upload training materials
    if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 2) {
        if (isset($_SESSION['change-password'])) {
            header('Location: changePassword.php');
        } else {
            header('Location: login.php');
        }
        die();
    }

    require_once('database/dbTrainingMaterials.php');

    $error = null;
    $eventID = $_GET['eventID'] ?? $_POST['eventID'] ?? '';

    if (!$eventID) {
        die("Missing event ID.");
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $connection = connect();
        $title = mysqli_real_escape_string($connection, trim($_POST['title']));
        $description = mysqli_real_escape_string($connection, trim($_POST['description']));
        $uploaded_by = $_SESSION['_id'];

        if (isset($_FILES['training_file']) && $_FILES['training_file']['error'] === 0) {
            $allowed_types = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'text/plain'
            ];

            $file_type = $_FILES['training_file']['type'];
            $file_name = basename($_FILES['training_file']['name']);
            $upload_dir = 'training_docs/';
            $unique_name = time() . '_' . $file_name;
            $target_path = $upload_dir . $unique_name;

            if (in_array($file_type, $allowed_types)) {
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                if (move_uploaded_file($_FILES['training_file']['tmp_name'], $target_path)) {
                    $ok = add_training_material(
                        $eventID,
                        $title,
                        $description,
                        $file_name,
                        $target_path,
                        $file_type,
                        $uploaded_by
                    );

                    if ($ok) {
                        header("Location: event.php?id=$eventID&trainingUploadSuccess=1");
                        exit();
                    } else {
                        $error = "Database insert failed.";
                    }
                } else {
                    $error = "Failed to save file. Make sure the training_docs/ folder exists in your project root.";
                }
            } else {
                $error = "Invalid file type. Please upload a PDF, Word, PowerPoint, or plain text file.";
            }
        } else {
            $error = "No file uploaded or an upload error occurred.";
        }

        mysqli_close($connection);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="./css/base.css" rel="stylesheet">
    <title>Add Training Material</title>
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
        .form-group input[type="file"],
        .form-group textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-family: Quicksand, sans-serif;
            font-size: 15px;
            background-color: #f8f8f8;
        }
        .form-group input[type="text"]:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #6b8caf;
            background-color: #fff;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
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
    <h2><b>Add Training Material</b></h2>

    <?php if ($error): ?>
        <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="eventID" value="<?php echo htmlspecialchars($eventID); ?>">

        <div class="form-group">
            <label for="title">Material Title:</label>
            <input type="text" id="title" name="title" placeholder="e.g. Volunteer Orientation Packet" required>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" placeholder="Optional short description of the training material"></textarea>
        </div>

        <div class="form-group">
            <label for="training_file">Attach File (PDF, Word, PowerPoint, or TXT):</label>
            <input type="file" id="training_file" name="training_file" accept=".pdf,.doc,.docx,.ppt,.pptx,.txt" required>
        </div>

        <button type="submit" class="btn-submit">Upload Material</button>
        <a href="event.php?id=<?php echo urlencode($eventID); ?>" class="btn-cancel">Cancel</a>
    </form>
</div>

</body>
</html>