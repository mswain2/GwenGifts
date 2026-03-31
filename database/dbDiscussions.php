<?php /* Implemented by Aidan Meyer */

include_once('dbinfo.php');
include_once('dbDiscussionReplies.php');
include_once(dirname(__FILE__).'/../domain/Discussion.php');

function add_discussion($discussion, $category = 'general') {
    if (!$discussion instanceof Discussion)
        die("Error: add_discussion type mismatch");

    $con = connect();

    $author_id = mysqli_real_escape_string($con, $discussion->get_author_id());
    $title = mysqli_real_escape_string($con, $discussion->get_title());
    $body = mysqli_real_escape_string($con, $discussion->get_body());
    $time = mysqli_real_escape_string($con, $discussion->get_time());
    $category_esc = mysqli_real_escape_string($con, $category);

    $query = "SELECT * FROM dbdiscussions 
              WHERE author_id = '$author_id' 
              AND title = '$title' 
              AND category = '$category_esc'";

    $result = mysqli_query($con, $query);

    if ($result == null || mysqli_num_rows($result) == 0) {

        $query = "INSERT INTO dbdiscussions 
        (author_id, title, body, time, category) 
        VALUES ('$author_id', '$title', '$body', '$time', '$category_esc')";

        mysqli_query($con, $query);
        mysqli_close($con);
        return true;
    }

    mysqli_close($con);
    return false;
}

function remove_discussion($author_id, $title, $category = null) {
    $con = connect();
    if ($category) {
        $query = "DELETE FROM dbdiscussions WHERE author_id = '$author_id' AND title = '$title' AND category = '$category'";
    } else {
        $query = "DELETE FROM dbdiscussions WHERE author_id = '$author_id' AND title = '$title'";
    }
    $result = mysqli_query($con, $query);
    mysqli_close($con);
    return $result;
}

function update_discussion($author_id, $title, $newBody, $edited_by, $category = null) {
    $con = connect();
    $author_id = mysqli_real_escape_string($con, $author_id);
    $title = mysqli_real_escape_string($con, $title);
    $newBody = mysqli_real_escape_string($con, $newBody);
    $edited_by = mysqli_real_escape_string($con, $edited_by);
    $edited_at = date("Y-m-d-H:i");

    if ($category) {
        $category = mysqli_real_escape_string($con, $category);
        $query = "UPDATE dbdiscussions SET body='$newBody', edited_by='$edited_by', edited_at='$edited_at'
                  WHERE author_id='$author_id' AND title='$title' AND category='$category'";
    } else {
        $query = "UPDATE dbdiscussions SET body='$newBody', edited_by='$edited_by', edited_at='$edited_at'
                  WHERE author_id='$author_id' AND title='$title'";
    }

    $result = mysqli_query($con, $query);
    mysqli_close($con);
    return $result;
}

function get_discussion($title, $category = null) {
    $con = connect();

    $title = mysqli_real_escape_string($con, $title);

    if ($category) {
        $category = mysqli_real_escape_string($con, $category);
        $query = "SELECT * FROM dbdiscussions 
                  WHERE title = '$title' 
                  AND category = '$category'";
    } else {
        $query = "SELECT * FROM dbdiscussions 
                  WHERE title = '$title'";
    }

    $result = mysqli_query($con, $query);

    if (!$result) {
        die("SQL Error: " . mysqli_error($con));
    }

    if (mysqli_num_rows($result) > 0) {
        $discussion = mysqli_fetch_assoc($result);
        mysqli_close($con);
        return $discussion;
    }

    mysqli_close($con);
    return null;
}

function get_all_discussions() {
    $con = connect();
    $query = "SELECT * FROM dbdiscussions WHERE category = 'general' OR category IS NULL";
    $result = mysqli_query($con, $query);
    $discussions = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $discussions[] = $row;
    }

    mysqli_close($con);
    return $discussions;
}

function get_board_discussions() {
    $con = connect();
    $query = "SELECT * FROM dbdiscussions WHERE category = 'board'";
    $result = mysqli_query($con, $query);
    $discussions = [];
 
    while ($row = mysqli_fetch_assoc($result)) {
        $discussions[] = $row;
    }
 
    mysqli_close($con);
    return $discussions;
}

function get_user_from_author($author_id){
    $con=connect();
    $query = "SELECT * FROM dbpersons WHERE id = '" . $author_id . "'";
    $result = mysqli_query($con,$query);
    if (mysqli_num_rows($result) !== 1) {
        mysqli_close($con);
        return false;
    }
    $result_row = mysqli_fetch_assoc($result);
    // var_dump($result_row);
    $thePerson = make_a_person($result_row);
//    mysqli_close($con);
    return $thePerson;
}
function discussion_exists($title, $category = null) {
    $con = connect();
    $title = mysqli_real_escape_string($con, $title);
    if ($category) {
        $query = "SELECT * FROM dbdiscussions WHERE title = '$title' AND category = '$category'";
    } else {
        $query = "SELECT * FROM dbdiscussions WHERE title = '$title'";
    }
    $result = mysqli_query($con, $query);
    $exists = $result && mysqli_num_rows($result) > 0;
    mysqli_close($con);
    return $exists;
}


function deleteDiscussions($discussions) {
    $con = connect();
    $success = true;

    foreach ($discussions as $entry) {
        $data = explode('|', $entry); // expects "author_id|title"
        $data = explode('|', $entry);
        if (count($data) == 3) {
            $author_id = mysqli_real_escape_string($con, $data[0]);
            $title = mysqli_real_escape_string($con, $data[1]);
            $category = mysqli_real_escape_string($con, $data[2]);
            delete_all_replies_in($title, $category);
            $query = "DELETE FROM dbdiscussions WHERE author_id = '$author_id' AND title = '$title' AND category = '$category'";
            $result = mysqli_query($con, $query);
            if (!$result) $success = false;
        }
    }

    mysqli_close($con);
    return $success;
}

?>