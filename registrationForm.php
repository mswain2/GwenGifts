<?php

// Error messaging
function field_error($key) {
    global $error_messages;
    if (!empty($error_messages[$key])) {
        echo '<p class="error">' . htmlspecialchars($error_messages[$key]) . '</p>';
    }
}
$error_messages = $error_messages ?? [];

// Hydration and persistance
function old($key, $default = '') {
    global $args;
    return htmlspecialchars($args[$key] ?? $default);
}
$args = $args ?? [];
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
        <input type="hidden" name="form_submitted" value="1">
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
        <input type="text" id="first_name" name="first_name" required placeholder="Enter your first name" 
            value="<?php echo old('first_name'); ?>">

        <label for="last_name"><em>* </em>Last Name</label>
        <input type="text" id="last_name" name="last_name" required placeholder="Enter your last name"
            value="<?php echo old('last_name'); ?>">

        <div class="median-div"></div>
            
        <label for="gender"><em>* </em>Gender</label>
        <select id="gender" name="gender" required>
            <option value="Male" <?php echo old('gender') === 'Male' ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo old('gender') === 'Female' ? 'selected' : ''; ?>>Female</option>
            <option value="Other" <?php echo old('gender') === 'Other' ? 'selected' : ''; ?>>Nonbinary | Other</option>
            <option value="Unlisted" <?php echo (old('gender') === 'Unlisted' || old('gender') === '') ? 'selected' : ''; ?>>Prefer not to say</option>
        </select>

        <label for="t_shirt_size"><em>* </em>T-shirt Size</label>
        <select id="t_shirt_size" name="t_shirt_size" required>
            <option value="" disabled <?php echo old('t_shirt_size') === '' ? 'selected' : ''; ?>>-- Select t-shirt size --</option>
            <option value="S" <?php echo old('t_shirt_size') === 'S' ? 'selected' : ''; ?>>S</option>
            <option value="M" <?php echo old('t_shirt_size') === 'M' ? 'selected' : ''; ?>>M</option>
            <option value="L" <?php echo old('t_shirt_size') === 'L' ? 'selected' : ''; ?>>L</option>
            <option value="XL" <?php echo old('t_shirt_size') === 'XL' ? 'selected' : ''; ?>>XL</option>
            <option value="XXL" <?php echo old('t_shirt_size') === 'XXL' ? 'selected' : ''; ?>>2XL</option>
        </select>

        <label for="birthday"><em>* </em>Date of Birth</label>
        <input type="date" id="birthday" name="birthday" required 
            max="<?php echo date('Y-m-d'); ?>" value="<?php echo old('birthday'); ?>">
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
        <input type="text" id="street_address" name="street_address" required placeholder="Enter your street address"
            value="<?php echo old('street_address'); ?>">

        <label for="city"><em>* </em>City</label>
        <input type="text" id="city" name="city" required placeholder="Enter your city"
            value="<?php echo old('city'); ?>">

        <label for="state"><em>* </em>State</label>
        
        <?php
        $states = [
            'AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California',
            'CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','DC'=>'District Of Columbia',
            'FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois',
            'IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana',
            'ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota',
            'MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada',
            'NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York',
            'NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon',
            'PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota',
            'TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia',
            'WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming'
        ];
        $selected_state = old('state') ?: 'VA';
        ?>
        <select id="state" name="state" required>
            <?php foreach ($states as $abbr => $name): ?>
                <option value="<?= $abbr ?>" <?= $selected_state === $abbr ? 'selected' : '' ?>><?= $name ?></option>
            <?php endforeach; ?>
        </select>
        <?php field_error('state'); ?>

        <label for="zip_code"><em>* </em>Zip Code</label>
        <input type="text" id="zip_code" name="zip" pattern="[0-9]{5}" title="5-digit zip code" required placeholder="Enter your 5-digit zip code"
            value="<?php echo old('zip'); ?>">
        <?php field_error('zip'); ?>

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
        <input type="email" id="email" name="email" required placeholder="Enter your e-mail address"
            value="<?php echo old('email'); ?>">
        <?php field_error('email'); ?>

        <div class="median-div"></div>

        <label for="phone1"><em>* </em>Phone Number</label>
        <input type="tel" id="phone1" name="phone1" pattern="(\D{0,1})\d{3}(\D{0,2})\d{3}(.{0,1})\d{4}" placeholder="Ex. (555) 555-5555" required
            value="<?php echo old('phone1'); ?>">
        <?php field_error('phone1'); ?>

        <label for="phone1type"><em>* </em>Phone Type</label>
        <div class="radio-group">
        <div class="radio-element">
            <input type="radio" id="phone-type-cellphone" name="phone_type" value="cellphone" 
                <?php echo old('phone_type') === 'cellphone' ? 'checked' : ''; ?> required>
            <label for="phone-type-cellphone"> Cell</label>
        </div>
        <div class="radio-element">
            <input type="radio" id="phone-type-home" name="phone_type" value="home" 
                <?php echo old('phone_type') === 'home' ? 'checked' : ''; ?> required>
            <label for="phone-type-home"> Home</label>
        </div>
        <div class="radio-element">
            <input type="radio" id="phone-type-work" name="phone_type" value="work" 
                <?php echo old('phone_type') === 'work' ? 'checked' : ''; ?> required>
            <label for="phone-type-work">Work</label>
        </div>
        </div>
        <?php field_error('phone_type'); ?>

    </fieldset>

    <!-- Notification Preferences -->
    <fieldset class="section-box mb-4">
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

        <label><input type="checkbox" id="email_prefs" name="email_prefs" value="true" 
            <?php echo isset($args['email_prefs']) ? 'checked' : ''; ?>> I consent.</label>
    </fieldset>


    <!-- Emergency Contact Information Section -->
    <fieldset class="section-box mb-4">
        <h3>Emergency Contact Information</h3>
        <p class="mb-2">Please provide us with someone's contact information on your behalf in case of an emergency.</p>
        <div class="blue-div"></div>

        <label for="emergency_contact_first_name" required><em>* </em>First Name</label>
        <input type="text" id="emergency_contact_first_name" name="emergency_contact_first_name" required placeholder="Enter emergency contact first name"
            value="<?php echo old('emergency_contact_first_name'); ?>">

        <label for="emergency_contact_last_name" required><em>* </em>Last Name</label>
        <input type="text" id="emergency_contact_last_name" name="emergency_contact_last_name" required placeholder="Enter emergency contact last name"
            value="<?php echo old('emergency_contact_last_name'); ?>">

        <label for="emergency_contact_relation"><em>* </em>Relationship to You</label>
        <input type="text" id="emergency_contact_relation" name="emergency_contact_relation" required placeholder="Ex. Spouse, Mother, Father, Sister, Brother, Friend"
            value="<?php echo old('emergency_contact_relation'); ?>">

        <label for="emergency_contact_phone"><em>* </em>Phone Number</label>
        <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" 
            pattern="(\D{0,1})\d{3}(\D{0,2})\d{3}(.{0,1})\d{4}" 
            required placeholder="Ex. (555) 555-5555"
            value="<?php echo old('emergency_contact_phone'); ?>">
        <?php field_error('emergency_contact_phone'); ?>

        <label for="emergency_contact_phone_type"><em>* </em>Phone Type</label>
        <div class="radio-group">
        <div class="radio-element">
            <input type="radio" id="emergency-phone-type-cellphone" name="emergency_contact_phone_type" value="cellphone"
                <?php echo old('emergency_contact_phone_type') === 'cellphone' ? 'checked' : ''; ?> required>
            <label for="emergency-phone-type-cellphone"> Cell</label>
        </div>
        <div class="radio-element">
            <input type="radio" id="emergency-phone-type-home" name="emergency_contact_phone_type" value="home"
                <?php echo old('emergency_contact_phone_type') === 'home' ? 'checked' : ''; ?> required>
            <label for="emergency-phone-type-home"> Home</label>
        </div>
        <div class="radio-element">
            <input type="radio" id="emergency-phone-type-work" name="emergency_contact_phone_type" value="work"
                <?php echo old('emergency_contact_phone_type') === 'work' ? 'checked' : ''; ?> required>
            <label for="emergency-phone-type-work"> Work</label>
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
            // Only reset if coming from back/forward cache, not a validation re-render
            if (event.persisted) {
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
            }
        });
        </script>

        <?php

        $day_availability = $day_availability ?? [];
        $args = $args ?? [];

        // Generate time options for the availability selectors
        function timeOptions($selected_value = '') {
            $time_selection = '<option value="" ' . ($selected_value === '' ? 'selected' : '') . '>-- Select time --</option>';
            for ($h = 0; $h < 24; $h++) {
                if ($h == 0) { $value = '12am'; $label = '12 AM'; }
                elseif ($h < 12) { $value = $h . 'am'; $label = $h . ' AM'; }
                elseif ($h == 12) { $value = '12pm'; $label = '12 PM'; }
                else { $value = ($h - 12) . 'pm'; $label = ($h - 12) . ' PM'; }
                $selected = ($selected_value === $value) ? 'selected' : '';
                $time_selection .= "<option value=\"$value\" $selected>$label</option>";
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
        function dayAvailability($day, $day_availability, $args) {
            $d = strtolower($day);
            $is_checked = in_array($day, $day_availability);
            $checked_attr = $is_checked ? 'checked' : '';
            $display = $is_checked ? 'block' : 'none';
            $disabled = $is_checked ? '' : 'disabled';
            $start_val = $args[$d . '_start'] ?? '';
            $end_val   = $args[$d . '_end'] ?? '';

            echo "
            <div>
                <input type='checkbox' id='$d' name='day_availability[]' value='$day' 
                    onchange='toggleDay(\"$d\")' $checked_attr>
                <label for='$d'> $day</label>
                <div id='{$d}_times' style='display:$display'>
                    <p class='mb-2'>If you are available on $day, please indicate your availability below.</p>
                    <p class='mb-2'>Start Availability Time (From):</p>
                    <select name='{$d}_start' $disabled>" . timeOptions($start_val) . "</select>
                    <p class='mb-2'>End Availability Time (To):</p>
                    <select name='{$d}_end' $disabled>" . timeOptions($end_val) . "</select>
                </div>
            </div>";
        }

        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        
        // Loop through days of the week to generate availability checkboxes and time selectors
        foreach ($days as $day) {
            dayAvailability($day, $day_availability, $args);
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

        // Previously selected languages from a failed submission, default to English
        // Use raw submitted languages if available, otherwise default to English
        $selected_languages = isset($args['selected_languages']) && is_array($args['selected_languages']) 
            ? array_map(fn($l) => preg_replace('/[^a-z_]/', '', $l), $args['selected_languages'])
            : ['english'];
        ?>

        <!-- 
            Generate a multi-select dropdown for the 20 most spoken languages in Virginia with PHP. 
            English and Spanish are anchored to the top, rest is sorted alphabetically in the list
        -->
        <label>Languages spoken:</label>
        <p class="mb-2">Select all languages you are proficient in.</p>

        <select id="language_select" multiple size="6">
            <option value="" disabled>-- Select languages --</option>
            <?php foreach ($languages as $lang): ?>
                <?php $d = strtolower(str_replace(' ', '_', $lang)); ?>
                <option value="<?= $d ?>" data-label="<?= $lang ?>"
                    <?= in_array($d, $selected_languages) ? 'selected' : '' ?>>
                    <?= $lang ?>
                </option>
            <?php endforeach; ?>
        </select>
        <div id="language_hidden_inputs">
            <?php foreach ($selected_languages as $lang): ?>
                <input type="hidden" name="selected_languages[]" value="<?= htmlspecialchars($lang) ?>">
            <?php endforeach; ?>
        </div>
        <p class="mb-2"><small>Hold Ctrl (Windows | Linux) or Cmd (Mac) to select multiple.</small></p>

        <?php field_error('language_competency'); ?>

        <?php
        // Helper to render a competency select, preserving previously selected value
        function competencySelect($name, $label, $lang_label, $selected_val = '') {
            $options = ['beginner' => 'Beginner', 'intermediate' => 'Intermediate', 'advanced' => 'Advanced', 'fluent' => 'Native/Fluent'];
            echo "<label><em>* </em>$lang_label $label Competency:</label>";
            echo "<p class='mb-2'>Please indicate your $label competency level in $lang_label.</p>";
            echo "<select name='$name' required>";
            echo "<option value=''>-- Select competency --</option>";
            foreach ($options as $val => $display) {
                $sel = ($selected_val === $val) ? 'selected' : '';
                echo "<option value='$val' $sel>$display</option>";
            }
            echo "</select>";
        }
        ?>

        <!-- Server-side rendered competency fields for previously selected languages -->
        <div id="competency_container">
            <?php foreach ($selected_languages as $lang):
                $lang = preg_replace('/[^a-z_]/', '', $lang);
                $lang_label = ucwords(str_replace('_', ' ', $lang));
                $speaking  = $args['speaking_competency_'  . $lang] ?? '';
                $listening = $args['listening_competency_' . $lang] ?? '';
                $reading   = $args['reading_competency_'   . $lang] ?? '';
                $writing   = $args['writing_competency_'   . $lang] ?? '';
            ?>
                <div class="language-competency-block" data-lang="<?= $lang ?>" data-label="<?= $lang_label ?>">
                    <?php competencySelect("speaking_competency_$lang",  'Speaking',  $lang_label, $speaking);  ?>
                    <?php competencySelect("listening_competency_$lang", 'Listening', $lang_label, $listening); ?>
                    <?php competencySelect("reading_competency_$lang",   'Reading',   $lang_label, $reading);   ?>
                    <?php competencySelect("writing_competency_$lang",   'Writing',   $lang_label, $writing);   ?>
                    <div class="median-div"></div>
                </div>
            <?php endforeach; ?>
        </div>

        <script>
        
        var serverRendered = document.querySelectorAll('.language-competency-block').length > 0;

        // Listen for changes to the language multi-select and dynamically show competency selectors for each selected language
        document.getElementById('language_select').addEventListener('change', function() {
            // On first change after server-render, flip the flag but don't rebuild
            if (serverRendered) {
                serverRendered = false;
                return;
            }

            var selected = Array.from(this.selectedOptions);
            var container = document.getElementById('competency_container');
            var hiddenContainer = document.getElementById('language_hidden_inputs');

            // Keep track of which blocks currently exist
            var existingBlocks = {};
            container.querySelectorAll('.language-competency-block').forEach(function(block) {
                existingBlocks[block.dataset.lang] = block;
            });

            // Update hidden inputs
            hiddenContainer.innerHTML = '';
            selected.forEach(function(option) {
                var hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'selected_languages[]';
                hidden.value = option.value;
                hiddenContainer.appendChild(hidden);
            });

            // Add blocks for newly selected languages, remove deselected ones
            var selectedValues = selected.map(function(o) { return o.value; });

            // Remove deselected
            Object.keys(existingBlocks).forEach(function(lang) {
                if (!selectedValues.includes(lang)) {
                    existingBlocks[lang].remove();
                }
            });

            // Add newly selected
            selected.forEach(function(option) {
                if (!existingBlocks[option.value]) {
                    var div = document.createElement('div');
                    div.className = 'language-competency-block';
                    div.dataset.lang = option.value;
                    div.dataset.label = option.dataset.label;
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
                }
            });
        });

       // Only dispatch on fresh load, OUTSIDE the listener
        if (!serverRendered) {
            document.getElementById('language_select').dispatchEvent(new Event('change'));
        }
        </script>

        <!-- I manually added an unlisted lang section. This might be a placeholder as I feel there's a better implementation for this, but it will work for now. -->
        <label>Unlisted Language</label>
        <p class="mb-2">Listed above are the 20 most commonly spoken languages in Virginia.</p>
        <p class="mb-2">If there is a language you are proficient in that is not listed above, please indicate it here along with your competency level.</p>
        <input type="text" id="other_language" name="other_language" placeholder=""
            value="<?php echo old('other_language'); ?>">

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
        <?php field_error('other_language_competency'); ?>

    </fieldset>

    <!-- Skills and Experience Section -->
    <fieldset class="section-box mb-4">
        <h3>Skills and Experience</h3>
        <p class="mb-2">Please provide any additional information about your skills and experience that you believe may be relevant for volunteering with our organization.</p>
    
        <div class="blue-div"></div>

        <label for="skills">Skills</label>
        <p class="mb-2">Please list any relevant skills you have that may be useful for our services.</p>
        <textarea id="skills" name="skills" placeholder="Ex. Event planning..."><?php echo old('skills'); ?></textarea>

        <label for="experience">Experience</label>
        <p class="mb-2">Please describe any relevant experience you have volunteering or working.</p>
        <textarea id="experience" name="experience" placeholder="Eg. other volunteer work..."><?php echo old('experience'); ?></textarea>    </fieldset>

    <!-- Additional Information Section -->
    <fieldset class="section-box mb-4">
        <h3>Additional Information</h3>
        <p class="mb-2">The following information will help us determine the best volunteer opportunities for you and ensure that we are providing you with the best experience possible.</p>
        
        <div class="blue-div"></div>
        
        <label for="computer_access"><em>* </em>Computer Access</label>
        <p class="mb-2">Do you have regular access to a computer and the internet?</p>
        <div class="radio-group">
            <div class="radio-element">
                <input type="radio" id="computer_access_yes" name="computer_access" value="yes"
                    <?php echo old('computer_access') === 'yes' ? 'checked' : ''; ?> required>
                <label for="computer_access_yes"> Yes</label>
            </div>
            <div class="radio-element">
                <input type="radio" id="computer_access_no" name="computer_access" value="no"
                    <?php echo old('computer_access') === 'no' ? 'checked' : ''; ?> required>
                <label for="computer_access_no"> No</label>
            </div>
        </div>

        <div class="median-div"></div>

        <label for="camera_access"><em>* </em>Camera Access</label>
        <p class="mb-2">Do you have access to a camera for taking photos? Cell phone cameras are acceptable.</p>
        <div class="radio-group">
            <div class="radio-element">
                <input type="radio" id="camera_access_yes" name="camera_access" value="yes"
                    <?php echo old('camera_access') === 'yes' ? 'checked' : ''; ?> required>
                <label for="camera_access_yes"> Yes</label>
            </div>
            <div class="radio-element">
                <input type="radio" id="camera_access_no" name="camera_access" value="no"
                    <?php echo old('camera_access') === 'no' ? 'checked' : ''; ?> required>
                <label for="camera_access_no"> No</label>
            </div>
        </div>

        <div class="median-div"></div>

        <label for="transportation_access"><em>* </em>Transportation Access</label>
        <p class="mb-2">Do you have reliable transportation to get to volunteer sites?</p>
        <div class="radio-group">
            <div class="radio-element">
                <input type="radio" id="transportation_access_yes" name="transportation_access" value="yes"
                    <?php echo old('transportation_access') === 'yes' ? 'checked' : ''; ?> required>
                <label for="transportation_access_yes"> Yes</label>
            </div>
            <div class="radio-element">
                <input type="radio" id="transportation_access_no" name="transportation_access" value="no"
                    <?php echo old('transportation_access') === 'no' ? 'checked' : ''; ?> required>
                <label for="transportation_access_no"> No</label>
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
        <input type="text" id="username" name="username" required placeholder="Enter a username"
            value="<?php echo old('username'); ?>">

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
                <input type="radio" id="agree-about" name="about_consent" value="yes"
                    <?php echo old('about_consent') === 'yes' ? 'checked' : ''; ?> required>
                <label for="agree-about">I agree.</label>
            </div>
            <div class="radio-element">
                <input type="radio" id="disagree-about" name="about_consent" value="no"
                    <?php echo old('about_consent') === 'no' ? 'checked' : ''; ?> required>
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
