<?php
    // Author: Lauren Knight
    // Description: Profile edit page
    session_cache_expire(30);
    session_start();
    ini_set("display_errors",1);
    error_reporting(E_ALL);
    if (!isset($_SESSION['_id'])) {
        header('Location: login.php');
        die();
    }

    require_once('include/input-validation.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["modify_access"]) && isset($_POST["id"])) {
        $id = $_POST['id'];
        header("Location: /gwyneth/modifyUserRole.php?id=$id");
    } else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["profile-edit-form"])) {
        require_once('domain/Person.php');
        require_once('database/dbPersons.php');
        // make every submitted field SQL-safe except for password
        $ignoreList = array('password');
        $args = sanitize($_POST, $ignoreList);

        $editingSelf = true;
        if ($_SESSION['access_level'] >= 2 && isset($_POST['id'])) {
            $id = $_POST['id'];
            $editingSelf = $id == $_SESSION['_id'];
            $id = $args['id'];
            // Check to see if user is a lower-level manager here
        } else {
            $id = $_SESSION['_id'];
            $errors = false;
        }

        // echo "<p>The form was submitted:</p>";
        // foreach ($args as $key => $value) {
        //     echo "<p>$key: $value</p>";
        // }

        // Deprecated

        /* $required = array(
            'first_name', 'last_name', 'city', 'state',
            'email', 'phone1', 'email_consent', 
            'affiliation', 'branch'
        );
        $errors = false;
        if (!wereRequiredFieldsSubmitted($args, $required)) {
            $errors = true;
        } */

        

        $first_name = $args['first_name'];
        $last_name = $args['last_name'];
        $gender = $args['gender'];
        $t_shirt_size = $args['t_shirt_size'];
        
        $birthday = validateDate($args['birthday']);
        if (!$birthday) {
            $errors = true;
            // echo 'bad dob';
        }
        
        $street_address = $args['street_address'];
        $city = $args['city'];
        $state = $args['state'];
        if (!valueConstrainedTo($state, array('AK', 'AL', 'AR', 'AZ', 'CA', 'CO', 'CT', 'DC', 'DE', 'FL', 'GA',
                'HI', 'IA', 'ID', 'IL', 'IN', 'KS', 'KY', 'LA', 'MA', 'MD', 'ME',
                'MI', 'MN', 'MO', 'MS', 'MT', 'NC', 'ND', 'NE', 'NH', 'NJ', 'NM',
                'NV', 'NY', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX',
                'UT', 'VA', 'VT', 'WA', 'WI', 'WV', 'WY'))) {
            $errors = true;
        }

        $zip_code = $args['zip_code'];
        if (!validateZipcode($zip_code)) {
            $errors = true;
            // echo 'bad zip';
        }

        $email = validateEmail($args['email']);
        if (!$email) {
            $errors = true;
            // echo 'bad email';
        }

        $phone1 = validateAndFilterPhoneNumber($args['phone1']);
        if (!$phone1) {
            $errors = true;
            // echo 'bad phone';
        }

        $phone1type = $args['phone1type'];
        /*
        if (!valueConstrainedTo($phone1type, array('cellphone', 'home', 'work'))) {
            $errors = true;
            // echo 'bad phone type';
        }*/
        
        /*@
        $contactWhen = $args['contact-when'];
        $contactMethod = $args['contact-method'];
        if (!valueConstrainedTo($contactMethod, array('phone', 'text', 'email'))) {
            $errors = true;
            // echo 'bad contact method';
        }
        @*/

        $emergency_contact_first_name = $args['emergency_contact_first_name'];
        $emergency_contact_last_name = $args['emergency_contact_last_name'];
        $emergency_contact_relation = $args['emergency_contact_relation'];
        
        $emergency_contact_phone = validateAndFilterPhoneNumber($args['emergency_contact_phone']);
        if (!$emergency_contact_phone) {
            $errors = true;
            // echo 'bad e-contact phone';
        }

        $emergency_contact_phone_type = $args['emergency_contact_phone_type'];
        /*
        if (!valueConstrainedTo($emergency_contact_phone_type, array('cellphone', 'home', 'work'))) {
            $errors = true;
            // echo 'bad phone type';
        } */

        //$emergency_contact_relation = $args['emergency_contact_relation'];

        /*@
        $gender = $args['gender'];
        if (!valueConstrainedTo($gender, ['Male', 'Female', 'Other'])) {
            $errors = true;
            echo 'bad gender';
        }
        @*/

       /*$type = 'v';
        $skills = $args['skills'];
        $interests = $args['interests'];*/
        $person = retrieve_person($id);

        $computer_access = $args['computer_access'];
        $camera_access = $args['camera_access'];
        $transportation_access = $args['transportation_access'];
        $skills = $args['skills'] ?? '';
        $experience = $args['experience'] ?? '';
        $email_prefs = isset($args['email_prefs']) ? 'true' : 'false';
        $notes = $args['notes'] ?? $person->get_notes();

        

        /*if(isset($args['email_prefs'])) {
            $email_consent = $args['email_prefs']; 
        } else {
            $email_consent = $person->get_email_prefs();
        }*/

        /*
        if(isset($args['branch'])) {
            $branch = $args['branch'];
        } else {
            $branch = $person->get_branch();
        }

        if(isset($args['affiliation'])) {
            $affiliation = $args['affiliation'];
        } else {
            $affiliation = $person->get_affiliation();
        }
        */

        //$notes = isset($args['notes']) ? $args['notes'] : $person->get_notes();

        if (!$errors) {
            $result = update_person_full(
                $id, $first_name, $last_name, $gender, $t_shirt_size, $birthday,
                $street_address, $city, $state, $zip_code,
                $email, $email_prefs, $phone1, $phone1type,
                $emergency_contact_first_name, $emergency_contact_last_name,
                $emergency_contact_phone, $emergency_contact_phone_type, $emergency_contact_relation,
                $computer_access, $camera_access, $transportation_access,
                $skills, $experience, $notes
            );

            if ($result) {
                // Handle availabilities — delete old, insert new
                $con = connect();
                $safe_id = mysqli_real_escape_string($con, $id);
                mysqli_query($con, "DELETE FROM dbavailabilities WHERE person_id = '$safe_id'");
                mysqli_close($con);

                $day_availability = isset($args['day_availability']) ? (array)$args['day_availability'] : [];
                if (!empty($day_availability)) {
                    add_availabilities($id, $day_availability, $args);
                }

                // Handle languages — delete old, insert new
                $con = connect();
                mysqli_query($con, "DELETE FROM dblanguages WHERE person_id = '$safe_id'");
                mysqli_close($con);

                $selected_languages = isset($args['selected_languages']) ? (array)$args['selected_languages'] : [];
                $selected_languages = array_map(function($l) { return preg_replace('/[^a-z_]/', '', $l); }, $selected_languages);                $language_data = [];
                foreach ($selected_languages as $lang) {
                    $language_data[$lang] = [
                        'speaking'  => $args['speaking_competency_'  . $lang] ?? null,
                        'listening' => $args['listening_competency_' . $lang] ?? null,
                        'reading'   => $args['reading_competency_'   . $lang] ?? null,
                        'writing'   => $args['writing_competency_'   . $lang] ?? null,
                    ];
                }

                // Handle unlisted language
                $other_language = $args['other_language'] ?? null;
                if (!empty($other_language)) {
                    $lang_key = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', trim($other_language)));
                    $language_data[$lang_key] = [
                        'speaking'  => $args['speaking_competency_other_language']  ?? null,
                        'listening' => $args['listening_competency_other_language'] ?? null,
                        'reading'   => $args['reading_competency_other_language']   ?? null,
                        'writing'   => $args['writing_competency_other_language']   ?? null,
                    ];
                }

                if (!empty($language_data)) {
                    add_languages($id, $language_data);
                }

                if ($editingSelf) {
                    header('Location: viewProfile.php?editSuccess');
                } else {
                    header('Location: viewProfile.php?editSuccess&id=' . $id);
                }
                die();
            }
        }
        
        /*
        $result = update_person_required(
            $id, $first_name, $last_name, $city, $state,
            $email, $phone1, $email_consent, $affiliation, $branch,
            $notes
        );
        if ($result) {
            if ($editingSelf) {
                header('Location: viewProfile.php?editSuccess');
            } else {
                header('Location: viewProfile.php?editSuccess&id='. $id);
            }
            die();
        }*/




    }
?>
<!DOCTYPE html>
<html>
<head>
    <?php require_once('universal.inc'); ?>
    <title>Gwyneth's Gift | Edit Profile</title>
    <link src="css/base.css" rel="stylesheet">
</head>
<body>
    <h1>Edit Profile</h1>
    <?php
        require_once('header.php');
        $isAdmin = $_SESSION['access_level'] >= 2;
        require_once('profileEditForm.php');
    ?>
    
</body>
</html>