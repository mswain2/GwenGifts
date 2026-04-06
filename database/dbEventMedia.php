<?php

require_once('database/dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Event.php');
date_default_timezone_set("America/New_York");


function add_event_media($eid, $url, $format, $description, $uploaded_by) {
    $con = connect();
    $query = $query = "
        insert into dbeventmedia (eventID, url, format, description, uploaded_by)
        values ('$eid', '$url', '$format', '$description', '$uploaded_by')
    ";

    $result = mysqli_query($con, $query);
    if (!$result) {
        return null;
    }

    mysqli_close($con);
    return false;
}


function get_event_media($eventID) {
    $connection = connect();

    $query = "select * from dbeventmedia
              where eventID='$eventID'
              order by uploaded_at";

    $result = mysqli_query($connection, $query);
    $event_media = array();
    while($result_row = mysqli_fetch_assoc($result)){
        $event_media[] = $result_row;
    }
    mysqli_close($connection);
    return $event_media;
}

function delete_event_media($mediaID) {
     $query = "delete from dbeventmedia where id='$mediaID'";
     $connection = connect();
     $result = mysqli_query($connection, $query);
     mysqli_close($connection);
     if ($result) {
         return true;
     }
     return false;
 }