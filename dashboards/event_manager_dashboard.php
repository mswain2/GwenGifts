<?php
/* ── Data preparation ───────────────────────────────────────────────────── */
require_once('database/dbMessages.php');
require_once('database/dbEvents.php');
require_once('database/dbDiscussions.php');

$uid   = $person->get_id();
$today = date('Y-m-d');
$hour  = (int) date('H');

if      ($hour < 12) { $greeting = 'Good morning'; }
elseif  ($hour < 17) { $greeting = 'Good afternoon'; }
else                  { $greeting = 'Good evening'; }

/* Unread messages */
$unreadCount  = (int)(get_user_unread_count($uid) ?? 0);
$inboxPreview = array_slice(get_user_unread_messages($uid) ?: [], 0, 3);

/* Calendar events: 4 weeks back → 8 weeks forward */
$calStart       = date('Y-m-d', strtotime('-4 weeks'));
$calEnd         = date('Y-m-d', strtotime('+8 weeks'));
$calEvents      = fetch_events_in_date_range($calStart, $calEnd) ?: [];
$calData        = [];
foreach ($calEvents as $d => $evs) {
    $calData[$d] = array_values(array_map(fn($e) => $e['name'], $evs));
}
$calDataJson    = json_encode($calData, JSON_HEX_TAG | JSON_HEX_QUOT);
$todayEventsRaw = $calEvents[$today] ?? [];

/* Upcoming registered events (next 5) + signup dates for calendar */
$signupIds      = fetch_user_signups($uid);
$upcomingEvents = [];
$signupDates    = [];
foreach (array_keys($signupIds) as $eid) {
    $ev = fetch_event_by_id($eid);
    if (!$ev) continue;
    if (!empty($ev['startDate'])) {
        $signupDates[] = $ev['startDate'];
    }
    if (isset($ev['startDate']) && $ev['startDate'] >= $today && ($ev['completed'] ?? '') !== 'Y') {
        $upcomingEvents[] = $ev;
    }
}
usort($upcomingEvents, fn($a, $b) => strcmp($a['startDate'], $b['startDate']));
$signedUpCount   = count($upcomingEvents);
$upcomingEvents  = array_slice($upcomingEvents, 0, 5);
$signupDatesJson = json_encode(array_values(array_unique($signupDates)), JSON_HEX_TAG);


/* Recent discussions */
$allDiscussions = get_all_discussions() ?: [];
usort($allDiscussions, fn($a, $b) => strcmp($b['time'] ?? '', $a['time'] ?? ''));
$recentDiscussions = array_slice($allDiscussions, 0, 3);

/* Event stats for left column panels */
$_allUpcoming   = fetch_events_in_date_range($today, date('Y-m-d', strtotime('+365 days'))) ?: [];
$totalUpcoming  = array_sum(array_map('count', $_allUpcoming));
$_monthStart    = date('Y-m-01');
$_monthEnd      = date('Y-m-t');
$_monthEvents   = fetch_events_in_date_range($_monthStart, $_monthEnd) ?: [];
$totalThisMonth = array_sum(array_map('count', $_monthEvents));

/* Profile stats */
$attendedRows        = get_events_attended_by($uid) ?: [];
$eventsAttendedCount = count($attendedRows);
$attendedDates       = array_values(array_unique(array_filter(array_column($attendedRows, 'startDate'))));
$attendedDatesJson   = json_encode($attendedDates, JSON_HEX_TAG);
$volunteerHours      = floatval($person->get_total_hours_volunteered());
$memberSince         = $person->get_start_date();
$profilePic          = $person->get_profile_pic() ?: 'images/usaicon.png';
$volEmail            = $person->get_email();
$volType             = ucfirst(str_replace('_', ' ', $person->get_type() ?: 'Event Manager'));
?>

<body>
<a class="vd-skip" href="#vd-main-content">Skip to main content</a>

<?php require 'header.php'; ?>
<?php require 'partials/toasts.php'; ?>

<!-- ── HERO ──────────────────────────────────────────────────────────────── -->
<section class="vd-hero" aria-label="Dashboard header">
    <div class="vd-hero-left">
        <p class="vd-greeting" aria-hidden="true"><?php echo htmlspecialchars($greeting); ?></p>
        <h1 class="vd-hero-name"><?php echo htmlspecialchars($person->get_first_name()); ?></h1>
        <div class="vd-hero-bar" aria-hidden="true"></div>
    </div>
    <img src="images/gwenythsGiftLogo.png" alt="Gwyneth's Gift" class="vd-hero-logo" aria-hidden="true" onerror="this.onerror=null;this.style.display='none'">
