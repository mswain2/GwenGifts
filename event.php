<?php

session_cache_expire(30);
session_start();

// check RBAC
if (isset($_SESSION['access_level']) && $_SESSION['access_level'] >= 2) {
    $isEventManager = true;
} else {
    $isEventManager = false;
}

if (isset($_SESSION['access_level']) && $_SESSION['access_level'] >= 1) {
    $isVolunteer = true;
} else {
    header('Location: index.php');
    die();
}

// Ensure user is logged in
// if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 1) {
//     header('Location: login.php');
//     die();
// }

require_once('include/input-validation.php');
$args = sanitize($_GET);
$displayUpdateMessage = false;
if (isset($args["id"])) {
    $id = $args["id"];
} else {
    header('Location: calendar.php');
    die();
}

if (isset($args["update"])) {
    $displayUpdateMessage = true;
}

include_once('database/dbEvents.php');
require_once('database/dbTrainingMaterials.php');
require_once('database/dbEventMedia.php');

// We need to check for a bad ID here before we query the db
// otherwise we may be vulnerable to SQL injection(!)
$event_info = fetch_event_by_id($id);
if ($event_info == NULL) {
    // TODO: Need to create error page for no event found
    // header('Location: calendar.php');

    // Lauren: changing this to a more specific error message for testing
    echo 'bad event ID';
    die();
}
//Is if this event is part of a recurring series
$isRecurring = !empty($event_info['series_id']);
$confirmText = $isRecurring
    ? "This is a recurring event. Deleting it will remove all occurrences. Are you sure you want to delete this recurring event?"
    : "Are you sure you want to delete this event?";

// Get number of signups to display on event page
$event_num_signups = fetch_num_signups($id);
$trainingMaterials = get_training_materials_by_event($id);
$event_media = get_event_media($id);


include_once('database/dbPersons.php');
if (isset($_SESSION['access_level'])) {
    $access_level = $_SESSION['access_level'];
}

//if($args['user_id'] == 'guest') {
/*if($args['user_id'] == 'guest') {

    } else {*/
$user = retrieve_person($_SESSION['_id']);
$active = $user->get_status() == 'Active';
//}


