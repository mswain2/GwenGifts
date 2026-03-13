<?php
    session_start();
    date_default_timezone_set("America/New_York");

    if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 1) {
        header('Location: login.php');
        die();
    }

    include_once('database/dbinfo.php');
    include_once('database/dbPersons.php');
    include_once('domain/Person.php');

    // Only admins and superadmins can delete
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
        header('Location: boardDocuments.php?error=1');
        die();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['doc_id'])) {
        $connection = connect();
        $doc_id     = (int)$_POST['doc_id'];
        $deleted_by = mysqli_real_escape_string($connection, $_SESSION['_id']);
        $deleted_at = date('Y-m-d H:i:s');

        $query = "UPDATE boarddocuments 
                  SET deleted = 1, deleted_at = '$deleted_at', deleted_by = '$deleted_by'
                  WHERE id = $doc_id AND deleted = 0";
        $result = mysqli_query($connection, $query);

        if ($result && mysqli_affected_rows($connection) > 0) {
            header('Location: boardDocuments.php?deleted=1');
        } else {
            header('Location: boardDocuments.php?error=1');
        }
    } else {
        header('Location: boardDocuments.php');
    }
    exit();
?>
