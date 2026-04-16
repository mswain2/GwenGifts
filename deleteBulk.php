<?php
ob_start();
session_cache_expire(30);
session_start();

include_once "database/dbDiscussions.php";
include_once "database/dbPersons.php";

$userType = 'volunteer';
if (isset($_SESSION['_id'])) {
    if ($_SESSION['_id'] === 'vmsroot') {
        $userType = 'superadmin';
    } else {
        $person = retrieve_person($_SESSION['_id']);
        if ($person) $userType = $person->get_type();
    }
}

if (!in_array($userType, ['admin', 'superadmin'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

// Detect if request is AJAX
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

$category = isset($_POST['category']) ? trim($_POST['category']) : 'general';
$category = in_array($category, ['general', 'board']) ? $category : 'general';
$redirect = $category === 'board' ? 'viewBoardDiscussions.php' : 'viewDiscussions.php';

$response = ['success' => false];

if (isset($_POST['bulk_delete']) && isset($_POST['selected_discussions'])) {
    $selected = json_decode($_POST['selected_discussions'], true);
    if (is_array($selected) && count($selected) > 0) {
        deleteDiscussions($selected);
        $response['success'] = true;
        $response['redirect'] = $redirect;
    } else {
        error_log('Selected discussions are invalid or empty: ' . var_export($selected, true));
    }
} 
else if (isset($_POST['delete_all'])) {
    deleteAllDiscussions($category);
    $response['success'] = true;
    $response['redirect'] = $redirect;
    
    // If not AJAX, do a real redirect
    if (!$isAjax) {
        header('Location: ' . $redirect);
        exit;
    }
} 
else {
    error_log('Missing required data for bulk delete. POST data: ' . var_export($_POST, true));
}

// For AJAX requests, return JSON
header('Content-Type: application/json');
echo json_encode($response);
exit;
