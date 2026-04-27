<?php
/* Guest sidebar — shown on public pages (no session required) */
?>
<script>
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

        <a class="sb-item <?= basename($_SERVER['PHP_SELF']) === 'calendar.php' ? 'sb-current' : '' ?>" href="calendar.php">
            <img class="sb-icon" src="images/view-calendar.svg" alt="">
            <span class="sb-label">Calendar</span>
        </a>

        <a class="sb-item <?= basename($_SERVER['PHP_SELF']) === 'login.php' ? 'sb-current' : '' ?>" href="login.php">
            <img class="sb-icon" src="images/logout.svg" alt="">
            <span class="sb-label">Login</span>
        </a>

    </div>
</nav>

<script>
(function(){
    var btn     = document.getElementById('gg-hamburger');
    var body    = document.body;
    if (!btn) return;

    var initCollapsed = body.classList.contains('sidebar-collapsed');
    btn.setAttribute('aria-expanded', String(!initCollapsed));

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