</section>

<!-- ── STATS BAR ─────────────────────────────────────────────────────────── -->
<div class="vd-stats" aria-label="Your activity summary">
    <div class="vd-stat">
        <span class="vd-stat-val"><?php echo number_format($volunteerHours, 1); ?></span>
        <span class="vd-stat-lbl">Own Hours Volunteered</span>
    </div>
    <div class="vd-stat">
        <span class="vd-stat-val"><?php echo count($upcomingEvents); ?></span>
        <span class="vd-stat-lbl">Upcoming Events</span>
    </div>
    <div class="vd-stat">
        <span class="vd-stat-val"><?php echo $unreadCount; ?></span>
        <span class="vd-stat-lbl">Unread Messages</span>
    </div>
</div>

<!-- ── QUICK NAV ─────────────────────────────────────────────────────────── -->
<!--
<nav class="vd-quicknav" aria-label="Quick navigation">
    <a class="vd-qn-card" href="viewProfile.php" aria-label="My Profile">
        <img class="vd-qn-icon" src="images/view-profile.svg" alt="" aria-hidden="true">
        <span class="vd-qn-label">My Profile</span>
    </a>
    <a class="vd-qn-card" href="editProfile.php" aria-label="Edit Profile">
        <img class="vd-qn-icon" src="images/manage-account.svg" alt="" aria-hidden="true">
        <span class="vd-qn-label">Edit Profile</span>
    </a>
    <a class="vd-qn-card" href="viewMyUpcomingEvents.php" aria-label="My Events">
        <img class="vd-qn-icon" src="images/new-event.svg" alt="" aria-hidden="true">
        <span class="vd-qn-label">My Events</span>
    </a>
    <a class="vd-qn-card" href="viewAllEvents.php" aria-label="Sign Up for Events">
        <img class="vd-qn-icon" src="images/list-solid.svg" alt="" aria-hidden="true">
        <span class="vd-qn-label">Sign Up</span>
    </a>
    <a class="vd-qn-card" href="inbox.php" aria-label="Notifications<?php echo $unreadCount > 0 ? ", $unreadCount unread" : ''; ?>">
        <img class="vd-qn-icon" src="images/<?php echo $unreadCount > 0 ? 'inbox-unread.svg' : 'inbox.svg'; ?>" alt="" aria-hidden="true">
        <span class="vd-qn-label">Notifications</span>
        <?php if ($unreadCount > 0): ?>
            <span class="vd-qn-badge" aria-hidden="true"><?php echo $unreadCount; ?></span>
        <?php endif; ?>
    </a>
    <a class="vd-qn-card" href="boardDocuments.php" aria-label="Documents">
        <img class="vd-qn-icon" src="images/file-regular.svg" alt="" aria-hidden="true">
        <span class="vd-qn-label">Documents</span>
    </a>
    <a class="vd-qn-card" href="viewDiscussions.php" aria-label="Discussions">
        <img class="vd-qn-icon" src="images/group.svg" alt="" aria-hidden="true">
        <span class="vd-qn-label">Discussions</span>
    </a>
    <a class="vd-qn-card" href="myTrainingMaterials.php" aria-label="Training Materials">
        <img class="vd-qn-icon" src="images/clipboard-regular.svg" alt="" aria-hidden="true">
        <span class="vd-qn-label">Training</span>
    </a>
</nav>

