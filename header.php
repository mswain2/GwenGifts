<!-- This looks really, really great!  -Thomas -->
<?php
date_default_timezone_set('America/New_York');
/*
 * Copyright 2013 by Allen Tucker. 
 * This program is part of RMHP-Homebase, which is free software.  It comes with 
 * absolutely no warranty. You can redistribute and/or modify it under the terms 
 * of the GNU General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
 * 
if (date("H:i:s") > "18:19:59") {
	require_once 'database/dbShifts.php';
	auto_checkout_missing_shifts();
}
 */

// check if we are in locked mode, if so,
// user cannot access anything else without 
// logging back in
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;700&family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".nav-item").forEach(item => {
                item.addEventListener("click", function(event) {
                    event.stopPropagation();
                    document.querySelectorAll(".nav-item").forEach(nav => {
                        if (nav !== item) {
                            nav.classList.remove("active");
                            if(nav.querySelector(".dropdown") !== null) {
                                nav.querySelector(".dropdown").style.display = "none";
                            }
                        }
                    });
                    this.classList.toggle("active");
                    let dropdown = this.querySelector(".dropdown");
                    if (dropdown) {
                        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
                    }
                });
            });
            document.addEventListener("click", function() {
                document.querySelectorAll(".nav-item").forEach(nav => {
                    nav.classList.remove("active");
                    if(nav.querySelector(".dropdown") !== null) {
                        nav.querySelector(".dropdown").style.display = "none";
                    }
                });
            });
        });
    </script>
</head>

