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
require_once('database/dbTrainingPersons.php');

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

$pending_signups = [];
if (is_callable('fetch_pending_signups')) {
    $pending_signups = call_user_func('fetch_pending_signups', $id);
} elseif (is_callable('fetch_event_pending_signups')) {
    $pending_signups = call_user_func('fetch_event_pending_signups', $id);
}

$access_level = $_SESSION['access_level'];
$attendance_statuses = get_attendance_statuses_for_event($id);

$user_ids_for_trainings = [];
foreach ($signups as $signup) {
    if (!empty($signup['userID'])) {
        $user_ids_for_trainings[] = $signup['userID'];
    }
}

foreach ($pending_signups as $signup) {
    if (!empty($signup['username'])) {
        $user_ids_for_trainings[] = $signup['username'];
    }
}

$training_statuses = get_training_statuses_for_users($user_ids_for_trainings);

function maskEmailForRoster($email): string
{
    $email = trim((string)$email);

    if ($email === '' || strpos($email, '@') === false) {
        return 'N/A';
    }

    [$local, $domain] = explode('@', $email, 2);

    if ($local === '') {
        return 'N/A';
    }

    $visible = min(2, strlen($local));
    $masked_local = substr($local, 0, $visible) . str_repeat('*', max(3, strlen($local) - $visible));

    return $masked_local . '@' . $domain;
}

function maskPhoneForRoster($phone): string
{
    $digits = preg_replace('/\D+/', '', (string)$phone);

    if ($digits === '' || strlen($digits) < 4) {
        return 'N/A';
    }

    return '***-***-' . substr($digits, -4);
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

function formatRosterNotes($notes): string
{
    $notes = trim((string)$notes);

    if ($notes === '') {
        return 'N/A';
    }

    $formatted_notes = htmlspecialchars($notes);
    $formatted_notes = str_replace('|', '<br>', $formatted_notes);
    $formatted_notes = preg_replace('/Dietary restrictions:\s*<br>/', 'Dietary restrictions: None<br>', $formatted_notes);
    $formatted_notes = preg_replace('/Skills:\s*<br>/', 'Skills: None<br>', $formatted_notes);
    $formatted_notes = preg_replace('/Disabilities:\s*<br>/', 'Disabilities: None<br>', $formatted_notes);
    $formatted_notes = preg_replace('/Materials:\s*<br>/', 'Materials: None<br>', $formatted_notes);

    return $formatted_notes;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once('universal.inc'); ?>
    <title>Gwyneth's Gift | View Event Sign-Ups</title>
    <link rel="stylesheet" href="css/messages.css" />
    <script>
        function showResolutionConfirmation(userId, notes) {
            document.getElementById('resolution-confirmation-wrapper').classList.remove('hidden');
            document.getElementById('modal-user-id').value = userId;
            document.getElementById('modal-notes').value = notes;
            document.getElementById('modal-user-id-reject').value = userId;
            document.getElementById('modal-notes-reject').value = notes;
            return false;
        }

        function showApprove() {
            document.getElementById('approve-confirmation-wrapper').classList.remove('hidden');
            return false;
        }

        function showReject() {
            document.getElementById('reject-confirmation-wrapper').classList.remove('hidden');
            return false;
        }

        document.addEventListener('DOMContentLoaded', function() {
            var selectAll = document.getElementById('select-all-pending');
            var itemChecks = document.querySelectorAll('.bulk-select');
            var bulkApprove = document.getElementById('bulk-approve');
            var bulkReject = document.getElementById('bulk-reject');

            function updateButtons() {
                var anyChecked = Array.prototype.slice.call(itemChecks).some(function(cb) {
                    return cb.checked;
                });

                if (bulkApprove) bulkApprove.disabled = !anyChecked;
                if (bulkReject) bulkReject.disabled = !anyChecked;
            }

            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    itemChecks.forEach(function(cb) {
                        cb.checked = selectAll.checked;
                    });
                    updateButtons();
                });
            }

            itemChecks.forEach(function(cb) {
                cb.addEventListener('change', updateButtons);
            });

            updateButtons();

            function postForm(url, data) {
                return fetch(url, {
                    method: 'POST',
                    body: data,
                    credentials: 'same-origin'
                });
            }

            function handleBulk(action) {
                var selected = Array.prototype.slice.call(document.querySelectorAll('.bulk-select:checked'));
                if (!selected.length) return;

                var id = document.getElementById('event-id').value;
                var tasks = selected.map(function(cb) {
                    var fd = new FormData();
                    fd.append('id', id);
                    fd.append('user_id', cb.value);
                    fd.append('notes', cb.dataset.notes || '');

                    var endpoint = action === 'approve' ? 'approveSignup.php' : 'rejectSignup.php';
                    return postForm(endpoint, fd);
                });

                Promise.all(tasks).then(function() {
                    var url = new URL(window.location.href);
                    url.searchParams.set('id', id);
                    url.searchParams.set('pendingSignupSuccess', '1');
                    window.location.href = url.toString();
                });
            }

            if (bulkApprove) {
                bulkApprove.addEventListener('click', function(e) {
                    e.preventDefault();
                    handleBulk('approve');
                });
            }

            if (bulkReject) {
                bulkReject.addEventListener('click', function(e) {
                    e.preventDefault();
                    handleBulk('reject');
                });
            }
        });
    </script>

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

        th.select-col,
        td.select-col {
            text-align: center;
            width: 52px;
            vertical-align: middle;
        }

        .phone-col {
            white-space: nowrap;
            min-width: 170px;
        }

        .bulk-actions {
            display: flex;
            gap: .5rem;
            align-items: center;
            flex-wrap: wrap;
            margin: 1rem 0;
        }

        .bulk-actions .spacer {
            flex: 1 1 auto;
        }
    </style>