<!-- ── 3-COLUMN BODY ─────────────────────────────────────────────────────── -->
<main class="vd-body" id="vd-main-content">

    <!-- ── LEFT COLUMN ──────────────────────────────────────────────────── -->
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        <!-- Profile Card -->
        <div class="vd-panel" role="region" aria-labelledby="vd-profile-heading">
            <div class="vd-panel-header">
                <span class="vd-panel-title" id="vd-profile-heading">My Profile</span>
            </div>
            <div class="vd-panel-body">
                <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="Your profile picture" class="vd-prof-avatar" onerror="this.onerror=null;this.src='images/usaicon.png'">
                <p class="vd-prof-name"><?php echo htmlspecialchars($person->get_first_name() . ' ' . $person->get_last_name()); ?></p>
                <p class="vd-prof-role"><?php echo htmlspecialchars($volType); ?></p>
                <hr class="vd-prof-divider">
                <div class="vd-meta-row">
                    <span class="vd-meta-key">Email</span>
                    <span class="vd-meta-val" style="text-align:right;max-width:60%;">
                        <a class="vd-meta-link" href="mailto:<?php echo htmlspecialchars($volEmail); ?>">
                            <?php echo htmlspecialchars($volEmail); ?>
                        </a>
                    </span>
                </div>
                <div class="vd-meta-row">
                    <span class="vd-meta-key">Since</span>
                    <span class="vd-meta-val">
                        <?php echo $memberSince ? htmlspecialchars(date('M j, Y', strtotime($memberSince))) : '—'; ?>
                    </span>
                </div>
                <div class="vd-prof-actions">
                    <a href="viewProfile.php"    class="vd-btn vd-btn--primary">View Profile</a>
                    <a href="editProfile.php"    class="vd-btn vd-btn--outline">Edit Profile</a>
                </div>
            </div>
        </div>

        <!-- Upcoming Events Stat -->
        <div class="vd-panel" role="region" aria-labelledby="vd-upcount-heading">
            <div class="vd-panel-header">
                <span class="vd-panel-title" id="vd-upcount-heading">Upcoming Events</span>
            </div>
            <div class="vd-panel-body" style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2rem 1rem;">
                <span style="font-size:3.5rem;font-weight:800;color:var(--main-color);line-height:1;"><?php echo $totalUpcoming; ?></span>
                <span style="font-size:.8rem;color:#888;margin-top:.5rem;text-transform:uppercase;letter-spacing:.06em;">events scheduled</span>
            </div>
        </div>

        <!-- Events This Month Stat -->
        <div class="vd-panel" role="region" aria-labelledby="vd-monthcount-heading">
            <div class="vd-panel-header">
                <span class="vd-panel-title" id="vd-monthcount-heading">Events This Month</span>
            </div>
            <div class="vd-panel-body" style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2rem 1rem;">
                <span style="font-size:3.5rem;font-weight:800;color:var(--main-color);line-height:1;"><?php echo $totalThisMonth; ?></span>
                <span style="font-size:.8rem;color:#888;margin-top:.5rem;text-transform:uppercase;letter-spacing:.06em;"><?php echo date('F Y'); ?></span>
            </div>
        </div>

    </div>

    <!-- ── CENTER COLUMN ─────────────────────────────────────────────────── -->
    <div class="vd-cal-panel">

        <!-- Today strip -->
        <div class="vd-today-strip" role="region" aria-label="Today's events">
            <div class="vd-today-date"><?php echo date('l, F j'); ?></div>
            <div class="vd-today-sub">
                <?php echo empty($todayEventsRaw) ? 'No events scheduled today.' : count($todayEventsRaw) . ' event' . (count($todayEventsRaw) !== 1 ? 's' : '') . ' today'; ?>
            </div>
            <?php if (!empty($todayEventsRaw)): ?>
                <div class="vd-today-events" aria-live="polite">
                    <?php foreach ($todayEventsRaw as $te): ?>
                        <div class="vd-today-ev"><?php echo htmlspecialchars($te['name'] ?? $te); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Weekly calendar -->
        <div class="vd-panel" role="region" aria-labelledby="vd-cal-heading">
            <div class="vd-week-nav">
                <button class="vd-week-btn" id="vd-prev-week" aria-label="Previous week">&#8592;</button>
                <span class="vd-week-label" id="vd-week-label" aria-live="polite"></span>
                <button class="vd-week-btn" id="vd-next-week" aria-label="Next week">&#8594;</button>
            </div>
            <div class="vd-week-grid" id="vd-week-grid" role="grid" aria-label="Weekly calendar"></div>
            <div class="vd-cal-legend" aria-label="Calendar legend">
                <span class="vd-cal-legend-item">
                    <span class="vd-cal-legend-dot" style="background:var(--secondary-accent-color)"></span>Past (unattended)
                </span>
                <span class="vd-cal-legend-item">
                    <span class="vd-cal-legend-dot" style="background:var(--main-color)"></span>Attending / Attended
                </span>
                <span class="vd-cal-legend-item">
                    <span class="vd-cal-legend-dot" style="background:var(--accent-color)"></span>Other events
                </span>
            </div>
            <div style="padding:.25rem 1.1rem .75rem; text-align:center;">
                <a href="calendar.php" class="vd-panel-action">Open full calendar →</a>
            </div>
        </div>

        <!-- Upcoming registered events -->
        <div class="vd-panel" style="flex:1;" role="region" aria-labelledby="vd-upcoming-heading">
            <div class="vd-panel-header">
                <span class="vd-panel-title" id="vd-upcoming-heading">My Upcoming Events</span>
                <a href="viewMyUpcomingEvents.php" class="vd-panel-action">View all →</a>
            </div>
            <div class="vd-panel-body">
                <?php if (empty($upcomingEvents)): ?>
                    <p class="vd-empty-sm">No upcoming events registered. <a href="viewAllEvents.php" class="vd-panel-action">Browse events →</a></p>
                <?php else: ?>
                    <?php foreach ($upcomingEvents as $ev): ?>
                        <?php
                            $evDate  = $ev['startDate'] ?? '';
                            $evMonth = $evDate ? date('M', strtotime($evDate)) : '—';
                            $evDay   = $evDate ? date('j', strtotime($evDate))  : '—';
                            $evName  = $ev['name'] ?? 'Event';
                            $evLoc   = $ev['location'] ?? '';
                            $evId    = $ev['id'] ?? '';
                        ?>
                        <a href="event.php?id=<?php echo urlencode($evId); ?>" style="text-decoration:none;">
                            <div class="vd-upc-item">
                                <div class="vd-upc-date" aria-hidden="true">
                                    <div class="vd-upc-month"><?php echo $evMonth; ?></div>
                                    <div class="vd-upc-day"><?php echo $evDay; ?></div>
                                </div>
                                <div class="vd-upc-info">
                                    <div class="vd-upc-name"><?php echo htmlspecialchars($evName); ?></div>
                                    <?php if ($evLoc): ?><div class="vd-upc-loc"><?php echo htmlspecialchars($evLoc); ?></div><?php endif; ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- ── RIGHT COLUMN ──────────────────────────────────────────────────── -->
    <div style="display:flex;flex-direction:column;gap:1.25rem;align-self:stretch;">

        <!-- Inbox preview -->
        <div class="vd-panel" role="region" aria-labelledby="vd-inbox-heading">
            <div class="vd-panel-header">
                <span class="vd-panel-title" id="vd-inbox-heading">Notifications<?php echo $unreadCount > 0 ? " ($unreadCount)" : ''; ?></span>
                <a href="inbox.php" class="vd-panel-action">View all →</a>
            </div>
            <div class="vd-panel-body" aria-live="polite">
                <?php if (empty($inboxPreview)): ?>
                    <p class="vd-empty-sm">No unread messages.</p>
                <?php else: ?>
                    <?php foreach ($inboxPreview as $msg): ?>
                        <div class="vd-msg-item">
                            <div class="vd-msg-from"><?php echo htmlspecialchars($msg['senderID'] ?? 'System'); ?></div>
                            <div class="vd-msg-subj"><?php echo htmlspecialchars($msg['title'] ?? '(no subject)'); ?></div>
                            <div class="vd-msg-time"><?php echo htmlspecialchars($msg['time'] ?? ''); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent discussions -->
        <div class="vd-panel" role="region" aria-labelledby="vd-disc-heading">
            <div class="vd-panel-header">
                <span class="vd-panel-title" id="vd-disc-heading">Discussions</span>
                <a href="viewDiscussions.php" class="vd-panel-action">View all →</a>
            </div>
            <div class="vd-panel-body">
                <?php if (empty($recentDiscussions)): ?>
                    <p class="vd-empty-sm">No discussions yet.</p>
                <?php else: ?>
                    <?php foreach ($recentDiscussions as $disc): ?>
                        <a href="viewDiscussions.php" style="text-decoration:none;">
                            <div class="vd-disc-item">
                                <div class="vd-disc-title"><?php echo htmlspecialchars($disc['title'] ?? 'Untitled'); ?></div>
                                <div class="vd-disc-meta">
                                    <?php echo htmlspecialchars($disc['author_id'] ?? ''); ?>
                                    <?php if (!empty($disc['time'])): ?> · <?php echo htmlspecialchars(date('M j', strtotime($disc['time']))); ?><?php endif; ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Event Management -->
        <div class="vd-panel" style="flex:1;" role="region" aria-labelledby="vd-evmgmt-heading">
            <div class="vd-panel-header">
                <span class="vd-panel-title" id="vd-evmgmt-heading">Event Management</span>
            </div>
            <div class="vd-panel-body" style="display:flex;flex-direction:column;gap:.5rem;">
                <a href="addEvent.php"      class="vd-btn vd-btn--primary" style="text-align:center;">Create Event</a>
                <a href="viewAllEvents.php" class="vd-btn vd-btn--outline"  style="text-align:center;">Browse Events</a>
                <a href="editHours.php"     class="vd-btn vd-btn--outline"  style="text-align:center;">Manage Volunteer Hours</a>
                <a href="generateReport.php" class="vd-btn vd-btn--outline" style="text-align:center;">Generate Event Report</a>
            </div>
        </div>


    </div>

