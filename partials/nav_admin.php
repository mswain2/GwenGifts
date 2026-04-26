<?php $sb_current = basename($_SERVER['PHP_SELF']); ?>


<script>
/* Apply body classes before first paint to avoid layout flash */
(function(){
    document.body.classList.add('sb-active');
    if (localStorage.getItem('gg_sidebar') === 'collapsed') {
        document.body.classList.add('sidebar-collapsed');
    }
})();
</script>

<!-- ── TOP BAR ────────────────────────────────────────────────────────────── -->
<div class="gg-topbar">
    <button class="gg-hamburger" id="gg-hamburger" aria-label="Toggle navigation" aria-expanded="true" aria-controls="gg-sidebar">
        <span></span><span></span><span></span>
    </button>
    <a href="index.php" class="gg-topbar-brand">
        <img src="images/cropped-logo.png" alt="Gwyneth's Gift" class="gg-topbar-logo">
        <span class="gg-topbar-name">Gwyneth's Gift</span>
    </a>
</div>

<!-- ── SIDEBAR ────────────────────────────────────────────────────────────── -->
<nav class="gg-sidebar" id="gg-sidebar" aria-label="Main navigation">
    <div class="sb-nav">

        <a class="sb-item <?= $sb_current==='index.php' ? 'sb-current' : '' ?>" href="index.php">
            <svg class="sb-icon" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
            <span class="sb-label">Home</span>
        </a>
        <a class="sb-item <?= $sb_current==='calendar.php' ? 'sb-current' : '' ?>" href="calendar.php">
            <img class="sb-icon" src="images/view-calendar.svg" alt="">
            <span class="sb-label">Calendar</span>
        </a>

        <div class="sb-section">Events</div>
        <a class="sb-item <?= $sb_current==='addEvent.php' ? 'sb-current' : '' ?>" href="addEvent.php">
            <img class="sb-icon" src="images/plus-solid.svg" alt="">
            <span class="sb-label">Create Event</span>
        </a>

        <a class="sb-item <?= $sb_current==='viewAllEvents.php' ? 'sb-current' : '' ?>" href="viewAllEvents.php">
            <img class="sb-icon" src="images/list-solid.svg" alt="">
            <span class="sb-label">Browse Events</span>
        </a>
        <a class="sb-item <?= $sb_current==='viewMyUpcomingEvents.php' ? 'sb-current' : '' ?>" href="viewMyUpcomingEvents.php">
            <img class="sb-icon" src="images/new-event.svg" alt="">
            <span class="sb-label">My Events</span>
        </a>
        <?php if (in_array($_SESSION['type'] ?? '', ['admin', 'superadmin', 'board_member'])): ?>
        <a class="sb-item <?= $sb_current==='addBoardMeeting.php' ? 'sb-current' : '' ?>" href="addBoardMeeting.php">
            <img class="sb-icon" src="images/view-calendar.svg" alt="">
            <span class="sb-label">Add Board Meeting</span>
        </a>
        <?php endif; ?>

        <div class="sb-section">Volunteers</div>
        <?php if (in_array($_SESSION['type'] ?? '', ['admin', 'superadmin'])): ?>
        <a class="sb-item <?= $sb_current==='VolunteerRegister.php' ? 'sb-current' : '' ?>" href="VolunteerRegister.php">
            <img class="sb-icon" src="images/add-person.svg" alt="">
            <span class="sb-label">Register Volunteer</span>
        </a>
        <?php endif; ?>
        <a class="sb-item <?= $sb_current==='personSearch.php' ? 'sb-current' : '' ?>" href="personSearch.php">
            <img class="sb-icon" src="images/person-search.svg" alt="">
            <span class="sb-label">Search Users</span>
        </a>
        <a class="sb-item <?= $sb_current==='editHours.php' ? 'sb-current' : '' ?>" href="editHours.php">
            <img class="sb-icon" src="images/clock-regular.svg" alt="">
            <span class="sb-label">Volunteer Hours</span>
        </a>
        
        <a class="sb-item <?= $sb_current==='generateReport.php' ? 'sb-current' : '' ?>" href="generateReport.php">
            <img class="sb-icon" src="images/clipboard-regular.svg" alt="">
            <span class="sb-label">Generate Report</span>
        </a>

        <div class="sb-section">Community</div>
        <a class="sb-item <?= $sb_current==='inbox.php' ? 'sb-current' : '' ?>" href="inbox.php">
            <img class="sb-icon" src="images/inbox.svg" alt="">
            <span class="sb-label">Notifications</span>
        </a>
        <?php if (in_array($_SESSION['type'] ?? '', ['admin', 'superadmin', 'board_member'])): ?>
        <a class="sb-item <?= $sb_current==='viewBoardDiscussions.php' ? 'sb-current' : '' ?>" href="viewBoardDiscussions.php">
            <img class="sb-icon" src="images/group.svg" alt="">
            <span class="sb-label">Board Discussions</span>
        </a>
        <?php endif; ?>
        <a class="sb-item <?= $sb_current==='viewDiscussions.php' ? 'sb-current' : '' ?>" href="viewDiscussions.php">
            <img class="sb-icon" src="images/group.svg" alt="">
            <span class="sb-label">Discussions</span>
        </a>
        <a class="sb-item <?= $sb_current==='createEmail.php' ? 'sb-current' : '' ?>" href="createEmail.php">
            <img class="sb-icon" src="images/inbox.svg" alt="">
            <span class="sb-label">Create Email</span>
        </a>
        <a class="sb-item <?= $sb_current==='viewDrafts.php' ? 'sb-current' : '' ?>" href="viewDrafts.php">
            <img class="sb-icon" src="images/file-regular.svg" alt="">
            <span class="sb-label">View Drafts</span>
        </a>
        <a class="sb-item <?= $sb_current==='personSearch.php' ? 'sb-current' : '' ?>" href="personSearch.php">
            <img class="sb-icon" src="images/send.png" alt="">
            <span class="sb-label">Email List</span>
        </a>

        <div class="sb-section">Resources</div>
        <a class="sb-item <?= $sb_current==='myTrainingMaterials.php' ? 'sb-current' : '' ?>" href="myTrainingMaterials.php">
            <img class="sb-icon" src="images/clipboard-regular.svg" alt="">
            <span class="sb-label">My Training Materials</span>
        </a>
        <a class="sb-item <?= $sb_current==='boardDocuments.php' ? 'sb-current' : '' ?>" href="boardDocuments.php">
            <img class="sb-icon" src="images/file-regular.svg" alt="">
            <span class="sb-label">Documents</span>
        </a>
        <a class="sb-item <?= $sb_current==='help.php' ? 'sb-current' : '' ?>" href="help.php">
            <svg class="sb-icon" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/></svg>
            <span class="sb-label">Help</span>
        </a>

        <hr class="sb-divider">

        <div class="sb-section">My Account</div>
        <a class="sb-item <?= $sb_current==='viewProfile.php' ? 'sb-current' : '' ?>" href="viewProfile.php">
            <img class="sb-icon" src="images/view-profile.svg" alt="">
            <span class="sb-label">View Profile</span>
        </a>
        <a class="sb-item <?= $sb_current==='editProfile.php' ? 'sb-current' : '' ?>" href="editProfile.php">
            <img class="sb-icon" src="images/manage-account.svg" alt="">
            <span class="sb-label">Edit Profile</span>
        </a>
        <a class="sb-item <?= $sb_current==='changePassword.php' ? 'sb-current' : '' ?>" href="changePassword.php">
            <img class="sb-icon" src="images/change-password.svg" alt="">
            <span class="sb-label">Change Password</span>
        </a>
        <a class="sb-item" href="logout.php">
            <img class="sb-icon" src="images/logout.svg" alt="">
            <span class="sb-label">Log Out</span>
        </a>

    </div>

    <a class="sb-profile" href="viewProfile.php" aria-label="View your profile">
        <img class="sb-pfp" src="<?= $_pfp_escaped ?>" alt="" onerror="this.onerror=null;this.src='images/usaicon.png'">
        <div class="sb-profile-info">
            <div class="sb-profile-name"><?= htmlspecialchars(trim(($_SESSION['f_name'] ?? '') . ' ' . ($_SESSION['l_name'] ?? ''))); ?></div>
            <div class="sb-profile-role"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $_SESSION['type'] ?? 'Admin'))); ?></div>
        </div>
    </a>
</nav>

<script>
(function(){
    var btn     = document.getElementById('gg-hamburger');
    var body    = document.body;
    var profile = document.querySelector('.sb-profile');
    if (!btn) return;

    function setProfile(visible) {
        if (!profile) return;
        profile.style.opacity       = visible ? '1' : '0';
        profile.style.pointerEvents = visible ? '' : 'none';
    }

    /* Apply initial state instantly (no transition) */
    var initCollapsed = body.classList.contains('sidebar-collapsed');
    if (profile) {
        profile.style.transition = 'none';
        setProfile(!initCollapsed);
        profile.offsetHeight; /* force reflow so transition is re-enabled after */
        profile.style.transition = '';
    }

    btn.setAttribute('aria-expanded', String(!initCollapsed));
    btn.addEventListener('click', function(){
        var mobile = window.innerWidth <= 768;
        if (mobile) {
            var open = body.classList.toggle('sidebar-open');
            btn.setAttribute('aria-expanded', String(open));
            setProfile(open);
        } else {
            var collapsed = body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('gg_sidebar', collapsed ? 'collapsed' : 'expanded');
            btn.setAttribute('aria-expanded', String(!collapsed));
            setProfile(!collapsed);
        }
    });
})();
</script>
