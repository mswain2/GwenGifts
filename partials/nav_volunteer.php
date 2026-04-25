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
        <a class="sb-item <?= $sb_current==='viewMyUpcomingEvents.php' ? 'sb-current' : '' ?>" href="viewMyUpcomingEvents.php">
            <img class="sb-icon" src="images/new-event.svg" alt="">
            <span class="sb-label">My Upcoming</span>
        </a>
        <a class="sb-item <?= $sb_current==='viewAllEvents.php' ? 'sb-current' : '' ?>" href="viewAllEvents.php">
            <img class="sb-icon" src="images/list-solid.svg" alt="">
            <span class="sb-label">Browse Events</span>
        </a>

        <div class="sb-section">Community</div>
        <a class="sb-item <?= $sb_current==='inbox.php' ? 'sb-current' : '' ?>" href="inbox.php">
            <img class="sb-icon" src="images/inbox.svg" alt="">
            <span class="sb-label">Notifications</span>
        </a>
        <a class="sb-item <?= $sb_current==='viewDiscussions.php' ? 'sb-current' : '' ?>" href="viewDiscussions.php">
            <img class="sb-icon" src="images/group.svg" alt="">
            <span class="sb-label">Discussions</span>
        </a>

        <div class="sb-section">Resources</div>
        <a class="sb-item <?= $sb_current==='boardDocuments.php' ? 'sb-current' : '' ?>" href="boardDocuments.php">
            <img class="sb-icon" src="images/file-regular.svg" alt="">
            <span class="sb-label">Documents</span>
        </a>
        <a class="sb-item <?= $sb_current==='myTrainingMaterials.php' ? 'sb-current' : '' ?>" href="myTrainingMaterials.php">
            <img class="sb-icon" src="images/clipboard-regular.svg" alt="">
            <span class="sb-label">Training</span>
        </a>
        <a class="sb-item <?= $sb_current==='help.php' ? 'sb-current' : '' ?>" href="help.php">
            <svg class="sb-icon" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/></svg>
            <span class="sb-label">Help</span>
        </a>

        <hr class="sb-divider">
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
            <div class="sb-profile-role"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $_SESSION['type'] ?? 'Volunteer'))); ?></div>
        </div>
    </a>
</nav>

<script>
(function(){
    var btn  = document.getElementById('gg-hamburger');
    var body = document.body;
    if (!btn) return;
    btn.setAttribute('aria-expanded', String(!body.classList.contains('sidebar-collapsed')));
    btn.addEventListener('click', function(){
        var mobile = window.innerWidth <= 768;
        if (mobile) {
            var open = body.classList.toggle('sidebar-open');
            btn.setAttribute('aria-expanded', String(open));
        } else {
            var collapsed = body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('gg_sidebar', collapsed ? 'collapsed' : 'expanded');
            btn.setAttribute('aria-expanded', String(!collapsed));
        }
    });
})();
</script>
