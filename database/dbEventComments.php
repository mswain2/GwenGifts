<?php

require_once('database/dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Event.php');
date_default_timezone_set("America/New_York");

function add_event_comment($user_id, $event_id, $comment){
    $con = connect();
    $query = $query = "
        insert into dbeventcomments (user_id, event_id, comment)
        values ('$user_id', '$event_id', '$comment')
    ";
    $result = mysqli_query($con, $query);
    if (!$result) {
        return null;
    }

    mysqli_close($con);
    return true;
}

function get_event_comments($event_id, $limit = null, $offset = 0) {
    $connection = connect();

    $query = "select * from dbeventcomments
              where event_id='$event_id'
              order by uploaded_at DESC";

    if ($limit !== null) {
        $query .= " LIMIT $limit OFFSET $offset";
    }

    $result = mysqli_query($connection, $query);
    $event_comments = array();
    while($result_row = mysqli_fetch_assoc($result)){
        $event_comments[] = $result_row;
    }
    mysqli_close($connection);
    return $event_comments;
}

function get_event_comments_count($event_id) {
    $connection = connect();

    $query = "select COUNT(*) as count from dbeventcomments
              where event_id='$event_id'";

    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);
    mysqli_close($connection);
    return $row['count'];
}

function delete_event_comment($comment_id) {
     $query = "delete from dbeventcomments where id='$comment_id'";
     $connection = connect();
     $result = mysqli_query($connection, $query);
     mysqli_close($connection);
     if ($result) {
         return true;
     }
     return false;
 }