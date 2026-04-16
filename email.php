<?php
require_once(__DIR__ . '/database/dbinfo.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/database/dbinfo.php');
require_once(__DIR__ . '/database/dbPersons.php');

// Manual PHPMailer include
require_once __DIR__ . '/email/PHPMailer/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/email/PHPMailer/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/email/PHPMailer/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ------------------------
// Get all members for dropdown
// ------------------------
function getUsersAndEmails() {
    $conn = connect();
    $members = [];
    $res = $conn->query("SELECT id, CONCAT(first_name,' ',last_name,' (',email,')') as label FROM dbpersons WHERE email_prefs = 'true' ORDER BY first_name");
    while ($row = $res->fetch_assoc()) {
        $members[] = ['label' => $row['label'], 'value' => $row['id']];
    }
    return $members;
}

// ------------------------
// Load email environment
// ------------------------
function loadEnv(string $file): array {
    $env = [];
    if (!file_exists($file)) return $env;
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) continue;
        [$key, $value] = explode('=', $line, 2);
        $env[trim($key)] = trim($value);
    }
    return $env;
}

// ------------------------
// Validate the smtp connection
// ------------------------
function validateSmtpEnv(array $env): array {
    $required = ['SMTP_HOST', 'SMTP_USER', 'SMTP_PASS', 'SMTP_PORT', 'SMTP_FROM_NAME'];
    $missing = [];
    foreach ($required as $key) {
        if (empty($env[$key])) {
            $missing[] = $key;
        }
    }
    return $missing;
}

// ------------------------
// Send emails via PHPMailer
// ------------------------
function sendEmails(array $emails, string $subject, string $body): array {
    global $env; // use loaded .env variables
    $results = [];
    $success = true;

    $required = ['SMTP_HOST', 'SMTP_USER', 'SMTP_PASS', 'SMTP_PORT', 'SMTP_FROM_NAME'];
    foreach ($required as $key) {
        if (empty($env[$key])) {
            return ['success' => false, 'results' => [['email' => '', 'success' => false, 'error' => "Missing SMTP env key: $key"]]];
        }
    }

    foreach ($emails as $email) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = $env['SMTP_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $env['SMTP_USER'];
            $mail->Password   = $env['SMTP_PASS'];
            $mail->SMTPSecure = 'tls';
            $mail->Port       = $env['SMTP_PORT'];

            $mail->setFrom($env['SMTP_USER'], $env['SMTP_FROM_NAME']);
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            $results[] = ["email" => $email, "success" => true];
        } catch (Exception $e) {
            $success = false;
            $results[] = ["email" => $email, "success" => false, "error" => $mail->ErrorInfo];
        }
    }

    return ['success' => $success, 'results' => $results];
}


// ------------------------
// Retrieve emails from db
// ------------------------
function retrieveAllEmails(array $ids = []): array {
    $conn = connect();
    $emails = [];

    if (empty($ids)) {
        $res = $conn->query("SELECT id, email FROM dbpersons WHERE email IS NOT NULL AND email != '' AND email_prefs = 'true'");
        while ($row = $res->fetch_assoc()) {
            $emails[$row['id']] = $row['email'];
        }
        return $emails;
    }

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('s', count($ids));

    $sql = "SELECT id, email FROM dbpersons WHERE id IN ($placeholders) AND email IS NOT NULL AND email != '' AND email_prefs = 'true'";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) return [];

    $params = [&$types];
    foreach ($ids as $k => $v) $params[] = &$ids[$k];
    call_user_func_array([$stmt, 'bind_param'], $params);

    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) $emails[$row['id']] = $row['email'];
    $stmt->close();

    return $emails;
}

