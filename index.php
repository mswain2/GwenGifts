<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_cache_expire(30);
    session_start();

    date_default_timezone_set("America/New_York");
    
    if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 1) {
        if (isset($_SESSION['change-password'])) {
            header('Location: changePassword.php');
        } else {
            header('Location: login.php');
        }
        die();
    }
        
    include_once('database/dbPersons.php');
    include_once('domain/Person.php');
    // Get date?
    if (isset($_SESSION['_id'])) {
        $person = retrieve_person($_SESSION['_id']);
    }
    $notRoot = $person->get_id() != 'vmsroot';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="./css/base.css" rel="stylesheet">
    <title>Gwyneth's Gift | Dashboard</title>

<!--BEGIN TEST, UPLOAD AND NOTIFICATIONS CHANGED-->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelector(".extra-info").style.maxHeight = "0px"; // Ensure proper initialization
        });
        function toggleInfo(event) {
            event.stopPropagation(); // Prevents triggering the main button click
            let info = event.target.nextElementSibling;
            let isVisible = info.style.maxHeight !== "0px";
            info.style.maxHeight = isVisible ? "0px" : "100px";
            event.target.innerText = isVisible ? "↓" : "↑";
        }
    </script>
<!--END TEST-->
</head>

<!-- ONLY SUPER ADMIN WILL SEE THIS -->
<?php if ($_SESSION['access_level'] >= 2): ?>
<body>
<?php require 'header.php';?>

    <!-- Dummy content to enable scrolling -->
    <div style="margin-top: 0px; padding: 30px 20px;">
        <h2><b>Welcome <?php echo $person->get_first_name() ?>!</b> Let's get started.</h2>
    </div>

            <?php if (isset($_GET['pcSuccess'])): ?>
                <div class="happy-toast">Password changed successfully!</div>
            <?php elseif (isset($_GET['deleteService'])): ?>
                <div class="happy-toast">Service successfully removed!</div>
            <?php elseif (isset($_GET['serviceAdded'])): ?>
                <div class="happy-toast">Service successfully added!</div>
            <?php elseif (isset($_GET['animalRemoved'])): ?>
                <div class="happy-toast">Animal successfully removed!</div>
            <?php elseif (isset($_GET['locationAdded'])): ?>
                <div class="happy-toast">Location successfully added!</div>
            <?php elseif (isset($_GET['deleteLocation'])): ?>
                <div class="happy-toast">Location successfully removed!</div>
            <?php elseif (isset($_GET['registerSuccess'])): ?>
                <div class="happy-toast">Volunteer registered successfully!</div>
            <?php endif ?>

    <div class="full-width-bar">
    <div class="content-box">
        <img src="images/cpr.jpg" style="filter:brightness(3) contrast(20%) blur(3px);">
        <div class="small-text" style="color: #3A3A3A;">Make a difference.</div>
        <div class="large-text">User Management</div>
<button class="circle-arrow-button" onclick="window.location.href='volunteerManagement.php'">
    <span class="button-text">Go</span>
    <div class="circle">&gt;</div>
</button>
<!--
        <div class="nav-buttons">
            <button class="nav-button" onclick="window.location.href='personSearch.php'">
                <span>Find</span>
                <span class="arrow"><img src="images/person-search.svg" style="width: 40px; border-radius:5px; border-bottom-right-radius: 20px;"></span>
            </button>
            <button class="nav-button" onclick="window.location.href='VolunteerRegister.php'">
                <span>Register</span>
                <span class="arrow"><img src="images/add-person.svg" style="width: 40px; border-radius:5px; border-bottom-right-radius: 20px;"></span>
            </button>
        </div>
-->
    </div>

    <div class="content-box">
        <img src="images/momprom.jpg" style="filter:brightness(1) contrast(25%) blur(4px);">
        <div class="small-text" style="color: #3A3A3A;">Let’s have some fun!</div>
        <div class="large-text">Event Management</div>
<button class="circle-arrow-button" onclick="window.location.href='eventManagement.php'">
    <span class="button-text"><?php 
                        require_once('database/dbEvents.php');
                        require_once('database/dbPersons.php');
                        require_once('database/dbApplications.php');
                        $pendingsignups = all_pending_names();
                        if (sizeof($pendingsignups) > 0) {
                            echo '<span class="colored-box">' . sizeof($pendingsignups) . '</span>';
                        }   
                    ?> Sign-Ups </span>
    <div class="circle">&gt;</div>
