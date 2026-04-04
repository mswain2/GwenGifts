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
    require_once('database/dbEventComments.php');
    $args = sanitize($_GET);
    $required = ['eid', 'cid'];
    if (!wereRequiredFieldsSubmitted($args, $required)) {
        echo 'bad args';
        die();
    }
    $eid = $args['eid'];
    $cid = $args['cid'];
    delete_event_comment($cid);
    header('Location: viewEventComments.php?eventID=' . $eid . '&deleteSuccess');
    die();
?>