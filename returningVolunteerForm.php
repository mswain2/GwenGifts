<?php
session_cache_expire(30);
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('include/input-validation.php');
require_once('domain/Person.php');
require_once('database/dbPersons.php');
require_once('database/dbMessages.php');

// Must be in forced mode to access this page
$forced = isset($_SESSION['change-password']) && $_SESSION['change-password'];
if (!$forced || !isset($_SESSION['_id'])) {
    header('Location: login.php');
    die();
}

$userID = $_SESSION['_id'];  // still their email at this point
$existingUser = retrieve_person($userID);
if (!$existingUser) {
    header('Location: login.php');
    die();
}

$showPopup = false;
$errors = false;
$error_messages = [];
$args = [];
$day_availability = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ignoreList = array('password', 'password-reenter');
    $args = sanitize($_POST, $ignoreList);

    // Name validation (same as VolunteerRegister.php)
    $first_name = $args['first_name'] ?? '';
    if (empty($first_name)) { $errors = true; $error_messages['first_name'] = 'First name is required.'; }

    $last_name = $args['last_name'] ?? '';
    if (empty($last_name)) { $errors = true; $error_messages['last_name'] = 'Last name is required.'; }

    $gender = $args['gender'] ?? '';
    if (empty($gender)) { $errors = true; $error_messages['gender'] = 'Please select a gender.'; }

    $t_shirt_size = $args['t_shirt_size'] ?? '';
    if (empty($t_shirt_size)) { $errors = true; $error_messages['t_shirt_size'] = 'Please select a t-shirt size.'; }

    $birthday = validateDate($args['birthday'] ?? '');
    if (!$birthday) { $errors = true; $error_messages['birthday'] = 'Invalid birthday.'; }

    $street_address = $args['street_address'] ?? '';
    if (empty($street_address)) { $errors = true; $error_messages['street_address'] = 'Street address is required.'; }

    $city = $args['city'] ?? '';
    if (empty($city)) { $errors = true; $error_messages['city'] = 'City is required.'; }

    $state = $args['state'] ?? '';
    if (!valueConstrainedTo($state, array(
        'AK','AL','AR','AZ','CA','CO','CT','DC','DE','FL','GA','HI','IA','ID','IL','IN','KS','KY','LA','MA','MD','ME',
        'MI','MN','MO','MS','MT','NC','ND','NE','NH','NJ','NM','NV','NY','OH','OK','OR','PA','RI','SC','SD','TN','TX',
        'UT','VA','VT','WA','WI','WV','WY'))) {
        $errors = true; $error_messages['state'] = 'Invalid state.';
    }

    $zip_code = $args['zip'] ?? '';
    if (!validateZipcode($zip_code)) { $errors = true; $error_messages['zip'] = 'Invalid ZIP code.'; }

    $email = strtolower($args['email'] ?? '');
    if (!validateEmail($email)) { $errors = true; $error_messages['email'] = 'Invalid email address.'; }

    $email_consent = isset($args['email_prefs']) ? 'true' : 'false';

    $phone1 = validateAndFilterPhoneNumber($args['phone1'] ?? '');
    if (!$phone1) { $errors = true; $error_messages['phone1'] = 'Invalid phone number.'; }

    $phone1type = $args['phone_type'] ?? '';
    if (!valueConstrainedTo($phone1type, array('cellphone', 'home', 'work'))) {
        $errors = true; $error_messages['phone_type'] = 'Please select a phone type.';
    }

    $emergency_contact_first_name = $args['emergency_contact_first_name'] ?? '';
    $emergency_contact_last_name  = $args['emergency_contact_last_name']  ?? '';
    $emergency_contact_relation   = $args['emergency_contact_relation']   ?? '';

    $emergency_contact_phone = validateAndFilterPhoneNumber($args['emergency_contact_phone'] ?? '');
    if (!$emergency_contact_phone) { $errors = true; $error_messages['emergency_contact_phone'] = 'Invalid phone number.'; }

    $emergency_contact_phone_type = $args['emergency_contact_phone_type'] ?? '';
    if (!valueConstrainedTo($emergency_contact_phone_type, array('cellphone', 'home', 'work'))) {
        $errors = true; $error_messages['emergency_contact_phone_type'] = 'Please select a phone type.';
    }

    $computer_access = $args['computer_access'] ?? null;
    if (empty($computer_access)) { $errors = true; $error_messages['computer_access'] = 'Please select an option.'; }

    $camera_access = $args['camera_access'] ?? null;
    if (empty($camera_access)) { $errors = true; $error_messages['camera_access'] = 'Please select an option.'; }

    $transportation_access = $args['transportation_access'] ?? null;
    if (empty($transportation_access)) { $errors = true; $error_messages['transportation_access'] = 'Please select an option.'; }

    $skills     = $args['skills']     ?? null;
    $experience = $args['experience'] ?? null;

    // Username — must be unique
    $id = strtolower(trim($args['username'] ?? ''));
    if (empty($id)) {
        $errors = true; $error_messages['username'] = 'Username is required.';
    } else if ($id !== $userID && retrieve_person($id)) {
        $errors = true; $error_messages['username'] = 'That username is already taken.';
    }

    // Password
    $password = isSecurePassword($args['password'] ?? '');
    if (!$password) {
        $errors = true;
        $error_messages['password'] = 'Password must be at least 8 characters and contain at least one number, one uppercase, and one lowercase letter.';
    } else {
        $password = password_hash($args['password'], PASSWORD_BCRYPT);
    }

    // No about_consent needed — returning volunteer already consented

    // Day availability (same as VolunteerRegister.php)
    $day_availability = isset($args['day_availability']) ? (array)$args['day_availability'] : [];
    $time_order = [
        '12am'=>0,'1am'=>1,'2am'=>2,'3am'=>3,'4am'=>4,'5am'=>5,
        '6am'=>6,'7am'=>7,'8am'=>8,'9am'=>9,'10am'=>10,'11am'=>11,
        '12pm'=>12,'1pm'=>13,'2pm'=>14,'3pm'=>15,'4pm'=>16,'5pm'=>17,
        '6pm'=>18,'7pm'=>19,'8pm'=>20,'9pm'=>21,'10pm'=>22,'11pm'=>23
    ];
    foreach ($day_availability as $day) {
        $d = strtolower($day);
        $start = $args[$d . '_start'] ?? '';
        $end   = $args[$d . '_end']   ?? '';
        $start_val = $time_order[$start] ?? -1;
        $end_val   = $time_order[$end]   ?? -1;
        if (empty($start) || empty($end)) {
            $errors = true; $error_messages[$d . '_time'] = $day . ': please select both a start and end time.';
        } elseif ($start_val === -1 || $end_val === -1) {
            $errors = true; $error_messages[$d . '_time'] = $day . ': invalid time selection.';
        } elseif ($start_val >= $end_val) {
            $errors = true; $error_messages[$d . '_time'] = $day . ': start time must be before end time.';
        }
    }

    // Language validation (same as VolunteerRegister.php)
    $language_data = [];
    $selected_languages = isset($args['selected_languages']) ? array_map(function($l) { return preg_replace('/[^a-z_]/', '', $l); }, $args['selected_languages']) : [];
    foreach ($selected_languages as $lang) {
        $language_data[$lang] = [
            'speaking'  => $args['speaking_competency_'  . $lang] ?? null,
            'listening' => $args['listening_competency_' . $lang] ?? null,
            'reading'   => $args['reading_competency_'   . $lang] ?? null,
            'writing'   => $args['writing_competency_'   . $lang] ?? null,
        ];
    }
    foreach ($selected_languages as $lang) {
        foreach (['speaking', 'listening', 'reading', 'writing'] as $skill) {
            if (empty($language_data[$lang][$skill])) {
                $errors = true;
                $error_messages['language_competency'] = 'Please fill in all competency fields for each selected language.';
                break 2;
            }
        }
    }

    $other_language = $args['other_language'] ?? null;
    if (!empty($other_language)) {
        $lang_key = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', trim($other_language)));
        $language_data[$lang_key] = [
            'speaking'  => $args['speaking_competency_other_language']  ?? null,
            'listening' => $args['listening_competency_other_language'] ?? null,
            'reading'   => $args['reading_competency_other_language']   ?? null,
            'writing'   => $args['writing_competency_other_language']   ?? null,
        ];
        foreach (['speaking', 'listening', 'reading', 'writing'] as $skill) {
            if (empty($language_data[$lang_key][$skill])) {
                $errors = true;
                $error_messages['other_language_competency'] = 'Please fill in all competency fields for your unlisted language.';
                break;
            }
        }
    }

    if (!$errors) {
        // 1. Change primary key from email to new username
        $idResult = update_person_id($userID, $id);
        if (!$idResult) {
            $showPopup = true;  // username taken (race condition safety)
        } else {
            // 2. Update all fields + password + clear force flag
            $con = connect();
            $query = "UPDATE dbpersons SET
                first_name = '" . mysqli_real_escape_string($con, $first_name) . "',
                last_name = '" . mysqli_real_escape_string($con, $last_name) . "',
                street_address = '" . mysqli_real_escape_string($con, $street_address) . "',
                city = '" . mysqli_real_escape_string($con, $city) . "',
                state = '" . mysqli_real_escape_string($con, $state) . "',
                zip_code = '" . mysqli_real_escape_string($con, $zip_code) . "',
                phone1 = '" . mysqli_real_escape_string($con, $phone1) . "',
                phone1type = '" . mysqli_real_escape_string($con, $phone1type) . "',
                birthday = '" . mysqli_real_escape_string($con, $birthday) . "',
                email = '" . mysqli_real_escape_string($con, $email) . "',
                email_prefs = '" . $email_consent . "',
                gender = '" . mysqli_real_escape_string($con, $gender) . "',
                t_shirt_size = '" . mysqli_real_escape_string($con, $t_shirt_size) . "',
                emergency_contact_first_name = '" . mysqli_real_escape_string($con, $emergency_contact_first_name) . "',
                emergency_contact_last_name = '" . mysqli_real_escape_string($con, $emergency_contact_last_name) . "',
                emergency_contact_relation = '" . mysqli_real_escape_string($con, $emergency_contact_relation) . "',
                emergency_contact_phone = '" . mysqli_real_escape_string($con, $emergency_contact_phone) . "',
                emergency_contact_phone_type = '" . mysqli_real_escape_string($con, $emergency_contact_phone_type) . "',
                computer_access = '" . $computer_access . "',
                camera_access = '" . $camera_access . "',
                transportation_access = '" . $transportation_access . "',
                skills = '" . mysqli_real_escape_string($con, $skills ?? '') . "',
                experience = '" . mysqli_real_escape_string($con, $experience ?? '') . "',
                password = '" . $password . "',
                force_password_change = 0
                WHERE id = '" . mysqli_real_escape_string($con, $id) . "'";
            mysqli_query($con, $query);
            mysqli_close($con);

            // 3. Languages + availability (same as VolunteerRegister.php)
            if (!empty($language_data)) add_languages($id, $language_data);
            if (!empty($day_availability)) add_availabilities($id, $day_availability, $args);

            // 4. Notify admins
            $title = $id . " (returning volunteer) has re-registered";
            $body = "Returning volunteer account has been set up";
            system_message_all_admins($title, $body);

            // 5. Update session
            $_SESSION['_id'] = $id;
            $_SESSION['f_name'] = $first_name;
            $_SESSION['l_name'] = $last_name;
            $_SESSION['access_level'] = 1;
            $_SESSION['logged_in'] = true;
            unset($_SESSION['change-password']);

            header('Location: index.php?pcSuccess');
            die();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gwyneth's Gift | Returning Volunteer</title>
    <link href="css/base.css" rel="stylesheet">
    <?php
    $tailwind_mode = true;
    require_once('header.php');
    ?>
</head>
<body class="relative">

<?php if ($showPopup && !$errors): ?>
<div id="popupMessage" class="absolute left-[40%] top-[20%] z-50 bg-red-800 p-4 text-white rounded-xl text-xl shadow-lg">
    That username is already taken.
</div>
<?php endif; ?>

<?php require_once('returningRegistrationForm.php'); ?>

<script>
window.addEventListener('DOMContentLoaded', () => {
    const popup = document.getElementById('popupMessage');
    if (popup) {
        popup.style.transition = 'opacity 0.5s ease';
        setTimeout(() => {
            popup.style.opacity = '0';
            setTimeout(() => { popup.style.display = 'none'; }, 500);
        }, 4000);
    }
});
</script>
</body>
</html>