</main>

<?php require 'partials/footer.php'; ?>

<script>
(function () {
    var calData      = <?php echo $calDataJson; ?>;
    var signupDates  = <?php echo $signupDatesJson; ?>;
    var attendedDates= <?php echo $attendedDatesJson; ?>;
    var today        = '<?php echo $today; ?>';
    var DAYS         = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
    var MONTHS       = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

    var signupSet = {}, attendedSet = {};
    signupDates.forEach(function(d)   { signupSet[d]   = true; });
    attendedDates.forEach(function(d) { attendedSet[d]  = true; });

    function getMonday(dateStr) {
        var d = new Date(dateStr + 'T00:00:00');
        var day = d.getDay();
        var diff = (day === 0) ? -6 : 1 - day;
        d.setDate(d.getDate() + diff);
        return d;
    }

    function isoDate(d) {
        var mm = String(d.getMonth() + 1).padStart(2, '0');
        var dd = String(d.getDate()).padStart(2, '0');
        return d.getFullYear() + '-' + mm + '-' + dd;
    }

    var monday = getMonday(today);

    function renderWeek() {
        var grid  = document.getElementById('vd-week-grid');
        var label = document.getElementById('vd-week-label');
        var sunday = new Date(monday); sunday.setDate(monday.getDate() + 6);
        label.textContent =
            MONTHS[monday.getMonth()] + ' ' + monday.getDate() +
            ' – ' +
            MONTHS[sunday.getMonth()] + ' ' + sunday.getDate() + ', ' + sunday.getFullYear();

        grid.innerHTML = '';
        for (var i = 0; i < 7; i++) {
            var d   = new Date(monday); d.setDate(monday.getDate() + i);
            var iso = isoDate(d);
            var evs = calData[iso] || [];
            var col = document.createElement('div');
            col.className = 'vd-week-col';
            col.setAttribute('role', 'gridcell');
            col.setAttribute('aria-label', DAYS[d.getDay()] + ' ' + d.getDate() + (evs.length ? ', ' + evs.length + ' event' + (evs.length !== 1 ? 's' : '') : ''));

            var dow = document.createElement('div');
            dow.className = 'vd-week-dow';
            dow.textContent = DAYS[d.getDay()];

            var num = document.createElement('div');
            num.className = 'vd-week-day';
            var isToday      = iso === today;
            var isAttended   = !!attendedSet[iso];
            var isSignup     = !!signupSet[iso];
            var isPast       = iso < today;
            var isUnattended = isPast && evs.length > 0 && !isAttended;
            var isActive     = isAttended || (isSignup && !isUnattended);

            if (isToday) {
                num.classList.add('vd-week-day--today');
                num.setAttribute('aria-current', 'date');
            }
            if (isActive)          num.classList.add('vd-week-day--signup');
            else if (isUnattended) num.classList.add('vd-week-day--unattended');
            else if (evs.length > 0) num.classList.add('vd-week-day--has');
            num.textContent = d.getDate();

            var dotClass = isActive ? 'vd-week-dot vd-week-dot--signup'
                         : isUnattended ? 'vd-week-dot vd-week-dot--unattended'
                         : 'vd-week-dot';
            var dots = document.createElement('div');
            dots.className = 'vd-week-dots';
            var dotCount = Math.min(evs.length || (isActive || isUnattended ? 1 : 0), 3);
            for (var j = 0; j < dotCount; j++) {
                var dot = document.createElement('div');
                dot.className = dotClass;
                dots.appendChild(dot);
            }

            col.appendChild(dow);
            col.appendChild(num);
            col.appendChild(dots);
            grid.appendChild(col);
        }
    }

    document.getElementById('vd-prev-week').addEventListener('click', function () {
        monday.setDate(monday.getDate() - 7);
        renderWeek();
    });
    document.getElementById('vd-next-week').addEventListener('click', function () {
        monday.setDate(monday.getDate() + 7);
        renderWeek();
    });

    renderWeek();
})();
</script>
</body>
