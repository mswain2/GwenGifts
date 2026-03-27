<?php
    // Template for new VMS pages. Base your new page on this one

    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();
    date_default_timezone_set("America/New_York");

    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    $userType = 'volunteer';  
    $error = '';
    $success = false;


    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
        $accessLevel = $_SESSION['access_level'];
        $userID = $_SESSION['_id'];
    }

    require_once('database/dbPersons.php');
	require_once('database/dbEvents.php');
	require_once('include/input-validation.php');

    if (isset($_SESSION['_id'])) {
        if ($_SESSION['_id'] === 'vmsroot') {
            $userType = 'superadmin';
        } else {
            $person = retrieve_person($_SESSION['_id']);
            if ($person) $userType = $person->get_type();
        }
    }

    if (!in_array($userType, ['event_manager', 'admin', 'superadmin', 'board_member'])) {
        header('Location: login.php');
        die();
    }
    

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $connection = connect();

        $userID = mysqli_real_escape_string($connection, $_POST['userID']);
        $eventID = mysqli_real_escape_string($connection, $_POST['eventID']);

		$event = retrieve_event2($eventID);
        if (!$event || !isset($event['startDate'])) {       
            mysqli_close($connection);     
            $error = "Invalid event.";
        } else {
            $date = $event['startDate'];
            $startTime = trim($_POST['start-time'] ?? '');
            $endTime = trim($_POST['end-time'] ?? '');


    
            $start24 = validate12hTimeAndConvertTo24h($startTime);
            $end24 = validate12hTimeAndConvertTo24h($endTime);

            if (!$start24 || !$end24) {
                $error = "Invalid time format. Use format like 10:00 AM";
            } else {
                $formatted_start_time = $date . ' ' . $start24;
                $formatted_end_time = $date . ' ' . $end24;

    
                if (strtotime($formatted_end_time) <= strtotime($formatted_start_time)) {
                    $error = "End time must be after start time.";
                } else {
                    $query = "INSERT INTO dbpersonhours 
                            (personID, eventID, start_time, end_time)
                            VALUES ('$userID', '$eventID', '$formatted_start_time', '$formatted_end_time')";

                if (!mysqli_query($connection, $query)) {
                    $error = "Database error. Please try again.";
                } else {
                    mysqli_close($connection);
                    header('Location: eventList.php?username=' . urlencode($userID));
                    exit();
                }
            }
        }
    }

    mysqli_close($connection);
    
}

	// Fetch event data
	if (isset($_GET['eventID'], $_GET['eventName'])) {
		$eventID = $_GET['eventID'];
		$eventName = htmlspecialchars($_GET['eventName']);
	} else {
		header('Location: login.php');
		die();
	}

    if ($userType === 'volunteer') {
        $userID = $_SESSION['_id'];
    } else {
        $userID = $_GET['userID'] ?? null;
        if (!$userID) {
            header('Location: login.php');
            die();
        }
    }

    

 ?>

<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>Gwyneth's Gift | Add New Check-In</title>
    </head>
    <body>
	<?php require_once('header.php') ?>
        <h1>Add New Check-In</h1>

		<?php if ($success): ?>
            <div class="happy-toast">Check-In Added Successfully!</div>
        <?php endif ?>

        <main class="date">

			<h2><?php echo $eventName ?></h2>
            <?php if (isset($error)): ?>
                <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            
			<form id="new-check-in" method="post">

                <label for="name">* New Check-In Time </label>                
                <input type="text" id="start-time" name="start-time" pattern="([1-9]|10|11|12):[0-5][0-9] ?([aApP][mM])" required placeholder="Enter new check-in time. Ex. 12:00 PM">

                <label for="name">* New Check-Out Time </label>
                <input type="text" id="end-time" name="end-time" pattern="([1-9]|10|11|12):[0-5][0-9] ?([aApP][mM])" required placeholder="Enter new check-out time. Ex. 12:00 PM">

				<input type="hidden" id="eventID" name="eventID" value="<?php echo $eventID ?>">
				<input type="hidden" id="userID" name="userID" value="<?php echo $userID ?>">

                <input type="submit" value="Confirm New Check-In">
            </form>

            <a class="button cancel" href="index.php" style="margin-top: -.5rem">Return to Dashboard</a>
  
                <!--
                <label for="name">* Animal</label>
                <select for="name" id="animal" name="animal" required>
                    <?php 
                        // fetch data from the $all_animals variable
                        // and individually display as an option
                        while ($animal = mysqli_fetch_array(
                                $all_animals, MYSQLI_ASSOC)):; 
                    ?>
                    <option value="<?php echo $animal['id'];?>">
                        <?php echo $animal['name'];?>
                    </option>
                    <?php 
                        endwhile; 
                        // terminate while loop
                    ?>
                </select>
                <br/>
                <p></p>
                <input type="submit" value="Create Event">
            </form>
                <?php if ($date): ?>
                    <a class="button cancel" href="calendar.php?month=<?php echo substr($date, 0, 7) ?>" style="margin-top: -.5rem">Return to Calendar</a>
                <?php else: ?>
                    <a class="button cancel" href="index.php" style="margin-top: -.5rem">Return to Dashboard</a>
                <?php endif ?>

                <!-- Require at least one checkbox be checked -->
                <script type="text/javascript">
                    $(document).ready(function(){
                        var checkboxes = $('.checkboxes');
                        checkboxes.change(function(){
                            if($('.checkboxes:checked').length>0) {
                                checkboxes.removeAttr('required');
                            } else {
                                checkboxes.attr('required', 'required');
                            }
                        });
                    });

                </script>
                
        </main>
    </body>
</html>