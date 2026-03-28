<?php
session_cache_expire(30);
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('include/input-validation.php');
require_once('domain/Person.php');
require_once('database/dbPersons.php');
require_once('database/dbMessages.php');

$showPopup = false;
$errors = false;
$error_messages = [];
$args = [];
$day_availability = [];

$isAdminCreating = false;
if (isset($_SESSION['_id'])) {
    $loggedInUser = retrieve_person($_SESSION['_id']);
    $isAdminCreating = $loggedInUser && in_array($loggedInUser->get_type(), ['admin', 'superadmin']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ignoreList = array('password', 'password-reenter');
    $args = sanitize($_POST, $ignoreList);

    // Name validation
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

    $id = $args['username'] ?? '';
    if (empty($id)) { $errors = true; $error_messages['username'] = 'Username is required.'; }

    $password = isSecurePassword($args['password'] ?? '');
    if (!$password) {
        $errors = true;
        $error_messages['password'] = 'Password must be at least 8 characters and contain at least one number, one uppercase, and one lowercase letter.';
    } else {
        $password = password_hash($args['password'], PASSWORD_BCRYPT);
    }

    $about_consent = $args['about_consent'] ?? '';
    if (!$isAdminCreating && $about_consent !== 'yes') {
        $errors = true;
        $error_messages['about_consent'] = 'You must agree to the About Us affirmation.';
    }
    $about_consent = $isAdminCreating ? 'yes' : $about_consent;

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
        $newperson = new Person(
            $id, date("Y-m-d"),
            $first_name, $last_name, $street_address,
            $city, $state, $zip_code, $phone1, '',
            $phone1type, $emergency_contact_phone, $emergency_contact_phone_type, $birthday,
            $email, $email_consent,
            $emergency_contact_first_name, null, $emergency_contact_relation,
            null, "volunteer", "Active", null,
            $password, null, null, null,
            $emergency_contact_last_name,
            $gender, $t_shirt_size, $computer_access, $camera_access,
            $transportation_access, $skills, $experience, $about_consent
        );

        $result = add_person($newperson);
        if (!$result) {
            $showPopup = true;
        } else {
            if (!empty($language_data)) add_languages($id, $language_data);
            if (!empty($day_availability)) add_availabilities($id, $day_availability, $args);
            $title = $id . " has been added as a volunteer";
            $body  = "New volunteer account has been created";
            system_message_all_admins($title, $body);
            if ($isAdminCreating) {
                header('Location: viewProfile.php?id=' . $id . '&registerSuccess');
            } else {
                header('Location: login.php?registerSuccess');
            }
            die();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    
    <title>Gwyneth's Gift | Register</title>
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

<?php error_log("first_name in args: " . ($args['first_name'] ?? 'EMPTY')); ?>

<?php require_once('registrationForm.php'); ?>

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