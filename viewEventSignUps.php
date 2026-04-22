<?php
session_cache_expire(30);
session_start();

if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 1) {
    header('Location: login.php');
    die();
}

require_once('include/input-validation.php');
require_once('database/dbEvents.php');
require_once('database/dbPersons.php');
require_once('database/dbAttendance.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);

$args = sanitize($_GET);
$id = $args['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['bulk_action'])) {
    $event_id = $_POST['event_id'] ?? null;
    $user_id = $_POST['user_id'] ?? null;

    if (!$event_id) {
        echo 'Event ID is missing.';
        die();
    }

    if (!$user_id) {
        echo 'User ID is missing.';
        die();
    }

    if (remove_user_from_event($event_id, $user_id)) {
        $remove_success = "User $user_id was successfully removed.";
    } else {
        $remove_error = "Failed to remove user $user_id.";
    }
}

$event_info = fetch_event_by_id($id);
if (!$event_info) {
    echo 'Invalid event ID.';
    die();
}

$signups = fetch_event_signups($id);

$access_level = $_SESSION['access_level'];
$attendance_statuses = get_attendance_statuses_for_event($id);

function maskEmailForRoster($email)
{
    $email = trim((string)$email);
    return $email !== '' ? $email : 'N/A';
}

function maskPhoneForRoster($phone)
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

function volunteerConsentedToShareShirtSize($user_info): bool
{
    if (!$user_info) {
        return false;
    }

    $consent = strtolower(trim((string)$user_info->get_about_consent()));
    return in_array($consent, ['yes', 'true', '1', 'y'], true);
}

function rosterShirtSize($user_info): string
{
    if (!$user_info || !volunteerConsentedToShareShirtSize($user_info)) {
        return 'Hidden';
    }

    $size = trim((string)$user_info->get_t_shirt_size());
    return $size !== '' ? $size : 'N/A';
}

function qualificationStatusLabel($value): string
{
    return strtolower(trim((string)$value)) === 'yes' ? 'Completed' : 'Not Done';
}

function trainingDetailsFromPerson($user_info): array
{
    if (!$user_info) {
        return array(
            'cpr' => 'Not Done',
            'aed' => 'Not Done'
        );
    }

    return array(
        'cpr' => qualificationStatusLabel($user_info->get_cpr_training_completion()),
        'aed' => qualificationStatusLabel($user_info->get_aed_training_completion())
    );
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once('universal.inc'); ?>
    <title>Gwyneth's Gift | Manage Event Signups</title>
    <link rel="stylesheet" href="css/messages.css" />


    <style>
        main.general {
            width: 92%;
            max-width: 1600px;
            margin: 2rem auto;
            padding: 2rem;
            border: 2px solid #314767;
            border-radius: 12px;
            background-color: #fff;
        }

        main.general h2 {
            text-align: center;
            font-size: 2.2rem;
            font-weight: 400;
            margin-bottom: 1rem;
            color: #243b5a;
        }

        h3 {
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            font-size: 1.15rem;
            color: #243b5a;
            text-align: left;
            font-weight: 700;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            margin-bottom: 20px;
        }

        table.general {
            width: 100%;
            min-width: 1100px;
            border-collapse: collapse;
            background: #fff;
            border: 1px solid #c8d1db;
        }

        table.general thead th {
            background-color: #c7d6ea;
            color: #243b5a;
            font-weight: 700;
            padding: 18px 16px;
            text-align: left;
            border: 1px solid #c8d1db;
            font-size: 1rem;
            white-space: normal;
        }

        table.general tbody td {
            padding: 18px 16px;
            border: 1px solid #d6dde5;
            vertical-align: middle;
            font-size: 0.98rem;
            white-space: normal;
            text-align: left;
        }

        table.general tbody tr {
            background-color: #fff;
        }

        table.general tbody tr:hover {
            background-color: #fafafa;
        }

        .training-breakdown {
            line-height: 1.45;
        }

        .training-breakdown div+div {
            margin-top: 0.2rem;
        }

        .phone-col {
            white-space: nowrap;
            min-width: 170px;
        }
    </style>
</head>

<body>
    <?php require_once('header.php'); ?>

    <h1>Manage Event Signups</h1>

    <main class="general">
        <h2><?php echo $event_info['name']; ?></h2>

        <?php if (isset($remove_success)): ?>
            <p class="success"><?php echo htmlspecialchars($remove_success); ?></p>
        <?php elseif (isset($remove_error)): ?>
            <p class="error"><?php echo htmlspecialchars($remove_error); ?></p>
        <?php endif; ?>
        <?php if (count($signups) === 1): ?>
            <p>1 person has signed up for this event.</p>
        <?php else: ?>
            <p><?php echo htmlspecialchars((string)count($signups)); ?> people have signed up for this event.</p>
        <?php endif; ?>


        <?php if (count($signups) > 0): ?>
            <h3>Search Results</h3>
            <div class="table-wrapper">
                <table class="general">
                    <thead>
                        <tr>
                            <th>Volunteer Name</th>
                            <th>Attendance</th>
                            <th>Email</th>
                            <th class="phone-col">Phone</th>
                            <th>Training</th>
                            <th>Shirt Size</th>
                            <th>User ID</th>
                            <?php if ($access_level >= 2): ?>
                                <th>Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($signups as $signup): ?>
                            <?php
                            $user_info = retrieve_person($signup['userID']);

                            $first_name = $user_info ? $user_info->get_first_name() : '';
                            $last_name = $user_info ? $user_info->get_last_name() : '';
                            $full_name = trim($first_name . ' ' . $last_name);

                            $email = $user_info ? $user_info->get_email() : '';
                            $phone = $user_info ? $user_info->get_phone1() : '';

                            $attendance_status = $attendance_statuses[$signup['userID']] ?? 'Absent';
                            $display_email = maskEmailForRoster($email);
                            $display_phone = maskPhoneForRoster($phone);
                            $training_details = trainingDetailsFromPerson($user_info);
                            $shirt_size = rosterShirtSize($user_info);
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($full_name !== '' ? $full_name : 'Unknown'); ?></td>
                                <td><?php echo htmlspecialchars($attendance_status); ?></td>
                                <td><?php echo htmlspecialchars($display_email); ?></td>
                                <td><?php echo htmlspecialchars($display_phone); ?></td>
                                <td class="training-breakdown">
                                    <div>CPR: <?php echo htmlspecialchars($training_details['cpr']); ?></div>
                                    <div>AED: <?php echo htmlspecialchars($training_details['aed']); ?></div>
                                </td>
                                <td><?php echo htmlspecialchars($shirt_size); ?></td>
                                <td>
                                    <a href="viewProfile.php?id=<?php echo urlencode($signup['userID']); ?>">
                                        <?php echo htmlspecialchars($signup['userID']); ?>
                                    </a>
                                </td>

                                <?php if ($access_level >= 2): ?>
                                    <td>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($id); ?>">
                                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($signup['userID']); ?>">
                                            <button type="submit" class="button danger" onclick="return confirm('Are you sure you want to remove this user?');">Remove</button>
                                        </form>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <a class="button cancel" href="index.php">Return to Dashboard</a>
    </main>
</body>

</html>