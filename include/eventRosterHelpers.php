<?php
require_once(dirname(__FILE__) . '/../database/dbEvents.php');
require_once(dirname(__FILE__) . '/../database/dbPersons.php');
require_once(dirname(__FILE__) . '/../database/dbAttendance.php');
require_once(dirname(__FILE__) . '/../database/dbTrainingPersons.php');

function normalize_event_roster_filters($source)
{
    $attendance = isset($source['attendance']) ? strtolower(trim((string)$source['attendance'])) : 'all';
    $cpr = isset($source['cpr']) ? strtolower(trim((string)$source['cpr'])) : 'all';
    $aed = isset($source['aed']) ? strtolower(trim((string)$source['aed'])) : 'all';

    if (!in_array($attendance, array('all', 'present', 'absent'), true)) {
        $attendance = 'all';
    }
    if (!in_array($cpr, array('all', 'completed', 'not_done'), true)) {
        $cpr = 'all';
    }
    if (!in_array($aed, array('all', 'completed', 'not_done'), true)) {
        $aed = 'all';
    }

    return array(
        'attendance' => $attendance,
        'cpr' => $cpr,
        'aed' => $aed
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
    $cpr = strtolower(trim((string)($row['cpr_training_status'] ?? 'Not Done')));
    $aed = strtolower(trim((string)($row['aed_training_status'] ?? 'Not Done')));

    if (($filters['attendance'] ?? 'all') !== 'all' && $attendance !== $filters['attendance']) {
        return false;
    }

    if (($filters['cpr'] ?? 'all') !== 'all') {
        $normalizedCpr = ($cpr === 'completed') ? 'completed' : 'not_done';
        if ($normalizedCpr !== $filters['cpr']) {
            return false;
        }
    }

    if (($filters['aed'] ?? 'all') !== 'all') {
        $normalizedAed = ($aed === 'completed') ? 'completed' : 'not_done';
        if ($normalizedAed !== $filters['aed']) {
            return false;
        }
    }

    return true;
}

function event_roster_qualification_label($value)
{
    return strtolower(trim((string)$value)) === 'yes' ? 'Completed' : 'Not Done';
}

function event_roster_training_details_from_person($user_info)
{
    if (!$user_info) {
        return array(
            'cpr' => 'Not Done',
            'aed' => 'Not Done'
        );
    }

    return array(
        'cpr' => event_roster_qualification_label($user_info->get_cpr_training_completion()),
        'aed' => event_roster_qualification_label($user_info->get_aed_training_completion())
    );
}

function event_roster_training_filter_status_from_details($trainingDetails)
{
    return (
        ($trainingDetails['cpr'] ?? 'Not Done') === 'Completed' &&
        ($trainingDetails['aed'] ?? 'Not Done') === 'Completed'
    ) ? 'Completed' : 'Not Done';
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
        $trainingDetails = event_roster_training_details_from_person($user_info);

        $rows[] = array(
            'user_id' => $userID,
            'full_name' => $fullName !== '' ? $fullName : 'Unknown',
            'attendance_status' => $attendanceStatuses[$userID] ?? 'Absent',
            'email' => event_roster_mask_email($email),
            'raw_email' => trim((string)$email),
            'phone' => event_roster_mask_phone($phone),
            'training_status' => event_roster_training_filter_status_from_details($trainingDetails),
            'cpr_training_status' => $trainingDetails['cpr'],
            'aed_training_status' => $trainingDetails['aed'],
            'shirt_size' => event_roster_shirt_size($user_info)
        );
    }

    usort($rows, function ($a, $b) {
        return strcasecmp($a['full_name'], $b['full_name']);
    });

    return $rows;
}
