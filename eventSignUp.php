<?php
session_cache_expire(30);
session_start();

$loggedIn = false;
$accessLevel = 0;
$userID = null;

if (isset($_SESSION['_id'])) {
    $loggedIn = true;
    // 0 = not logged in, 1 = standard user, 2 = manager/admin, 3 = super admin
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
}

require_once('database/dbEvents.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once('include/input-validation.php');

    $args = sanitize($_POST, null);
    $required = array("event-name", "account-name", "event_id");

    if (!wereRequiredFieldsSubmitted($args, $required)) {
        echo 'bad form data';
        die();
    }

    $name = htmlspecialchars_decode($args['event-name']);
    $account_name = htmlspecialchars_decode($args['account-name']);
    $event_id = isset($args['event_id']) ? intval($args['event_id']) : 0;
    $role = isset($args['role']) ? $args['role'] : 'p';

    if ($event_id === 0) {
        header('Location: requestFailed.php');
        die();
    }

    $event_info = fetch_event_by_id($event_id);
    if (!$event_info) {
        header('Location: requestFailed.php');
        die();
    }

    $event_type = isset($event_info['type']) ? $event_info['type'] : '';
    $is_recurring_event = !empty($event_info['series_id']);

    $skills = isset($args['skills']) ? trim($args['skills']) : '';
    $disabilities = isset($args['disabilities']) ? trim($args['disabilities']) : '';
    $materials = isset($args['materials']) ? trim($args['materials']) : '';

    $notes = '';
    if ($is_recurring_event) {
        $notesParts = array(
            'Skills: ' . ($skills !== '' ? $skills : 'None'),
            'Disabilities: ' . ($disabilities !== '' ? $disabilities : 'None'),
            'Materials: ' . ($materials !== '' ? $materials : 'None')
        );
        $notes = implode(' | ', $notesParts);
    }

    if ($event_type === "Retreat") {
        require_once('database/dbApplications.php');
        require_once('database/dbMessages.php');

        $app_data = array(
            'user_id' => $account_name,
            'event_id' => $event_id,
            'status' => 'Pending',
            'flagged' => 0,
            'notes' => $notes
        );

        $app_id = create_app($app_data);
        if (!$app_id) {
            header('Location: requestFailed.php');
            die();
        }

        send_system_message(
            $userID,
            "Your request to sign up for $name has been sent to an admin.",
            "Your request to sign up for $name will be reviewed by an admin shortly. You will get another notification when you are approved or denied."
        );

        header('Location: signupPending.php');
        die();
    } else {
        require_once('database/dbMessages.php');

        $id = sign_up_for_event($event_id, $account_name, $role, $notes);
        if (!$id) {
            header('Location: eventFailure.php');
            exit();
        }

        send_system_message(
            $userID,
            "You are now signed up for $name!",
            "Thank you for signing up for $name!"
        );

        header('Location: signupSuccess.php');
        die();
    }
}

// Get event id from URL
if (isset($_GET['id'])) {
    $event_id = intval($_GET['id']);
} elseif (isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']);
} else {
    $event_id = 0;
}

if ($event_id === 0) {
    header('Location: requestFailed.php');
    die();
}

$event_info = fetch_event_by_id($event_id);
if (!$event_info) {
    header('Location: requestFailed.php');
    die();
}

$event_name = $event_info['name'];
$event_type = isset($event_info['type']) ? $event_info['type'] : '';
$is_recurring_event = !empty($event_info['series_id']);

$account_name = isset($_SESSION['_id']) ? $_SESSION['_id'] : '';
?>
<!DOCTYPE html>
<html>

<head>
    <?php require_once('universal.inc') ?>
    <title>Gwyneth's Gift | Sign-Up for Event</title>
</head>

<body>
    <?php require_once('header.php') ?>
    <h1>Sign-Up for Event</h1>

    <main class="date">
        <h2>Sign-Up for Event Form</h2>

        <form id="new-event-form" method="post">
            <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">

            <label for="event-name">* Event Name </label>
            <input
                type="text"
                id="event-name"
                name="event-name"
                required
                value="<?php echo htmlspecialchars_decode($event_name, ENT_QUOTES); ?>"
                placeholder="Event name"
                readonly>

            <label for="account-name">* Your Account Name </label>
            <input
                type="text"
                id="account-name"
                name="account-name"
                <?php echo ($accessLevel >= 2) ? '' : 'readonly'; ?>
                value="<?php echo htmlspecialchars($account_name); ?>"
                placeholder="Enter account name">

            <?php if ($is_recurring_event): ?>
                <label for="skills">Do You Have Any Skills To Share?</label>
                <input type="text" id="skills" name="skills" placeholder="Enter skills. Ex. crochet, tap dancer">

                <label for="disabilities">Do You Have Any Disabilities We Should Be Aware Of?</label>
                <input type="text" id="disabilities" name="disabilities" placeholder="Enter disabilities">

                <label for="materials">Are You Bringing Any Materials (e.g. snacks, craft supplies)?</label>
                <input type="text" id="materials" name="materials" placeholder="Enter materials. Ex. felt, pipe cleaners">
            <?php endif; ?>

            <input type="hidden" name="type" value="<?php echo htmlspecialchars($event_type); ?>">
            <input type="hidden" name="role" value="p">

            <br />
            <input type="submit" value="Sign up for Event">
        </form>

        <a class="button cancel" href="event.php?id=<?php echo urlencode((string)$event_id); ?>" style="margin-top: -.5rem">Return to Event</a>
    </main>
</body>

</html>