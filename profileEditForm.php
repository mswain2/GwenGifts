<?php
    require_once('domain/Person.php');
    require_once('database/dbPersons.php');
    require_once('include/output.php');

    // Required imports for Cleave JS to work
    echo('<script src="https://nosir.github.io/cleave.js/dist/cleave.min.js"></script>');
    echo('<script src="https://nosir.github.io/cleave.js/dist/cleave-phone.i18n.js"></script>');
    $args = sanitize($_GET);
    if ($_SESSION['access_level'] >= 2 && isset($args['id'])) {
        $id = $args['id'];
        $editingSelf = $id == $_SESSION['_id'];
        // Check to see if user is a lower-level manager here
    } else {
        $editingSelf = true;
        $id = $_SESSION['_id'];
    }

    $person = retrieve_person($id);
    if (!$person) {
        echo '<main class="signup-form"><p class="error-toast">That user does not exist.</p></main></body></html>';
        die();
    }

    $times = [
        '12:00 AM', '1:00 AM', '2:00 AM', '3:00 AM', '4:00 AM', '5:00 AM',
        '6:00 AM', '7:00 AM', '8:00 AM', '9:00 AM', '10:00 AM', '11:00 AM',
        '12:00 PM', '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM', '5:00 PM',
        '6:00 PM', '7:00 PM', '8:00 PM', '9:00 PM', '10:00 PM', '11:00 PM',
        '11:59 PM'
    ];
    $values = [
        "00:00", "01:00", "02:00", "03:00", "04:00", "05:00", 
        "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", 
        "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", 
        "18:00", "19:00", "20:00", "21:00", "22:00", "23:00",
        "23:59"
    ];
    
    function buildSelect($name, $disabled=false, $selected=null) {
        global $times;
        global $values;
        if ($disabled) {
            $select = '
                <select id="' . $name . '" name="' . $name . '" disabled>';
        } else {
            $select = '
                <select id="' . $name . '" name="' . $name . '">';
        }
        if (!$selected) {
            $select .= '<option disabled selected value>Select a time</option>';
        }
        $n = count($times);
        for ($i = 0; $i < $n; $i++) {
            $value = $values[$i];
            if ($selected == $value) {
                $select .= '
                    <option value="' . $values[$i] . '" selected>' . $times[$i] . '</option>';
            } else {
                $select .= '
                    <option value="' . $values[$i] . '">' . $times[$i] . '</option>';
            }
        }
        $select .= '</select>';
        return $select;
    }