</button>
    </div>

    <div class="content-box">
        <img src="images/whiskeyBarrels.png" style="filter:brightness(3) contrast(25%) blur(4px);">
        <div class="small-text" style="color: #3A3A3A;">Get away from it all.</div>
        <div class="large-text">Retreat Applications</div>
<button class="circle-arrow-button" onclick="window.location.href='viewAllApplications.php'">
    <span class="button-text">Go</span>
    <div class="circle">&gt;</div>
</button>
    </div>

</div>

<div style="margin-top: 50px; padding: 0px 80px;">
    <h2><b>Admin Dashboard</b></h2>
</div>

<div class="full-width-bar-sub">

    <?php
        require_once('database/dbMessages.php');

        // Ensure variable is always defined
        $unreadMessageCount = 0;
        $inboxIcon = 'inbox.svg';
        if (isset($person)) {
            $unreadMessageCount = get_user_unread_count($person->get_id());
            if ($unreadMessageCount > 0) {
                $inboxIcon = 'inbox-unread.svg';
            }
        }
    ?>

    <!-- Calendar -->
    <div class="content-box-test" onclick="window.location.href='calendar.php'">
        <div class="icon-overlay">
            <img style="border-radius: 5px;" src="images/view-calendar.svg" alt="Calendar Icon">
        </div>
        
        <div class="large-text-sub">Calendar</div>
        <div class="graph-text">See upcoming events/trainings.</div>
        <button class="arrow-button">→</button>
    </div>

    <!-- Manage Documents -->
    <div class="content-box-test" onclick="window.location.href='view_encrypted_gallery.php'">
        <div class="icon-overlay">
            <img style="border-radius: 5px;" src="images/file-regular.svg" alt="Document Icon">
        </div>
       
        <div class="large-text-sub">View Pending IDs </div>
        <div class="graph-text">View pending and arbitrate user submitted IDs.</div>
        <button class="arrow-button">→</button>
    </div>

    <!-- System Notifications -->
    <div class="content-box-test" onclick="window.location.href='inbox.php'">
        <div class="icon-overlay">
            <img style="border-radius: 5px;" src="images/<?php echo $inboxIcon ?>" alt="Notification Icon">
        </div>
        
        <div class="large-text-sub">
            System Notifications<?php 
                if ($unreadMessageCount > 0) {
                    echo ' (' . $unreadMessageCount . ')';
                }
            ?>
        </div>
        <div class="graph-text">Stay up to date.</div>
        <button class="arrow-button">→</button>
    </div>

    <!-- Generate Report -->
    <div class="content-box-test" onclick="window.location.href='generateReport.php'">
        <div class="icon-overlay">
            <img style="border-radius: 5px;" src="images/create-report.svg" alt="Report Icon">
        </div>
        
        <div class="large-text-sub">Generate Report</div>
        <div class="graph-text">From this quarter or annual.</div>
        <button class="arrow-button">→</button>
    </div>

    <!-- Create Email -->
    <div class="content-box-test" onclick="window.location.href='createEmail.php'">
        <div class="icon-overlay">
            <img style="border-radius: 5px;" src="images/inbox.svg" alt="Email Icon">
        </div>
        
        <div class="large-text-sub">Create Email</div>
        <div class="graph-text">Send new messages to volunteers.</div>
        <button class="arrow-button">→</button>
    </div>

    <!-- View Drafts -->
    <div class="content-box-test" onclick="window.location.href='viewDrafts.php'">
        <div class="icon-overlay">
            <img style="border-radius: 5px;" src="images/search.svg" alt="Drafts Icon">
        </div>
        
        <div class="large-text-sub">View Drafts</div>
        <div class="graph-text">Check saved email drafts.</div>
        <button class="arrow-button">→</button>
    </div>

    <!-- Generate Email List -->
    <div class="content-box-test" onclick="window.location.href='generateEmailList.php'">
        <div class="icon-overlay">
            <img style="border-radius: 5px;" src="images/send.png" alt="Email List Icon">
        </div>
         
        <div class="large-text-sub">Generate Email List</div>
        <div class="graph-text">Volunteer Emails</div>
        <button class="arrow-button">→</button>
    </div>

    <!-- Discussions -->
    <div class="content-box-test" onclick="window.location.href='viewSuggestions.php'">
        <div class="icon-overlay">
            <img style="border-radius: 5px;" src="images/clipboard-regular.svg" alt="Discussions Icon">
        </div>
        
        <div class="large-text-sub">User Suggestions</div>
        <div class="graph-text">View user submitted suggestions.</div>
        <button class="arrow-button">→</button>
    </div>
    
    <!-- Board Documents -->
    <div class="content-box-test" onclick="window.location.href='boardDocuments.php'" style="background-color: #f8f8f8; border-radius: 12px; padding: 20px; color: black; border: 1px solid #e0e0e0;">
        <div class="icon-overlay">
            <img style="border-radius: 5px;" src="images/file-regular.svg" alt="Documents Icon">
        </div>
        
        <div class="large-text-sub" style="color:black;">Board Documents</div>
        <div class="graph-text" style="color:#3A3A3A;">Access and manage board resources.</div>
        <button class="arrow-button">→</button>
    </div>
