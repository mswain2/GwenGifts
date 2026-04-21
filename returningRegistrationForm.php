<?php

// Error messaging (same as registrationForm.php)
function field_error($key) {
    global $error_messages;
    if (!empty($error_messages[$key])) {
        echo '<p class="error">' . htmlspecialchars($error_messages[$key]) . '</p>';
    }
}
$error_messages = $error_messages ?? [];

// Hydration: on POST error use submitted value, on first load use DB value
function prefill($key, $dbValue) {
    global $args;
    if (!empty($args)) {
        return htmlspecialchars($args[$key] ?? $dbValue ?? '', ENT_QUOTES, 'UTF-8');
    }
    return htmlspecialchars($dbValue ?? '', ENT_QUOTES, 'UTF-8');
}

// For fields with no DB fallback (username, password)
function old($key, $default = '') {
    global $args;
    return htmlspecialchars($args[$key] ?? $default, ENT_QUOTES, 'UTF-8');
}
?>

<!-- imports -->
<script src="https://nosir.github.io/cleave.js/dist/cleave.min.js"></script>
<script src="https://nosir.github.io/cleave.js/dist/cleave-phone.i18n.js"></script>
<style>
.progress-container {
    position: relative;
    width: 100%;
    margin-bottom: 1rem;
}
progress {
    width: 100%;
    height: 25px;
    appearance: none;
}
progress::-webkit-progress-bar {
    background-color: #eee;
    border-radius: 5px;
}
progress::-webkit-progress-value {
    background-color: var(--secondary-accent-color);
    border-radius: 5px;
}
progress::-moz-progress-bar {
    background-color: var(--secondary-accent-color);
    border-radius: 5px;
}
.progress-label {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: var(--main-color);
    pointer-events: none;
}
.form-pagination-nav {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    margin: 1rem 0;
}
.form-pagination-nav button {
    padding: 0.5rem 1.35rem;
    cursor: pointer;
}
.field-error {
    border-color: var(--error-color, #c0392b) !important;
    outline-color: var(--error-color, #c0392b);
}
</style>
<!-- Hero Section with Title -->
<?php require_once('header.php') ?>
<h1>Welcome Back — Complete Your Account</h1>

<main>
  <div class="main-content-box">
    <form class="signup-form" method="post">
        <input type="hidden" name="form_submitted" value="1">
        <?php if (!empty($error_messages)): ?>
            <div class="error-toast">Please correct the errors below before submitting.</div>
        <?php endif; ?>
	<div class="text-center spacing-bottom">

        <!-- Title -->
        <h2 class="mb-8">Review Your Information</h2>
        <?php $total_pages = 10; ?>
        <div class="progress-container">
            <progress id="myProgress" value="0" max="100"></progress>
            <div class="progress-label" id="progressLabel">0%</div>
        </div>
        <div class="info-box">
            <p class="sub-text">Welcome back! Please review your information below, make any corrections, and set up your new login credentials.</p>
        </div>
	</div>

    <!-- Directions Section -->
    <fieldset id="page1" class="section-box mb-4">
        <h3 class="mt-2">Directions</h3>
        <p class="mb-2">To complete your account setup, please follow the instructions below:</p>

        <div class="blue-div"></div>

        <p class="mb-2">Please review each section and make any corrections to your information.</p>
        <p class="mb-2">Then, choose a username and password in the "Login Credentials" section at the bottom.</p>
        <p class="mb-2">Lastly, click the "Submit" button at the bottom of the form to complete your account.</p>
        <p>An asterisk ( <em>*</em> ) indicates a required field.</p>
    </fieldset>

    <!-- Personal Information Section -->
    <fieldset id="page2" class="section-box mb-4" style="display:none;">
        <h3 class="mt-2">Personal Information</h3>
        <p class="mb-2">The following information will help us identify you within our system.</p>

        <div class="blue-div"></div>

        <label for="first_name"><em>* </em>First Name</label>
        <input type="text" id="first_name" name="first_name" required placeholder="Enter your first name"
            value="<?php echo prefill('first_name', $existingUser->get_first_name()); ?>">
        <?php field_error('first_name'); ?>

        <label for="last_name"><em>* </em>Last Name</label>
        <input type="text" id="last_name" name="last_name" required placeholder="Enter your last name"
            value="<?php echo prefill('last_name', $existingUser->get_last_name()); ?>">
        <?php field_error('last_name'); ?>

        <div class="median-div"></div>

        <?php $genderVal = prefill('gender', $existingUser->get_gender()); ?>
        <label for="gender"><em>* </em>Gender</label>
        <select id="gender" name="gender" required>
            <option value="Male" <?php echo $genderVal === 'Male' ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo $genderVal === 'Female' ? 'selected' : ''; ?>>Female</option>
            <option value="Other" <?php echo $genderVal === 'Other' ? 'selected' : ''; ?>>Nonbinary | Other</option>
            <option value="Unlisted" <?php echo ($genderVal === 'Unlisted' || $genderVal === '') ? 'selected' : ''; ?>>Prefer not to say</option>
        </select>
        <?php field_error('gender'); ?>

        <?php $shirtVal = prefill('t_shirt_size', $existingUser->get_t_shirt_size()); ?>
        <label for="t_shirt_size"><em>* </em>T-shirt Size</label>
        <select id="t_shirt_size" name="t_shirt_size" required>
            <option value="" disabled <?php echo $shirtVal === '' ? 'selected' : ''; ?>>-- Select t-shirt size --</option>
            <option value="S" <?php echo $shirtVal === 'S' ? 'selected' : ''; ?>>S</option>
            <option value="M" <?php echo $shirtVal === 'M' ? 'selected' : ''; ?>>M</option>
            <option value="L" <?php echo $shirtVal === 'L' ? 'selected' : ''; ?>>L</option>
            <option value="XL" <?php echo $shirtVal === 'XL' ? 'selected' : ''; ?>>XL</option>
            <option value="XXL" <?php echo $shirtVal === 'XXL' ? 'selected' : ''; ?>>2XL</option>
        </select>
        <?php field_error('t_shirt_size'); ?>

        <label for="birthday"><em>* </em>Date of Birth</label>
        <input type="date" id="birthday" name="birthday" required
            max="<?php echo date('Y-m-d'); ?>" value="<?php echo prefill('birthday', $existingUser->get_birthday()); ?>">
        <?php field_error('birthday'); ?>

        <div class="median-div"></div>

        <label for="street_address"><em>* </em>Street Address</label>
        <input type="text" id="street_address" name="street_address" required placeholder="Enter your street address"
            value="<?php echo prefill('street_address', $existingUser->get_street_address()); ?>">
        <?php field_error('street_address'); ?>

        <label for="city"><em>* </em>City</label>
        <input type="text" id="city" name="city" required placeholder="Enter your city"
            value="<?php echo prefill('city', $existingUser->get_city()); ?>">
        <?php field_error('city'); ?>

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
        $selected_state = prefill('state', $existingUser->get_state()) ?: 'VA';
        ?>
        <select id="state" name="state" required>
            <?php foreach ($states as $abbr => $name): ?>
                <option value="<?= $abbr ?>" <?= $selected_state === $abbr ? 'selected' : '' ?>><?= $name ?></option>
            <?php endforeach; ?>
        </select>
        <?php field_error('state'); ?>

        <label for="zip_code"><em>* </em>Zip Code</label>
        <input type="text" id="zip_code" name="zip" pattern="[0-9]{5}" title="5-digit zip code" required placeholder="Enter your 5-digit zip code"
            value="<?php echo prefill('zip', $existingUser->get_zip_code()); ?>">
        <?php field_error('zip'); ?>

    </fieldset>

    <!-- Personal Contact Information Section -->
    <fieldset id="page3" class="section-box mb-4" style="display:none;">
        <h3>Personal Contact Information</h3>
        <p class="mb-2">The following information will help us determine the best way to contact you regarding event coordination.</p>

        <div class="blue-div"></div>

        <label for="email"><em>* </em>E-mail</label>
        <input type="email" id="email" name="email" required placeholder="Enter your e-mail address"
            value="<?php echo prefill('email', $existingUser->get_email()); ?>">
        <?php field_error('email'); ?>

        <div class="median-div"></div>

        <label for="phone1"><em>* </em>Phone Number</label>
        <input type="tel" id="phone1" name="phone1" pattern="(\D{0,1})\d{3}(\D{0,2})\d{3}(.{0,1})\d{4}" placeholder="Ex. 555-555-5555" required
            value="<?php echo prefill('phone1', $existingUser->get_phone1()); ?>">
        <?php field_error('phone1'); ?>

        <?php $phoneTypeVal = prefill('phone_type', $existingUser->get_phone1type()); ?>
        <label for="phone1type"><em>* </em>Phone Type</label>
        <div class="radio-group">
        <div class="radio-element">
            <input type="radio" id="phone-type-cellphone" name="phone_type" value="cellphone"
                <?php echo $phoneTypeVal === 'cellphone' ? 'checked' : ''; ?> required>
            <label for="phone-type-cellphone"> Cell</label>
        </div>
        <div class="radio-element">
            <input type="radio" id="phone-type-home" name="phone_type" value="home"
                <?php echo $phoneTypeVal === 'home' ? 'checked' : ''; ?> required>
            <label for="phone-type-home"> Home</label>
        </div>
        <div class="radio-element">
            <input type="radio" id="phone-type-work" name="phone_type" value="work"
                <?php echo $phoneTypeVal === 'work' ? 'checked' : ''; ?> required>
            <label for="phone-type-work">Work</label>
        </div>
        </div>
        <?php field_error('phone_type'); ?>

    </fieldset>

    <!-- Notification Preferences -->
    <fieldset id="page4" class="section-box mb-4" style="display:none;">
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
    <fieldset id="page5" class="section-box mb-4" style="display:none;">
        <h3>Emergency Contact Information</h3>
        <p class="mb-2">Please provide us with someone's contact information on your behalf in case of an emergency.</p>
        <div class="blue-div"></div>

        <label for="emergency_contact_first_name" required><em>* </em>First Name</label>
        <input type="text" id="emergency_contact_first_name" name="emergency_contact_first_name" required placeholder="Enter emergency contact first name"
            value="<?php echo prefill('emergency_contact_first_name', $existingUser->get_emergency_contact_first_name()); ?>">

        <label for="emergency_contact_last_name" required><em>* </em>Last Name</label>
        <input type="text" id="emergency_contact_last_name" name="emergency_contact_last_name" required placeholder="Enter emergency contact last name"
            value="<?php echo prefill('emergency_contact_last_name', $existingUser->get_emergency_contact_last_name()); ?>">

        <label for="emergency_contact_relation"><em>* </em>Relationship to You</label>
        <input type="text" id="emergency_contact_relation" name="emergency_contact_relation" required placeholder="Ex. Spouse, Mother, Father, Sister, Brother, Friend"
            value="<?php echo prefill('emergency_contact_relation', $existingUser->get_emergency_contact_relation()); ?>">

        <label for="emergency_contact_phone"><em>* </em>Phone Number</label>
        <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone"
            pattern="(\D{0,1})\d{3}(\D{0,2})\d{3}(.{0,1})\d{4}"
            required placeholder="Ex. 555-555-5555"
            value="<?php echo prefill('emergency_contact_phone', $existingUser->get_emergency_contact_phone()); ?>">
        <?php field_error('emergency_contact_phone'); ?>

        <?php $ecPhoneTypeVal = prefill('emergency_contact_phone_type', $existingUser->get_emergency_contact_phone_type()); ?>
        <label for="emergency_contact_phone_type"><em>* </em>Phone Type</label>
        <div class="radio-group">
        <div class="radio-element">
            <input type="radio" id="emergency-phone-type-cellphone" name="emergency_contact_phone_type" value="cellphone"
                <?php echo $ecPhoneTypeVal === 'cellphone' ? 'checked' : ''; ?> required>
            <label for="emergency-phone-type-cellphone"> Cell</label>
        </div>
        <div class="radio-element">
            <input type="radio" id="emergency-phone-type-home" name="emergency_contact_phone_type" value="home"
                <?php echo $ecPhoneTypeVal === 'home' ? 'checked' : ''; ?> required>
            <label for="emergency-phone-type-home"> Home</label>
        </div>
        <div class="radio-element">
            <input type="radio" id="emergency-phone-type-work" name="emergency_contact_phone_type" value="work"
                <?php echo $ecPhoneTypeVal === 'work' ? 'checked' : ''; ?> required>
            <label for="emergency-phone-type-work"> Work</label>
        </div>
        </div>
        <?php field_error('emergency_contact_phone_type'); ?>
    </fieldset>

    <!-- Availability Section -->
    <fieldset id="page6" class="section-box mb-4" style="display:none;">
        <h3>Availability</h3>
        <p class="mb-2">The following information will help us determine the best volunteer opportunities for you.</p>
        <p class="mb-2">Click the checkbox next to each day you are available.</p>
        <div class="blue-div"></div>

        <script>
        function toggleDay(day) {
            var times = document.getElementById(day + '_times');
            var start = document.querySelector('[name=' + day + '_start]');
            var end = document.querySelector('[name=' + day + '_end]');
            var checked = document.getElementById(day).checked;

            times.style.display = checked ? 'block' : 'none';
            start.disabled = !checked;
            end.disabled = !checked;
        }

        window.addEventListener('pageshow', function(event) {
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

        function dayAvailability($day, $day_availability, $args) {
            global $error_messages;
            $d = strtolower($day);
            $is_checked = in_array($day, $day_availability);
            $checked_attr = $is_checked ? 'checked' : '';
            $display = $is_checked ? 'block' : 'none';
            $disabled = $is_checked ? '' : 'disabled';
            $start_val = $args[$d . '_start'] ?? '';
            $end_val   = $args[$d . '_end'] ?? '';
            $error = !empty($error_messages[$d . '_time'])
                ? '<p class="error">' . htmlspecialchars($error_messages[$d . '_time']) . '</p>'
                : '';

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
                    $error
                </div>
            </div>";
        }

        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        foreach ($days as $day) {
            dayAvailability($day, $day_availability, $args);
        }
        ?>

    </fieldset>


    <!-- Languages Section -->
    <fieldset id="page7" class="section-box mb-4" style="display:none;">
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

        $selected_languages = isset($args['selected_languages']) && is_array($args['selected_languages'])
            ? array_map(function($l) { return preg_replace('/[^a-z_]/', '', $l); }, $args['selected_languages'])
            : ['english'];
        ?>

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

        document.getElementById('language_select').addEventListener('change', function() {
            if (serverRendered) {
                serverRendered = false;
                return;
            }

            var selected = Array.from(this.selectedOptions);
            var container = document.getElementById('competency_container');
            var hiddenContainer = document.getElementById('language_hidden_inputs');

            var existingBlocks = {};
            container.querySelectorAll('.language-competency-block').forEach(function(block) {
                existingBlocks[block.dataset.lang] = block;
            });

            hiddenContainer.innerHTML = '';
            selected.forEach(function(option) {
                var hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'selected_languages[]';
                hidden.value = option.value;
                hiddenContainer.appendChild(hidden);
            });

            var selectedValues = selected.map(function(o) { return o.value; });

            Object.keys(existingBlocks).forEach(function(lang) {
                if (!selectedValues.includes(lang)) {
                    existingBlocks[lang].remove();
                }
            });

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

        if (!serverRendered) {
            document.getElementById('language_select').dispatchEvent(new Event('change'));
        }
        </script>

        <label>Unlisted Language</label>
        <p class="mb-2">Listed above are the 20 most commonly spoken languages in Virginia.</p>
        <p class="mb-2">If there is a language you are proficient in that is not listed above, please indicate it here along with your competency level.</p>
        <input type="text" id="other_language" name="other_language" placeholder=""
            value="<?php echo old('other_language'); ?>">

        <label>Speaking Competency:</label>
        <p class="mb-2">Please indicate your speaking competency level in the language you have provided.</p>
        <select name="speaking_competency_other_language">
            <option value="">-- Select competency --</option>
            <option value="beginner" <?php echo old('speaking_competency_other_language') === 'beginner' ? 'selected' : ''; ?>>Beginner</option>
            <option value="intermediate" <?php echo old('speaking_competency_other_language') === 'intermediate' ? 'selected' : ''; ?>>Intermediate</option>
            <option value="advanced" <?php echo old('speaking_competency_other_language') === 'advanced' ? 'selected' : ''; ?>>Advanced</option>
            <option value="fluent" <?php echo old('speaking_competency_other_language') === 'fluent' ? 'selected' : ''; ?>>Native/Fluent</option>
        </select>

        <label>Listening Competency:</label>
        <select name="listening_competency_other_language">
            <option value="">-- Select competency --</option>
            <option value="beginner" <?php echo old('listening_competency_other_language') === 'beginner' ? 'selected' : ''; ?>>Beginner</option>
            <option value="intermediate" <?php echo old('listening_competency_other_language') === 'intermediate' ? 'selected' : ''; ?>>Intermediate</option>
            <option value="advanced" <?php echo old('listening_competency_other_language') === 'advanced' ? 'selected' : ''; ?>>Advanced</option>
            <option value="fluent" <?php echo old('listening_competency_other_language') === 'fluent' ? 'selected' : ''; ?>>Native/Fluent</option>
        </select>

        <label>Reading Competency:</label>
        <p class="mb-2">Please indicate your reading competency level in the language you have provided.</p>
        <select name="reading_competency_other_language">
            <option value="">-- Select competency --</option>
            <option value="beginner" <?php echo old('reading_competency_other_language') === 'beginner' ? 'selected' : ''; ?>>Beginner</option>
            <option value="intermediate" <?php echo old('reading_competency_other_language') === 'intermediate' ? 'selected' : ''; ?>>Intermediate</option>
            <option value="advanced" <?php echo old('reading_competency_other_language') === 'advanced' ? 'selected' : ''; ?>>Advanced</option>
            <option value="fluent" <?php echo old('reading_competency_other_language') === 'fluent' ? 'selected' : ''; ?>>Native/Fluent</option>
        </select>

        <label>Writing Competency:</label>
        <p class="mb-2">Please indicate your writing competency level in the language you have provided.</p>
        <select name="writing_competency_other_language">
            <option value="">-- Select competency --</option>
            <option value="beginner" <?php echo old('writing_competency_other_language') === 'beginner' ? 'selected' : ''; ?>>Beginner</option>
            <option value="intermediate" <?php echo old('writing_competency_other_language') === 'intermediate' ? 'selected' : ''; ?>>Intermediate</option>
            <option value="advanced" <?php echo old('writing_competency_other_language') === 'advanced' ? 'selected' : ''; ?>>Advanced</option>
            <option value="fluent" <?php echo old('writing_competency_other_language') === 'fluent' ? 'selected' : ''; ?>>Native/Fluent</option>
        </select>
        <?php field_error('other_language_competency'); ?>

    </fieldset>

    <!-- Skills and Experience Section -->
    <fieldset id="page8" class="section-box mb-4" style="display:none;">
        <h3>Skills and Experience</h3>
        <p class="mb-2">Please provide any additional information about your skills and experience that you believe may be relevant for volunteering with our organization.</p>

        <div class="blue-div"></div>

        <label for="skills">Skills</label>
        <p class="mb-2">Please list any relevant skills you have that may be useful for our services.</p>
        <textarea id="skills" name="skills" placeholder="Ex. Event planning..."><?php echo prefill('skills', $existingUser->get_skills()); ?></textarea>

        <label for="experience">Experience</label>
        <p class="mb-2">Please describe any relevant experience you have volunteering or working.</p>
        <textarea id="experience" name="experience" placeholder="Eg. other volunteer work..."><?php echo prefill('experience', $existingUser->get_experience()); ?></textarea>
    </fieldset>

    <!-- Additional Information Section -->
    <fieldset id="page9" class="section-box mb-4" style="display:none;">
        <h3>Additional Information</h3>
        <p class="mb-2">The following information will help us determine the best volunteer opportunities for you and ensure that we are providing you with the best experience possible.</p>

        <div class="blue-div"></div>

        <?php $compVal = prefill('computer_access', $existingUser->get_computer_access()); ?>
        <label for="computer_access"><em>* </em>Computer Access</label>
        <p class="mb-2">Do you have regular access to a computer and the internet?</p>
        <div class="radio-group">
            <div class="radio-element">
                <input type="radio" id="computer_access_yes" name="computer_access" value="yes"
                    <?php echo $compVal === 'yes' ? 'checked' : ''; ?> required>
                <label for="computer_access_yes"> Yes</label>
            </div>
            <div class="radio-element">
                <input type="radio" id="computer_access_no" name="computer_access" value="no"
                    <?php echo $compVal === 'no' ? 'checked' : ''; ?> required>
                <label for="computer_access_no"> No</label>
            </div>
        </div>
        <?php field_error('computer_access'); ?>

        <div class="median-div"></div>

        <?php $camVal = prefill('camera_access', $existingUser->get_camera_access()); ?>
        <label for="camera_access"><em>* </em>Camera Access</label>
        <p class="mb-2">Do you have access to a camera for taking photos? Cell phone cameras are acceptable.</p>
        <div class="radio-group">
            <div class="radio-element">
                <input type="radio" id="camera_access_yes" name="camera_access" value="yes"
                    <?php echo $camVal === 'yes' ? 'checked' : ''; ?> required>
                <label for="camera_access_yes"> Yes</label>
            </div>
            <div class="radio-element">
                <input type="radio" id="camera_access_no" name="camera_access" value="no"
                    <?php echo $camVal === 'no' ? 'checked' : ''; ?> required>
                <label for="camera_access_no"> No</label>
            </div>
        </div>
        <?php field_error('camera_access'); ?>

        <div class="median-div"></div>

        <?php $transVal = prefill('transportation_access', $existingUser->get_transportation_access()); ?>
        <label for="transportation_access"><em>* </em>Transportation Access</label>
        <p class="mb-2">Do you have reliable transportation to get to volunteer sites?</p>
        <div class="radio-group">
            <div class="radio-element">
                <input type="radio" id="transportation_access_yes" name="transportation_access" value="yes"
                    <?php echo $transVal === 'yes' ? 'checked' : ''; ?> required>
                <label for="transportation_access_yes"> Yes</label>
            </div>
            <div class="radio-element">
                <input type="radio" id="transportation_access_no" name="transportation_access" value="no"
                    <?php echo $transVal === 'no' ? 'checked' : ''; ?> required>
                <label for="transportation_access_no"> No</label>
            </div>
        </div>
        <?php field_error('transportation_access'); ?>

        <div class="median-div"></div>

        <?php $disVal = prefill('has_disability', $existingUser->get_has_disability()); ?>
        <label for="has_disability"><em>* </em>Disability</label>
        <p class="mb-2">Do you have one or more disabilities that may affect your volunteering?</p>
        <div class="radio-group">
            <div class="radio-element">
                <input type="radio" id="has_disability_yes" name="has_disability" value="yes"
                    <?php echo $disVal === 'yes' ? 'checked' : ''; ?> required>
                <label for="has_disability_yes"> Yes</label>
            </div>
            <div class="radio-element">
                <input type="radio" id="has_disability_no" name="has_disability" value="no"
                    <?php echo ($disVal === 'no' || $disVal === '') ? 'checked' : ''; ?> required>
                <label for="has_disability_no"> No</label>
            </div>
        </div>

        <div id="disability_spec_section" style="display:<?php echo $disVal === 'yes' ? 'block' : 'none'; ?>">
            <label for="disability_specifications">Disability Specifications</label>
            <p class="mb-2">Please briefly describe your disability or any accommodations you may need.</p>
            <textarea id="disability_specifications" name="disability_specifications"
                placeholder="Ex. Wheelchair user, hearing impaired, etc."><?php echo prefill('disability_specifications', $existingUser->get_disability_specifications()); ?></textarea>
        </div>

        <script>
        document.querySelectorAll('input[name="has_disability"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.getElementById('disability_spec_section').style.display =
                    this.value === 'yes' ? 'block' : 'none';
            });
        });
        </script>
        <?php field_error('has_disability'); ?>

    </fieldset>


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


    <!-- Login Credentials Section -->
    <fieldset id="page10" class="section-box mb-4" style="display:none;">
        <h3>Login Credentials</h3>
        <p class="mb-2">Choose a username and password to log in to the system.</p>
        <p class="mb-2">We recommend that you save your login information somewhere secure.</p>
    <div class="blue-div"></div>

        <label for="username"><em>* </em>Username</label>
        <input type="text" id="username" name="username" required placeholder="Choose a username"
            value="<?php echo old('username'); ?>">
        <?php field_error('username'); ?>

        <label for="password"><em>* </em>Password</label>
        <p>Your password must be at least 8 characters long, contain at least one number, one uppercase letter, and one lowercase letter.</p>
        <input type="password" id="password" name="password" placeholder="Enter a strong password" required>
        <?php field_error('password'); ?>
        <p id="password-error" class="error hidden">Password does not meet requirements.</p>

        <label for="password-reenter"><em>* </em>Re-enter Password</label>
        <input type="password" id="password-reenter" name="password-reenter" placeholder="Re-enter password" required>
        <p id="password-match-error" class="error hidden">Passwords do not match.</p>

    </fieldset>

    <!-- No Consent Notice section — returning volunteers already consented -->

    <div class="form-pagination-nav">
        <button type="button" id="prevBtn" onclick="changePage(-1)" style="display:none;">&#8592; Previous</button>
        <button type="button" id="nextBtn" onclick="handleNext()">Next &#8594;</button>
    </div>
    <p class="text-center notice"></p>
    <input type="submit" id="submitBtn" name="registration-form" value="Submit" style="width: 50%; margin: auto; display:none;">
    </form>
   </div>
   <script>
    var currentPage = 1;
    var totalPages = <?php echo $total_pages; ?>;

    // ─── Progress bar ─────────────────────────────────────────────────────────
    function updateProgress(pageNumber) {
        var progress = Math.round((pageNumber / totalPages) * 100);
        document.getElementById('myProgress').value = progress;
        document.getElementById('progressLabel').textContent = progress + '%';
    }

    // ─── Page visibility ──────────────────────────────────────────────────────
    function showPage(n) {
        for (var i = 1; i <= totalPages; i++) {
            var el = document.getElementById('page' + i);
            if (el) el.style.display = (i === n) ? '' : 'none';
        }
        document.getElementById('prevBtn').style.display = (n === 1) ? 'none' : '';
        document.getElementById('nextBtn').style.display = (n === totalPages) ? 'none' : '';
        document.getElementById('submitBtn').style.display = (n === totalPages) ? '' : 'none';
        updateProgress(n);
        window.scrollTo(0, 0);
    }

    // ─── Navigation (validates before advancing) ──────────────────────────────
    function changePage(direction) {
        if (direction > 0 && !validateCurrentPage()) return;
        var next = currentPage + direction;
        if (next < 1 || next > totalPages) return;
        currentPage = next;
        showPage(currentPage);
    }

    /*
     * handleNext() – async wrapper for the Next button.
     * On page 3 (Personal Contact Information), after sync validation passes,
     * it checks email uniqueness against the DB, excluding the current user's
     * own account so they can keep their existing address.
     */
    async function handleNext() {
        if (!validateCurrentPage()) return;

        if (currentPage === 3) {
            var emailField = document.getElementById('email');
            if (emailField && emailField.value.trim()) {
                var btn = document.getElementById('nextBtn');
                var origText = btn.textContent;
                btn.disabled = true;
                btn.textContent = 'Checking…';
                try {
                    var body = new URLSearchParams({
                        action:     'check_email',
                        email:      emailField.value.trim().toLowerCase(),
                        exclude_id: '<?php echo htmlspecialchars($existingUser->get_id(), ENT_QUOTES); ?>'
                    });
                    var res  = await fetch('returningVolunteerForm.php', { method: 'POST', body: body });
                    var data = await res.json();
                    if (!data.available) {
                        showError(emailField,
                            'This email address is already registered to a different account. ' +
                            'Please use a different email address.');
                        var firstErr = document.getElementById('page3').querySelector('.client-error');
                        if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        btn.disabled = false;
                        btn.textContent = origText;
                        return;
                    }
                } catch (e) {
                    // Network error — allow proceeding rather than blocking the user.
                }
                btn.disabled = false;
                btn.textContent = origText;
            }
        }

        var next = currentPage + 1;
        if (next > totalPages) return;
        currentPage = next;
        showPage(currentPage);
    }

    // ─── Error display helpers ────────────────────────────────────────────────
    function showError(field, message) {
        field.classList.add('field-error');
        var err = document.createElement('p');
        err.className = 'error client-error';
        err.textContent = message;
        field.parentNode.insertBefore(err, field.nextSibling);
    }

    function clearErrors(fieldset) {
        fieldset.querySelectorAll('.client-error').forEach(function(el) { el.remove(); });
        fieldset.querySelectorAll('.field-error').forEach(function(el) { el.classList.remove('field-error'); });
    }

    // ─── Per-page validation ──────────────────────────────────────────────────
    function validateCurrentPage() {
        var fieldset = document.getElementById('page' + currentPage);
        if (!fieldset) return true;

        clearErrors(fieldset);
        var valid = true;

        // ── 1. Required text / email / tel / date / select / textarea ──────────
        var selector = [
            'input[required]:not([type=radio]):not([type=checkbox])',
            'select[required]',
            'textarea[required]'
        ].join(', ');

        fieldset.querySelectorAll(selector).forEach(function(field) {
            if (field.disabled) return;
            if (isHidden(field)) return;

            var value = field.value.trim();
            if (!value) {
                showError(field, 'This field is required.');
                valid = false;
                return;
            }
            if (field.pattern) {
                var re = new RegExp('^(?:' + field.pattern + ')$');
                if (!re.test(field.value)) {
                    showError(field, field.title || 'Please match the requested format.');
                    valid = false;
                    return;
                }
            }
            if (field.type === 'email' && !isValidEmail(value)) {
                showError(field, 'Please enter a valid email address.');
                valid = false;
            }
        });

        // ── 2. Radio groups ───────────────────────────────────────────────────
        var radioGroups = {};
        fieldset.querySelectorAll('input[type=radio][required]').forEach(function(radio) {
            if (!radioGroups[radio.name]) radioGroups[radio.name] = [];
            radioGroups[radio.name].push(radio);
        });
        Object.keys(radioGroups).forEach(function(name) {
            var radios = radioGroups[name];
            var anyChecked = radios.some(function(r) { return r.checked; });
            if (!anyChecked) {
                showError(radios[radios.length - 1], 'Please select an option.');
                valid = false;
            }
        });

        // ── 3. Availability time-range rules (page 6) ─────────────────────────
        if (currentPage === 6) {
            var days = ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'];
            days.forEach(function(day) {
                var checkbox = document.getElementById(day);
                if (!checkbox || !checkbox.checked) return;

                var startSel = document.querySelector('[name=' + day + '_start]');
                var endSel   = document.querySelector('[name=' + day + '_end]');
                if (!startSel || !endSel) return;

                var startVal = startSel.value;
                var endVal   = endSel.value;
                var dayLabel = day.charAt(0).toUpperCase() + day.slice(1);

                if (!startVal) {
                    showError(startSel, dayLabel + ': Please select a start time.');
                    valid = false;
                }
                if (!endVal) {
                    showError(endSel, dayLabel + ': Please select an end time.');
                    valid = false;
                }
                if (startVal && endVal && parseTimeValue(endVal) <= parseTimeValue(startVal)) {
                    showError(endSel, dayLabel + ': End time must be after start time.');
                    valid = false;
                }
            });
        }

        // ── 4. Password page special rules (page 10) ──────────────────────────
        if (currentPage === 10) {
            var pw   = document.getElementById('password');
            var pwRe = document.getElementById('password-reenter');
            if (pw && pw.value && !isValidPassword(pw.value)) {
                showError(pw, 'Password must be at least 8 characters and include a number, an uppercase letter, and a lowercase letter.');
                valid = false;
            } else if (pw && pwRe && pw.value && pwRe.value && pw.value !== pwRe.value) {
                showError(pwRe, 'Passwords do not match.');
                valid = false;
            }
        }

        if (!valid) {
            var firstError = fieldset.querySelector('.client-error');
            if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        return valid;
    }

    // ─── Helper: is the field or an ancestor hidden? ──────────────────────────
    function isHidden(field) {
        var el = field;
        while (el && el.tagName !== 'FIELDSET') {
            if (window.getComputedStyle(el).display === 'none') return true;
            el = el.parentElement;
        }
        return false;
    }

    // ─── Format validators ────────────────────────────────────────────────────
    function isValidEmail(val) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
    }

    function isValidPassword(val) {
        return val.length >= 8
            && /[0-9]/.test(val)
            && /[A-Z]/.test(val)
            && /[a-z]/.test(val);
    }

    function parseTimeValue(val) {
        var hour = parseInt(val, 10);
        var isPm = val.slice(-2) === 'pm';
        if (isPm && hour !== 12) return hour + 12;
        if (!isPm && hour === 12) return 0;
        return hour;
    }

    // ─── Live error clearing ──────────────────────────────────────────────────
    document.querySelector('form.signup-form').addEventListener('input', function(e) {
        var field = e.target;
        if (!field.classList.contains('field-error')) return;
        field.classList.remove('field-error');
        var next = field.nextSibling;
        while (next) {
            if (next.nodeType === 1 && next.classList.contains('client-error')) { next.remove(); break; }
            if (next.nodeType === 1) break;
            next = next.nextSibling;
        }
    });

    document.querySelector('form.signup-form').addEventListener('change', function(e) {
        var field = e.target;
        if (field.type !== 'radio' && field.type !== 'select-one') return;
        if (field.classList.contains('field-error')) {
            field.classList.remove('field-error');
            var next = field.nextSibling;
            while (next) {
                if (next.nodeType === 1 && next.classList.contains('client-error')) { next.remove(); break; }
                if (next.nodeType === 1) break;
                next = next.nextSibling;
            }
        }
        if (field.type === 'radio') {
            var fieldset = document.getElementById('page' + currentPage);
            if (!fieldset) return;
            fieldset.querySelectorAll('input[type=radio][name="' + field.name + '"]').forEach(function(r) {
                r.classList.remove('field-error');
            });
            var allInGroup = fieldset.querySelectorAll('input[type=radio][name="' + field.name + '"]');
            var last = allInGroup[allInGroup.length - 1];
            var sib = last.nextSibling;
            while (sib) {
                if (sib.nodeType === 1 && sib.classList.contains('client-error')) { sib.remove(); break; }
                if (sib.nodeType === 1) break;
                sib = sib.nextSibling;
            }
        }
    });

    // ─── Initialize ───────────────────────────────────────────────────────────
    showPage(1);
    </script>
</main>
