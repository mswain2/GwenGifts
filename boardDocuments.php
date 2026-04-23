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

$user_type = 'participant';
if (isset($_SESSION['_id'])) {
    if ($_SESSION['_id'] === 'vmsroot') {
        $user_type = 'superadmin';
    } else {
        $person = retrieve_person($_SESSION['_id']);
        if ($person) $user_type = $person->get_type();
    }
}

$clearance_map = [
    'participant'   => [],
    'volunteer'     => ['volunteer'],
    'event_manager' => ['volunteer', 'event_manager'],
    'board_member'  => ['volunteer', 'board_member'],
    'admin'         => ['volunteer', 'event_manager', 'board_member', 'admin'],
    'superadmin'    => ['volunteer', 'event_manager', 'board_member', 'admin', 'superadmin'],
];
$allowed_clearances = $clearance_map[$user_type] ?? ['public'];
$is_admin   = in_array($user_type, ['admin', 'superadmin']);
$can_upload = in_array($user_type, ['event_manager', 'board_member', 'admin', 'superadmin']);

$search_name      = isset($_GET['search_name'])      ? trim($_GET['search_name'])      : '';
$filter_clearance = isset($_GET['filter_clearance']) ? trim($_GET['filter_clearance']) : '';
$filter_sort      = isset($_GET['filter_sort'])      ? trim($_GET['filter_sort'])      : 'date_desc';

$clearance_labels = [
    'volunteer'     => 'Volunteer',
    'event_manager' => 'Event Manager',
    'board_member'  => 'Board Member',
    'admin'         => 'Admin',
    'superadmin'    => 'Super Admin',
];
    $connection = connect();

// SCRUM-125: Handle bulk soft delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_delete_ids']) && $is_admin) {
    $ids = array_map('intval', (array)$_POST['bulk_delete_ids']);
    if (!empty($ids)) {
        $id_list = implode(',', $ids);
        $safe_user = mysqli_real_escape_string($connection, $_SESSION['_id']);
        mysqli_query($connection, "UPDATE boarddocuments SET deleted = 1, deleted_at = NOW(), deleted_by = '$safe_user' WHERE id IN ($id_list) AND deleted = 0");
    }
    header('Location: boardDocuments.php?bulk_deleted=1');
    exit();
}

    $escaped_clearances = array_map(fn($c) => "'" . mysqli_real_escape_string($connection, $c) . "'", $allowed_clearances);
$clearance_in = implode(',', $escaped_clearances);

$where = "WHERE deleted = 0 AND clearance_level IN ($clearance_in)";
if ($search_name !== '') {
    $safe_name = mysqli_real_escape_string($connection, $search_name);
    $where .= " AND doc_name LIKE '%$safe_name%'";
}
if ($filter_clearance !== '' && in_array($filter_clearance, $allowed_clearances)) {
    $safe_clearance = mysqli_real_escape_string($connection, $filter_clearance);
    $where .= " AND clearance_level = '$safe_clearance'";
}
$order = match($filter_sort) {
    'date_asc'   => 'ORDER BY uploaded_at ASC',
    'name_asc'   => 'ORDER BY doc_name ASC',
    'name_desc'  => 'ORDER BY doc_name DESC',
    'clearance'  => 'ORDER BY FIELD(clearance_level,"volunteer","event_manager","board_member","admin","superadmin")',
    default      => 'ORDER BY uploaded_at DESC',
};
$query = "SELECT * FROM boarddocuments $where $order";
$result = mysqli_query($connection, $query);
    $result = mysqli_query($connection, $query);