</div>



    

<div style="width: 90%; /* Stops before page ends */
            height: 100%;
            outline: 1px #828282 solid;
            outline-offset: -0.5px;
            margin: 70px auto; /* Adds vertical space and centers */
            padding: 1px 0;"> <!-- Adds spacing inside the div -->
</div>


    <footer class="footer" style="margin-top: 100px;">
        <!-- Left Side: Logo & Socials -->
        <div class="footer-left">
            <img src="images/logo.png" alt="Logo" class="footer-logo">
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>

        <!-- Right Side: Page Links -->
        <div class="footer-right">
            <div class="footer-section">
                <div class="footer-topic">Connect</div>
                <a href="https://www.facebook.com/gwynethsgift">Facebook</a>
                <a href="https://www.instagram.com/gwynethsgift/">Instagram</a>
                <a href="https://gwynethsgift.org/">Main Website</a>
            </div>
            <div class="footer-section">
                <div class="footer-topic">Contact Us</div>
                <a href="https://gwynethsgift.org/contact-us/">Send Us An Email</a>
                <!-- <a href="tel:5408981500">540-898-1500 (ext 117)</a> -->
            </div>
        </div>
    </footer>

    <!-- Font Awesome for Icons -->
    <script src="https://kit.fontawesome.com/yourkit.js" crossorigin="anonymous"></script>


</body>
<?php endif ?>

<!-- ONLY VOLUNTEERS WILL SEE THIS -->
<?php if ($notRoot) : ?>
<body>
<?php require 'header.php';?>

  

  <!-- Icon Container -->
<div style="position: absolute; top: 110px; right: 30px; z-index: 999; display: flex; flex-direction: row; gap: 30px; align-items: center; text-align: center;">





