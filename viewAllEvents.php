<?php
    // Browse Events — Card / Grid / List views with filtering, sorting, pagination.
    session_cache_expire(30);
    session_start();

    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    $userType = 'guest';
    $isBoardMember = false;
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        $accessLevel = $_SESSION['access_level'];
        $userID = $_SESSION['_id'];
    }

    require_once('database/dbEvents.php');
    require_once('database/dbPersons.php');
    require_once('include/output.php');

    if ($loggedIn && $userID !== 'guest') {
        if ($userID === 'vmsroot') {
            $userType = 'superadmin';
            $isBoardMember = true;
        } else {
            $personObj = retrieve_person($userID);
            if ($personObj) {
                $userType = $personObj->get_type();
                $isBoardMember = in_array($userType, ['board_member', 'admin', 'superadmin']);
            }
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <?php require_once('universal.inc') ?>
    <link rel="stylesheet" href="css/viewAllEvents.css">
    <title>Gwyneth's Gift | Browse Events</title>
</head>
<body>
    <?php require_once('header.php') ?>

    <h1>Browse Events</h1>

    <main class="browse-main">
    <?php
        $allEvents = get_all_events_sorted_by_date_not_archived();
        $archivedEvents = get_all_events_sorted_by_date_and_archived();

        $today = new DateTime();
        $today->setTime(0, 0, 0);

        $upcomingEvents = [];
        $boardEvents    = [];

        foreach ($allEvents as $event) {
            $eventDate = new DateTime($event->getStartDate());
            if ($eventDate < $today) continue;
            $evtType = $event->getEventType();
            if ($evtType === 'Board') {
                $boardEvents[] = $event;
            } else {
                $upcomingEvents[] = $event;
            }
        }

        // Collect unique locations (case-insensitive)
        $locationMap = [];
        foreach (array_merge($upcomingEvents, $boardEvents, $archivedEvents) as $evt) {
            $loc = trim($evt->getLocation() ?? '');
            if ($loc !== '') {
                $key = strtolower($loc);
                if (!isset($locationMap[$key])) $locationMap[$key] = $loc;
            }
        }
        ksort($locationMap);
        $locations = array_values($locationMap);

        // Batch-fetch signup data (2 queries total instead of 2 per event)
        $allSignupCounts = fetch_all_signup_counts();
        $userSignups = ($loggedIn && $userID) ? fetch_user_signups($userID) : [];

        // Prepare event data
        function prepareEventData($event, $loggedIn, $userID, $allSignupCounts, $userSignups) {
            $eventID   = $event->getID();
            $title     = $event->getName();
            $startDate = $event->getStartDate();
            $startTime = $event->getStartTime();
            $endTime   = $event->getEndTime();
            $capacity  = (int) $event->getCapacity();
            $location  = $event->getLocation() ?? '';
            $access    = $event->getAccess();
            $type      = $event->getEventType();
            $desc      = $event->getDescription() ?? '';

            $numSignups     = $allSignupCounts[$eventID] ?? 0;
            $slotsRemaining = max(0, $capacity - $numSignups);
            $isSignedUp     = $loggedIn ? isset($userSignups[$eventID]) : false;

            $dateObj       = new DateTime($startDate);
            $dayNum        = $dateObj->format('d');
            $monthShort    = strtoupper($dateObj->format('M'));
            $yearShort     = $dateObj->format('Y');
            $formattedDate = $dateObj->format('M j, Y');
            $dateShort     = $dateObj->format('M j');
            $timeStart     = time24hTo12h($startTime);
            $timeEnd       = time24hTo12h($endTime);
            $timeDisplay   = $timeStart . ' - ' . $timeEnd;

            $pctFull = $capacity > 0 ? round(($numSignups / $capacity) * 100) : 100;

            $shortDesc = mb_strlen($desc) > 120 ? mb_substr($desc, 0, 120) . '...' : $desc;
            $listDesc  = mb_strlen($desc) > 60  ? mb_substr($desc, 0, 60) . '...'  : $desc;

            return [
                'eventID'        => $eventID,
                'title'          => $title,
                'startDate'      => $startDate,
                'startTime'      => $startTime,
                'endTime'        => $endTime,
                'capacity'       => $capacity,
                'location'       => $location,
                'access'         => $access,
                'type'           => $type,
                'desc'           => $desc,
                'numSignups'     => $numSignups,
                'slotsRemaining' => $slotsRemaining,
                'isSignedUp'     => $isSignedUp,
                'dayNum'         => $dayNum,
                'monthShort'     => $monthShort,
                'yearShort'      => $yearShort,
                'formattedDate'  => $formattedDate,
                'dateShort'      => $dateShort,
                'timeStart'      => $timeStart,
                'timeEnd'        => $timeEnd,
                'timeDisplay'    => $timeDisplay,
                'pctFull'        => $pctFull,
                'shortDesc'      => $shortDesc,
                'listDesc'       => $listDesc,
            ];
        }

        $upcomingData = [];
        foreach ($upcomingEvents as $evt) {
            $upcomingData[] = prepareEventData($evt, $loggedIn, $userID, $allSignupCounts, $userSignups);
        }
        $boardData = [];
        foreach ($boardEvents as $evt) {
            $boardData[] = prepareEventData($evt, $loggedIn, $userID, $allSignupCounts, $userSignups);
        }
        $archivedData = [];
        foreach ($archivedEvents as $evt) {
            $archivedData[] = prepareEventData($evt, $loggedIn, $userID, $allSignupCounts, $userSignups);
        }
        $totalCount = count($upcomingData) + count($boardData);

        // Common data attributes for filtering
        function dataAttrs($d) {
            return ' data-name="' . htmlspecialchars(strtolower($d['title'])) . '"'
                 . ' data-start-date="' . htmlspecialchars($d['startDate']) . '"'
                 . ' data-location="' . htmlspecialchars(strtolower($d['location'])) . '"'
                 . ' data-type="' . htmlspecialchars($d['type']) . '"'
                 . ' data-start-time="' . htmlspecialchars($d['startTime']) . '"'
                 . ' data-slots-remaining="' . $d['slotsRemaining'] . '"'
                 . ' data-desc="' . htmlspecialchars(strtolower($d['desc'])) . '"';
        }

        // Action button / link
        function actionBtn($d, $loggedIn, $userID, $style = 'full') {
            $access = $d['access'];
            $viewBtn = '<a class="act-btn act-view" href="event.php?id=' . urlencode($d['eventID']) . '">View Event</a>';

            if (!$loggedIn || $userID === 'guest') {
                return '<a class="act-btn act-login" href="login.php">Login to Sign Up</a>' . $viewBtn;
            }
            if ($d['isSignedUp']) {
                return '<a class="act-btn act-signedup" href="event.php?id=' . urlencode($d['eventID']) . '">Signed Up</a>' . $viewBtn;
            }
            if ($d['numSignups'] >= $d['capacity']) {
                return '<a class="act-btn act-waitlist" href="eventSignUp.php?event_name=' . urlencode($d['title']) . '&restricted=' . urlencode($access) . '&id=' . urlencode($d['eventID']) . '&waitlist=1">Join Waitlist</a>' . $viewBtn;
            }
            return '<a class="act-btn act-register" href="eventSignUp.php?event_name=' . urlencode($d['title']) . '&restricted=' . urlencode($access) . '&id=' . urlencode($d['eventID']) . '">Sign Up</a>' . $viewBtn;
        }

        // Capacity ring SVG (used in card view)
        function capacityRing($d) {
            $pct = $d['pctFull'];
            $num = $d['numSignups'];
            $cap = $d['capacity'];
            $slots = $d['slotsRemaining'];
            $radius = 30;
            $circumference = 2 * M_PI * $radius;
            $offset = $circumference - ($circumference * min($pct, 100) / 100);
            $colorClass = $pct >= 100 ? 'ring-full' : ($pct >= 80 ? 'ring-limited' : 'ring-available');
            $html  = '<div class="cap-ring ' . $colorClass . '">';
            $html .= '<svg width="72" height="72" viewBox="0 0 72 72">';
            $html .= '<circle cx="36" cy="36" r="' . $radius . '" class="ring-bg"/>';
            $html .= '<circle cx="36" cy="36" r="' . $radius . '" class="ring-fill" style="stroke-dasharray:' . round($circumference, 1) . ';stroke-dashoffset:' . round($offset, 1) . '"/>';
            $html .= '</svg>';
            $html .= '<span class="ring-label">' . $slots . '</span>';
            $html .= '<span class="ring-sublabel">' . ($slots === 1 ? 'slot left' : 'slots left') . '</span>';
            $html .= '</div>';
            return $html;
        }

        // Capacity progress bar (used in grid & list views)
        function capacityBar($d, $showSlots = true) {
            $pct = $d['pctFull'];
            $num = $d['numSignups'];
            $cap = $d['capacity'];
            $slots = $d['slotsRemaining'];
            $colorClass = $pct >= 100 ? 'bar-full' : ($pct >= 80 ? 'bar-limited' : 'bar-available');
            $html  = '<div class="cap-bar-wrap">';
            if ($showSlots) {
                if ($pct >= 100) {
                    $html .= '<span class="cap-slots cap-slots-full">Full</span>';
                } else {
                    $html .= '<span class="cap-slots ' . $colorClass . '">' . $slots . ' ' . ($slots === 1 ? 'slot' : 'slots') . ' left</span>';
                }
            }
            $html .= '<div class="cap-bar"><div class="cap-bar-fill ' . $colorClass . '" style="width:' . min($pct, 100) . '%"></div></div>';
            $html .= '</div>';
            return $html;
        }

        // Type badge
        function typeBadge($type) {
            $lc = strtolower($type);
            $label = $type === 'Normal' ? 'Volunteer' : htmlspecialchars($type);
            return '<span class="type-badge tb-' . $lc . '">' . $label . '</span>';
        }

        // Access badge
        function accessBadge($access) {
            $lc = strtolower($access);
            if ($lc === 'private') {
                $icon = '<svg class="badge-icon" viewBox="0 0 16 16" width="12" height="12"><path fill="currentColor" d="M4 7V5a4 4 0 118 0v2h1a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1V8a1 1 0 011-1h1zm2-2a2 2 0 114 0v2H6V5z"/></svg>';
            } else {
                $icon = '<svg class="badge-icon" viewBox="0 0 16 16" width="12" height="12"><circle cx="8" cy="8" r="7" fill="currentColor"/><path fill="white" d="M8 2a6 6 0 100 12A6 6 0 008 2zM4.5 6.5L7 9l4.5-4.5" stroke="white" fill="none" stroke-width="1.5"/></svg>';
            }
            return '<span class="access-badge ab-' . $lc . '">' . $icon . ' ' . htmlspecialchars($access) . '</span>';
        }

        // Almost-full badge
        function almostFullBadge($d) {
            if ($d['pctFull'] >= 80 && $d['pctFull'] < 100) {
                return '<span class="almost-full-badge">Almost Full</span>';
            }
            if ($d['pctFull'] >= 100) {
                return '<span class="event-full-badge">Event Full</span>';
            }
            return '';
        }

        // Render functions for each view

        // ── CARD VIEW (full-width horizontal cards) ──
        function renderCard($d, $loggedIn, $userID) {
            $html  = '<div class="ev-card event-item"' . dataAttrs($d) . '>';

            // Left: big date
            $html .= '<div class="ev-card-date">';
            $html .= '<span class="ev-date-day">' . $d['dayNum'] . '</span>';
            $html .= '<span class="ev-date-month">' . $d['monthShort'] . '</span>';
            $html .= '<span class="ev-date-year">' . $d['yearShort'] . '</span>';
            $html .= '</div>';

            // Center: info
            $html .= '<div class="ev-card-body">';
            $html .= '<div class="ev-card-badges">';
            $html .= typeBadge($d['type']);
            $html .= '</div>';
            $html .= '<h3 class="ev-card-title"><a href="event.php?id=' . urlencode($d['eventID']) . '">' . htmlspecialchars($d['title']) . '</a></h3>';
            if ($d['shortDesc'] !== '') {
                $html .= '<p class="ev-card-desc">' . htmlspecialchars($d['shortDesc']) . '</p>';
            }
            $html .= '<div class="ev-card-meta">';
            $html .= '<span class="meta-i"><svg class="m-icon" viewBox="0 0 16 16" width="13" height="13"><rect x="1" y="3" width="14" height="12" rx="2" fill="none" stroke="currentColor" stroke-width="1.3"/><line x1="1" y1="7" x2="15" y2="7" stroke="currentColor" stroke-width="1.3"/><line x1="5" y1="1" x2="5" y2="4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/><line x1="11" y1="1" x2="11" y2="4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg> ' . htmlspecialchars($d['formattedDate']) . '</span>';
            $html .= '<span class="meta-i"><svg class="m-icon" viewBox="0 0 16 16" width="13" height="13"><circle cx="8" cy="8" r="7" fill="none" stroke="currentColor" stroke-width="1.3"/><polyline points="8,4 8,8 11,10" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg> ' . $d['timeDisplay'] . '</span>';
            if ($d['location'] !== '') {
                $html .= '<span class="meta-i"><svg class="m-icon" viewBox="0 0 16 16" width="13" height="13"><path d="M8 1C5.2 1 3 3.2 3 6c0 4 5 9 5 9s5-5 5-9c0-2.8-2.2-5-5-5zm0 7a2 2 0 110-4 2 2 0 010 4z" fill="none" stroke="currentColor" stroke-width="1.3"/></svg> ' . htmlspecialchars($d['location']) . '</span>';
            }
            $html .= '</div>';
            $html .= almostFullBadge($d);
            $html .= '</div>'; // ev-card-body

            // Right: capacity ring
            $html .= '<div class="ev-card-right">';
            $html .= capacityRing($d);
            $html .= '</div>';

            // Action
            $html .= '<div class="ev-card-action">';
            $html .= actionBtn($d, $loggedIn, $userID, 'full');
            $html .= '</div>';

            $html .= '</div>';
            return $html;
        }

        // ── GRID VIEW (3-column cards) ──
        function renderGridCard($d, $loggedIn, $userID) {
            $statusClass = $d['isSignedUp'] ? 'grid-signed' : 'grid-default';
            $typeClass = strtolower($d['type']);

            $html  = '<div class="ev-grid-card event-item ' . $statusClass . ' gc-' . $typeClass . '"' . dataAttrs($d) . '>';

            // Header row: badge + icons
            $html .= '<div class="gc-header">';
            $html .= '<div class="gc-badges">';
            $html .= typeBadge($d['type']);
            $html .= '</div>';
            $html .= '</div>';

            // Title + description
            $html .= '<h3 class="gc-title"><a href="event.php?id=' . urlencode($d['eventID']) . '">' . htmlspecialchars($d['title']) . '</a></h3>';
            if ($d['shortDesc'] !== '') {
                $html .= '<p class="gc-desc">' . htmlspecialchars($d['shortDesc']) . '</p>';
            }

            // Meta
            $html .= '<div class="gc-meta">';
            $html .= '<span class="meta-i"><svg class="m-icon" viewBox="0 0 16 16" width="13" height="13"><rect x="1" y="3" width="14" height="12" rx="2" fill="none" stroke="currentColor" stroke-width="1.3"/><line x1="1" y1="7" x2="15" y2="7" stroke="currentColor" stroke-width="1.3"/><line x1="5" y1="1" x2="5" y2="4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/><line x1="11" y1="1" x2="11" y2="4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg> ' . htmlspecialchars($d['dateShort']) . '</span>';
            $html .= '<span class="meta-i"><svg class="m-icon" viewBox="0 0 16 16" width="13" height="13"><circle cx="8" cy="8" r="7" fill="none" stroke="currentColor" stroke-width="1.3"/><polyline points="8,4 8,8 11,10" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg> ' . $d['timeDisplay'] . '</span>';
            if ($d['location'] !== '') {
                $html .= '<span class="meta-i"><svg class="m-icon" viewBox="0 0 16 16" width="13" height="13"><path d="M8 1C5.2 1 3 3.2 3 6c0 4 5 9 5 9s5-5 5-9c0-2.8-2.2-5-5-5zm0 7a2 2 0 110-4 2 2 0 010 4z" fill="none" stroke="currentColor" stroke-width="1.3"/></svg> ' . htmlspecialchars($d['location']) . '</span>';
            }
            $html .= '</div>';

            // Capacity bar
            $html .= capacityBar($d);

            // Action
            $html .= '<div class="gc-action">';
            $html .= actionBtn($d, $loggedIn, $userID, 'full');
            $html .= '</div>';

            $html .= '</div>';
            return $html;
        }

        // ── LIST VIEW (compact rows) ──
        function renderListRow($d, $loggedIn, $userID) {
            $statusClass = $d['isSignedUp'] ? 'lr-signed' : 'lr-default';
            $typeClass = strtolower($d['type']);

            $html  = '<div class="ev-list-row event-item ' . $statusClass . ' lr-' . $typeClass . '"' . dataAttrs($d) . '>';

            // Event info
            $html .= '<div class="lr-info">';
            $html .= '<div class="lr-badges">' . typeBadge($d['type']) . '</div>';
            $html .= '<div class="lr-title-wrap">';
            $html .= '<a class="lr-title" href="event.php?id=' . urlencode($d['eventID']) . '">' . htmlspecialchars($d['title']) . '</a>';
            if ($d['listDesc'] !== '') {
                $html .= '<span class="lr-desc">' . htmlspecialchars($d['listDesc']) . '</span>';
            }
            $html .= '</div>';
            $html .= '</div>';

            // Date & Time
            $html .= '<div class="lr-datetime">';
            $html .= '<span class="meta-i"><svg class="m-icon" viewBox="0 0 16 16" width="13" height="13"><rect x="1" y="3" width="14" height="12" rx="2" fill="none" stroke="currentColor" stroke-width="1.3"/><line x1="1" y1="7" x2="15" y2="7" stroke="currentColor" stroke-width="1.3"/><line x1="5" y1="1" x2="5" y2="4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/><line x1="11" y1="1" x2="11" y2="4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg> ' . htmlspecialchars($d['dateShort']) . '</span>';
            $html .= '<span class="meta-i"><svg class="m-icon" viewBox="0 0 16 16" width="13" height="13"><circle cx="8" cy="8" r="7" fill="none" stroke="currentColor" stroke-width="1.3"/><polyline points="8,4 8,8 11,10" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg> ' . $d['timeDisplay'] . '</span>';
            $html .= '</div>';

            // Location
            $html .= '<div class="lr-location">';
            if ($d['location'] !== '') {
                $html .= '<span class="meta-i"><svg class="m-icon" viewBox="0 0 16 16" width="13" height="13"><path d="M8 1C5.2 1 3 3.2 3 6c0 4 5 9 5 9s5-5 5-9c0-2.8-2.2-5-5-5zm0 7a2 2 0 110-4 2 2 0 010 4z" fill="none" stroke="currentColor" stroke-width="1.3"/></svg> ' . htmlspecialchars($d['location']) . '</span>';
            } else {
                $html .= '<span class="meta-i">&mdash;</span>';
            }
            $html .= '</div>';

            // Availability
            $html .= '<div class="lr-avail">';
            $html .= capacityBar($d);
            $html .= '</div>';

            // Action
            $html .= '<div class="lr-action">';
            $html .= actionBtn($d, $loggedIn, $userID, 'full');
            $html .= '</div>';

            $html .= '</div>';
            return $html;
        }

        // Render all events for a section
        function renderSection($data, $loggedIn, $userID, $id) {
            if (count($data) === 0) return;

            // Card view
            echo '<div class="ev-cards-wrap view-card" id="' . $id . '-cards">';
            foreach ($data as $d) echo renderCard($d, $loggedIn, $userID);
            echo '</div>';

            // Grid view
            echo '<div class="ev-grid-wrap view-grid hidden" id="' . $id . '-grid">';
            foreach ($data as $d) echo renderGridCard($d, $loggedIn, $userID);
            echo '</div>';

            // List view
            echo '<div class="ev-list-wrap view-list hidden" id="' . $id . '-list">';
            echo '<div class="lr-header">';
            echo '<div class="lr-info">Event</div>';
            echo '<div class="lr-datetime">Date &amp; Time</div>';
            echo '<div class="lr-location">Location</div>';
            echo '<div class="lr-avail">Availability</div>';
            echo '<div class="lr-action">Action</div>';
            echo '</div>';
            foreach ($data as $d) echo renderListRow($d, $loggedIn, $userID);
            echo '</div>';
        }
    ?>

        <!-- ===== Toolbar: Search + Filters + View Toggle + Sort ===== -->
        <div class="toolbar">
            <div class="toolbar-top">
                <div class="search-box">
                    <span class="search-icon">&#128269;</span>
                    <input type="text" id="search-name" placeholder="Search events by name, description, or location...">
                </div>
                <div class="view-toggle" id="view-toggle">
                    <button type="button" class="vt-btn active" data-view="card" title="Card View">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="7" rx="1"/><rect x="3" y="14" width="18" height="7" rx="1"/></svg>
                        Card
                    </button>
                    <button type="button" class="vt-btn" data-view="grid" title="Grid View">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                        Grid
                    </button>
                    <button type="button" class="vt-btn" data-view="list" title="List View">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                        List
                    </button>
                </div>
            </div>

            <div class="toolbar-filters">
                <div class="filter-pills">
                    <select id="filter-tab" class="pill-select">
                        <option value="upcoming">All</option>
                        <option value="normal">Normal</option>
                        <option value="past">Past</option>
                        <?php if ($isBoardMember): ?>
                            <option value="board">Board</option>
                        <?php endif; ?>
                        <?php if ($accessLevel >= 2): ?>
                            <option value="archived">Archived</option>
                        <?php endif; ?>
                    </select>
                    <select id="filter-location" class="pill-select">
                        <option value="">All</option>
                        <?php foreach ($locations as $loc): ?>
                            <option value="<?php echo htmlspecialchars(strtolower($loc)); ?>"><?php echo htmlspecialchars($loc); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select id="filter-time" class="pill-select">
                        <option value="">Any Time</option>
                        <option value="morning">Morning</option>
                        <option value="afternoon">Afternoon</option>
                        <option value="evening">Evening</option>
                    </select>
                    <button type="button" id="more-filters-btn" class="pill-btn">&#9881; More Filters</button>
                </div>
                <div class="sort-wrap">
                    <select id="sort-by" class="pill-select sort-select">
                        <option value="date-asc">Start Time (Ascending)</option>
                        <option value="date-desc">Start Time (Descending)</option>
                        <option value="slots-desc">Available Slots (Most)</option>
                        <option value="slots-asc">Available Slots (Fewest)</option>
                        <option value="name-asc">Name (A &ndash; Z)</option>
                        <option value="name-desc">Name (Z &ndash; A)</option>
                    </select>
                </div>
            </div>

            <!-- Extra filters panel (hidden by default) -->
            <div class="extra-filters hidden" id="extra-filters">
                <div class="ef-row">
                    <div class="ef-group">
                        <label for="filter-date-from">From Date</label>
                        <input type="date" id="filter-date-from">
                    </div>
                    <div class="ef-group">
                        <label for="filter-date-to">To Date</label>
                        <input type="date" id="filter-date-to">
                    </div>
                    <button type="button" id="clear-filters" class="pill-btn clear-btn">Clear All Filters</button>
                </div>
            </div>
        </div>

        <!-- Results count -->
        <div class="results-bar">
            <span id="results-count">Showing <?php echo $totalCount; ?> events</span>
        </div>

        <!-- ===== Event Sections ===== -->
        <div id="upcoming-section" class="events-section">
            <?php if (count($upcomingData) > 0): ?>
                <?php renderSection($upcomingData, $loggedIn, $userID, 'upcoming'); ?>
            <?php else: ?>
                <p class="no-events-msg">No upcoming events available.</p>
            <?php endif; ?>
            <p class="no-results hidden" id="no-upcoming">No upcoming events match your filters.</p>
        </div>

        <?php if ($isBoardMember): ?>
        <div id="board-section" class="events-section hidden">
            <?php if (count($boardData) > 0): ?>
                <?php renderSection($boardData, $loggedIn, $userID, 'board'); ?>
            <?php else: ?>
                <p class="no-events-msg">No board events scheduled.</p>
            <?php endif; ?>
            <p class="no-results hidden" id="no-board">No board events match your filters.</p>
        </div>
        <?php endif; ?>

        <?php if ($accessLevel >= 2): ?>
        <div id="archived-section" class="events-section hidden">
            <?php if (count($archivedData) > 0): ?>
                <?php renderSection($archivedData, $loggedIn, $userID, 'archived'); ?>
            <?php else: ?>
                <p class="no-events-msg">No archived events to display.</p>
            <?php endif; ?>
            <p class="no-results hidden" id="no-archived">No archived events match your filters.</p>
        </div>
        <?php endif; ?>

        <!-- Pagination -->
        <div class="pagination-bar">
            <span id="page-info"></span>
            <div class="pagination" id="pagination"></div>
        </div>

        <!-- Footer Actions -->
        <div class="page-actions">
            <a class="button" href="calendar.php">View Calendar</a>
            <?php if ($accessLevel >= 2): ?>
                <a class="button" href="addEvent.php">Create New Event</a>
            <?php endif; ?>
            <a class="button cancel" href="index.php">Return to Dashboard</a>
        </div>

    </main>
    <script src="js/viewAllEvents.js"></script>
</body>
</html>