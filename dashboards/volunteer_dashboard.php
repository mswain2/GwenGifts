<?php
/* ── Data preparation ───────────────────────────────────────────────────── */
require_once('database/dbMessages.php');
require_once('database/dbEvents.php');
require_once('database/dbDiscussions.php');
require_once('database/dbTrainingMaterials.php');

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
$upcomingEvents  = array_slice($upcomingEvents, 0, 5);
$signupDatesJson = json_encode(array_values(array_unique($signupDates)), JSON_HEX_TAG);

/* Training materials */
$trainingPreview = array_slice(get_training_materials_by_user($uid) ?: [], 0, 4);

/* Recent discussions */
$allDiscussions = get_all_discussions() ?: [];
usort($allDiscussions, fn($a, $b) => strcmp($b['time'] ?? '', $a['time'] ?? ''));
$recentDiscussions = array_slice($allDiscussions, 0, 4);

/* Board documents (3 most recent accessible to volunteers) */
$boardDocsPreview = [];
$_dbconn = connect();
$_bdResult = mysqli_query($_dbconn, "SELECT doc_name, file_path, uploaded_at FROM boarddocuments WHERE deleted = 0 AND clearance_level = 'volunteer' ORDER BY uploaded_at DESC LIMIT 3");
if ($_bdResult) { while ($_row = mysqli_fetch_assoc($_bdResult)) { $boardDocsPreview[] = $_row; } }

/* Milestone calculations */
$_hourTiers  = [5, 10, 25, 50, 100, 250, 500, 1000];
$_eventTiers = [1, 5, 10, 25, 50, 100];

/* Profile stats */
$attendedRows        = get_events_attended_by($uid) ?: [];
$eventsAttendedCount = count($attendedRows);
$attendedDates       = array_values(array_unique(array_filter(array_column($attendedRows, 'startDate'))));
$attendedDatesJson   = json_encode($attendedDates, JSON_HEX_TAG);
$volunteerHours      = floatval($person->get_total_hours_volunteered());
$memberSince         = $person->get_start_date();
$profilePic          = $person->get_profile_pic() ?: 'images/usaicon.png';
$volEmail            = $person->get_email();
$volType             = ucfirst($person->get_type() ?: 'Volunteer');
?>

<body>
<a class="vd-skip" href="#vd-main-content">Skip to main content</a>

<?php require 'header.php'; ?>
<?php require 'partials/toasts.php'; ?>

<!-- ── HERO ──────────────────────────────────────────────────────────────── -->
<section class="vd-hero" aria-label="Dashboard header">
    <div class="vd-hero-left">
        <p class="vd-greeting" aria-hidden="true"><?php echo htmlspecialchars($greeting); ?></p>
        <h1 class="vd-hero-name"><?php echo htmlspecialchars($person->get_first_name()); ?> <span class="vd-hero-username">(<?php echo htmlspecialchars($uid); ?>)</span> — <?php echo htmlspecialchars($volType); ?></h1>
        <p class="vd-hero-date"><?php echo date('l, F j, Y'); ?></p>
        <div class="vd-hero-bar" aria-hidden="true"></div>
    </div>
    <div class="vd-hero-right" aria-hidden="true">
        <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="Your profile picture" class="vd-hero-avatar" onerror="this.onerror=null;this.src='images/usaicon.png'">
        <img src="images/gwenythsGiftLogo.png" alt="Gwyneth's Gift" class="vd-hero-logo" onerror="this.onerror=null;this.style.display='none'">
    </div>
</section>

<!-- ── STATS BAR ─────────────────────────────────────────────────────────── -->
<nav class="vd-stats" aria-label="Your activity summary">
    <a class="vd-stat" href="viewProfile.php" aria-label="<?php echo $volunteerHours; ?> volunteer hours">
        <span class="vd-stat-val"><?php echo number_format($volunteerHours, 1); ?></span>
        <span class="vd-stat-lbl">Hours Volunteered</span>
    </a>
    <a class="vd-stat" href="viewProfile.php" aria-label="<?php echo $eventsAttendedCount; ?> events attended">
        <span class="vd-stat-val"><?php echo $eventsAttendedCount; ?></span>
        <span class="vd-stat-lbl">Events Attended</span>
    </a>
    <a class="vd-stat" href="viewMyUpcomingEvents.php" aria-label="<?php echo count($upcomingEvents); ?> upcoming events">
        <span class="vd-stat-val"><?php echo count($upcomingEvents); ?></span>
        <span class="vd-stat-lbl">Upcoming Events</span>
    </a>
    <a class="vd-stat" href="inbox.php" aria-label="<?php echo $unreadCount; ?> unread messages">
        <span class="vd-stat-val"><?php echo $unreadCount; ?></span>
        <span class="vd-stat-lbl">Unread Messages</span>
    </a>
