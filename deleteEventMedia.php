<?php
    session_cache_expire(30);
    session_start();

    if (isset($_SESSION['access_level']) && $_SESSION['access_level'] >= 2) {
    $isEventManager = true;
    } else {
        $isEventManager = false;
        header('Location: index.php');
        die();    
    }
    require_once('include/input-validation.php');
    require_once('database/dbEventMedia.php');
    $args = sanitize($_GET);
    $required = ['eid', 'mid'];
    if (!wereRequiredFieldsSubmitted($args, $required)) {
        echo 'bad args';
        die();
    }
    $eid = $args['eid'];
    $mid = $args['mid'];
    delete_event_media($mid);
    header('Location: event.php?id=' . $eid . '&removeSuccess');
?>