<header>

    <?php
    //Log-in security
    //If they aren't logged in, display our log-in form.
    $showing_login = false;
    if (!isset($_SESSION['logged_in'])) {
		echo('<div class="navbar">
        <!-- Left Section: Logo & Nav Links -->
        <div class="left-section">
            <div class="logo-container">
                <a href="index.php"><img src="images/cropped-logo.png" alt="Logo"></a>
            </div>
            <div class="nav-links">
                <div class="nav-item">
                    <a href="index.php" class="nav-link">Home</a>
                </div>
                <div class="nav-item">
                    <a href="calendar.php" class="nav-link">Events Calendar</a>
                </div>
            </div>
        </div>

        <!-- Right Section: Date & Icon -->
        <div class="right-section">
            <div class="nav-links">
                <div class="nav-item">
                    <div class="icon">
                        <img src="images/usaicon.png" alt="User Icon" class="icon-img in-nav-img">
                        <div class="dropdown">
                            <a href="signup.php" class="dropdown-link"><div>Create Account</div></a>
                            <a href="login.php" class="dropdown-link"><div>Log in</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>');

    } else if ($_SESSION['logged_in']) {

        /*         * Set our permission array.
         * anything a guest can do, a volunteer and manager can also do
         * anything a volunteer can do, a manager can do.
         *
         * If a page is not specified in the permission array, anyone logged into the system
         * can view it. If someone logged into the system attempts to access a page above their
         * permission level, they will be sent back to the home page.
         */
        //pages guests are allowed to view
        // LOWERCASE
        /*
        *  For A guest can log in, go to WVF's home page,  
        * -Evan
        */
        $permission_array['index.php'] = 0; // WVF Home page
        $permission_array['about.php'] = 0; //WVF - Not able to directly access - Likely just need to re-route to 
        $permission_array['apply.php'] = 0; //WVF - Not able to directly access
        $permission_array['logout.php'] = 0; //WVF - Logout page ain
        $permission_array['volunteerregister.php'] = 0; //WVF - Alter to registering for account
	    $permission_array['leaderboard.php'] = 0; //WVF - Probably get rid of this guy
        // $permission_array['findanimal.php'] = 0; //TODO DELETE
        //pages volunteers can view
        $permission_array['help.php'] = 1;
        $permission_array['dashboard.php'] = 1; //WVF - Might be good to alter this for registered users to be able to see registered events and where they can edit user info 
        $permission_array['calendar.php'] = 0; //WVF - Everyone can see this
        $permission_array['eventsearch.php'] = 1; 
        $permission_array['changepassword.php'] = 1;
        $permission_array['editprofile.php'] = 1; //WVF - Repurpose for SCRUM-5
        $permission_array['inbox.php'] = 1; //WVF - Not for registered users, since they want emails. But would be good for 'suggestions' for ADMINS to see 
        $permission_array['date.php'] = 1; 
        $permission_array['event.php'] = 0; 
        $permission_array['viewprofile.php'] = 1;
        $permission_array['viewnotification.php'] = 1;
        $permission_array['volunteerreport.php'] = 1; //WVF - Attendance Report?
        $permission_array['viewmyupcomingevents.php'] = 1;
        $permission_array['volunteerviewgroup.php'] = 1; 
	    $permission_array['viewcheckinout.php'] = 1;
        $permission_array['viewresources.php'] = 1;
        $permission_array['discussionmain.php'] = 1;
        $permission_array['viewdiscussions.php'] = 1; //WVF - Edit discussions for suggestions?
        $permission_array['discussioncontent.php'] = 1; //WVF - Edit discussions for suggestions?
        $permission_array['milestonepoints.php'] = 1;
        $permission_array['selectvotm.php'] = 1;
        $permission_array['volunteerviewgroupmembers.php'] = 1;
        //pages only managers can view
        $permission_array['viewallevents.php'] = 0; //WVF - For admins to do view 
        $permission_array['personsearch.php'] = 2;
        $permission_array['personedit.php'] = 0; // changed to 0 so that applicants can apply
        $permission_array['viewschedule.php'] = 2;
        $permission_array['addweek.php'] = 2;
        $permission_array['log.php'] = 2;
        $permission_array['reports.php'] = 2;
        $permission_array['eventedit.php'] = 2; //WVF - TODO: Evaluated differenced between eventedit and editevent.
        $permission_array['modifyuserrole.php'] = 2;
        $permission_array['addevent.php'] = 2; //WVF - Admin Event work!
        $permission_array['editevent.php'] = 2; //WVF - Admin Event work!
        // $permission_array['roster.php'] = 2; //TODO DELETE
        $permission_array['report.php'] = 2; // WVF TODO: Look to see how these reports can be reworked to do attendance report
        $permission_array['reportspage.php'] = 2;
        $permission_array['resetpassword.php'] = 2;
        // $permission_array['addappointment.php'] = 2; //TODO DELETE
        // $permission_array['addanimal.php'] = 2; //TODO DELETE
        // $permission_array['addservice.php'] = 2; //TODO DELETE
        // $permission_array['addlocation.php'] = 2; //TODO DELETE
        // $permission_array['viewvece.php'] = 2; //TODO DELETE
        // $permission_array['viewlocation.php'] = 2; //TODO DELETE
        // $permission_array['viewarchived.php'] = 2; //TODO DELETE
        // $permission_array['animal.php'] = 2; //TODO DELETE
        // $permission_array['editanimal.php'] = 2; //TODO DELETE
        $permission_array['eventsuccess.php'] = 2;
        $permission_array['viewsignuplist.php'] = 2;
        $permission_array['vieweventsignups.php'] = 2;
        $permission_array['viewpendingapps.php'] = 2;
        $permission_array['resources.php'] = 2;
        $permission_array['uploadresources.php'] = 2;        
        $permission_array['deleteresources.php'] = 2;
        $permission_array['creategroup.php'] = 2;
        $permission_array['showgroups.php'] = 2;
        $permission_array['groupview.php'] = 2;
        $permission_array['managemembers.php'] = 2;
        $permission_array['deleteGroup.php'] = 2;
        $permission_array['volunteermanagement.php'] = 2;
        $permission_array['groupmanagement.php'] = 2;
        $permission_array['eventmanagement.php'] = 2;
        $permission_array['creatediscussion.php'] = 1;
        $permission_array['checkedinvolunteers.php'] = 2;
        $permission_array['deletediscussion.php'] = 2;
        $permission_array['generatereport.php'] = 2; //adding this to the generate report page
        $permission_array['generateemaillist.php'] = 2; //adding this to the generate report page
        $permission_array['clockoutbulk.php'] = 2;
        $permission_array['clockOut.php'] = 2;
        $permission_array['edithours.php'] = 2;
        $permission_array['eventlist.php'] = 1;   
        $permission_array['eventsignup.php'] = 1;
        $permission_array['eventfailure.php'] = 1;
        $permission_array['signupsuccess.php'] = 1;
        $permission_array['edittimes.php'] = 1;
        $permission_array['adminviewingevents.php'] = 2;
        $permission_array['pendingApp.php'] = 1;
        $permission_array['requestfailed.php'] = 1;
        $permission_array['settimes.php'] = 1;
        $permission_array['eventfailurebaddeparturetime.php'] = 1;
        $permission_array['viewretreatapplications.php'] = 2;
        $permission_array['viewapplication.php'] = 2;
        $permission_array['createemail.php'] = 2;
        $permission_array['viewallapplications.php'] = 2;
        $permission_array['applicationsuccess.php'] = 2;
        $permission_array['denyapplication.php'] = 2;
        $permission_array['createemail.php'] = 2;
        $permission_array['viewdrafts.php'] = 2;  // Not sure if we want normal users to be able to send emails
        $permission_array['editdrafts.php'] = 2;
        $permission_array['logattendees.php'] = 2;
        $permission_array['processattendees.php'] = 2;
        $permission_array['viewdata.php'] = 2;
        $permission_array['deleteusersearch.php'] = 2;
        $permission_array['noshows.php'] = 2;
        $permission_array["view_encrypted_gallery.php"] = 2;
        $permission_array['upload_encrypted_image.php'] = 1;
        $permission_array['createsuggestion.php'] = 1;
        $permission_array['viewsuggestion.php'] = 2;
        $permission_array['boarddocuments.php'] = 1;
        $permission_array['addboarddocument.php'] = 2;
        $permission_array['addboardmeeting.php'] = 2;
        $permission_array['viewsuggestions.php'] = 2;
        $permission_array['eventtrainingmanagement.php'] = 2;
        $permission_array['viewboarddiscussions.php'] = 1;
        $permission_array['createboarddiscussion.php'] = 1;
        $permission_array['deleteboarddocument.php'] = 2;
        $permission_array['boarddocumentstrash.php'] = 2;
        $permission_array['addtrainingmaterial.php'] = 2;
        $permission_array['mytrainingmaterials.php'] = 1;
        $permission_array['editboarddocument.php'] = 1;
        $permission_array['editdiscussion.php'] = 1;
        $permission_array['editreply.php'] = 1;
        // LOWERCASE



        //Check if they're at a valid page for their access level.
        $current_page = strtolower(substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/') + 1));
        $current_page = substr($current_page, strpos($current_page,"/"));
        
        if($permission_array[$current_page]>$_SESSION['access_level']){
            //in this case, the user doesn't have permission to view this page.
            //we redirect them to the index page.
            echo "<script type=\"text/javascript\">window.location = \"index.php\";</script>";
            //note: if javascript is disabled for a user's browser, it would still show the page.
            //so we die().
            die();
        }
        //This line gives us the path to the html pages in question, useful if the server isn't installed @ root.
        $path = strrev(substr(strrev($_SERVER['SCRIPT_NAME']), strpos(strrev($_SERVER['SCRIPT_NAME']), '/')));
		$venues = array("portland"=>"RMH Portland"); // Is this used anywhere? Do we need it? -Blue
        
        //they're logged in and session variables are set.
	
        // load header according to user role/type
        if ($type === 'admin' || $type === 'board_member' || $type === 'event_manager') {
            require 'partials/nav_admin.php';
        }
        else {
            require 'partials/nav_volunteer.php';
        }

    }
?>
<script>
    function updateDateAndCheckBoxes() {
        const now = new Date();
        const width = window.innerWidth;

        // Format the date based on width
        let formatted = "";
        if (width > 1650) {
        formatted = "Today is " + now.toLocaleDateString("en-US", {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric"
        });
        } else if (width >= 1450) {
        formatted = now.toLocaleDateString("en-US", {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric"
        });
        } else {
        formatted = now.toLocaleDateString("en-US"); // e.g., 04/17/2025
        }

        // Update right-section date boxes
        document.querySelectorAll(".right-section .date-box").forEach(el => {
        if (width < 1130) {
            el.style.display = "none";
        } else {
            el.style.display = "";
            el.textContent = formatted;
        }
        });

        // Update left-section date boxes (Check In / Out or icon)
        document.querySelectorAll(".left-section .date-box").forEach(el => {
        if (width < 750) {
            el.style.display = "none";
        } else {
            el.style.display = "";
            el.textContent = width < 1130 ? "🔁" : "Check In/Out";
        }
        });

        document.querySelectorAll(".icon-butt").forEach(el => {
        if (width < 800) {
            el.style.display = "none";
        } else {
            el.style.display = "";
        } 
        });

    }

    // Run on load and resize
    window.addEventListener("resize", updateDateAndCheckBoxes);
    window.addEventListener("load", updateDateAndCheckBoxes);
</script>

<!-- Accessibility Button + Modal -->
<button class="accessibility-btn" id="accessibilityBtn" aria-haspopup="dialog" aria-controls="accessibilityModal" title="Accessibility settings">
    <img src="images/accessibility-menu.png" alt="Accessibility Menu">
</button>

<div class="accessibility-modal-backdrop" id="accessibilityBackdrop" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="accessibility-modal" id="accessibilityModal">
        <div class="modal-header">
            <h3>Accessibility Settings</h3>
            <button id="accessibilityClose" class="modal-close" style="max-width: 22%;">&times;</button>
        </div>
        <p class="modal-desc">Adjust font size, font style, and color scheme. Settings persist across pages and visits.</p>

        <div class="accessibility-row">
            <label for="acc-font-size">Font size</label>
            <div style="display:flex; align-items:center; gap:8px;">
                <input id="acc-font-size" type="range" min="12" max="24" step="1" value="14">
                <span id="acc-font-size-value">14pt</span>
            </div>
        </div>

        <div class="accessibility-row">
            <label for="acc-font-family">Font style</label>
            <select id="acc-font-family">
                <option value="nunito">Nunito (default)</option>
                <option value="quicksand">Quicksand</option>
                <option value="comic">Comic Sans</option>
                <option value="opendyslexic">OpenDyslexic</option>
                <option value="times">Times New Roman</option>
            </select>
        </div>

        <!-- Color scheme removed; keeping font controls only -->

        <div class="accessibility-actions">
            <button class="reset" id="accReset">Reset</button>
            <button class="save" id="accSave">Save</button>
        </div>
    </div>
</div>

<script>
    (function(){
        const KEY = 'wv_accessibility_settings';
        const defaults = { fontSize: 14, fontFamily: 'nunito' };

        function getSettings(){
            try{
                const raw = localStorage.getItem(KEY);
                return raw ? JSON.parse(raw) : Object.assign({}, defaults);
            }catch(e){ return Object.assign({}, defaults); }
        }

        function saveSettings(s){
            try{ localStorage.setItem(KEY, JSON.stringify(s)); }catch(e){}
        }

        function applySettings(s){
            // font size in points
            var size = Number(s.fontSize) || defaults.fontSize;
            if(size < 12) size = 12; if(size > 24) size = 24;
            document.documentElement.style.fontSize = size + 'pt';
            // update visible slider value if present
            var sizeDisplay = document.getElementById('acc-font-size-value'); if(sizeDisplay) sizeDisplay.textContent = size + 'pt';

            // font family mapping
            if(s.fontFamily === 'nunito'){
                document.body.style.fontFamily = 'Nunito, Quicksand, sans-serif';
            } else if (s.fontFamily === 'quicksand'){
                document.body.style.fontFamily = 'Quicksand, sans-serif';
            } else if (s.fontFamily === 'comic'){
                document.body.style.fontFamily = '"Comic Sans MS", "Comic Sans", cursive';
            } else if (s.fontFamily === 'opendyslexic'){
                document.body.style.fontFamily = 'OpenDyslexic, "Arial", sans-serif';
            } else if (s.fontFamily === 'times'){
                document.body.style.fontFamily = '"Times New Roman", Times, serif';
            }

            // color scheme support removed; icons keep their default CSS filters
        }

        // Initialize UI values from settings
        function populateUI(s){
            const size = document.getElementById('acc-font-size');
            const sizeVal = document.getElementById('acc-font-size-value');
            const ff = document.getElementById('acc-font-family');
            if(size) size.value = (s.fontSize !== undefined ? s.fontSize : defaults.fontSize);
            if(sizeVal) sizeVal.textContent = (s.fontSize !== undefined ? s.fontSize : defaults.fontSize) + 'pt';
            if(ff) ff.value = s.fontFamily || defaults.fontFamily;
        }

        // DOM elements
        const btn = document.getElementById('accessibilityBtn');
        const backdrop = document.getElementById('accessibilityBackdrop');
        const closeBtn = document.getElementById('accessibilityClose');
        const saveBtn = document.getElementById('accSave');
        const resetBtn = document.getElementById('accReset');

        // open/close helpers
        function openModal(){ backdrop.style.display = 'flex'; backdrop.setAttribute('aria-hidden','false'); document.getElementById('acc-font-size').focus(); }
        function closeModal(){ backdrop.style.display = 'none'; backdrop.setAttribute('aria-hidden','true'); btn.focus(); }

        btn.addEventListener('click', function(e){
            e.stopPropagation();
            const s = getSettings();
            populateUI(s);
            openModal();
        });
        closeBtn.addEventListener('click', closeModal);
        backdrop.addEventListener('click', function(e){ if(e.target === backdrop) closeModal(); });

        saveBtn.addEventListener('click', function(){
            const s = {
                fontSize: Number(document.getElementById('acc-font-size').value),
                fontFamily: document.getElementById('acc-font-family').value
            };
            applySettings(s);
            saveSettings(s);
            closeModal();
        });

        // live update when moving slider
        const slider = document.getElementById('acc-font-size');
        if(slider){ slider.addEventListener('input', function(){ document.getElementById('acc-font-size-value').textContent = this.value + 'pt'; }); }

        resetBtn.addEventListener('click', function(){
            localStorage.removeItem(KEY);
            const s = Object.assign({}, defaults);
            applySettings(s);
            populateUI(s);
        });

        // apply on load
        document.addEventListener('DOMContentLoaded', function(){
            const s = getSettings();
            applySettings(s);
        });
    })();
</script>
</header>
