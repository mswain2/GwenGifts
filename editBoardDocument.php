<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_cache_expire(30);
    session_start();

    date_default_timezone_set("America/New_York");

    if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 1) {
        header('Location: login.php');
        die();
    }

    include_once('database/dbinfo.php');
    include_once('database/dbPersons.php');
    include_once('domain/Person.php');

    // Get user type
    $user_type = 'participant';
    if (isset($_SESSION['_id'])) {
        if ($_SESSION['_id'] === 'vmsroot') {
            $user_type = 'superadmin';
        } else {
            $person = retrieve_person($_SESSION['_id']);
            if ($person) $user_type = $person->get_type();
        }
    }

    // Only uploaders can edit
    $can_upload = in_array($user_type, ['event_manager', 'board_member', 'admin', 'superadmin']);
    if (!$can_upload) {
        header('Location: boardDocuments.php');
        die();
    }

    $connection = connect();
    $error = null;
    $success = false;

    // Get document id
    $doc_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if (!$doc_id) {
        header('Location: boardDocuments.php');
        die();
    }

    // Fetch the document
    $doc_result = mysqli_query($connection, "SELECT * FROM boarddocuments WHERE id = $doc_id AND deleted = 0");
    $doc = mysqli_fetch_assoc($doc_result);
    if (!$doc) {
        header('Location: boardDocuments.php');
        die();
    }

    // Clearance options based on user type
    $clearance_options_map = [
        'event_manager' => ['public', 'volunteer', 'manager'],
        'board_member'  => ['public', 'volunteer', 'board_member'],
        'admin'         => ['public', 'volunteer', 'manager', 'board_member', 'admin'],
        'superadmin'    => ['public', 'volunteer', 'manager', 'board_member', 'admin', 'superadmin'],
    ];
    $available_clearances = $clearance_options_map[$user_type] ?? ['public'];

    $clearance_labels = [
        'public'       => 'Public — Everyone can access',
        'volunteer'    => 'Volunteer — Volunteers and above',
        'manager'      => 'Manager — Event Managers and above',
        'board_member' => 'Board Member — Board Members, Admins, Super Admins',
        'admin'        => 'Admin — Admins and Super Admins only',
        'superadmin'   => 'Super Admin — Super Admins only',
    ];

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_name      = mysqli_real_escape_string($connection, trim($_POST['doc_name']));
        $new_clearance = trim($_POST['clearance_level']);

        if (empty($new_name)) {
            $error = "Document name cannot be empty.";
        } elseif (!in_array($new_clearance, $available_clearances)) {
            $error = "Invalid role access level selected.";
        } else {
            $update = "UPDATE boarddocuments SET doc_name = '$new_name', clearance_level = '$new_clearance' WHERE id = $doc_id AND deleted = 0";
            if (mysqli_query($connection, $update)) {
                header("Location: boardDocuments.php?edited=1");
                exit();
            } else {
                $error = "Failed to update document. Please try again.";
            }
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
    <title>Edit Document</title>
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
        .form-group select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-family: Quicksand, sans-serif;
            font-size: 15px;
            background-color: #f8f8f8;
        }
        .form-group input[type="text"]:focus,
        .form-group select:focus {
            outline: none;
            border-color: #6b8caf;
            background-color: #fff;
        }
        .form-group .hint {
            font-size: 13px;
            color: #828282;
            margin-top: 5px;
        }
        .file-info {
            background: #f8f8f8;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            color: #555;
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
    <h2><b>Edit Document</b></h2>

    <?php if ($error): ?>
        <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="doc_name">Document Name:</label>
            <input type="text" id="doc_name" name="doc_name"
                   value="<?php echo htmlspecialchars($doc['doc_name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="clearance_level">Role Access:</label>
            <select id="clearance_level" name="clearance_level" required>
                <?php foreach ($available_clearances as $cl): ?>
                    <option value="<?php echo $cl; ?>" <?php if ($doc['clearance_level'] === $cl) echo 'selected'; ?>>
                        <?php echo $clearance_labels[$cl] ?? ucfirst($cl); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="hint">Only users with the selected role or higher will see this document.</p>
        </div>

        <div class="form-group">
            <label>Attached File (cannot be changed):</label>
            <div class="file-info">
                <?php echo htmlspecialchars(basename($doc['file_path'])); ?>
            </div>
            <p class="hint">To change the file, delete this document and upload a new one.</p>
        </div>

        <button type="submit" class="btn-submit">Save Changes</button>
        <a href="boardDocuments.php" class="btn-cancel">Cancel</a>
    </form>
</div>

</body>
</html>
