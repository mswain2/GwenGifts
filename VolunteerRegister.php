<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<?php
    require_once('include/input-validation.php');
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once('database/dbMessages.php'); ?>
    <title>Gwyneth's Gift | Register</title>
    <link href="css/base.css" rel="stylesheet">

<?php
$tailwind_mode = true;
require_once('header.php');
?>

</head>
<body class="relative">
<?php
    require_once('domain/Person.php');
    require_once('database/dbPersons.php');

    $showPopup = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $ignoreList = array('password', 'password-reenter');
        $args = sanitize($_POST, $ignoreList);

        // Original array. Changed to fit WVF needs
        /*$required = array(
            'first_name', 'last_name', 'birthdate',
            'street_address', 'city', 'state', 'zip', 
            'email', 'phone', 'phone_type',
            'emergency_contact_first_name', 'emergency_contact_last_name',
            'emergency_contact_relation', 'emergency_contact_phone',
            'emergency_contact_phone_type',
            'username', 'password',
            'is_community_service_volunteer',
            'is_new_volunteer', 
            'total_hours_volunteered'
        );*/

        // Deprecated arrays from previous iteration, kept for reference.
        /*
        $required = array(
            'first_name', 'last_name', 'age',
            'city', 'state',
            'affiliation', 'branch',
            'email', 'username', 'password',
            'privacy_consent'
        );

        $optional = array(
            'phone', 'email_prefs'
        );
        */


        // Current updated version
        $required = array(
            'first_name', 'last_name', 'gender', 'birthday',
            'street_address', 'city', 'state', 'zip',
            'email', 'phone1', 'phone_type',
            'emergency_contact_first_name', 'emergency_contact_last_name',
            'emergency_contact_relation', 'emergency_contact_phone',
            'emergency_contact_phone_type',
            'computer_access', 'camera_access', 'transportation_access',
            't_shirt_size',
            'username', 'password',
            'about_consent'
        );

        $optional = array(
            'email_prefs', 'skills', 'experience', 'other_language',
            'day_availability'
        );


        // Validation
        $errors = false;
        if (!wereRequiredFieldsSubmitted($args, $required)) {
            $errors = true;
        }

        // Name validation
        $first_name = $args['first_name'];
        $last_name = $args['last_name'];

        // Gender validation
        $gender = $args['gender'];

        // Unused
        /*$age = $args['age'];  Passes either "true" or "false" */

        // Birthday validation
        $birthday = validateDate($args['birthday']);
        if (!$birthday) {
            echo "<p>Invalid birthday.</p>";
            $errors = true;
        } 

        // Address validation
        $street_address = $args['street_address'];
        $city = $args['city'];
        $state = $args['state'];
        if (!valueConstrainedTo($state, array(
            'AK','AL','AR','AZ','CA','CO','CT','DC','DE','FL','GA','HI','IA','ID','IL','IN','KS','KY','LA','MA','MD','ME',
            'MI','MN','MO','MS','MT','NC','ND','NE','NH','NJ','NM','NV','NY','OH','OK','OR','PA','RI','SC','SD','TN','TX',
            'UT','VA','VT','WA','WI','WV','WY'))) {
            echo "<p>Invalid state.</p>";
            $errors = true;
        }

        $zip_code = $args['zip'];
        if (!validateZipcode($zip_code)) {
            echo "<p>Invalid ZIP code.</p>";
            $errors = true;
        }

        // Email validation
        $email = strtolower($args['email']);
        if (!validateEmail($email)) {
            echo "<p>Invalid email.</p>";
            $errors = true;
        }

        // Email consent validation
        $email_consent = isset($args['email_prefs']) ? 'true' : 'false';

        // Phone validation
        $phone1 = validateAndFilterPhoneNumber($args['phone1']);
        if (!$phone1) {
            echo "<p>Invalid phone number.</p>";
            $errors = true;
        }


        
        /*if(isset($args['phone1'])) { // Make phone number optional 
            $phone1 = validateAndFilterPhoneNumber($args['phone1']);
            if (!$phone1) {
                echo "<p>Invalid phone number.</p>";
                $errors = true;
            }
        } else {
            $phone1 = null;
        }

        $status = $args['status'];

        if(isset($args['email_prefs'])) {
            $email_consent = $args['email_prefs'];
        } else {
            $email_consent = 'false';
        }

        if(!isset($args['privacy_consent']) || $args['privacy_consent'] == 'no') {
            echo "<p>You must agree to the privacy policy to create an account.</p>";
            $errors = true;
        }*/

        /*$affiliation = $args['affiliation'];
        $branch = $args['branch'];*/

        // Phone type validation
        $phone1type = $args['phone_type'];
        if (!valueConstrainedTo($phone1type, array('cellphone', 'home', 'work'))) {
            echo "<p>Invalid phone type.</p>";
            $errors = true;
        }

        // Emergency contact validation
        $emergency_contact_first_name = $args['emergency_contact_first_name'];
        $emergency_contact_last_name = $args['emergency_contact_last_name'];
        $emergency_contact_relation = $args['emergency_contact_relation'];

        $emergency_contact_phone = validateAndFilterPhoneNumber($args['emergency_contact_phone']);
        if (!$emergency_contact_phone) {
            echo "<p>Invalid emergency contact phone.</p>";
            $errors = true;
        } 

        $emergency_contact_phone_type = $args['emergency_contact_phone_type'];
        if (!valueConstrainedTo($emergency_contact_phone_type, array('cellphone', 'home', 'work'))) {
            echo "<p>Invalid emergency phone type.</p>";
            $errors = true;
        }

        // So this availability section is NOT deprecated I added this, but I cannot quite place how to go about the actual implementation into the database. Work in progress.
        $day_availability = isset($args['day_availability']) ? $args['day_availability'] : [];
        /*$availability = [];
        foreach (['sunday','monday','tuesday','wednesday','thursday','friday','saturday'] as $d) {
            if (in_array(ucfirst($d), $day_availability)) {
                $availability[$d] = [
                    'start' => isset($args[$d . '_start']) ? $args[$d . '_start'] : null,
                    'end'   => isset($args[$d . '_end'])   ? $args[$d . '_end']   : null,
                ];
            }
        }*/

        // Languages
        $languages = ['english','spanish','amharic','arabic','french','german','gujarati',
            'haitian_creole','hindi','japanese','korean','mandarin_chinese','punjabi',
            'portuguese','russian','somali','tagalog','tigrinya','urdu','vietnamese'];

        // Loop through languages and collect competency data for each language if provided, stored into arrays.
        $language_data = [];
        foreach ($languages as $lang) {
            if (isset($args['speaking_competency_' . $lang])) {
                $language_data[$lang] = [
                    'speaking'   => $args['speaking_competency_' . $lang],
                    'listening'  => $args['listening_competency_' . $lang] ?? null,
                    'reading'    => $args['reading_competency_' . $lang] ?? null,
                    'writing'    => $args['writing_competency_' . $lang] ?? null,
                ];
            }
        }

        $other_language = isset($args['other_language']) ? $args['other_language'] : null;

        // Skills and experience validation
        $skills = isset($args['skills']) ? $args['skills'] : null;
        $experience = isset($args['experience']) ? $args['experience'] : null;

        // Additional validations
        $computer_access = $args['computer_access'];
        $camera_access = $args['camera_access'];
        $transportation_access = $args['transportation_access'];
        $t_shirt_size = $args['t_shirt_size'];

        // Unused fields from previous iteration, left for reference. These may be added back in the future as needed.

        //$interests = isset($args['interests']) ? $args['interests'] : '';
        /*$is_community_service_volunteer = $args['is_community_service_volunteer'] === 'yes' ? 1 : 0;
        $is_new_volunteer = isset($args['is_new_volunteer']) ? (int)$args['is_new_volunteer'] : 1;
        $total_hours_volunteered = isset($args['total_hours_volunteered']) ? (float)$args['total_hours_volunteered'] : 0.00;
        $type = ($is_community_service_volunteer === 1) ? 'volunteer' : 'participant';
        $archived = 0;
        $status = "Inactive";
        $training_level = "None";*/

        // user and password validation
        $id = $args['username'];

        $password = isSecurePassword($args['password']);
        if (!$password) {
            echo "<p>Password is not secure enough.</p>";
            $errors = true;
        } else {
            $password = password_hash($args['password'], PASSWORD_BCRYPT);
        }

        if ($errors) {
            echo '<p class="error">Your form submission contained unexpected or invalid input.</p>';
            die();
        }

        // About consent validation
        $about_consent = isset($args['about_consent']) ? $args['about_consent'] : null;

        // Deprecated constructor, left for reference. Updated version below.
        /*$newperson = new Person(
            $id, $password, date("Y-m-d"),
            $first_name, $last_name, $birthday,
            $street_address, $city, $state, $zip_code,
            $phone1, $phone1type, $email,
            $emergency_contact_first_name, $emergency_contact_last_name,
            $emergency_contact_phone, $emergency_contact_phone_type,
            $emergency_contact_relation, $type, $status, $archived, 
            $skills, $interests, $training_level,
            $is_community_service_volunteer, $is_new_volunteer,
            $total_hours_volunteered
        ); */

        // Deprecated constructor, left for reference. Updated version below.
        /*$newperson = new Person(
            $id, date("Y-m-d"),
            $first_name, $last_name, null,
            $city, $state, $zip_code, $phone1, $age, 
            null, null, null, null, 
            $email, $email_consent, null,
            null, null, null, null, null, $status, null, 
            $password, $affiliation, $branch, null, null
        );*/

        // Updated constructor with new fields. Note that some fields are being passed as null because they are not currently being collected in the form. These will be updated in the future as needed.
        $newperson = new Person(
            $id, date("Y-m-d"),
            $first_name, $last_name, $street_address,
            $city, $state, $zip_code, $phone1, '',
            $phone1type, $emergency_contact_phone, $emergency_contact_phone_type, $birthday,
            $email, $email_consent,
            $emergency_contact_first_name, null, $emergency_contact_relation,
            null, null, null, null,
            $password, null, null, null,
            $emergency_contact_last_name,
            // new fields
            $gender, $t_shirt_size, $computer_access, $camera_access,
            $transportation_access, $skills, $experience, $about_consent
        );

        // Push of new person into dbpersons
        $result = add_person($newperson);
        if (!$result) {
            $showPopup = true;
        } else {
            echo '<script>document.location = "login.php?registerSuccess";</script>';
            $title = $id . " has been added as a volunteer";
            $body = "New volunteer account has been created";
            system_message_all_admins($title, $body);
        }
    } else {
        require_once('registrationForm.php');
    }
?>

<?php if ($showPopup): ?>
<div id="popupMessage" class="absolute left-[40%] top-[20%] z-50 bg-red-800 p-4 text-white rounded-xl text-xl shadow-lg">
    That username is already taken.
</div>
<?php endif; ?>

<!-- Auto-hide popup -->
<script>
window.addEventListener('DOMContentLoaded', () => {
    const popup = document.getElementById('popupMessage');
    if (popup) {
        popup.style.transition = 'opacity 0.5s ease';
        setTimeout(() => {
            popup.style.opacity = '0';
            setTimeout(() => {
                popup.style.display = 'none';
            }, 500);
        }, 4000);
    }
});
</script>

</body>
</html>
