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
    if (!in_array($training, array('all', 'none_completed', 'cpr_completed', 'aed_completed', 'all_completed'), true)) {
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
    return $email !== '' ? $email : 'N/A';
}

function event_roster_mask_phone($phone)
{
    $digits = preg_replace('/\D+/', '', (string)$phone);

    if (strlen($digits) === 11 && $digits[0] === '1') {
        $digits = substr($digits, 1);
    }

    if (strlen($digits) !== 10) {
        return 'N/A';
    }

    return substr($digits, 0, 3) . '-' .
        substr($digits, 3, 3) . '-' .
        substr($digits, 6, 4);
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

    if (($filters['attendance'] ?? 'all') !== 'all' && $attendance !== $filters['attendance']) {
        return false;
    }

    $trainingFilter = $filters['training'] ?? 'all';
    if ($trainingFilter === 'cpr_completed' && strtolower($row['cpr_training_status'] ?? '') !== 'completed') {
        return false;
    }
    if ($trainingFilter === 'aed_completed' && strtolower($row['aed_training_status'] ?? '') !== 'completed') {
        return false;
    }
    if ($trainingFilter === 'all_completed' && (
        strtolower($row['cpr_training_status'] ?? '') !== 'completed' ||
        strtolower($row['aed_training_status'] ?? '') !== 'completed'
    )) {
        return false;
    }
    if ($trainingFilter === 'none_completed' && (
        strtolower($row['cpr_training_status'] ?? '') === 'completed' ||
        strtolower($row['aed_training_status'] ?? '') === 'completed'
    )) {
        return false;
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