// ------------------------
// Retrieve role based emails from db
// ------------------------
function retrieveRoleEmails(string $recipientsType){
    $conn = connect();
    $emails = [];

    if ($recipientsType === 'vols'){
        $res = $conn->query("SELECT id, email FROM dbpersons WHERE email IS NOT NULL AND email != '' AND email_prefs = 'true' AND type = 'volunteer'");
        while ($row = $res->fetch_assoc()) {
            $emails[$row['id']] = $row['email'];
        }
        return $emails;
    } elseif ($recipientsType === 'ems'){
        $res = $conn->query("SELECT id, email FROM dbpersons WHERE email IS NOT NULL AND email != '' AND email_prefs = 'true' AND type = 'event_manager'");
        while ($row = $res->fetch_assoc()) {
            $emails[$row['id']] = $row['email'];
        }
        return $emails;
    } elseif ($recipientsType === 'bms'){
        $res = $conn->query("SELECT id, email FROM dbpersons WHERE email IS NOT NULL AND email != '' AND email_prefs = 'true' AND type = 'board_member'");
        while ($row = $res->fetch_assoc()) {
            $emails[$row['id']] = $row['email'];
        }
        return $emails;
    } elseif ($recipientsType === 'admin'){
        $res = $conn->query("SELECT id, email FROM dbpersons WHERE email IS NOT NULL AND email != '' AND email_prefs = 'true' AND type = 'admin'");
        while ($row = $res->fetch_assoc()) {
            $emails[$row['id']] = $row['email'];
        }
        return $emails;
    }
}

// ------------------------
// Submit or schedule email
// ------------------------
function submitEmail(array $recipientIDs, int $event_id, string $subject, string $body, bool $sendNow, string $sendDate, string $recipientsType): array {
    global $missingEnvKeys;
    $errors = [];

    if (!empty($missingEnvKeys)) {
        return ['success' => false, 'errors' => ["Missing SMTP configuration: " . implode(', ', $missingEnvKeys)]];
    }

    // Determine recipients
    if ($recipientsType === 'specific' && !empty($recipientIDs)) {
        $emails = retrieveAllEmails($recipientIDs);
    } 
    elseif ($recipientsType === 'vols' || $recipientsType === 'ems' || $recipientsType === 'bms' || $recipientsType === 'admin'){
        $emails = retrieveRoleEmails($recipientsType);
        $recipientIDs = array_keys($emails);
    }
    else {
        $emails = retrieveAllEmails();
        $recipientIDs = array_keys($emails);
    }

    if (empty($emails)) {
        return ['success' => false, 'errors' => ["No emails found for selected recipients."]];
    }

    // Send Now
    if ($sendNow) {
        $results = sendEmails(array_values($emails), $subject, $body);
        if (!$results['success']) {
            foreach ($results['results'] as $f) $errors[] = "Failed to send to {$f['email']}: {$f['error']}";
            return ['success' => false, 'errors' => $errors ?: ["Unknown error sending emails"]];
        }
        return ['success' => true, 'errors' => []];
    }

    // Schedule email
    if (empty($sendDate)) return ['success' => false, 'errors' => ["Send date is required for scheduled emails."]];

    if (empty($event_id)){
        $event_id = 0;
    }

    $conn = connect();
    foreach ($recipientIDs as $recipientID) {
        $stmt = $conn->prepare("
            INSERT INTO dbscheduledemails
            (userID, event_id, recipientID, subject, body, scheduledSend, sent)
            VALUES (?, ?, ?, ?, ?, ?, 0)
        ");
        if (!$stmt) {
            $errors[] = "DB prepare failed: " . $conn->error;
            continue;
        }
        $uid = (string)$_SESSION['_id'];
        $rid = (string)$recipientID;
        $stmt->bind_param('sissss', $uid, $event_id, $rid, $subject, $body, $sendDate);
        if (!$stmt->execute()) $errors[] = "Failed to schedule email for {$recipientID}: " . $stmt->error;
        $stmt->close();
    }

    return ['success' => empty($errors), 'errors' => $errors];
}

// ------------------------
// Remove a scheduled email
// ------------------------
function removeEmail(string $rid, int $event_id){
    //Check if it has been sent
    //If it has been sent, don't worry about removing it
    //If it has not been sent, remove it
}