?>
<main class="signup-form">
    <?php if (isset($updateSuccess)): ?>
        <?php if ($updateSuccess): ?>
            <div class="happy-toast">Profile updated successfully!</div>
        <?php else: ?>
            <div class="error-toast">An error occurred.</div>
        <?php endif ?>
    <?php endif ?>
    <?php if ($isAdmin): ?>
        <?php if (strtolower($id) == 'vmsroot') : ?>
            <div class="error-toast">The root user profile cannot be modified</div></main></body>
            <?php die() ?>
        <?php elseif (isset($_GET['id']) && $_GET['id'] != $_SESSION['_id']): ?>
            <!-- <a class="button" href="modifyUserRole.php?id=<?php echo htmlspecialchars($_GET['id']) ?>">Modify User Access</a> -->
        <?php endif ?>
    <?php endif ?>
    <div class="sidebar-wrapper">
        <div class="sidebar">
            <div class="sidebar-item" style="pointer-events: none;">
                <h3 style="text-align: center; width: 100%;">Jump To</h3>
            </div>
            <div class="sidebar-item">
                <a href="#login">
                    <img src="images/change-password.png"> Login Credentials
                </a>
            </div>
            <div class="sidebar-item">
                <a href="#personal-info">
                    <img src="images/view-profile.svg"> Personal Information
                </a>
            </div>
            <div class="sidebar-item">
                <a href="#contact-info">
                    <img src="images/phone.png"> Contact Information
                </a>
            </div>
            <div class="sidebar-item">
                <a href="#emergency-contact">
                    <img src="images/users-solid.svg"> Emergency Contact
                </a>
            </div>
            <div class="sidebar-item">
                <a href="#notifs">
                    <img src="images/inbox.svg"> Notification Preferences
                </a>
            </div>
            <div class="sidebar-item">
                <a href="#availability">
                    <img src="images/clock-regular.svg"> Availability
                </a>
            </div>
            <div class="sidebar-item">
                <a href="#languages">
                    <img src="images/file-regular.svg"> Languages
                </a>
            </div>
            <div class="sidebar-item">
                <a href="#skills-experience">
                    <img src="images/list-solid.svg"> Skills and Experience
                </a>
            </div>
            <div class="sidebar-item">
                <a href="#additional-info">
                    <img src="images/clipboard-regular.svg"> Additional Information
                </a>
            </div>
        </div>
    </div>
    <div class="main-content-box">

    <form class="signup-form" method="post">
	<div class="text-center">
          <h2 class="mb-8">Directions</h2>
            <div class="info-box" style="padding-left: 0rem;">
              <p>An asterisk ( <em>*</em> ) indicates a required field.</p>
            </div>
	</div>
        <fieldset class="section-box" id="login">
            <h3 class="mt-2">Login Credentials</h3>
            <div class="blue-div"></div>
            <label>Username</label>
            <p><?php echo $person->get_id() ?></p>

            <label>Password</label>
                <a class="button-signup" href='changePassword.php' style="color: var(--button-font-color); font-weight: bold; width: 28%;">Change Password</a>
        </fieldset>

        <fieldset class="section-box" id="personal-info">
            <h3 class="mt-2">Personal Information</h3>
            <p class="mb-2">The following information will help us identify you within our system.</p>
            <div class="blue-div"></div>
            <label for="first_name"><em>* </em>First Name</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo hsc($person->get_first_name()); ?>" required placeholder="Enter your first name">

            <label for="last_name"><em>* </em>Last Name</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo hsc($person->get_last_name()); ?>" required placeholder="Enter your last name">

            <div class="median-div"></div>

            <label for="gender"><em>* </em>Gender</label>
            <select id="gender" name="gender" required>
                <option value="Male" <?php if ($person->get_gender() == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if ($person->get_gender() == 'Female') echo 'selected'; ?>>Female</option>
                <option value="Other" <?php if ($person->get_gender() == 'Other') echo 'selected'; ?>>Nonbinary | Other</option>
                <option value="Unlisted" <?php if ($person->get_gender() == 'Unlisted') echo 'selected'; ?>>Prefer not to say</option>
            </select>

            <label for="t_shirt_size"><em>* </em>T-shirt Size</label>
            <?php $tsize = $person->get_t_shirt_size(); ?>
            <select id="t_shirt_size" name="t_shirt_size" required>
                <option value="" disabled <?php if (!$tsize) echo 'selected'; ?>>-- Select t-shirt size --</option>
                <option value="S" <?php if ($tsize == 'S') echo 'selected'; ?>>S</option>
                <option value="M" <?php if ($tsize == 'M') echo 'selected'; ?>>M</option>
                <option value="L" <?php if ($tsize == 'L') echo 'selected'; ?>>L</option>
                <option value="XL" <?php if ($tsize == 'XL') echo 'selected'; ?>>XL</option>
                <option value="XXL" <?php if ($tsize == 'XXL') echo 'selected'; ?>>2XL</option>
            </select>

            <label for="birthday"><em>* </em>Date of Birth</label>
            <input type="date" id="birthday" name="birthday" value="<?php echo hsc($person->get_birthday()); ?>" required max="<?php echo date('Y-m-d'); ?>">

            <div class="median-div"></div>

            <label for="street_address"><em>* </em>Street Address</label>
            <input type="text" id="street_address" name="street_address" value="<?php echo hsc($person->get_street_address()); ?>" required placeholder="Enter your street address">

            <label for="city"><em>* </em>City</label>
            <input type="text" id="city" name="city" value="<?php echo hsc($person->get_city()); ?>" required placeholder="Enter your city">

            <label for="state"><em>* </em>State</label>
            <select id="state" name="state" required>
                <?php
                    $state = $person->get_state();
                    $states = array(
                        'Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware', 'District Of Columbia', 'Florida', 'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota', 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
                    );
                    $abbrevs = array(
                        'AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY'
                    );
                    $length = count($states);
                    for ($i = 0; $i < $length; $i++) {
                        if ($abbrevs[$i] == $state) {
                            echo '<option value="' . $abbrevs[$i] . '" selected>' . $states[$i] . '</option>';
                        } else {
                            echo '<option value="' . $abbrevs[$i] . '">' . $states[$i] . '</option>';
                        }
                    }
                ?>
            </select>

            <label for="zip_code"><em>* </em>Zip Code</label>
            <input type="text" id="zip_code" name="zip_code" value="<?php echo hsc($person->get_zip_code()); ?>" pattern="[0-9]{5}" title="5-digit zip code" required placeholder="Enter your 5-digit zip code">

            <?php if ($isAdmin): ?>
                <div class="median-div"></div>
                <label for="notes">Personal Notes</label>
                <textarea id="notes" name="notes" placeholder="Any additional notes"><?php echo hsc($person->get_notes()); ?></textarea>
            <?php endif ?>
        </fieldset>

        <fieldset class="section-box" id="contact-info">
            <h3 class="mt-2">Contact Information</h3>
            <p class="mb-2">The following information will help us determine the best way to contact you regarding event coordination.</p>
            <div class="blue-div"></div>
            <label for="email"><em>* </em>E-mail</label>
            <input type="email" id="email" name="email" value="<?php echo hsc($person->get_email()); ?>" required placeholder="Enter your e-mail address">

            <label for="phone1"><em>* </em>Phone Number</label>
            <input type="tel" id="phone1" class="phone" name="phone1" value="<?php echo formatPhoneNumber($person->get_phone1()); ?>" pattern="(\D{0,1})\d{3}(\D{0,2})\d{3}(.{0,1})\d{4}" placeholder="Ex. (555) 555-5555">

            <label for="phone1type"><em>* </em>Phone Type</label>
            <div class="radio-group">
                <?php $type = $person->get_phone1type(); ?>
                <div class="radio-element">
                    <input type="radio" id="phone-type-cellphone" name="phone1type" value="cellphone" <?php if ($type == 'cellphone') echo 'checked'; ?> required><label for="phone-type-cellphone"> Cell</label>
                </div>
                <div class="radio-element">
                    <input type="radio" id="phone-type-home" name="phone1type" value="home" <?php if ($type == 'home') echo 'checked'; ?> required><label for="phone-type-home"> Home</label>
                </div>
                <div class="radio-element">
                    <input type="radio" id="phone-type-work" name="phone1type" value="work" <?php if ($type == 'work') echo 'checked'; ?> required><label for="phone-type-work"> Work</label>
                </div>
            </div>

        </fieldset>

        <fieldset class="section-box" id="notifs">
            <h3 class="mt-2">Notification Preferences</h3>
            <p class="mb-2">You may change your email preferences at any time.</p>
            <div class="blue-div"></div>

            <label for="email_consent">E-mail Notifications</label>
            <p>By checking the box below, you acknowledge that you hereby consent to being contactd by Gwyneth's Gift via email for the purpose of:</p>
            <ol>
                <li>- Event Registration Confirmations</li>
                <li>- Event Reminders</li>
                <li>- Event and General Communications</li>
            </ol>
            <p>You may change your email preferences at any time through your account settings.</p>

            <label><input type="checkbox" id="email_prefs" name="email_prefs" value="true" <?php if ($person->get_email_prefs()) echo 'checked'; ?>> I consent.</label>
        </fieldset>

        <fieldset class="section-box" id="emergency-contact">
            <h3 class="mt-2">Emergency Contact</h3>
            <p class="mb-2">Please provide us with someone's contact information on your behalf in case of an emergency.</p>
            <div class="blue-div"></div>

            <label for="emergency_contact_first_name"><em>* </em>First Name</label>
            <input type="text" id="emergency_contact_first_name" name="emergency_contact_first_name" value="<?php echo hsc($person->get_emergency_contact_first_name()); ?>" required placeholder="Enter emergency contact first name">

            <label for="emergency_contact_last_name"><em>* </em>Last Name</label>
            <input type="text" id="emergency_contact_last_name" name="emergency_contact_last_name" value="<?php echo hsc($person->get_emergency_contact_last_name()); ?>" required placeholder="Enter emergency contact last name">

            <label for="emergency_contact_relation"><em>* </em>Relationship to You</label>
            <input type="text" id="emergency_contact_relation" name="emergency_contact_relation" value="<?php echo hsc($person->get_emergency_contact_relation()); ?>" required placeholder="Ex. Spouse, Mother, Father, Sister, Brother, Friend">

            <label for="emergency_contact_phone"><em>* </em>Phone Number</label>
            <input type="tel" id="emergency_contact_phone" class="phone" name="emergency_contact_phone" value="<?php echo formatPhoneNumber($person->get_emergency_contact_phone()); ?>" pattern="(\D{0,1})\d{3}(\D{0,2})\d{3}(.{0,1})\d{4}" placeholder="Ex. (555) 555-5555">

            <label for="emergency_contact_phone_type"><em>* </em>Phone Type</label>
            <div class="radio-group">
                <?php $ec_type = $person->get_emergency_contact_phone_type(); ?>
                <div class="radio-element">
                    <input type="radio" id="ec-phone-type-cellphone" name="emergency_contact_phone_type" value="cellphone" <?php if ($ec_type == 'cellphone') echo 'checked'; ?> required><label for="ec-phone-type-cellphone"> Cell</label>
                </div>
                <div class="radio-element">
                    <input type="radio" id="ec-phone-type-home" name="emergency_contact_phone_type" value="home" <?php if ($ec_type == 'home') echo 'checked'; ?> required><label for="ec-phone-type-home"> Home</label>
                </div>
                <div class="radio-element">
                    <input type="radio" id="ec-phone-type-work" name="emergency_contact_phone_type" value="work" <?php if ($ec_type == 'work') echo 'checked'; ?> required><label for="ec-phone-type-work"> Work</label>
                </div>
            </div>

        </fieldset>

        <!--<fieldset class="section-box">
            <h3 class="mt-2">Volunteer Information</h3>
            <div class="blue-div"></div>

 
    <label>Account Type</label>
    <p>
        <?php 
            //echo $person->get_is_community_service_volunteer() 
                // ? 'Community Service Volunteer' 
                // : 'Standard Volunteer'; 
        ?>
    </p>
        </fieldset>-->

            <!--<label>Are there any specific skills you have that you believe could be useful for volunteering at FredSPCA?</label>
            <input type="text" id="skills" name="skills" value="<?php //echo hsc($person->get_skills()); ?>" placeholder="">

            <label>Do you have any interests?</label>
            <input type="text" id="interests" name="interests" value="<?php //echo hsc($person->get_interests()); ?>" placeholder="">-->

            <!-- Availability Section -->
    <fieldset class="section-box mb-4" id="availability">
        <h3 class="mt-2">Availability</h3>
        <p class="mb-2">The following information will help us determine the best volunteer opportunities for you.</p>
        <p class="mb-2">Click the checkbox next to each day you are available.</p>
        <div class="blue-div"></div>
        
        <script>
        
        // Toggle the display of time selectors based on day availability checkboxes
        function toggleDay(day) {
            var times = document.getElementById(day + '_times');
            var start = document.querySelector('[name=' + day + '_start]');
            var end = document.querySelector('[name=' + day + '_end]');
            var checked = document.getElementById(day).checked;

            times.style.display = checked ? 'block' : 'none';
            start.disabled = !checked;
            end.disabled = !checked;
        }
        </script>

        <?php

        // Generate time options for the availability selectors
        function timeOptions() {
            $time_selection = '<option value="" selected>-- Select time --</option>';
            for ($h = 0; $h < 24; $h++) {
                $value = $h < 12 ? $h . 'am' : ($h - 12) . 'pm';
                $label = $h == 0 ? '12 AM' : ($h < 12 ? $h . ' AM' : ($h == 12 ? '12 PM' : ($h - 12) . ' PM'));
                $time_selection .= "<option value=\"$value\">$label</option>";
            }
            return $time_selection;
        }
        
        /* 
        Generate availability checkboxes and time selectors for each day of the week
        
        ID reference example for each day:
            "sunday" for checkbox
            "sunday_times" for the div containing time selectors
            "sunday_start" and "sunday_end" for time selectors
        */
        function dayAvailability($day) {
            $d = strtolower($day);
            echo "
            <div>
                <input type='checkbox' id='$d' name='day_availability' value='$day' onchange='toggleDay(\"$d\")'>
                <label for='$d'> $day</label>
                <div id='{$d}_times' style='display:none'>
                    <p class='mb-2'>If you are available on $day, please indicate your availability below.</p>
                    <p class='mb-2'>Start Availability Time (From):</p>
                    <select name='{$d}_start' disabled>" . timeOptions() . "</select>
                    <p class='mb-2'>End Availability Time (To):</p>
                    <select name='{$d}_end' disabled>" . timeOptions() . "</select>
                </div>
            </div>";
        }

        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        
        // Loop through days of the week to generate availability checkboxes and time selectors
        foreach ($days as $day) {
            dayAvailability($day);
        }
        ?>
        
    </fieldset>


    <!-- Languages Section -->
    <fieldset class="section-box mb-4" id="languages">
        <h3 class="mt-2">Languages</h3>
        <p class="mb-2">Please describe your language skills.</p>
        <div class="blue-div"></div>
        
        <?php
        $languages = [
            'English', 'Spanish', 'Amharic', 'Arabic', 'French',
            'German', 'Gujarati', 'Haitian Creole', 'Hindi', 'Japanese',
            'Korean', 'Mandarin Chinese', 'Punjabi', 'Portuguese', 'Russian',
            'Somali', 'Tagalog', 'Tigrinya', 'Urdu', 'Vietnamese'
        ];
        ?>

        <!-- 
            Generate a multi-select dropdown for the 20 most spoken languages in Virginia with PHP. 
            English and Spanish are anchored to the top, rest is sorted alphabetically in the list
        -->
        <label>Languages spoken:</label>
        <p class="mb-2">Select all languages you are proficient in. We will ask you to indicate your competency level for each language selected.</p>
        <select id="language_select" multiple size="6">
            <option value="" disabled>-- Select languages --</option>
            <?php foreach ($languages as $lang): ?>
                <?php $d = strtolower(str_replace(' ', '_', $lang)); ?>
                <option value="<?= $d ?>" data-label="<?= $lang ?>" <?= $lang === 'English' ? 'selected' : '' ?>><?= $lang ?></option>
            <?php endforeach; ?>
        </select>
        <p class="mb-2"><small>Hold Ctrl (Windows | Linux) or Cmd (Mac) to select multiple.</small></p>

        <div id="competency_container"></div>

        <script>
        
        // Listen for changes to the language multi-select and dynamically show competency selectors for each selected language
        document.getElementById('language_select').addEventListener('change', function() {
            var selected = Array.from(this.selectedOptions);
            var container = document.getElementById('competency_container');
            container.innerHTML = '';

            selected.forEach(function(option) {
                var div = document.createElement('div');
                div.innerHTML = `
                    <label>${option.dataset.label} Speaking Competency:</label>
                    <p class="mb-2">Please indicate your speaking competency level in ${option.dataset.label}.</p>
                    <select name="speaking_competency_${option.value}">
                        <option value="">-- Select competency --</option>
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                        <option value="fluent">Native/Fluent</option>
                    </select>

                    <label>${option.dataset.label} Listening Competency:</label>
                    <p class="mb-2">Please indicate your listening competency level in ${option.dataset.label}.</p>
                    <select name="listening_competency_${option.value}">
                        <option value="">-- Select competency --</option>
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                        <option value="fluent">Native/Fluent</option>
                    </select>

                    <label>${option.dataset.label} Reading Competency:</label>
                    <p class="mb-2">Please indicate your reading competency level in ${option.dataset.label}.</p>
                    <select name="reading_competency_${option.value}">
                        <option value="">-- Select competency --</option>
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                        <option value="fluent">Native/Fluent</option>
                    </select>

                    <label>${option.dataset.label} Writing Competency:</label>
                    <p class="mb-2">Please indicate your writing competency level in ${option.dataset.label}.</p>
                    <select name="writing_competency_${option.value}">
                        <option value="">-- Select competency --</option>
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                        <option value="fluent">Native/Fluent</option>
                    </select>

                    <div class="median-div"></div>
                `;
                container.appendChild(div);
            });
        });

        // Trigger on page load so English competency shows automatically
        document .getElementById('language_select').dispatchEvent(new Event('change'));
        </script>

        <!-- I manually added an unlisted lang section. This might be a placeholder as I feel there's a better implementation for this, but it will work for now. -->
        <label>Unlisted Language</label>
        <p class="mb-2">Listed above are the 20 most commonly spoken languages in Virginia.</p>
        <p class="mb-2">If there is a language you are proficient in that is not listed above, please indicate it here along with your competency level.</p>
        <input type="text" id="other_language" name="other_language" placeholder="">

        <label>Speaking Competency:</label>
        <p class="mb-2">Please indicate your speaking competency level in the language you have provided.</p>
        <select name="speaking_competency_other_language">
            <option value="">-- Select competency --</option>
            <option value="beginner">Beginner</option>
            <option value="intermediate">Intermediate</option>
            <option value="advanced">Advanced</option>
            <option value="fluent">Native/Fluent</option>
        </select>

        <label>Listening Competency:</label>
        <p class="mb-2">Please indicate your listening competency level in the language you have provided.</p>
        <select name="listening_competency_other_language">
            <option value="">-- Select competency --</option>
            <option value="beginner">Beginner</option>
            <option value="intermediate">Intermediate</option>
            <option value="advanced">Advanced</option>
            <option value="fluent">Native/Fluent</option>
        </select>

        <label>Reading Competency:</label>
        <p class="mb-2">Please indicate your reading competency level in the language you have provided.</p>
        <select name="reading_competency_other_language">
            <option value="">-- Select competency --</option>
            <option value="beginner">Beginner</option>
            <option value="intermediate">Intermediate</option>
            <option value="advanced">Advanced</option>
            <option value="fluent">Native/Fluent</option>
        </select>

        <label>Writing Competency:</label>
        <p class="mb-2">Please indicate your writing competency level in the language you have provided.</p>
        <select name="writing_competency_other_language">
            <option value="">-- Select competency --</option>
            <option value="beginner">Beginner</option>
            <option value="intermediate">Intermediate</option>
            <option value="advanced">Advanced</option>
            <option value="fluent">Native/Fluent</option>
        </select>

    </fieldset>

    <!-- Skills and Experience Section -->
    <fieldset class="section-box mb-4" id="skills-experience">
        <h3 class="mt-2">Skills and Experience</h3>
        <p class="mb-2">Please provide any additional information about your skills and experience that you believe may be relevant for volunteering with our organization.</p>
    
        <div class="blue-div"></div>

        <label for="skills">Skills</label>
        <p class="mb-2">Please list any relevant skills you have that may be useful for our services.</p>
        <textarea id="skills" name="skills" placeholder="Ex. Event planning, social media management, fundraising, translating, etc."></textarea>

        <label for="experience">Experience</label>
        <p class="mb-2">Please describe any relevant experience you have volunteering or working.</p>
        <textarea id="experience" name="experience" placeholder="Eg. other volunteer work, industry experience, etc."></textarea>
    </fieldset>

    <!-- Additional Information Section -->
    <fieldset class="section-box mb-4" id="additional-info">
        <h3 class="mt-2">Additional Information</h3>
        <p class="mb-2">The following information will help us determine the best volunteer opportunities for you and ensure that we are providing you with the best experience possible.</p>
        
        <div class="blue-div"></div>
        
        <label for="computer_access"><em>* </em>Computer Access</label>
        <p class="mb-2">Do you have regular access to a computer and the internet?</p>
        <div class="radio-group">
            <div class="radio-element">
                <input type="radio" id="computer_access_yes" name="computer_access" value="yes" <?php if ($person->get_computer_access() == 'yes') echo 'checked'; ?> required><label for="computer_access_yes"> Yes</label>
            </div>
            <div class="radio-element">
                <input type="radio" id="computer_access_no" name="computer_access" value="no" <?php if ($person->get_computer_access() == 'no') echo 'checked'; ?> required><label for="computer_access_no"> No</label>
            </div>
        </div>

        <div class="median-div"></div>

        <label for="camera_access"><em>* </em>Camera Access</label>
        <p class="mb-2">Do you have access to a camera for taking photos? Cell phone cameras are acceptable.</p>
        <div class="radio-group">
            <div class="radio-element">
                <input type="radio" id="camera_access_yes" name="camera_access" value="yes" <?php if ($person->get_camera_access() == 'yes') echo 'checked'; ?> required><label for="camera_access_yes"> Yes</label>
            </div>
            <div class="radio-element">
                <input type="radio" id="camera_access_no" name="camera_access" value="no" <?php if ($person->get_camera_access() == 'no') echo 'checked'; ?> required><label for="camera_access_no"> No</label>
            </div>
        </div>

        <div class="median-div"></div>

        <label for="transportation_access"><em>* </em>Transportation Access</label>
        <p class="mb-2">Do you have reliable transportation to get to volunteer sites?</p>
        <div class="radio-group">
            <div class="radio-element">
                <input type="radio" id="transportation_access_yes" name="transportation_access" value="yes" <?php if ($person->get_transportation_access() == 'yes') echo 'checked'; ?> required><label for="transportation_access_yes"> Yes</label>
            </div>
            <div class="radio-element">
                <input type="radio" id="transportation_access_no" name="transportation_access" value="no" <?php if ($person->get_transportation_access() == 'no') echo 'checked'; ?> required><label for="transportation_access_no"> No</label>
            </div>
        </div>

    </fieldset>


    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <input type="submit" name="profile-edit-form" value="Update Profile">
    <?php if ($editingSelf): ?>
        <button type="button" class="button cancel" onclick="history.back();" style="margin-top: -.5rem">Cancel</button>
    <?php else: ?>
        <button type="button" class="button cancel" onclick="window.location.href='viewProfile.php?id=<?php echo htmlspecialchars($id); ?>';" style="margin-top: -.5rem">Cancel</button>
    <?php endif ?>

    </form>
    </div>
    <script>
        // Initialize Cleave.js for primary phone number
        new Cleave('#phone1', {
            phone: true,
            phoneRegionCode: 'US',
            delimiter: '-',
            numericOnly: true,
        });

        // Initialize Cleave.js for emergency contact phone number
        new Cleave('#emergency_contact_phone', {
            phone: true,
            phoneRegionCode: 'US',
            delimiter: '-',
            numericOnly: true,
        });
    </script>
</main>