$all_docs_result = null;
if ($is_admin) {
    $all_docs_result = mysqli_query($connection, "SELECT id, doc_name FROM boarddocuments WHERE deleted = 0 ORDER BY doc_name ASC");
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="./css/base.css" rel="stylesheet">
    <title>Documents</title>
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
            display: inline-flex;
            align-items: center;
            background-color: var(--main-color);
            color: white;
            padding: 10px 24px;
            border-radius: 0.25rem;
            text-decoration: none;
            font-weight: 700;
            font-size: 16px;
            transition: background 0.2s ease;
        }
        .add-btn:hover { background-color: var(--accent-color); color: white;}
        .top-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        .trash-btn {
            display: inline-flex;
            align-items: center;
            background-color: var(--main-color);
            color: white;
            padding: 10px 24px;
            border-radius: 0.25rem;
            text-decoration: none;
            font-weight: 700;
            font-size: 16px;
            transition: background 0.2s ease;
        }
        .trash-btn:hover { background-color: var(--accent-color); color: white;}
        .filter-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 24px;
            flex-wrap: wrap;
            align-items: center;
        }
        .filter-bar input[type="text"],
        .filter-bar select {
            padding: 8px 14px;
            border: 1px solid #e0e0e0;
            border-radius: 0.25rem;
            font-family: Quicksand, sans-serif;
            font-size: 14px;
            background-color: #f8f8f8;
            outline: none;
        }
        .filter-bar button {
            padding: 8px 20px;
            background-color: var(--main-color);
            color: white;
            border: none;
            border-radius: 0.25rem;
            font-family: Quicksand, sans-serif;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
        }
        .filter-bar button:hover { background-color: var(--accent-color); }
        .filter-bar a.clear-btn {
            padding: 8px 16px;
            background-color: var(--secondary-accent-color);
            color: white;
            border-radius: 0.25rem;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
        }
        .bulk-btn {
            display: 8px 20px;
            align-items: left;
            background-color: var(--secondary-accent-color);
            color: white;
            padding: 10px 24px;
            border-radius: 0.25rem;
            border: none;
            font-family: Quicksand, sans-serif;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.2s ease;
            width: auto;
            margin: 0;
        }
        .bulk-btn:hover { background-color: var(--accent-color); color: white; }
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .modal-overlay.active { display: flex; }
        .modal-box {
            background: white;
            border-radius: 12px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }
        .modal-box h3 { font-size: 20px; margin-bottom: 16px; font-weight: 700; }
        .modal-doc-list { list-style: none; margin-bottom: 20px; }
        .modal-doc-list li { padding: 8px 4px; border-bottom: 1px solid #e0e0e0; display: flex; align-items: center; gap: 10px; }
        .modal-doc-list li:last-child { border-bottom: none; }
        .modal-actions { display: flex; gap: 10px; flex-wrap: wrap; }
        .modal-delete-btn { background-color: var(--secondary-accent-color); color: white; border: none; padding: 10px 24px; border-radius: 50px; font-family: Quicksand, sans-serif; font-weight: 700; font-size: 15px; cursor: pointer; }
        .modal-delete-btn:hover { background-color: var(--accent-color); }
        .modal-cancel-btn { background-color: #f0f0f0; color: #333; border: none; padding: 10px 24px; border-radius: 50px; font-family: Quicksand, sans-serif; font-weight: 700; font-size: 15px; cursor: pointer; }
        .modal-cancel-btn:hover { background-color: #e0e0e0; }
        .bottom-bar { margin-top: 30px; display: flex; gap: 12px; flex-wrap: wrap; }
        .return-btn { display: inline-flex; align-items: center; background-color: var(--secondary-accent-color); color: white; padding: 10px 24px; border-radius: 0.25rem; text-decoration: none; font-weight: 700; font-size: 16px; transition: background 0.2s ease; }
        .return-btn:hover { background-color: var(--accent-color); color: white; }

        .clearance-badge { display: inline-block; padding: 3px 10px; border-radius: 50px; font-size: 12px; font-weight: 700; }
        .clearance-badge.public       { background-color: #e8f5e9; color: #2e7d32; }
        .clearance-badge.volunteer    { background-color: #e3f2fd; color: #1565c0; }
        .clearance-badge.manager      { background-color: #fff3e0; color: #e65100; }
        .clearance-badge.board_member { background-color: #f3e5f5; color: #6a1b9a; }
        .clearance-badge.admin        { background-color: #fce4ec; color: #880e4f; }
        .clearance-badge.superadmin   { background-color: #263238; color: #ffffff; }
        .delete-btn { background: none; border: none; color: #cc0000; font-size: 18px; cursor: pointer; padding: 4px 8px; border-radius: 0.25rem; }
        .delete-btn:hover { background-color: var(--accent-color); }
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
            color: var(--main-color);
            font-weight: 700;
            text-decoration: none;
        }
        .doc-table a:hover { text-decoration: underline; }

        .empty-msg {
            color: #828282;
            font-size: 16px;
            margin-top: 20px;
        }

    </style>
</head>
<body>
<?php require 'header.php'; ?>

<div class="page-container">
    <h2><b>Documents</b></h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="happy-toast">Document uploaded successfully!</div>
    <?php elseif (isset($_GET['deleted'])): ?>
        <div class="happy-toast">Document moved to trash.</div>
    <?php elseif (isset($_GET['edited'])): ?>
        <div class="happy-toast">Document updated successfully!</div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="error-toast">An error occurred. Please try again.</div>
    <?php elseif (isset($_GET['bulk_deleted'])): ?>
        <div class="happy-toast">Selected documents moved to trash.</div>
    <?php endif; ?>    
        <div class="top-bar">
        <?php if ($is_admin): ?>
            
        <?php endif; ?>
        <?php if ($can_upload): ?>
            <a class="add-btn" href="addBoardDocument.php">+ Add Document</a>
        <?php endif; ?>
        <?php if ($is_admin): ?>
            <a class="trash-btn" href="boardDocumentsTrash.php">🗑 View Trash</a>
            <button class="bulk-btn" onclick="document.getElementById('bulk-delete-modal').classList.add('active')">🗑 Delete Documents</button>
        <?php endif; ?>
    </div>

    <form method="GET" action="boardDocuments.php">
        <div class="filter-bar">
            <input type="text" name="search_name" placeholder="Search by name..."
                value="<?php echo htmlspecialchars($search_name); ?>">
            <select name="filter_clearance">
                <option value="">All Roles</option>
                <?php foreach ($allowed_clearances as $cl): ?>
                    <option value="<?php echo $cl; ?>" <?php if ($filter_clearance === $cl) echo 'selected'; ?>>
                        <?php echo $clearance_labels[$cl] ?? ucfirst($cl); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="filter_sort">
                <option value="date_desc">Newest First</option>
                <option value="date_asc">Oldest First</option>
                <option value="name_asc">Name A–Z</option>
                <option value="name_desc">Name Z–A</option>
                <option value="clearance">By Role Level</option>
            </select>
            <button type="submit">Search</button>
            <a href="boardDocuments.php" class="clear-btn">Clear</a>
        </div>
    </form>

    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <table class="doc-table">
            <thead>
                <tr>
                    <th>Document Name</th>
                    <th>Role Access</th>
                    <th>Uploaded At</th>
                    <th>Download</th>
                    <?php if ($is_admin): ?><th>Remove</th><?php endif; ?>
                    <?php if ($can_upload): ?><th>Edit</th><?php endif; ?>                </tr>
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
                    <td>
                        <a class="view-link" href="<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank">View / Download</a>
                    </td>
                    <?php if ($is_admin): ?>
                    <td>
                        <form method="POST" action="deleteBoardDocument.php"
                              onsubmit="return confirm('Move this document to trash?');">
                            <input type="hidden" name="doc_id" value="<?php echo (int)$row['id']; ?>">
                            <button type="submit" class="delete-btn" title="Move to trash">🗑</button>
                        </form>
                    </td>
                    <?php endif; ?>
                    <?php if ($can_upload): ?>
                    <td>
                        <a href="editBoardDocument.php?id=<?php echo (int)$row['id']; ?>"
                           style="color:var(--main-color);font-weight:700;text-decoration:none;"
                           onmouseover="this.style.textDecoration='underline'"
                           onmouseout="this.style.textDecoration='none'">Edit</a>
                    </td>
                    <?php endif; ?>
                </tr>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="empty-msg">No documents have been uploaded yet.</p>
    <?php endif; ?>
    <div class="bottom-bar">
    <a class="return-btn" href="index.php">← Return to Dashboard</a>
</div>
</div>
    <?php if ($is_admin && $all_docs_result): ?>
    <div class="modal-overlay" id="bulk-delete-modal">
        <div class="modal-box">
            <h3>🗑 Bulk Delete Documents</h3>
            <p style="color:#828282;font-size:14px;margin-bottom:16px;">Select documents to move to trash. This can be undone from the trash bin.</p>
            <form method="POST" action="boardDocuments.php" onsubmit="return confirmBulkDelete();">
                <ul class="modal-doc-list">
                    <?php while ($doc = mysqli_fetch_assoc($all_docs_result)): ?>
                    <li>
                        <input type="checkbox" name="bulk_delete_ids[]" value="<?php echo (int)$doc['id']; ?>" id="doc_<?php echo (int)$doc['id']; ?>">
                        <label for="doc_<?php echo (int)$doc['id']; ?>"><?php echo htmlspecialchars($doc['doc_name']); ?></label>
                    </li>
                    <?php endwhile; ?>
                </ul>
                <div class="modal-actions">
                    <button type="submit" class="modal-delete-btn">Delete All Selected</button>
                    <button type="button" class="modal-cancel-btn" onclick="document.getElementById('bulk-delete-modal').classList.remove('active')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <script>
    function confirmBulkDelete() {
        var checked = document.querySelectorAll('#bulk-delete-modal input[type="checkbox"]:checked');
        if (checked.length === 0) { alert('Please select at least one document.'); return false; }
        return confirm('Move ' + checked.length + ' document(s) to trash?');
    }
    document.getElementById('bulk-delete-modal').addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('active');
    });
    </script>
    <?php endif; ?>
</body>
</html>