ini_set("display_errors", 1);
error_reporting(E_ALL);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $args = sanitize($_POST);
    $get = sanitize($_GET);
    if (isset($_POST['attach-post-media-submit'])) {
        if ($_SESSION['access_level'] < 2) {
            echo 'forbidden';
            die();
        }
        $required = [
            'url',
            'description',
            'format',
            'id'
        ];
        if (!wereRequiredFieldsSubmitted($args, $required)) {
            echo "dude, args missing";
            die();
        }
        // $type = 'post';
        $format = $args['format'];
        $url = $args['url'];
        if ($format == 'video') {
            $url = convertYouTubeURLToEmbedLink($url);
            if (!$url) {
                echo "bad video link";
                die();
            }
        } else if (!validateURL($url)) {
            echo "bad url";
            die();
        }
        $eid = $args['id'];
        $description = $args['description'];
        if (!valueConstrainedTo($format, ['link', 'video', 'picture'])) {
            echo "dude, bad format";
            die();
        }
        attach_post_event_media($eid, $url, $format, $description);
        header('Location: event.php?id=' . $eid . '&attachSuccess');
        die();
    }
    if (isset($_POST['withdraw-submit'])) {
        $account_name = $_SESSION['_id'];
        if (remove_user_from_event($id, $account_name)) {
            header('Location: event.php?id=' . urlencode($id) . '&withdrawSuccess');
        } else {
            header('Location: event.php?id=' . urlencode($id) . '&withdrawFail');
        }
        die();
    }
    if (isset($_POST['signup-submit'])) {
        if (!$active) {
            echo 'forbidden';
            die();
        }
        if (htmlspecialchars_decode($event_info['startDate']) < date('Y-m-d')) {
            header('Location: event.php?id=' . urlencode($id));
            die();
        }
        $account_name = $_SESSION['_id'];
        $event_type = isset($event_info['type']) ? $event_info['type'] : '';
        $event_name = htmlspecialchars_decode($event_info['name']);

        if ($event_type === 'Retreat') {
            require_once('database/dbApplications.php');
            require_once('database/dbMessages.php');
            $app_data = array(
                'user_id' => $account_name,
                'event_id' => $id,
                'status' => 'Pending',
                'flagged' => 0,
                'notes' => ''
            );
            $app_id = create_app($app_data);
            if (!$app_id) {
                header('Location: requestFailed.php');
                die();
            }
            send_system_message($account_name, "Your request to sign up for $event_name has been sent to an admin.", "Your request to sign up for $event_name will be reviewed by an admin shortly. You will get another notification when you are approved or denied.");
            header('Location: signupPending.php');
            die();
        } else {
            require_once('database/dbMessages.php');
            $signup_id = sign_up_for_event($id, $account_name, 'p', '');
            if (!$signup_id) {
                header('Location: eventFailure.php');
                die();
            }
            send_system_message($account_name, "You are now signed up for $event_name!", "Thank you for signing up for $event_name!");
            header('Location: signupSuccess.php');
            die();
        }
    }
    if (isset($_POST['attach-training-media-submit'])) {
        if ($access_level < 2) {
            echo 'forbidden';
            die();
        }
        $required = [
            'url',
            'description',
            'format',
            'id'
        ];
        if (!wereRequiredFieldsSubmitted($args, $required)) {
            echo "dude, args missing";
            die();
        }
        // $type = 'post';
        $format = $args['format'];
        $url = $args['url'];
        if ($format == 'video') {
            $url = convertYouTubeURLToEmbedLink($url);
            if (!$url) {
                echo "bad video link";
                die();
            }
        } else if (!validateURL($url)) {
            echo "bad url";
            die();
        }
        $eid = $args['id'];
        $description = $args['description'];
        if (!valueConstrainedTo($format, ['link', 'video', 'picture'])) {
            echo "dude, bad format";
            die();
        }
        attach_event_training_media($eid, $url, $format, $description);
        header('Location: event.php?id=' . $eid . '&attachSuccess');
        die();
    }
} else {
    if (isset($args["request_type"])) {
        //if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $request_type = $args['request_type'];
        if (!valueConstrainedTo(
            $request_type,
            array('add self', 'add another', 'remove')
        )) {
            echo "Bad request";
            die();
        }
        $eventID = $args["id"];

        // Check if Get request from user is from an organization member
        // (volunteer, admin/super admin)
        if ($request_type == 'add self' && $access_level >= 1) {
            if (!$active) {
                echo 'forbidden';
                die();
            }
            $volunteerID = $args['selected_id'];
            $person = retrieve_person($volunteerID);
            $name = $person->get_first_name() . ' ' . $person->get_last_name();
            $name = htmlspecialchars_decode($name);
            require_once('database/dbMessages.php');
            require_once('include/output.php');
            $event = fetch_event_by_id($eventID);

            $eventName = htmlspecialchars_decode($event['name']);
            $eventDate = date('l, F j, Y', strtotime($event['date']));
            $eventStart = time24hto12h($event['start-time']);
            $eventEnd = time24hto12h($event['end-time']);
            system_message_all_admins("$name signed up for an event!", "Exciting news!\r\n\r\n$name signed up for the [$eventName](event: $eventID) event from $eventStart to $eventEnd on $eventDate.");
            // Check if GET request from user is from an admin/super admin
            // (Only admins and super admins can add another user)
        } else if ($request_type == 'add another' && $access_level > 1) {
            $volunteerID = strtolower($args['selected_id']);
            if ($volunteerID == 'vmsroot') {
                echo 'invalid user id';
                die();
            }
            require_once('database/dbMessages.php');
            require_once('include/output.php');
            $event = fetch_event_by_id($eventID);
            $eventName = htmlspecialchars_decode($event['name']);
            $eventDate = date('l, F j, Y', strtotime($event['date']));
            $eventStart = time24hto12h($event['startTime']);
            $eventEnd = time24hto12h($event['endTime']);
            send_system_message($volunteerID, 'You were assigned to an event!', "Hello,\r\n\r\nYou were assigned to the [$eventName](event: $eventID) event from $eventStart to $eventEnd on $eventDate.");
        } else {
            header('Location: event.php?id=' . $eventID);
            die();
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <?php
    require_once('universal.inc');
    require_once('database/dbEvents.php');
    ?>
    <title>Gwyneth's Gift | <?php echo $event_info['name'] ?></title>
    <link rel="stylesheet" href="event.css" type="text/css" />
    <?php if ($isEventManager) : ?>
        <script src="js/event.js"></script>
    <?php endif ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <?php require_once('header.php') ?>
    <h1>View Event</h1>
    <main class="event-info">
        <!-- Success notifications -->
        <?php if (isset($_GET['createSuccess'])): ?>
            <div class="happy-toast">Event created successfully!</div>
        <?php endif ?>
        <?php if (isset($_GET['removeSuccess'])): ?>
            <div class="happy-toast">Event Media removed successfully!</div>
        <?php endif ?>
        <?php if (isset($_GET['attachSuccess'])): ?>
            <div class="happy-toast">Event Media added successfully!</div>
        <?php endif ?>
        <?php if (isset($_GET['editSuccess'])): ?>
            <div class="happy-toast">Event details updated successfully!</div>
        <?php endif ?>
        <?php if (isset($_GET['cancelSuccess'])): ?>
            <div class="happy-toast">Sign-up canceled successfully!</div>
        <?php endif ?>
        <?php if (isset($_GET['withdrawSuccess'])): ?>
            <div class="happy-toast">You have withdrawn from this event.</div>
        <?php endif ?>
        <?php if (isset($_GET['withdrawFail'])): ?>
            <div class="happy-toast" style="background-color:#c0392b;">Failed to withdraw. Please try again.</div>
        <?php endif ?>
        <?php if ($displayUpdateMessage): ?>
            <div class="happy-toast">Attendance information updated successfully!</div>
        <?php endif ?>
        <?php if (isset($_GET['trainingUploadSuccess'])): ?>
            <div class="happy-toast">Training Document uploaded successfully!</div>
        <?php endif ?>
        <?php if (isset($_GET['trainingDeleteSuccess'])): ?>
            <div class="happy-toast">Training Document removed successfully!</div>
        <?php endif ?>

        <!-- Facebook share button -->
        <div id="fb-root"></div>
        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v22.0"></script>
        <!--@@@ Thomas: if user clicked check in/out-->
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['checking_in'])) {
                $personID = $_POST['personID'];
                $eventID = $_POST['eventID'];
                $timestamp = $_POST['timestamp'];
                check_in($personID, $eventID, $timestamp);
                echo "<div class='happy-toast'>You've checked in!</div>";
            } else if (isset($_POST['checking_out'])) {
                $personID = $_POST['personID'];
                $eventID = $_POST['eventID'];
                $timestamp = $_POST['timestamp'];
                check_out($personID, $eventID, $timestamp);
                echo "<div class='happy-toast'>You've checked out!</div>";
            } else if (isset($_POST['archiving'])) {
                $eventID = $_POST['eventID'];
                archive_event($eventID);
                echo "<div class='happy-toast'>Event has been archived!</div>";
            } else if (isset($_POST['unarchiving'])) {
                $eventID = $_POST['eventID'];
                unarchive_event($eventID);
                echo "<div class='happy-toast'>Event has been unarchived!</div>";
            }
        }
        ?>
        <!---->

        <?php
        require_once('include/output.php');
        $event_name = $event_info['name'];
        $event_abbr = $event_info['abbr_name'];
        $event_date = date('l, F j, Y', strtotime($event_info['startDate']));
        $event_timezone = $event_info['timezone'];

        $today = date('l, F j, Y');
        $now = date('H:i:s');
        $event_startTime = time24hto12h($event_info['startTime']);
        $event_endTime = time24hto12h($event_info['endTime']);
        $date = new DateTime($event_date);
        $time = new DateTime($event_startTime);
        $today = new DateTime($today);
        $now = new DateTime($now);
        $event_in_past = false;
        if ($today > $date) {
            $event_in_past = true;
        } elseif ($today == $date) {
            if ($now > $time) {
                $event_in_past = true;
            }
        }

        $event_description = $event_info['description'];
        $event_location = $event_info['location'];
        $event_capacity = $event_info['capacity'];
        //$event_training_level = $event_info['affiliation'];
        $num_signups = $event_num_signups['RowCount'];
        $recurrence = $event_info['recurrence_interval_days'];
        require_once('include/time.php');
        ?>

        <!-- Event Information Table -->
        <h2 class="event-head">
            <?php echo htmlspecialchars_decode($event_name); ?>
            <?php if ($isEventManager && !$event_in_past): ?>
                <a href="editEvent.php?id=<?= $id ?>" title="Edit Event" class="edit-icon">
                    <i class="fas fa-pencil-alt"></i>
                </a>
                <a href="deleteEvent.php?id=<?= $id ?>" title="Delete Event" class="delete-icon"
                    onclick="showDeleteConfirmation(); return false;">
                    <i class="fas fa-trash"></i>
                </a>
            <?php endif; ?>

        </h2>










        <div id="table-wrapper">
            <table>
                <tr>
                    <td class="label">Abbreviated Name</td>
                    <td><?php echo htmlspecialchars_decode($event_abbr); ?></td>
                </tr>
                <tr>
                    <td class="label">Date</td>
                    <td><?php echo $event_date; ?></td>
                </tr>
                <tr>
                    <td class="label">Time</td>
                    <td><?php echo $event_startTime . " - " . $event_endTime; ?></td>
                </tr>

                <tr>
                    <td class="label">Timezone</td>
                    <td><?php echo $event_timezone; ?></td>
                </tr>
                
                <?php if (isset($event_info['series_id']) && $event_info['series_id'] != NULL): ?>
                    <tr>
                        <td class="label">Recurrence</td>
                        <?php
                        $repeats = "Every " . $recurrence . " days";
                        if ($recurrence == 1) {
                            $repeats = "Daily";
                        } elseif ($recurrence == 7) {
                            $repeats = "Weekly";
                        } elseif ($recurrence == 30) {
                            $repeats = "Monthly";
                        } elseif ($recurrence == -1) {
                            $repeats = "Part of a deleted series";
                        }
                        ?>
                        <td><?php echo $repeats; ?></td>
                    </tr>
                <?php endif ?>
                <tr>
                    <td class="label">Location</td>
                    <td>
                        <?php echo wordwrap($event_location, 50, "<br />\n"); ?>
                    </td>
                </tr>

                <tr>
                    <td class="label">Description</td>
                    <td>
                        <?php echo wordwrap(htmlspecialchars_decode($event_description), 50, "<br />\n"); ?>
                    </td>
                </tr>
                <tr>
                    <td class="label">Capacity</td>
                    <td id="description-cell"><?php echo $event_capacity; ?></td>
                </tr>
                <tr>
                    <td class="label">Attendees</td>
                    <td id="description-cell"><?php echo $num_signups; ?></td>
                </tr>
            </table>
        </div>

        <section class="event-training-materials">
            <h2>Training Materials</h2>

            <?php if (empty($trainingMaterials)): ?>
                <p>No training materials have been uploaded for this event yet.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($trainingMaterials as $material): ?>
                        <li>
                            <a href="<?= htmlspecialchars($material['file_path']) ?>" target="_blank">
                                <?= htmlspecialchars($material['title']) ?>
                            </a>
                            <?php if (!empty($material['description'])): ?>
                                - <?= htmlspecialchars($material['description']) ?>
                            <?php endif; ?>

                            <?php if ($isEventManager): ?>
                                <a href="deleteTrainingMaterial.php?id=<?= urlencode($material['id']) ?>&eventID=<?= urlencode($id) ?>"
                                    onclick="return confirm('Delete this Training Document?');"
                                    style="color: red; margin-left: 10px;">
                                    Remove
                                </a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if ($isEventManager): ?>
                <p>
                    <a href="addTrainingMaterial.php?eventID=<?= urlencode($id) ?>" class="button signup" style="display: block; width: 100%; text-align: center;">
                        Add Training Document
                    </a>
                </p>
                <p>
                    <a href="manageTrainingMaterials.php?eventID=<?= urlencode((string)$id) ?>" class="button cancel" style="display: block; width: 100%; text-align: center;">
                        Delete Training Documents
                    </a>
                </p>
            <?php endif; ?>
        </section>

        <section class="event-media">
            <h2>Event Media</h2>
                
            <?php if (empty($event_media)): ?>
                <p>No event media has been uploaded for this event yet.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($event_media as $media): ?>
                        <li>
                           <?php 
                            if ($media['format'] == 'link') {
                                echo '<a href="' . $media['url'] . '">' . $media['description'] . '</a>';
                                if ($isEventManager) {
                                    echo ' <a style="color: red;" href="deleteEventMedia.php?eid=' . $id . '&mid=' . $media['id'] . '" onclick="return confirm(\'Delete this media link?\');">Remove</a>';
                                }
                            } else if ($media['format'] == 'picture') {
                                echo '<span>' . $media['description'] . '</span>';
                                if ($isEventManager) {
                                    echo ' <a style="color: red;" href="deleteEventMedia.php?eid=' . $id . '&mid=' . $media['id'] . '" onclick="return confirm(\'Delete this media photo?\');">Remove</a>';
                                }
                                echo '<br><a href="' . $media['url'] . '"><img style="max-width: 30vw" src="' . $media['url'] . '" alt="' . $media['description'] . '"></a>';
                            } else {
                                echo '<span>' . $media['description'] . '</span>';
                                if ($isEventManager) {
                                    echo ' <a style="color: red;" href="deleteEventMedia.php?eid=' . $id . '&mid=' . $media['id'] . '" onclick="return confirm(\'Delete this media video?\');">Remove</a>';
                                }
                                echo '<br><iframe width="560" height="315" src="' . $media['url'] . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
                            }
                            
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

                <p>
                    <a href="addEventMedia.php?eventID=<?= urlencode($id) ?>" class="button signup">
                        Add Event Media
                    </a>
                </p>
        </section>

        <section>
            <?php if (check_if_signed_up($id, $user->get_id()) || $isEventManager): ?>
                <h2>Event Comments</h2>
                <p>
                    <a href="viewEventComments.php?eventID=<?= urlencode($id) ?>" class="button signup">
                        View Event Comments
                    </a>
                </p>
            <?php endif ?>
        </section>

        <!-- Action Buttons -->
        <div class="action-buttons">

            <!--@@@ Check-In and Check-Out Buttons by Thomas -->
            <?php if (isset($user) && can_check_in($user->get_id(), $event_info)) : ?>
                <form method="POST" action="">
                    <input type="hidden" name="checking_in" value="1">
                    <input type="hidden" name="personID" value="<?php echo $user->get_id(); ?>">
                    <input type="hidden" name="eventID" value="<?php echo $event_info['id']; ?>">
                    <input type="hidden" name="timestamp" value="<?php echo date("Y-m-d H:i:s", time()); ?>">
                    <input type="hidden" name="id" value="<?php echo $event_info['id']; ?>">
                    <button type="submit" class="button success">Check-In</button>
                </form>
            <?php endif ?>

            <?php if (isset($user) && can_check_out($user->get_id(), $event_info)) : ?>
                <form method="POST" action="">
                    <input type="hidden" name="checking_out" value="1">
                    <input type="hidden" name="personID" value="<?php echo $user->get_id(); ?>">
                    <input type="hidden" name="eventID" value="<?php echo $event_info['id']; ?>">
                    <input type="hidden" name="timestamp" value="<?php echo date("Y-m-d H:i:s", time()); ?>">
                    <input type="hidden" name="id" value="<?php echo $event_info['id']; ?>">
                    <button type="submit" class="button danger">Check-Out</button>
                </form>
            <?php endif ?>

            <!-- end of Thomas's work-->

            <?php /*if ($access_level < 2) : ?>
                <?php if ($event_info["completed"] == "no") : ?>
                    <button onclick="showCancelConfirmation()" class="button danger">Cancel My Sign-Up</button>
                <?php endif ?>
            <?php endif*/ ?>

            <?php if (!check_if_signed_up($id, $user->get_id()) && !$event_in_past): ?>
            <form action="event.php?id=<?php echo urlencode($id); ?>" method="post">
                <input type="hidden" name="signup-submit" value="1">
                <button type="submit" class="button primary">Sign Up!</button>
            </form>
            <?php elseif (check_if_signed_up($id, $user->get_id())): ?>
            <form action="event.php?id=<?php echo urlencode($id); ?>" method="post" onsubmit="return confirm('Are you sure you want to withdraw from this event?');">
                <input type="hidden" name="withdraw-submit" value="1">
                <button type="submit" class="button cancel">Withdraw</button>
            </form>
            <?php endif; ?>
            <?php if ($isEventManager) : ?>
                <a href="eventRoster.php?id=<?php echo urlencode($id); ?>" class="button signup">Generate Event Roster</a>
                <a href="viewEventSignUps.php?id=<?php echo $id; ?>" class="button signup">View Event Signups</a>

                <!-- Archive and Unarchive buttons by Thomas -->
                <!--Remove archive stuff - Kenzie
                <?php if (is_archived($event_info['id'])) : ?>
                    <form method="POST" action="" onsubmit="return confirmAction('unarchive')">
                        <input type="hidden" name="unarchiving" value="1">
                        <input type="hidden" name="eventID" value="<?php echo $event_info['id']; ?>">
                        <input type="hidden" name="id" value="<?php echo $event_info['id']; ?>">
                        <button type="submit" class="button">Unarchive</button>
                    </form>

                <?php else : ?>
                    <form method="POST" action="" onsubmit="return confirmAction('archive')">
                        <input type="hidden" name="archiving" value="1">
                        <input type="hidden" name="eventID" value="<?php echo $event_info['id']; ?>">
                        <input type="hidden" name="id" value="<?php echo $event_info['id']; ?>">
                        <button type="submit" class="button">Archive</button>
                    </form>

                <?php endif ?>
                -->
                <!-- end of Thomas's work -->

                <a href="logAttendees.php?id=<?php echo urlencode($id); ?>" class="button signup">Log Event Attendees</a>


                <!-- <a href="editEvent.php?id=<?= $id ?>" class="button cancel">Edit Event Details</a> -->


            <?php endif ?>

            <a href="calendar.php?month=<?= substr($event_info['startDate'], 0, 7) ?>" class="button cancel">Return to Calendar</a>
        </div>

        <!-- Share Event on Facebook Button -->
        <!--<?php
            $page_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            ?>
            <meta property="og:image" content="https://jenniferp160.sg-host.com/images/FredSPCAlogo.png">
            <div class="fb-share-button" data-href= $page_link data-layout="" data-size=""><a target="_blank" 
                href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Flocalhost%2FfredSPCA%2FviewAllEvents.php&amp;src=sdkpreparse" 
                class="fb-xfbml-parse-ignore">Share</a>
            </div>-->

        <!-- Confirmation Modals -->
        <?php if ($isEventManager) : ?>
            <?php if (isset($event_info['series_id']) && $event_info['series_id'] != NULL) : ?>
                <div id="delete-confirmation-wrapper" class="modal hidden">
                    <div class="modal-content">
                        <p>This event is part of a repeating series.</p>
                        <p>What would you like to delete?</p>

                        <form method="get" action="deleteEvent.php">
                            <input type="hidden" name="id" value="<?= $id ?>">

                            <button type="submit" name="confirm" value="single" class="button danger">
                                Delete ONLY this event
                            </button>

                            <button type="submit" name="confirm" value="series" class="button danger">
                                Delete ENTIRE series
                            </button>
                        </form>

                        <button id="delete-cancel" class="button cancel">Cancel</button>
                    </div>
                </div>
            <?php else : ?>
                <div id="delete-confirmation-wrapper" class="modal hidden">
                    <div class="modal-content">
                        <p>Are you sure you want to delete this event?</p>

                        <form method="get" action="deleteEvent.php">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <button type="submit" name="confirm" value="single" class="button danger">
                                Delete this event
                            </button>
                        </form>

                        <button id="delete-cancel" class="button cancel">Cancel</button>
                    </div>
                </div>
            <?php endif ?>

            <div id="complete-confirmation-wrapper" class="modal hidden">
                <div class="modal-content">
                    <p>Are you sure you want to complete this event?</p>
                    <p>This action cannot be undone.</p>
                    <form method="post" action="completeEvent.php">
                        <input type="submit" value="Archive Event" class="button">
                        <input type="hidden" name="id" value="<?= $id ?>">
                    </form>
                    <button id="complete-cancel" class="button cancel">Cancel</button>

                </div>
            </div>
        <?php endif ?>


        <?php if ($isVolunteer) : ?>
            <div id="cancel-confirmation-wrapper" class="modal hidden">
                <div class="modal-content">
                    <p>Are you sure you want to cancel your sign-up for this event?</p>
                    <p>This action cannot be undone.</p>
                    <form method="post" action="cancelEvent.php">
                        <input type="submit" value="Cancel Sign-Up" class="button danger">
                        <input type="hidden" name="id" value="<?= $_REQUEST['id'] ?>">
                        <input type="hidden" name="user_id" value="<?= $_REQUEST['user_id'] ?>">
                    </form>
                    <button onclick="document.getElementById('cancel-confirmation-wrapper').classList.add('hidden')" id="cancel-cancel" class="button cancel">Cancel</button>
                </div>
            </div>
            <?php
            ?>
        <?php endif ?>



        <!-- Scripts for Modal Controls -->
        <script>
            function showDeleteConfirmation() {
                document.getElementById('delete-confirmation-wrapper').classList.remove('hidden');
            }

            function showCancelConfirmation() {
                document.getElementById('cancel-confirmation-wrapper').classList.remove('hidden');
            }

            function showCompleteConfirmation() {
                document.getElementById('complete-confirmation-wrapper').classList.remove('hidden');
            }
            document.getElementById('delete-cancel').onclick = function() {
                document.getElementById('delete-confirmation-wrapper').classList.add('hidden');
            };
            document.getElementById('cancel-cancel').onclick = function() {
                document.getElementById('cancel-confirmation-wrapper').classList.add('hidden');
            }
            document.getElementById('complete-cancel').onclick = function() {
                document.getElementById('complete-confirmation-wrapper').classList.add('hidden');
            };
        </script>
    </main>
</body>

</html>