</nav>

<!-- ── QUICK NAV ─────────────────────────────────────────────────────────── -->
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
    <a class="vd-qn-card" href="inbox.php" aria-label="Inbox<?php echo $unreadCount > 0 ? ", $unreadCount unread" : ''; ?>">
        <img class="vd-qn-icon" src="images/<?php echo $unreadCount > 0 ? 'inbox-unread.svg' : 'inbox.svg'; ?>" alt="" aria-hidden="true">
        <span class="vd-qn-label">Inbox</span>
        <?php if ($unreadCount > 0): ?>
            <span class="vd-qn-badge" aria-hidden="true"><?php echo $unreadCount; ?></span>
        <?php endif; ?>
    </a>
    <a class="vd-qn-card" href="boardDocuments.php" aria-label="Documents">
        <img class="vd-qn-icon" src="images/file-regular.svg" alt="" aria-hidden="true">
        <span class="vd-qn-label">Documents</span>
    </a>
    <a class="vd-qn-card" href="discussionMain.php" aria-label="Discussions">
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
                <a href="editProfile.php" class="vd-panel-action" aria-label="Edit your profile">Edit →</a>
            </div>
            <div class="vd-panel-body">
                <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="Your profile picture" class="vd-prof-avatar" onerror="this.onerror=null;this.src='images/usaicon.png'">
                <p class="vd-prof-name"><?php echo htmlspecialchars($person->get_first_name() . ' ' . $person->get_last_name()); ?></p>
                <p class="vd-prof-role"><?php echo htmlspecialchars($volType); ?></p>
                <hr class="vd-prof-divider">
                <div class="vd-meta-row">
                    <span class="vd-meta-key">Username</span>
                    <span class="vd-meta-val"><?php echo htmlspecialchars($uid); ?></span>
                </div>
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
                    <a href="changePassword.php" class="vd-btn vd-btn--outline">Change Password</a>
                </div>
            </div>
        </div>

        <!-- Training Materials -->
        <div class="vd-panel" role="region" aria-labelledby="vd-training-heading">
            <div class="vd-panel-header">
                <span class="vd-panel-title" id="vd-training-heading">Training Docs</span>
                <a href="myTrainingMaterials.php" class="vd-panel-action">View all →</a>
            </div>
            <div class="vd-panel-body">
                <?php if (empty($trainingPreview)): ?>
                    <p class="vd-empty-sm">No training documents assigned yet.</p>
                <?php else: ?>
                    <?php foreach ($trainingPreview as $doc): ?>
                        <div class="vd-train-item">
                            <img class="vd-train-icon" src="images/clipboard-regular.svg" alt="" aria-hidden="true">
                            <div>
                                <div class="vd-train-name"><?php echo htmlspecialchars($doc['title'] ?? $doc['name'] ?? 'Document'); ?></div>
                                <?php if (!empty($doc['event_name'])): ?>
                                    <div class="vd-train-event"><?php echo htmlspecialchars($doc['event_name']); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Board Documents Preview -->
        <div class="vd-panel" role="region" aria-labelledby="vd-docs-heading">
            <div class="vd-panel-header">
                <span class="vd-panel-title" id="vd-docs-heading">Documents</span>
                <a href="boardDocuments.php" class="vd-panel-action">View all →</a>
            </div>
            <div class="vd-panel-body">
                <?php if (empty($boardDocsPreview)): ?>
                    <p class="vd-empty-sm">No documents available yet.</p>
                <?php else: ?>
                    <?php foreach ($boardDocsPreview as $doc): ?>
                        <a href="<?php echo htmlspecialchars($doc['file_path']); ?>" target="_blank" style="text-decoration:none;">
                            <div class="vd-doc-item">
                                <img class="vd-doc-icon" src="images/file-regular.svg" alt="" aria-hidden="true">
                                <span class="vd-doc-name"><?php echo htmlspecialchars($doc['doc_name']); ?></span>
                                <span class="vd-doc-date"><?php echo htmlspecialchars(date('M j', strtotime($doc['uploaded_at']))); ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
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
                <span class="vd-panel-title" id="vd-inbox-heading">Inbox<?php echo $unreadCount > 0 ? " ($unreadCount)" : ''; ?></span>
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
                <a href="discussionMain.php" class="vd-panel-action">View all →</a>
            </div>
            <div class="vd-panel-body">
                <?php if (empty($recentDiscussions)): ?>
                    <p class="vd-empty-sm">No discussions yet.</p>
                <?php else: ?>
                    <?php foreach ($recentDiscussions as $disc): ?>
                        <a href="discussionMain.php" style="text-decoration:none;">
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

        <!-- Volunteer Milestones -->
        <div class="vd-panel" style="flex:1;" role="region" aria-labelledby="vd-milestone-heading">
            <div class="vd-panel-header">
                <span class="vd-panel-title" id="vd-milestone-heading">Milestones</span>
            </div>
            <div class="vd-panel-body">
                <?php
                $prevHourTier = 0; $nextHourTier = null;
                foreach ($_hourTiers as $_t) {
                    if ($volunteerHours >= $_t) { $prevHourTier = $_t; }
                    elseif ($nextHourTier === null) { $nextHourTier = $_t; }
                }
                $hourPct = $nextHourTier
                    ? min(100, round(($volunteerHours - $prevHourTier) / ($nextHourTier - $prevHourTier) * 100))
                    : 100;
                $prevEventTier = 0; $nextEventTier = null;
                foreach ($_eventTiers as $_t) {
                    if ($eventsAttendedCount >= $_t) { $prevEventTier = $_t; }
                    elseif ($nextEventTier === null) { $nextEventTier = $_t; }
                }
                $eventPct = $nextEventTier
                    ? min(100, round(($eventsAttendedCount - $prevEventTier) / ($nextEventTier - $prevEventTier) * 100))
                    : 100;
                ?>
                <div class="vd-ms-section">Hours Volunteered</div>
                <div class="vd-ms-tier">
                    <?php echo number_format($volunteerHours, 1); ?> hrs
                    <?php if ($prevHourTier > 0): ?>&nbsp;·&nbsp;<?php echo $prevHourTier; ?>hr Club<?php endif; ?>
                </div>
                <?php if ($nextHourTier): ?>
                    <div class="vd-ms-next"><?php echo $nextHourTier - $volunteerHours; ?> hrs to <?php echo $nextHourTier; ?>hr Club</div>
                    <div class="vd-ms-bar-wrap" role="progressbar" aria-valuenow="<?php echo $hourPct; ?>" aria-valuemin="0" aria-valuemax="100">
                        <div class="vd-ms-bar" style="width:<?php echo $hourPct; ?>%"></div>
                    </div>
                <?php else: ?>
                    <div class="vd-ms-next">All hour milestones unlocked!</div>
                    <div class="vd-ms-bar-wrap"><div class="vd-ms-bar" style="width:100%"></div></div>
                <?php endif; ?>
                <div class="vd-ms-badges">
                    <?php foreach ($_hourTiers as $_t): ?>
                        <span class="vd-ms-badge <?php echo $volunteerHours >= $_t ? 'vd-ms-badge--done' : ''; ?>"><?php echo $_t; ?>hr</span>
                    <?php endforeach; ?>
                </div>
                <div class="vd-ms-section">Events Attended</div>
                <div class="vd-ms-tier">
                    <?php echo $eventsAttendedCount; ?> event<?php echo $eventsAttendedCount !== 1 ? 's' : ''; ?>
                    <?php if ($prevEventTier > 0): ?>&nbsp;·&nbsp;<?php echo $prevEventTier; ?>-Event Club<?php endif; ?>
                </div>
                <?php if ($nextEventTier): ?>
                    <div class="vd-ms-next"><?php echo $nextEventTier - $eventsAttendedCount; ?> more to <?php echo $nextEventTier; ?>-Event Club</div>
                    <div class="vd-ms-bar-wrap" role="progressbar" aria-valuenow="<?php echo $eventPct; ?>" aria-valuemin="0" aria-valuemax="100">
                        <div class="vd-ms-bar" style="width:<?php echo $eventPct; ?>%"></div>
                    </div>
                <?php else: ?>
                    <div class="vd-ms-next">All event milestones unlocked!</div>
                    <div class="vd-ms-bar-wrap"><div class="vd-ms-bar" style="width:100%"></div></div>
                <?php endif; ?>
                <div class="vd-ms-badges">
                    <?php foreach ($_eventTiers as $_t): ?>
                        <span class="vd-ms-badge <?php echo $eventsAttendedCount >= $_t ? 'vd-ms-badge--done' : ''; ?>"><?php echo $_t; ?></span>
                    <?php endforeach; ?>
                </div>
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
            var isUnattended = isSignup && isPast && !isAttended;
            var isActive     = isAttended || (isSignup && !isUnattended);

            if (isToday) {
                num.classList.add('vd-week-day--today');
                num.setAttribute('aria-current', 'date');
            }
            if (isActive)      num.classList.add('vd-week-day--signup');
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
