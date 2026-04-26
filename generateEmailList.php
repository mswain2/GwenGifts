<?php
    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    $accessLevel = 0;
    if (isset($_SESSION['access_level'])) {
        $accessLevel = $_SESSION['access_level'];
    }
    // admin/event manager access
    if ($accessLevel < 2) {
        header('Location: index.php');
        die();
    }

    require_once 'database/dbEvents.php';
    require_once 'database/dbPersons.php';
    require_once 'domain/Event.php';
    require_once 'domain/Person.php';
    require_once 'include/input-validation.php';
    require_once 'include/output.php';

    // ---- Available filter options ----
    $allRoles = [
        'volunteer'     => 'Volunteer',
        'event_manager' => 'Event Manager',
        'board_member'  => 'Board Member',
        'admin'         => 'Administrator',
    ];
    $allStatuses = [
        'Active'   => 'Active',
        'Inactive' => 'Inactive',
    ];

    // All events (upcoming + archived) for the event autocomplete
    $eventsAll = array_merge(
        get_all_events_sorted_by_date_not_archived(),
        get_all_events_sorted_by_date_and_archived()
    );

    // All persons for name/email/username autocompletes
    $personsAll = array_merge(
        find_users('', '', '', '', '', 'Active', ''),
        find_users('', '', '', '', '', 'Inactive', '')
    );

    // ---- Parse selected filters from GET ----
    function splitCsv($raw) {
        if ($raw === null || $raw === '') return [];
        $parts = array_map('trim', explode(',', $raw));
        return array_values(array_filter($parts, fn($v) => $v !== ''));
    }

    $selRoles      = splitCsv($_GET['role']     ?? '');
    $selStatuses   = splitCsv($_GET['status']   ?? '');
    $selEvents     = splitCsv($_GET['event']    ?? '');
    $selEmails     = splitCsv($_GET['email']    ?? '');
    $selNames      = splitCsv($_GET['name']     ?? '');
    $selUsernames  = splitCsv($_GET['username'] ?? '');

    $anyFilter = $selRoles || $selStatuses || $selEvents || $selEmails || $selNames || $selUsernames;
    $searchRan = isset($_GET['search']);

    // Build id set from selected events (union of signups)
    $idsFromEvents = null; // null means "no event filter applied"
    if ($selEvents) {
        $idsFromEvents = [];
        foreach ($selEvents as $eid) {
            $signups = fetch_event_signups($eid);
            foreach ($signups as $row) {
                $idsFromEvents[$row['userID']] = true;
            }
        }
    }

    // ---- Apply filters in memory ----
    $results = [];
    if ($searchRan) {
        foreach ($personsAll as $p) {
            if ($selRoles     && !in_array($p->get_type(), $selRoles, true))       continue;
            if ($selStatuses  && !in_array($p->get_status(), $selStatuses, true))  continue;
            if ($selEmails    && !in_array($p->get_email(), $selEmails, true))     continue;
            if ($selUsernames && !in_array($p->get_id(), $selUsernames, true))     continue;
            if ($selNames) {
                $full = $p->get_first_name() . ' ' . $p->get_last_name();
                if (!in_array($full, $selNames, true)) continue;
            }
            if ($idsFromEvents !== null && !isset($idsFromEvents[$p->get_id()])) continue;
            $results[] = $p;
        }
    }

    // Compute the comma-separated mailing list for display below the submit button
    $emails = [];
    foreach ($results as $p) {
        $e = trim((string) $p->get_email());
        if ($e !== '') $emails[] = $e;
    }
    $mailingList = implode(', ', $emails);

    // Build label maps so chips can render with friendly text while the hidden input stores the filter value
    $eventLabels = [];
    foreach ($eventsAll as $ev) {
        $eid = (string) $ev->getID();
        $date = $ev->getStartDate() ? (new DateTime($ev->getStartDate()))->format('M d, Y') : '';
        $eventLabels[$eid] = $ev->getName() . ($date ? ' - ' . $date : '');
    }

    // Helper for selected-chip rendering (labels optional; falls back to value as label)
    function renderChips($values, $labelMap = null) {
        $html = '';
        foreach ($values as $v) {
            $label = ($labelMap && isset($labelMap[$v])) ? $labelMap[$v] : $v;
            $safeVal = htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
            $safeLabel = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
            $html .= '<span class="chip" data-value="' . $safeVal . '">'
                   . $safeLabel
                   . '<button type="button" class="chip-remove" aria-label="Remove">&times;</button></span>';
        }
        return $html;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Gwyneth's Gift | Generate Email List</title>
    <link href="css/normal_tw.css" rel="stylesheet">
    <script src="js/email-list-filters.js" defer></script>
<?php
$tailwind_mode = true;
require_once('header.php');
?>
</head>
<body>

<h1 style="color:white;">Generate Email List</h1>

<main>
    <div class="main-content-box w-[80%] p-8">

        <div class="text-center mb-8">
            <h2>Generate an email list</h2>
            <p class="sub-text">Filter users by role, status, event, name, or email. Select multiple values in any field.</p>
        </div>

        <?php if ($searchRan && !$anyFilter): ?>
            <div class="report-error">Select at least one filter.</div>
        <?php elseif ($searchRan && count($results) === 0): ?>
            <div class="report-error">No users matched your filters.</div>
        <?php endif; ?>

        <form id="email-list-form" method="get" class="space-y-6">
            <input type="hidden" name="search" value="1">

            <!-- Role (autocomplete multi-select) -->
            <div class="report-field">
                <label for="role-search">Role</label>
                <div class="multi-autocomplete" data-hidden="role-hidden">
                    <div class="chip-area"><?= renderChips($selRoles, $allRoles) ?></div>
                    <input type="text" id="role-search" class="multi-autocomplete-input" placeholder="Search or select a role..." autocomplete="off">
                    <div class="autocomplete-list multi-autocomplete-list">
                        <?php foreach ($allRoles as $value => $label): ?>
                            <div class="autocomplete-item" data-value="<?= htmlspecialchars($value) ?>" data-label="<?= htmlspecialchars($label) ?>"><?= htmlspecialchars($label) ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <input type="hidden" id="role-hidden" name="role" value="<?= htmlspecialchars(implode(',', $selRoles)) ?>">
            </div>

            <!-- Status (autocomplete multi-select) -->
            <div class="report-field">
                <label for="status-search">Status</label>
                <div class="multi-autocomplete" data-hidden="status-hidden">
                    <div class="chip-area"><?= renderChips($selStatuses, $allStatuses) ?></div>
                    <input type="text" id="status-search" class="multi-autocomplete-input" placeholder="Search or select a status..." autocomplete="off">
                    <div class="autocomplete-list multi-autocomplete-list">
                        <?php foreach ($allStatuses as $value => $label): ?>
                            <div class="autocomplete-item" data-value="<?= htmlspecialchars($value) ?>" data-label="<?= htmlspecialchars($label) ?>"><?= htmlspecialchars($label) ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <input type="hidden" id="status-hidden" name="status" value="<?= htmlspecialchars(implode(',', $selStatuses)) ?>">
            </div>

            <!-- Event (autocomplete multi-select) -->
            <div class="report-field">
                <label for="event-search">Event</label>
                <div class="multi-autocomplete" data-hidden="event-hidden">
                    <div class="chip-area"><?= renderChips($selEvents, $eventLabels) ?></div>
                    <input type="text" id="event-search" class="multi-autocomplete-input" placeholder="Search or select an event..." autocomplete="off">
                    <div class="autocomplete-list multi-autocomplete-list">
                        <?php foreach ($eventsAll as $ev):
                            $eid = (string) $ev->getID();
                            $label = $ev->getName();
                            $date = $ev->getStartDate() ? (new DateTime($ev->getStartDate()))->format('M d, Y') : '';
                            $display = $label . ($date ? ' - ' . $date : ''); ?>
                            <div class="autocomplete-item" data-value="<?= htmlspecialchars($eid) ?>" data-label="<?= htmlspecialchars($display) ?>"><?= htmlspecialchars($display) ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <input type="hidden" id="event-hidden" name="event" value="<?= htmlspecialchars(implode(',', $selEvents)) ?>">
            </div>

            <!-- Name (autocomplete multi-select) -->
            <div class="report-field">
                <label for="name-search">Name</label>
                <div class="multi-autocomplete" data-hidden="name-hidden">
                    <div class="chip-area"><?= renderChips($selNames) ?></div>
                    <input type="text" id="name-search" class="multi-autocomplete-input" placeholder="Search or select a name..." autocomplete="off">
                    <div class="autocomplete-list multi-autocomplete-list">
                        <?php
                            $seen = [];
                            foreach ($personsAll as $p) {
                                $full = trim($p->get_first_name() . ' ' . $p->get_last_name());
                                if (!$full || isset($seen[$full])) continue;
                                $seen[$full] = true;
                                $safe = htmlspecialchars($full);
                                echo "<div class='autocomplete-item' data-value='$safe' data-label='$safe'>$safe</div>";
                            }
                        ?>
                    </div>
                </div>
                <input type="hidden" id="name-hidden" name="name" value="<?= htmlspecialchars(implode(',', $selNames)) ?>">
            </div>

            <!-- Email (autocomplete multi-select) -->
            <div class="report-field">
                <label for="email-search">Email</label>
                <div class="multi-autocomplete" data-hidden="email-hidden">
                    <div class="chip-area"><?= renderChips($selEmails) ?></div>
                    <input type="text" id="email-search" class="multi-autocomplete-input" placeholder="Search or select an email..." autocomplete="off">
                    <div class="autocomplete-list multi-autocomplete-list">
                        <?php
                            $seen = [];
                            foreach ($personsAll as $p) {
                                $e = $p->get_email();
                                if (!$e || isset($seen[$e])) continue;
                                $seen[$e] = true;
                                $safe = htmlspecialchars($e);
                                echo "<div class='autocomplete-item' data-value='$safe' data-label='$safe'>$safe</div>";
                            }
                        ?>
                    </div>
                </div>
                <input type="hidden" id="email-hidden" name="email" value="<?= htmlspecialchars(implode(',', $selEmails)) ?>">
            </div>

            <div class="text-center pt-4">
                <input type="submit" value="Generate List" class="submit-button">
            </div>

            <?php if ($searchRan && $mailingList !== ''): ?>
                <div class="mailing-list-block">
                    <label>Result Mailing List (<?= count($emails) ?>)</label>
                    <p class="mailing-list-text"><?= htmlspecialchars($mailingList) ?></p>
                </div>
            <?php endif; ?>
        </form>
    </div>

        <a href="index.php" class="btn btn-back">Return to Dashboard</a>

</main>

</body>
</html>
