<?php
    session_cache_expire(30);
    session_start();

    ini_set("display_errors", 1);
    error_reporting(E_ALL);

    $loggedIn = false;
    $accessLevel = 0;
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        $accessLevel = $_SESSION['access_level'];
    }
    if (!$loggedIn) {
        header('Location: login.php');
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Gwyneth's Gift | Help Center</title>
    <link href="css/base.css" rel="stylesheet">
    <link href="css/help.css" rel="stylesheet">
    <?php require_once('header.php'); ?>
</head>
<body>

<h1>Help Center</h1>

<main>
    <div class="main-content-box" style="max-width: 900px; margin: 0 auto; padding: 2rem;">

        <!-- Search Bar -->
        <div class="help-search-wrapper">
            <svg class="search-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zM9.5 14C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
            </svg>
            <input type="text" id="help-search" placeholder="Search for help topics..." autocomplete="off">
        </div>

        <!-- Tab Cards (side by side) -->
        <div class="help-tabs">
            <div class="help-tab active" data-tab="faq">
                <div class="help-tab-title">FAQ</div>
            </div>
            <div class="help-tab" data-tab="getting-started">
                <div class="help-tab-title">Getting Started</div>
            </div>
            <div class="help-tab" data-tab="contact">
                <div class="help-tab-title">Contact Support</div>
            </div>
        </div>

        <!-- No Results Message -->
        <div class="help-no-results" id="help-no-results">
            No results found. Try a different search term.
        </div>

        <!-- ==================== FAQ Section ==================== -->
        <div class="help-section active" id="section-faq">
            <h3>Frequently Asked Questions</h3>

            <div class="faq-item" data-search="sign up event volunteer register">
                <button class="faq-question">
                    How do I sign up for an event?
                    <span class="faq-arrow">&#x25BC;</span>
                </button>
                <div class="faq-answer">
                    Navigate to the <strong>Events</strong> section from the top menu and click <strong>Browse Events</strong>. 
                    Find an event you're interested in and click on it to view the details. If spots are available, 
                    you'll see a <strong>Sign Up</strong> button to register for the event.
                </div>
            </div>

            <div class="faq-item" data-search="cancel event sign up withdraw">
                <button class="faq-question">
                    How do I cancel my event sign-up?
                    <span class="faq-arrow">&#x25BC;</span>
                </button>
                <div class="faq-answer">
                    Go to <strong>My Upcoming Events</strong> from the Events menu. Find the event you want to cancel 
                    and click on it. You'll see an option to withdraw your sign-up. Please try to cancel as early as 
                    possible so other volunteers can take the spot.
                </div>
            </div>

            <div class="faq-item" data-search="change password security login">
                <button class="faq-question">
                    How do I change my password?
                    <span class="faq-arrow">&#x25BC;</span>
                </button>
                <div class="faq-answer">
                    Click on your <strong>profile picture</strong> in the top-right corner of the navigation bar and select 
                    <strong>Change Password</strong>. You'll need to enter your current password and then your new password twice. 
                    Passwords must be at least 8 characters with at least one uppercase letter, one lowercase letter, and one number.
                </div>
            </div>

            <div class="faq-item" data-search="edit profile update information personal details">
                <button class="faq-question">
                    How do I update my personal information?
                    <span class="faq-arrow">&#x25BC;</span>
                </button>
                <div class="faq-answer">
                    Click on your <strong>profile picture</strong> in the top-right corner and select <strong>Edit Profile</strong>. 
                    From there you can update your name, address, phone number, emergency contact, availability, 
                    and other personal details.
                </div>
            </div>

            <div class="faq-item" data-search="calendar view events dates schedule">
                <button class="faq-question">
                    How do I view the events calendar?
                    <span class="faq-arrow">&#x25BC;</span>
                </button>
                <div class="faq-answer">
                    Click the <strong>calendar icon</strong> in the top navigation bar to go to the calendar view. 
                    You can switch between monthly, weekly, and daily views. Click on any event on the calendar 
                    to see its details and sign up.
                </div>
            </div>

            <div class="faq-item" data-search="hours volunteer time tracking check in out">
                <button class="faq-question">
                    How are my volunteer hours tracked?
                    <span class="faq-arrow">&#x25BC;</span>
                </button>
                <div class="faq-answer">
                    Your volunteer hours are tracked when you check in and check out of events. An event manager 
                    or admin will handle the check-in/check-out process at each event. You can view your total 
                    hours on your <strong>profile page</strong>.
                </div>
            </div>

            <div class="faq-item" data-search="notification message inbox alert">
                <button class="faq-question">
                    Where can I find my notifications?
                    <span class="faq-arrow">&#x25BC;</span>
                </button>
                <div class="faq-answer">
                    Check the <strong>bell icon</strong> or <strong>Inbox</strong> link in the navigation bar to view your 
                    notifications and messages. You'll receive notifications about event updates, sign-up confirmations, 
                    and system announcements.
                </div>
            </div>

            <div class="faq-item" data-search="availability schedule day time when free">
                <button class="faq-question">
                    How do I set my availability?
                    <span class="faq-arrow">&#x25BC;</span>
                </button>
                <div class="faq-answer">
                    Go to <strong>Edit Profile</strong> from the profile menu. Scroll down to the <strong>Availability</strong> 
                    section where you can select which days of the week you're available and set start and end times 
                    for each day.
                </div>
            </div>
        </div>

        <!-- ==================== Getting Started Section ==================== -->
        <div class="help-section" id="section-getting-started">
            <h3>Getting Started</h3>

            <div class="gs-step" data-search="create account register sign up new volunteer">
                <div class="gs-step-number">1</div>
                <div class="gs-step-content">
                    <h4>Create Your Account</h4>
                    <p>New volunteers can register by clicking <strong>Create Account</strong> on the login page. 
                    Fill out your personal information, set a username and password, and submit the form. 
                    Returning volunteers can use the <strong>Returning Volunteer</strong> link to reactivate their account.</p>
                </div>
            </div>

            <div class="gs-step" data-search="profile complete setup information">
                <div class="gs-step-number">2</div>
                <div class="gs-step-content">
                    <h4>Complete Your Profile</h4>
                    <p>After registering, make sure your profile is up to date. Add your availability, 
                    emergency contact information, and any special skills or language abilities you have. 
                    This helps event managers match you with the right opportunities.</p>
                </div>
            </div>

            <div class="gs-step" data-search="browse find events look search opportunities">
                <div class="gs-step-number">3</div>
                <div class="gs-step-content">
                    <h4>Browse Events</h4>
                    <p>Head over to the <strong>Browse Events</strong> page from the Events menu. You'll see all 
                    upcoming volunteer opportunities. Use the search bar and filters to find events that match 
                    your interests and availability.</p>
                </div>
            </div>

            <div class="gs-step" data-search="sign up register event join">
                <div class="gs-step-number">4</div>
                <div class="gs-step-content">
                    <h4>Sign Up for Events</h4>
                    <p>Click on any event to see its details — including date, time, location, and available spots. 
                    If the event has openings, click the <strong>Sign Up</strong> button to reserve your spot. 
                    You'll receive a confirmation notification.</p>
                </div>
            </div>

            <div class="gs-step" data-search="check in attend arrive event day of">
                <div class="gs-step-number">5</div>
                <div class="gs-step-content">
                    <h4>Attend &amp; Check In</h4>
                    <p>On the day of the event, arrive at the specified location and time. An event manager 
                    will check you in. When the event is over, you'll be checked out and your volunteer hours 
                    will be recorded automatically.</p>
                </div>
            </div>
        </div>

        <!-- ==================== Contact Support Section ==================== -->
        <div class="help-section" id="section-contact">
            <h3>Contact Support</h3>

            <div class="contact-cards">
                <div class="contact-card" data-search="email send message contact help">
                    <h4>Email Us</h4>
                    <p>Have a question or need assistance? Send us an email and we'll get back to you as soon as possible.</p>
                    <a href="https://gwynethsgift.org/contact-us/">Send Email</a>
                </div>

                <div class="contact-card" data-search="website main organization info about">
                    <h4>Visit Our Website</h4>
                    <p>Learn more about Gwyneth's Gift Foundation, our mission, and how you can get involved.</p>
                    <a href="https://gwynethsgift.org/" target="_blank">Main Website</a>
                </div>

                <div class="contact-card" data-search="suggestion feedback idea improve">
                    <h4>Submit a Suggestion</h4>
                    <p>Have an idea to improve the volunteer experience? We'd love to hear from you.</p>
                    <a href="createSuggestion.php">Make a Suggestion</a>
                </div>
            </div>
        </div>

    </div>

    <div class="text-center" style="margin-top: 1.5rem;">
        <a href="index.php" class="help-return-button">Return to Dashboard</a>
    </div>
</main>

<?php require_once('partials/footer.php'); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // ---- Tab Switching ----
    var tabs = document.querySelectorAll('.help-tab');
    var sections = document.querySelectorAll('.help-section');

    tabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            var target = this.getAttribute('data-tab');

            tabs.forEach(function(t) { t.classList.remove('active'); });
            this.classList.add('active');

            sections.forEach(function(s) { s.classList.remove('active'); });
            var targetSection = document.getElementById('section-' + target);
            if (targetSection) targetSection.classList.add('active');

            // Re-apply search filter when switching tabs
            applySearch();
        }.bind(tab));
    });

    // ---- FAQ Accordion ----
    document.querySelectorAll('.faq-question').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var item = this.parentElement;
            item.classList.toggle('open');
        });
    });

    // ---- Search Filtering ----
    var searchInput = document.getElementById('help-search');
    var noResults = document.getElementById('help-no-results');

    searchInput.addEventListener('input', function() {
        applySearch();
    });

    function applySearch() {
        var query = searchInput.value.toLowerCase().trim();
        var activeSection = document.querySelector('.help-section.active');
        if (!activeSection) return;

        var items = activeSection.querySelectorAll('.faq-item, .gs-step, .contact-card');
        var visibleCount = 0;

        items.forEach(function(item) {
            if (!query) {
                item.style.display = '';
                visibleCount++;
                return;
            }

            var text = item.textContent.toLowerCase();
            var searchData = (item.getAttribute('data-search') || '').toLowerCase();
            var match = text.indexOf(query) !== -1 || searchData.indexOf(query) !== -1;

            item.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });

        noResults.style.display = (query && visibleCount === 0) ? 'block' : 'none';
    }
});
</script>

</body>
</html>
