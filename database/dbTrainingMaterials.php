<?php
require_once('dbinfo.php');

function add_training_material($eventID, $title, $description, $fileName, $filePath, $fileType, $uploadedBy) {
    $con = connect();

    $eventID = mysqli_real_escape_string($con, $eventID);
    $title = mysqli_real_escape_string($con, $title);
    $description = mysqli_real_escape_string($con, $description);
    $fileName = mysqli_real_escape_string($con, $fileName);
    $filePath = mysqli_real_escape_string($con, $filePath);
    $fileType = mysqli_real_escape_string($con, $fileType);
    $uploadedBy = mysqli_real_escape_string($con, $uploadedBy);

    $query = "
        INSERT INTO dbtraining_materials
        (eventID, title, description, file_name, file_path, file_type, uploaded_by)
        VALUES
        ('$eventID', '$title', '$description', '$fileName', '$filePath', '$fileType', '$uploadedBy')
    ";

    $result = mysqli_query($con, $query);
    mysqli_close($con);
    return $result;
}

function get_training_materials_by_event($eventID) {
    $con = connect();
    $eventID = mysqli_real_escape_string($con, $eventID);

    $query = "
        SELECT *
        FROM dbtraining_materials
        WHERE eventID = '$eventID' AND is_active = 1
        ORDER BY uploaded_at DESC
    ";

    $result = mysqli_query($con, $query);
    $materials = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $materials[] = $row;
    }

    mysqli_close($con);
    return $materials;
}

function get_training_materials_by_user($userID) {
    $con = connect();
    $userID = mysqli_real_escape_string($con, $userID);

    $query = "
        SELECT tm.*, e.name AS event_name, e.startDate AS event_date
        FROM dbtraining_materials tm
        JOIN dbeventpersons ep ON ep.eventID = tm.eventID
        JOIN dbevents e ON e.id = tm.eventID
        WHERE ep.userID = '$userID'
          AND tm.is_active = 1
        ORDER BY e.startDate ASC, tm.uploaded_at DESC
    ";

    $result = mysqli_query($con, $query);
    $materials = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $materials[] = $row;
    }

    mysqli_close($con);
    return $materials;
}

function delete_training_material($id) {
    $con = connect();
    $id = mysqli_real_escape_string($con, $id);

    $getQuery = "SELECT file_path FROM dbtraining_materials WHERE id = '$id'";
    $getResult = mysqli_query($con, $getQuery);
    $row = mysqli_fetch_assoc($getResult);

    if ($row && !empty($row['file_path']) && file_exists($row['file_path'])) {
        unlink($row['file_path']);
    }

    $query = "DELETE FROM dbtraining_materials WHERE id = '$id'";
    $result = mysqli_query($con, $query);

    mysqli_close($con);
    return $result;
}
?>