</head>

<body>
    <?php require_once('header.php'); ?>

    <h1>View Sign-Up List</h1>

    <?php if (isset($_GET['pendingSignupSuccess'])) : ?>
        <div class="happy-toast">Sign-up request resolved successfully.</div>
    <?php endif; ?>

    <main class="general">
        <h2><?php echo htmlspecialchars($event_info['name']); ?></h2>

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

        <?php if (count($pending_signups) === 1): ?>
            <p>1 sign-up is pending for this event.</p>
        <?php else: ?>
            <p><?php echo htmlspecialchars((string)count($pending_signups)); ?> sign-ups are pending for this event.</p>
        <?php endif; ?>

        <input type="hidden" id="event-id" value="<?php echo htmlspecialchars($id); ?>">

        <?php if ($access_level >= 2 && count($pending_signups) > 0): ?>
            <div class="bulk-actions">
                <div class="spacer"></div>
                <button class="button success" id="bulk-approve" disabled>Approve Selected</button>
                <button class="button danger" id="bulk-reject" disabled>Reject Selected</button>
            </div>
        <?php endif; ?>

        <?php if (count($signups) > 0 || count($pending_signups) > 0): ?>
            <h3>Search Results</h3>
            <div class="table-wrapper">
                <table class="general">
                    <thead>
                        <tr>
                            <?php if ($access_level >= 2 && count($pending_signups) > 0): ?>
                                <th class="select-col">
                                    <input type="checkbox" id="select-all-pending" title="Select all pending">
                                </th>
                            <?php endif; ?>
                            <th>Volunteer Name</th>
                            <th>Attendance</th>
                            <th>Email</th>
                            <th class="phone-col">Phone</th>
                            <th>Training Status</th>
                            <th>Shirt Size</th>
                            <th>User ID</th>
                            <th>Notes</th>
                            <th>Pending</th>
                            <?php if ($access_level >= 2): ?>
                                <th>Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($signups as $signup): ?>
                            <?php
                            $user_info = retrieve_person($signup['userID']);
                            $pending = check_if_signed_up($args['id'], $signup['userID']);
                            $notes = isset($signup['notes']) && $signup['notes'] !== '' && $signup['notes'] !== null
                                ? $signup['notes']
                                : 'No notes.';

                            $first_name = $user_info ? $user_info->get_first_name() : '';
                            $last_name = $user_info ? $user_info->get_last_name() : '';
                            $full_name = trim($first_name . ' ' . $last_name);

                            $email = $user_info ? $user_info->get_email() : '';
                            $phone = $user_info ? $user_info->get_phone1() : '';

                            $attendance_status = $attendance_statuses[$signup['userID']] ?? 'Absent';
                            $masked_email = maskEmailForRoster($email);
                            $masked_phone = maskPhoneForRoster($phone);
                            $training_status = $training_statuses[$signup['userID']] ?? 'Incomplete';
                            $shirt_size = rosterShirtSize($user_info);
                            ?>
                            <tr>
                                <?php if ($access_level >= 2 && count($pending_signups) > 0): ?>
                                    <td class="select-col"></td>
                                <?php endif; ?>

                                <td><?php echo htmlspecialchars($full_name !== '' ? $full_name : 'Unknown'); ?></td>
                                <td><?php echo htmlspecialchars($attendance_status); ?></td>
                                <td><?php echo htmlspecialchars($masked_email); ?></td>
                                <td><?php echo htmlspecialchars($masked_phone); ?></td>
                                <td><?php echo htmlspecialchars($training_status); ?></td>
                                <td><?php echo htmlspecialchars($shirt_size); ?></td>
                                <td>
                                    <a href="viewProfile.php?id=<?php echo urlencode($signup['userID']); ?>">
                                        <?php echo htmlspecialchars($signup['userID']); ?>
                                    </a>
                                </td>
                                <td><?php echo formatRosterNotes($notes); ?></td>
                                <td>
                                    <?php
                                    if ($pending == '0') {
                                        echo 'Yes';
                                    } elseif ($pending == '1') {
                                        echo 'No';
                                    }
                                    ?>
                                </td>
                                <?php if ($access_level >= 2 && $pending == '1'): ?>
                                    <td>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($id); ?>">
                                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($signup['userID']); ?>">
                                            <button type="submit" class="button danger" onclick="return confirm('Are you sure you want to remove this user?');">Remove</button>
                                        </form>
                                    </td>
                                <?php elseif ($access_level >= 2): ?>
                                    <td></td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>

                        <?php foreach ($pending_signups as $signup): ?>
                            <?php
                            $user_info = retrieve_person($signup['username']);
                            if (!$user_info) {
                                continue;
                            }

                            $pending = check_if_signed_up($args['id'], $signup['username']);
                            $notes = (!empty($signup['notes'])) ? $signup['notes'] : 'No Notes';

                            $pending_user_id = $signup['username'];
                            $pending_full_name = trim($user_info->get_first_name() . ' ' . $user_info->get_last_name());
                            $pending_attendance_status = $attendance_statuses[$pending_user_id] ?? 'Absent';
                            $pending_masked_email = maskEmailForRoster($user_info->get_email());
                            $pending_masked_phone = maskPhoneForRoster($user_info->get_phone1());
                            $pending_training_status = $training_statuses[$pending_user_id] ?? 'Incomplete';
                            $pending_shirt_size = rosterShirtSize($user_info);
                            ?>
                            <tr>
                                <?php if ($access_level >= 2 && count($pending_signups) > 0): ?>
                                    <td class="select-col">
                                        <?php if ($pending == '0'): ?>
                                            <input
                                                type="checkbox"
                                                class="bulk-select"
                                                value="<?php echo htmlspecialchars($signup['username']); ?>"
                                                data-notes="<?php echo htmlspecialchars($signup['notes'] ?? ''); ?>">
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>

                                <td><?php echo htmlspecialchars($pending_full_name !== '' ? $pending_full_name : 'Unknown'); ?></td>
                                <td><?php echo htmlspecialchars($pending_attendance_status); ?></td>
                                <td><?php echo htmlspecialchars($pending_masked_email); ?></td>
                                <td class="phone-col"><?php echo htmlspecialchars($pending_masked_phone); ?></td>
                                <td><?php echo htmlspecialchars($pending_training_status); ?></td>
                                <td><?php echo htmlspecialchars($pending_shirt_size); ?></td>
                                <td>
                                    <a href="viewProfile.php?id=<?php echo urlencode($signup['username']); ?>">
                                        <?php echo htmlspecialchars($signup['username']); ?>
                                    </a>
                                </td>
                                <td><?php echo formatRosterNotes($notes); ?></td>
                                <td>
                                    <?php
                                    if ($pending == '0') {
                                        echo 'Yes';
                                    } elseif ($pending == '1') {
                                        echo 'No';
                                    }
                                    ?>
                                </td>

                                <?php if ($access_level >= 2 && $pending == '0'): ?>
                                    <td>
                                        <button
                                            type="button"
                                            onclick="showResolutionConfirmation('<?php echo htmlspecialchars($signup['username']); ?>', '<?php echo htmlspecialchars($signup['notes'] ?? ''); ?>')"
                                            class="button">
                                            Resolve
                                        </button>
                                    </td>
                                <?php elseif ($access_level >= 2): ?>
                                    <td></td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <a class="button cancel" href="index.php">Return to Dashboard</a>
    </main>
    <div id="resolution-confirmation-wrapper" class="modal-content hidden">
        <div class="modal-content">
            <p>Would you like to approve or reject this sign-up request?</p>
            <button onclick="showApprove()" class="button success">Approve</button>
            <button onclick="showReject()" class="button danger">Reject</button>
            <button onclick="document.getElementById('resolution-confirmation-wrapper').classList.add('hidden')" id="cancel-cancel" class="button cancel">Cancel</button>
        </div>
    </div>

    <div id="approve-confirmation-wrapper" class="modal-content hidden">
        <div class="modal-content">
            <p>Are you sure you want to approve this sign-up request?</p>
            <p>This action cannot be undone</p>
            <form method="post" action="approveSignup.php">
                <input type="submit" value="Approve" class="button success">
                <input type="hidden" name="id" value="<?= $_REQUEST['id'] ?>">
                <input type="hidden" id="modal-user-id" name="user_id" value="">
                <input type="hidden" id="modal-notes" name="notes" value="">
            </form>
            <button onclick="document.getElementById('approve-confirmation-wrapper').classList.add('hidden')" id="cancel-cancel" class="button cancel">Cancel</button>
        </div>
    </div>

    <div id="reject-confirmation-wrapper" class="modal-content hidden">
        <div class="modal-content">
            <p>Are you sure you want to reject this sign-up request?</p>
            <p>This action cannot be undone</p>
            <form method="post" action="rejectSignup.php">
                <input type="submit" value="Reject" class="button danger">
                <input type="hidden" name="id" value="<?= $_REQUEST['id'] ?>">
                <input type="hidden" id="modal-user-id-reject" name="user_id" value="">
                <input type="hidden" id="modal-notes-reject" name="notes" value="">
            </form>
            <button onclick="document.getElementById('reject-confirmation-wrapper').classList.add('hidden')" id="cancel-cancel" class="button cancel">Cancel</button>
        </div>
    </div>
</body>

</html>