</div>



    <!-- Dummy content to enable scrolling -->
    <div style="margin-top: 0px; padding: 30px 20px;">
        <h2><b>Welcome <?php echo $person->get_first_name() ?>!</b> Let's get started.</h2>
    </div>

    <div class="full-width-bar">
    <div class="content-box">
    <img src="images/VolM.png" />   
        <div class="small-text">Make a difference.</div>
        <div class="large-text">My Profile</div>
        <div class="nav-buttons">
            <button class="nav-button" onclick="window.location.href='viewProfile.php'">
                <span class="arrow"><img src="images/view-profile.svg" style="width: 40px; border-radius:5px; border-bottom-right-radius: 20px;"></span>
                <span class="text">View</span>
            </button>
            <button class="nav-button" onclick="window.location.href='editProfile.php'">
                <span class="arrow"><img src="images/manage-account.svg" style="width: 40px; border-radius:5px; border-bottom-right-radius: 20px;"></span>
                <span class="text">Edit</span>
            </button>
            
        </div>
    </div>

    <div class="content-box">
        <img src="images/EvM.png" />
        <div class="small-text">Let’s have some fun!</div>
        <div class="large-text">My Events</div>
        <div class="nav-buttons">
            <button class="nav-button" onclick="window.location.href='viewAllEvents.php'">
                <span class="arrow"><img src="images/new-event.svg" style="width: 40px; border-radius:5px; border-bottom-right-radius: 10px;"></span>
                <span class="text">Sign-Up</span>
            </button>
            <button class="nav-button" onclick="window.location.href='viewMyUpcomingEvents.php'">
                <span class="arrow"><img src="images/list-solid.svg" style="width: 40px; border-radius:5px; border-bottom-right-radius: 10px;"></span>
                <span class="text">Upcoming</span>
            </button>
            
        </div>
    </div>

    
    </div>

    <div style="margin-top: 50px; padding: 0px 80px;">
        <h2><b>Your Dashboard</h2>
    </div>
    <div class="full-width-bar-sub">
        <div class="content-box-test" onclick="window.location.href='calendar.php'">
            <div class="icon-overlay">
                <img style="border-radius: 5px;" src="images/view-calendar.svg" alt="Calendar Icon">
            </div>
            <img class="background-image" src="images/blank-white-background.jpg" />
            <div class="large-text-sub">Calendar</div>
            <div class="graph-text">See upcoming events/trainings.</div>
            <button class="arrow-button">→</button>
        </div>

               <?php
                    require_once('database/dbMessages.php');
                    $unreadMessageCount = get_user_unread_count($person->get_id());
                    $inboxIcon = 'inbox.svg';
                    if ($unreadMessageCount) {
                        $inboxIcon = 'inbox-unread.svg';
                    }   
                ?>  

        <div class="content-box-test" onclick="window.location.href='upload_encrypted_image.php'">
            <div class="icon-overlay">
                <img style="border-radius: 5px;" src="images/file-regular.svg" alt="Calendar Icon">
            </div>
            <img class="background-image" src="images/blank-white-background.jpg" />
            <div class="large-text-sub">Documentation Upload</div>
            <div class="graph-text">Upload an ID for verification.</div>
            <button class="arrow-button">→</button>
        </div>

        <div class="content-box-test" onclick="window.location.href='createSuggestion.php'">
            <div class="icon-overlay">
                <img style="border-radius: 5px;" src="images/clipboard-regular.svg" alt="Report Icon">
            </div>
            <img class="background-image" src="images/blank-white-background.jpg" />
            <div class="large-text-sub">Suggestions</div>
            <div class="graph-text">Suggest opportunities for charity events.</div>
            <button class="arrow-button">→</button>
        </div>

        <div class="content-box-test" onclick="window.location.href='inbox.php'">
            <div class="icon-overlay">
                <img style="border-radius: 5px;" src="images/<?php echo $inboxIcon ?>" alt="Notification Icon">
            </div>
            <img class="background-image" src="images/blank-white-background.jpg" />
            <div class="large-text-sub">Notifications</div>
            <div class="graph-text">Stay up to date.</div>
            <button class="arrow-button">→</button>
        </div>

    </div>

<div style="width: 90%; /* Stops before page ends */
            height: 100%;
            outline: 1px #828282 solid;
            outline-offset: -0.5px;
            margin: 70px auto; /* Adds vertical space and centers */
            padding: 1px 0;"> <!-- Adds spacing inside the div -->
</div>

    <footer class="footer" style="margin-top: 100px;">
        <!-- Left Side: Logo & Socials -->
        <div class="footer-left">
            <img src="images/logo.png" alt="Logo" class="footer-logo">
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>

        <!-- Right Side: Page Links -->
        <div class="footer-right">
            <div class="footer-section">
                <div class="footer-topic">Connect</div>
                <a href="https://www.facebook.com/gwynethsgift">Facebook</a>
                <a href="https://www.instagram.com/gwynethsgift/">Instagram</a>
                <a href="https://gwynethsgift.org/">Main Website</a>
            </div>
            <div class="footer-section">
                <div class="footer-topic">Contact Us</div>
                <a href="https://gwynethsgift.org/contact-us/">Send Us An Email</a>
                <!-- <a href="tel:5408981500">540-898-1500 (ext 117)</a> -->
            </div>
        </div>
    </footer>
    <p>_</p>

    <!-- Font Awesome for Icons -->
    <script src="https://kit.fontawesome.com/yourkit.js" crossorigin="anonymous"></script>

</body>
<?php endif ?>
</html>
