<?php
function field_error($key) {
    global $error_messages;
    if (!empty($error_messages[$key])) {
        echo '<p class="error">' . htmlspecialchars($error_messages[$key]) . '</p>';
    }
}
$error_messages = $error_messages ?? [];
?>

<!-- imports -->
<script src="https://nosir.github.io/cleave.js/dist/cleave.min.js"></script>
<script src="https://nosir.github.io/cleave.js/dist/cleave-phone.i18n.js"></script>
<!-- Hero Section with Title -->
<?php require_once('header.php') ?>
<h1>Account Registration</h1>

<!--
    Registration form for volunteers to create an account
    Form sections:
        - Personal Information
        - Personal Contact Information
        - Emergency Contact Information
        - Availability
        - Languages
        - Skills and Experience
        - Additional Information
        - Login Credentials
        - Consent Notice
    Each section will have a brief description and instructions for filling out the form
    Form fields are reaffirmed on the client side using JavaScript and on the server side using PHP
    Upon submission, the form will be processed by VolunteerRegister.php, which will validate the input and create a new Person object in the database if the input is valid
-->
<main>
  <div class="main-content-box">
    <form class="signup-form" method="post">
        <?php if (!empty($error_messages)): ?>
            <div class="error-toast">Please correct the errors below before submitting.</div>
        <?php endif; ?>
	<div class="text-center spacing-bottom">

        <!-- Title -->
        <h2 class="mb-8">Registration Form</h2>
        <div class="info-box">
            <p class="sub-text">We thank you sincerely for your interest in volunteering as a part of our foundation.</p>
        </div>
	</div>

    <!-- Directions Section -->
    <fieldset class="section-box mb-4">
        <h3 class="mt-2">Directions</h3>
        <p class="mb-2">To create your account, please follow the instructions below:</p>

        <div class="blue-div"></div>

        <p class="mb-2">First, if you have not yet done so, we ask that you familiarize yourself with our foundation <a href="https://gwynethsgift.org">here</a>.</p>
        <p class="mb-2">Then, please fill out each of the following sections of the form carefully and accurately.</p>
        <p class="mb-2">Lastly, once you have consented to the conditions, click the "Submit" button at the bottom of the form to create your account.</p>
        <p>An asterisk ( <em>*</em> ) indicates a required field.</p>
    </fieldset>
        
    <!-- Personal Information Section -->
    <fieldset class="section-box mb-4">
        <h3 class="mt-2">Personal Information</h3>
        <p class="mb-2">The following information will help us identify you within our system.</p>
    
        <div class="blue-div"></div>

        <label for="first_name"><em>* </em>First Name</label>
        <input type="text" id="first_name" name="first_name" required placeholder="Enter your first name">

        <label for="last_name"><em>* </em>Last Name</label>
        <input type="text" id="last_name" name="last_name" required placeholder="Enter your last name">

        <div class="median-div"></div>
            
        <label for="gender"><em>* </em>Gender</label>
        <select id="gender" name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Nonbinary | Other</option>
            <option value="Unlisted" selected>Prefer not to say</option>
        </select>

        <label for="birthday"><em>* </em>Date of Birth</label>
        <input type="date" id="birthday" name="birthday" required placeholder="Choose your birthday" max="<?php echo date('Y-m-d'); ?>">
        <?php field_error('birthday'); ?>

        <!--
        Deprecated Code for Over 21 Question - No longer relevant for volunteer registration form, but may be useful for future event registration forms

        <label for="over21"><em>* </em>Are you 21 or older?</label>
        <div class="radio-group">
            <div class="radio-element">
                <input type="radio" id="yes" name="age" value="true" required>
                <label for="yes">Yes</label>
            </div>
            <div class="radio-element">
                <input type="radio" id="no" name="age" value="false">
                <label for="no">No</label>
            </div>
        </div>
        -->
        <div class="median-div"></div>
        
        <label for="street_address"><em>* </em>Street Address</label>
        <input type="text" id="street_address" name="street_address" required placeholder="Enter your street address">

        <label for="city"><em>* </em>City</label>
        <input type="text" id="city" name="city" required placeholder="Enter your city">

        <label for="state"><em>* </em>State</label>
        
        <select id="state" name="state" required>
            <option value="AL">Alabama</option>
            <option value="AK">Alaska</option>
            <option value="AZ">Arizona</option>
            <option value="AR">Arkansas</option>
            <option value="CA">California</option>
            <option value="CO">Colorado</option>
            <option value="CT">Connecticut</option>
            <option value="DE">Delaware</option>
            <option value="DC">District Of Columbia</option>
            <option value="FL">Florida</option>
            <option value="GA">Georgia</option>
            <option value="HI">Hawaii</option>
            <option value="ID">Idaho</option>
            <option value="IL">Illinois</option>
            <option value="IN">Indiana</option>
            <option value="IA">Iowa</option>
            <option value="KS">Kansas</option>
            <option value="KY">Kentucky</option>
            <option value="LA">Louisiana</option>
            <option value="ME">Maine</option>
            <option value="MD">Maryland</option>
            <option value="MA">Massachusetts</option>
            <option value="MI">Michigan</option>
            <option value="MN">Minnesota</option>
            <option value="MS">Mississippi</option>
            <option value="MO">Missouri</option>
            <option value="MT">Montana</option>
            <option value="NE">Nebraska</option>
            <option value="NV">Nevada</option>
            <option value="NH">New Hampshire</option>
            <option value="NJ">New Jersey</option>
            <option value="NM">New Mexico</option>
            <option value="NY">New York</option>
            <option value="NC">North Carolina</option>
            <option value="ND">North Dakota</option>
            <option value="OH">Ohio</option>
            <option value="OK">Oklahoma</option>
            <option value="OR">Oregon</option>
            <option value="PA">Pennsylvania</option>
            <option value="RI">Rhode Island</option>
            <option value="SC">South Carolina</option>
            <option value="SD">South Dakota</option>
            <option value="TN">Tennessee</option>
            <option value="TX">Texas</option>
            <option value="UT">Utah</option>
            <option value="VT">Vermont</option>
            <option value="VA" selected>Virginia</option>
            <option value="WA">Washington</option>
            <option value="WV">West Virginia</option>
            <option value="WI">Wisconsin</option>
            <option value="WY">Wyoming</option>
        </select>
        <?php field_error('state'); ?>

        <label for="zip_code"><em>* </em>Zip Code</label>
        <input type="text" id="zip_code" name="zip" pattern="[0-9]{5}" title="5-digit zip code" required placeholder="Enter your 5-digit zip code">
        <?php field_error('zip'); ?>

        <div class="median-div"></div>

        <label for="t_shirt_size"><em>* </em>T-shirt Size</label>
        <p class="mb-2">Please select your t-shirt size for event purposes.</p>
        <select id="t_shirt_size" name="t_shirt_size" required>
            <option value="" disabled selected>-- Select t-shirt size --</option>
            <option value="S">S</option>
            <option value="M">M</option>
            <option value="L">L</option>
            <option value="XL">XL</option>
            <option value="XXL">2XL</option>
        </select>

        <!--
        The following fields are deprecated for volunteer registration form, but may be useful for future event registration forms

        <div class="median-div"></div>
        <label for="affiliation"><em>* </em>Military Affiliation</label>
        <select id="affiliation" name="affiliation" required>
            <option value="" disabled selected></option>
            <option value="Active duty">Active duty</option>
            <option value="Family">Family member (spouse, child, or parent)</option>
            <option value="Reserve">Reservist</option>
            <option value="Veteran">Veteran</option>
            <option value="Civilian">Civilian</option>
        </select>
        

        <label for="branch"><em>* </em>Branch of Service</label>
        <select id="branch" name="branch" required>
            <option value="" disabled selected></option>
            <option value="Air Force">Air Force</option>
            <option value="Army">Army</option>
            <option value="Coast Guard">Coast Guard</option>
            <option value="Marine Corp">Marine Corp</option>
            <option value="Navy">Navy</option>
            <option value="Space Force">Space Force</option>
        </select>
        -->

    </fieldset>

    <!-- Personal Contact Information Section -->
    <fieldset class="section-box mb-4">
        <h3>Personal Contact Information</h3>
        <p class="mb-2">The following information will help us determine the best way to contact you regarding event coordination.</p>

        <div class="blue-div"></div>

        <label for="email"><em>* </em>E-mail</label>
        <input type="email" id="email" name="email" required placeholder="Enter your e-mail address">
        <?php field_error('email'); ?>

        <div class="median-div"></div>

        <label for="phone1"><em>* </em>Phone Number</label>
        <input type="tel" id="phone1" name="phone1" pattern="(\D{0,1})\d{3}(\D{0,2})\d{3}(.{0,1})\d{4}" placeholder="Ex. (555) 555-5555" required>
        <?php field_error('phone1'); ?>

        <label for="phone1type"><em>* </em>Phone Type</label>
        <div class="radio-group">
        <div class="radio-element">
            <input type="radio" id="phone-type-cellphone" name="phone_type" value="cellphone" required><label for="phone-type-cellphone"> Cell</label>
        </div>
        <div class="radio-element">
            <input type="radio" id="phone-type-home" name="phone_type" value="home" required><label for="phone-type-home"> Home</label>
        </div>
        <div class="radio-element">
            <input type="radio" id="phone-type-work" name="phone_type" value="work" required><label for="phone-type-work">Work</label>
        </div>
        </div>
        <?php field_error('phone_type'); ?>

    </fieldset>

    <!-- Notification Preferences -->
    <fieldset>
        <h3>Notification Preferences</h3>
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

        <label><input type="checkbox" id="email_prefs" name="email_prefs" value="true"> I consent.</label>
    </fieldset>


    <!-- Emergency Contact Information Section -->
    <fieldset class="section-box mb-4">
        <h3>Emergency Contact Information</h3>
        <p class="mb-2">Please provide us with someone's contact information on your behalf in case of an emergency.</p>
        <div class="blue-div"></div>

        <label for="emergency_contact_first_name" required><em>* </em>First Name</label>
        <input type="text" id="emergency_contact_first_name" name="emergency_contact_first_name" required placeholder="Enter emergency contact first name">

        <label for="emergency_contact_last_name" required><em>* </em>Last Name</label>
        <input type="text" id="emergency_contact_last_name" name="emergency_contact_last_name" required placeholder="Enter emergency contact last name">

        <label for="emergency_contact_relation"><em>* </em>Relationship to You</label>
        <input type="text" id="emergency_contact_relation" name="emergency_contact_relation" required placeholder="Ex. Spouse, Mother, Father, Sister, Brother, Friend">

        <label for="emergency_contact_phone"><em>* </em>Phone Number</label>
        <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" 
            pattern="(\D{0,1})\d{3}(\D{0,2})\d{3}(.{0,1})\d{4}" 
            required placeholder="Ex. (555) 555-5555">
        <?php field_error('emergency_contact_phone'); ?>

        <label for="emergency_contact_phone_type"><em>* </em>Phone Type</label>
        <div class="radio-group">
        <div class="radio-element">
            <input type="radio" id="emergency-phone-type-cellphone" name="emergency_contact_phone_type" value="cellphone" required><label for="emergency-phone-type-cellphone"> Cell</label>
        </div>
        <div class="radio-element">
            <input type="radio" id="emergency-phone-type-home" name="emergency_contact_phone_type" value="home" required><label for="emergency-phone-type-home"> Home</label>
        </div>
        <div class="radio-element">
            <input type="radio" id="emergency-phone-type-work" name="emergency_contact_phone_type" value="work" required><label for="emergency-phone-type-work"> Work</label>
        </div>
        </div>
        <?php field_error('emergency_contact_phone_type'); ?>
    </fieldset>

    <!-- Availability Section -->
    <fieldset class="section-box mb-4">
        <h3>Availability</h3>
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

        // Reset availability checkboxes and time selectors on page load
        window.addEventListener('pageshow', function(event) {
            var days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            days.forEach(function(day) {
                var checkbox = document.getElementById(day);
                if (checkbox) {
                    checkbox.checked = false;
                    var times = document.getElementById(day + '_times');
                    if (times) times.style.display = 'none';
                    var start = document.querySelector('[name=' + day + '_start]');
                    var end = document.querySelector('[name=' + day + '_end]');
                    if (start) { start.disabled = true; start.value = ''; }
                    if (end) { end.disabled = true; end.value = ''; }
                }
            });
        });
        </script>

        <?php

        // Generate time options for the availability selectors
        function timeOptions() {
            $time_selection = '<option value="" selected>-- Select time --</option>';
            for ($h = 0; $h < 24; $h++) {
                if ($h == 0) { $value = '12am'; $label = '12 AM'; }
                elseif ($h < 12) { $value = $h . 'am'; $label = $h . ' AM'; }
                elseif ($h == 12) { $value = '12pm'; $label = '12 PM'; }
                else { $value = ($h - 12) . 'pm'; $label = ($h - 12) . ' PM'; }
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
                <input type='checkbox' id='$d' name='day_availability[]' value='$day' onchange='toggleDay(\"$d\")'>
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
    <fieldset class="section-box mb-4">
        <h3>Languages</h3>
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
        <div id="language_hidden_inputs"></div>
        <p class="mb-2"><small>Hold Ctrl (Windows | Linux) or Cmd (Mac) to select multiple.</small></p>

        <?php field_error('language_competency'); ?>
        <div id="competency_container"></div>

        <script>
        
        // Listen for changes to the language multi-select and dynamically show competency selectors for each selected language
        document.getElementById('language_select').addEventListener('change', function() {
            var selected = Array.from(this.selectedOptions);
            var container = document.getElementById('competency_container');
            var hiddenContainer = document.getElementById('language_hidden_inputs');

            container.innerHTML = '';
            hiddenContainer.innerHTML = '';

            selected.forEach(function(option) {
                var hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'selected_languages[]';
                hidden.value = option.value;
                hiddenContainer.appendChild(hidden);

                var div = document.createElement('div');
                div.innerHTML = `
                    <label><em>* </em>${option.dataset.label} Speaking Competency:</label>
                    <p class="mb-2">Please indicate your speaking competency level in ${option.dataset.label}.</p>
                    <select name="speaking_competency_${option.value}" required>
                        <option value="">-- Select competency --</option>
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                        <option value="fluent">Native/Fluent</option>
                    </select>

                    <label><em>* </em>${option.dataset.label} Listening Competency:</label>
                    <p class="mb-2">Please indicate your listening competency level in ${option.dataset.label}.</p>
                    <select name="listening_competency_${option.value}" required>
                        <option value="">-- Select competency --</option>
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                        <option value="fluent">Native/Fluent</option>
                    </select>

                    <label><em>* </em>${option.dataset.label} Reading Competency:</label>
                    <p class="mb-2">Please indicate your reading competency level in ${option.dataset.label}.</p>
                    <select name="reading_competency_${option.value}" required>
                        <option value="">-- Select competency --</option>
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                        <option value="fluent">Native/Fluent</option>
                    </select>

                    <label><em>* </em>${option.dataset.label} Writing Competency:</label>
                    <p class="mb-2">Please indicate your writing competency level in ${option.dataset.label}.</p>
                    <select name="writing_competency_${option.value}" required>
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
        document.getElementById('language_select').dispatchEvent(new Event('change'));
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
    <fieldset class="section-box mb-4">
        <h3>Skills and Experience</h3>
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
    <fieldset class="section-box mb-4">
        <h3>Additional Information</h3>
        <p class="mb-2">The following information will help us determine the best volunteer opportunities for you and ensure that we are providing you with the best experience possible.</p>
        
        <div class="blue-div"></div>
        
        <label for="computer_access"><em>* </em>Computer Access</label>
        <p class="mb-2">Do you have regular access to a computer and the internet?</p>
        <div class="radio-group">
            <div class="radio-element">
                <input type="radio" id="computer_access_yes" name="computer_access" value="yes" required><label for="computer_access_yes"> Yes</label>
            </div>
            <div class="radio-element">
                <input type="radio" id="computer_access_no" name="computer_access" value="no" required><label for="computer_access_no"> No</label>
            </div>
        </div>

        <div class="median-div"></div>

        <label for="camera_access"><em>* </em>Camera Access</label>
        <p class="mb-2">Do you have access to a camera for taking photos? Cell phone cameras are acceptable.</p>
        <div class="radio-group">
            <div class="radio-element">
                <input type="radio" id="camera_access_yes" name="camera_access" value="yes" required><label for="camera_access_yes"> Yes</label>
            </div>
            <div class="radio-element">
                <input type="radio" id="camera_access_no" name="camera_access" value="no" required><label for="camera_access_no"> No</label>
            </div>
        </div>

        <div class="median-div"></div>

        <label for="transportation_access"><em>* </em>Transportation Access</label>
        <p class="mb-2">Do you have reliable transportation to get to volunteer sites?</p>
        <div class="radio-group">
            <div class="radio-element">
                <input type="radio" id="transportation_access_yes" name="transportation_access" value="yes" required><label for="transportation_access_yes"> Yes</label>
            </div>
            <div class="radio-element">
                <input type="radio" id="transportation_access_no" name="transportation_access" value="no" required><label for="transportation_access_no"> No</label>
            </div>
        </div>

    </fieldset>


    <!-- The following section is deprecated for volunteer registration form, but may be useful for future event registration forms where court-ordered community service volunteers may be relevant. -->
    <!-- <fieldset class="section-box mb-4">
        <h3 class="mb-2">Other Required Information</h3>
    <div class="blue-div"></div>

        <label><em>* </em>Are you volunteering for court-ordered community service?</label>
        <div class="radio-group">
        <div class="radio-element">
            <input type="radio" id="yes" name="is_community_service_volunteer" value="yes" required>
            <label for="yes">Yes</label>
        </div>

        <div class="radio-element">
            <input type="radio" id="no" name="is_community_service_volunteer" value="no">
            <label for="no">No</label>
        </div>
        </div>
        
        <label>Are there any specific skills you have that you believe could be useful for volunteering at the FredSPCA</label>
        <input type="text" id="skills" name="skills" placeholder="">

        <label>Any interests/hobbies?</label>
        <input type="text" id="interests" name="interests" placeholder="">

    </fieldset> -->

    <!-- The following section is deprecated for volunteer registration form, but may be useful for future event registration forms where training requirements may be relevant.
    <script>
        
            // Event listeners for changes in volunteer/participant selection and the complete statuses
        //document.querySelectorAll('input[name="is_community_service_volunteer"]').forEach(radio => {
            //  radio.addEventListener('change', toggleTrainingSection);
        //});
        
        // Initial check on page load
        
    </script>
    -->

    <script>
    // Initialize Cleave.js for primary phone number
    new Cleave('#phone1', {
        blocks: [3, 3, 4],
        delimiter: '-',
        numericOnly: true,
    });

    // Initialize Cleave.js for emergency contact phone number
    new Cleave('#emergency_contact_phone', {
        blocks: [3, 3, 4],
        delimiter: '-',
        numericOnly: true,
    });
    </script>


    <!-- Login Credentials Section. This section has been left untouched. -->
    <fieldset class="section-box mb-4">
        <h3>Login Credentials</h3>
        <p class="mb-2">Provide the following information to log in to the system.</p>
        <p class="mb-2">We recommend that you save your login information somewhere secure.</p>
    <div class="blue-div"></div>

        <label for="username"><em>* </em>Username</label>
        <input type="text" id="username" name="username" required placeholder="Enter a username">

        <label for="password"><em>* </em>Password</label>
        <p>Your password must be at least 8 characters long, contain at least one number, one uppercase letter, and one lowercase letter.</p>
        <input type="password" id="password" name="password" placeholder="Enter a strong password" required>
        <?php field_error('password'); ?>
        <p id="password-error" class="error hidden">Password does not meet requirements.</p>

        <label for="password-reenter"><em>* </em>Re-enter Password</label>
        <input type="password" id="password-reenter" name="password-reenter" placeholder="Re-enter password" required>
        <p id="password-match-error" class="error hidden">Passwords do not match.</p>
        

    <!-- This is presumably deprecated? I did not touch this -->
            <!-- Required by backend -->
    <!--<input type="hidden" name="is_new_volunteer" value="1">
    <input type="hidden" name="total_hours_volunteered" value="0"> -->

    </fieldset>
    
    <!-- Consent Notice Section -->
    <fieldset class="section-box mb-4">
        <h3>Consent Notice</h3>
        <p class="mb-2">Please review the following before creating your account.</p>
        <div class="blue-div"></div>
        <label><em>* </em>About Us Affirmation</label>
        <p>I have read the <a href="https://gwynethsgift.org/about-us/">About Us</a> page, and I confirm that I will abide by the mission and values of the organization as a volunteer.</p>
        <div class="radio-group">
            <div class="radio-element">
                <input type="radio" id="agree-about" name="about_consent" value="yes" required>
                <label for="agree-about">I agree.</label>
            </div>
            <div class="radio-element">
                <input type="radio" id="disagree-about" name="about_consent" value="no">
                <label for="disagree-about">I do not agree.</label>
            </div>
        </div>

        <!--
        This is deprecated as there is no privacy policy for our project in place at the moment. This may change. 
        Otherwise, this section may be useful for future event registration forms where a privacy policy may be relevant.

        <label><em>* </em> Privacy Policy</label>
        <p>I confirm that I have read the <a href="https://whiskeyvalor.org/policies/privacy-policy">Privacy Policy</a> and consent to the Whiskey Valor Foundation collecting and storing my information for the purposes outlined therein.</p>
        <div class="radio-group">
            <div class="radio-element">
                <input type="radio" id="agree" name="privacy_consent" value="yes" required>
                <label for="agree">I agree.</label>
            </div>
            <div class="radio-element">
                <input type="radio" id="disagree" name="privacy_consent" value="no">
                <label for="disagree">I do not agree.</label>
            </div>
        </div>
        -->
    </fieldset>
    <p class="text-center notice"></p>
    <input type="submit" name="registration-form" value="Submit" style="width: 50%; margin: auto;">
    </form>
   </div> 
</main>
