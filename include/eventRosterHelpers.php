<?php
require_once(dirname(__FILE__) . '/../database/dbEvents.php');
require_once(dirname(__FILE__) . '/../database/dbPersons.php');
require_once(dirname(__FILE__) . '/../database/dbAttendance.php');
require_once(dirname(__FILE__) . '/../database/dbTrainingPersons.php');

function normalize_event_roster_filters($source)
{
    $attendance = isset($source['attendance']) ? strtolower(trim((string)$source['attendance'])) : 'all';
    $training = isset($source['training']) ? strtolower(trim((string)$source['training'])) : 'all';

    if (!in_array($attendance, array('all', 'present', 'absent'), true)) {
        $attendance = 'all';
    }

    if (!in_array($training, array('all', 'completed', 'not_done'), true)) {
        $training = 'all';
    }

    return array(
        'attendance' => $attendance,
        'training' => $training
    );
}

function event_roster_training_label($rawStatus)
{
    $rawStatus = strtolower(trim((string)$rawStatus));

    if ($rawStatus === 'complete' || $rawStatus === 'completed') {
        return 'Completed';
    }

    return 'Not Done';
}

function event_roster_mask_email($email)
{
    $email = trim((string)$email);

    if ($email === '' || strpos($email, '@') === false) {
        return 'N/A';
    }

    list($local, $domain) = explode('@', $email, 2);

    if ($local === '') {
        return 'N/A';
    }

    $visible = min(2, strlen($local));
    $maskedLocal = substr($local, 0, $visible) . str_repeat('*', max(3, strlen($local) - $visible));

    return $maskedLocal . '@' . $domain;
}

function event_roster_mask_phone($phone)
{
    $digits = preg_replace('/\D+/', '', (string)$phone);

    if ($digits === '' || strlen($digits) < 4) {
        return 'N/A';
    }

    return '***-***-' . substr($digits, -4);
}

function event_roster_can_share_shirt_size($user_info)
{
    if (!$user_info) {
        return false;
    }

    $consent = strtolower(trim((string)$user_info->get_about_consent()));
    return in_array($consent, array('yes', 'true', '1', 'y'), true);
}

function event_roster_shirt_size($user_info)
{
    if (!$user_info || !event_roster_can_share_shirt_size($user_info)) {
        return 'Hidden';
    }

    $size = trim((string)$user_info->get_t_shirt_size());
    return $size !== '' ? $size : 'N/A';
}

function event_roster_matches_filters($row, $filters)
{
    $attendance = strtolower(trim((string)($row['attendance_status'] ?? 'Absent')));
    $training = strtolower(trim((string)($row['training_status'] ?? 'Not Done')));

    if (($filters['attendance'] ?? 'all') !== 'all' && $attendance !== $filters['attendance']) {
        return false;
    }

    if (($filters['training'] ?? 'all') !== 'all') {
        $normalizedTraining = ($training === 'completed') ? 'completed' : 'not_done';

        if ($normalizedTraining !== $filters['training']) {
            return false;
        }
    }

    return true;
}

function build_event_roster_rows($eventID)
{
    $signups = fetch_event_signups($eventID);
    $attendanceStatuses = get_attendance_statuses_for_event($eventID);

    $userIds = array();
    foreach ($signups as $signup) {
        if (!empty($signup['userID'])) {
            $userIds[] = $signup['userID'];
        }
    }

    $trainingStatuses = get_training_statuses_for_users($userIds);
    $rows = array();

    foreach ($signups as $signup) {
        if (empty($signup['userID'])) {
            continue;
        }

        $userID = $signup['userID'];
        $user_info = retrieve_person($userID);

        $fullName = 'Unknown';
        $email = '';
        $phone = '';

        if ($user_info) {
            $firstName = $user_info->get_first_name();
            $lastName = $user_info->get_last_name();
            $fullName = trim($firstName . ' ' . $lastName);
            $email = $user_info->get_email();
            $phone = $user_info->get_phone1();
        }

        $rows[] = array(
            'user_id' => $userID,
            'full_name' => $fullName !== '' ? $fullName : 'Unknown',
            'attendance_status' => $attendanceStatuses[$userID] ?? 'Absent',
            'email' => event_roster_mask_email($email),
            'raw_email' => trim((string)$email),
            'phone' => event_roster_mask_phone($phone),
            'training_status' => event_roster_training_label($trainingStatuses[$userID] ?? 'Incomplete'),
            'shirt_size' => event_roster_shirt_size($user_info)
        );
    }

    usort($rows, function ($a, $b) {
        return strcasecmp($a['full_name'], $b['full_name']);
    });

    return $rows;
}
