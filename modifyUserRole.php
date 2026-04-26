<?php
    // Template for new VMS pages. Base your new page on this one

    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();
    ini_set("display_errors",1);
    error_reporting(E_ALL);

    // check RBAC
    if (isset($_SESSION['access_level']) && ($_SESSION['access_level'] == 4)) {
        $canModifyRole = true;
    } else {
        header('Location: index.php');
        die();  
    }

    require_once('include/input-validation.php');
    
    $get = sanitize($_GET);
    $id = $get['id'];
    // Does the person exist?
    require_once('domain/Person.php');
    require_once('database/dbPersons.php');
    $thePerson = retrieve_person($id);
    if (!$thePerson) {
        echo "That user does not exist";
        die();
    }
    
    // Was an ID supplied?
    if ($_SERVER["REQUEST_METHOD"] == "GET" && !isset($_GET['id'])) {
        header('Location: index.php');
        die();
    } else if ($_SERVER["REQUEST_METHOD"] == "POST"){
        require_once('database/dbPersons.php');
        require_once('database/dbMessages.php');
        $post = sanitize($_POST);
        $new_role = $post['s_role'];
        if (!valueConstrainedTo($new_role, ['volunteer', 'event_manager', 'board_member', 'admin'])) {
            die();
        }
        if (empty($new_role)){
            // echo "No new role selected";
        } else if ($canModifyRole) {
            update_type($id, $new_role);
            $typeChange = true;
            // echo "<meta http-equiv='refresh' content='0'>";
        }
        $new_status = $post['statsRadio'];
        if (!valueConstrainedTo($new_status, ['Active', 'Inactive'])) {
            die();
        }
        if (empty($new_status)){
            // echo "No new status selected";
        }else{
            update_status($id, $new_status);
            $statusChange = true;
            // echo "<meta http-equiv='refresh' content='0'>";
        }

        // Qualifications
        $cpr = $post['cpr_training'] ?? 'no';
        $aed = $post['aed_training'] ?? 'no';
        if (valueConstrainedTo($cpr, ['yes', 'no']) && valueConstrainedTo($aed, ['yes', 'no'])) {
            $con = connect();
            $safe_id = mysqli_real_escape_string($con, $id);
            mysqli_query($con, "UPDATE dbpersons SET cpr_training_completion='$cpr', aed_training_completion='$aed' WHERE id='$safe_id'");
            mysqli_close($con);
            $qualChange = true;
        }

        $currentStatus = $thePerson->get_status();
            
        if (isset($_POST['user_access_modified'])) { // Check if the form was submitted
            $newStatus = $_POST['statsRadio']; // Get the selected status
            if ($newStatus == "Inactive" && $currentStatus != "Inactive") {
                // Notify Admins about archived volunteers - Implemented by Aidan Meyer 

                $archive_title = $thePerson->get_id() . " has been archived.";
                $archive_message = "This user has been archived. For reinstatement, navigate to volunteer search and select Archive, then modify the field to Active";
                system_message_all_admins($archive_title, $archive_message);
            }
        }
        if (isset($notesChange) || isset($statusChange) || isset($typeChange) || isset($qualChange)) {
            header('Location: viewProfile.php?rscSuccess&id=' . $_GET['id']);
            die();
        }
    }

    // make every submitted field SQL-safe except for password
    $ignoreList = array('password');
    $args = sanitize($_POST);
?>
<!DOCTYPE html>
<html>
    <head>
        <link href="./css/base.css" rel="stylesheet">
        <?php require_once('universal.inc') ?>
        <title>Gwyneth's Gift | Archive User</title>
        <style>
            
        </style>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Modify Archive Status and Role</h1>
        <main class="user-role">
            <?php if ($canModifyRole): ?>
                <h2>Modify <?php echo $thePerson->get_first_name() . " " . $thePerson->get_last_name(); ?>'s Archive Status and Role</h2>
            <?php else: ?>
                <h2>Modify <?php echo $thePerson->get_first_name() . " " . $thePerson->get_last_name(); ?>'s Status</h2>
            <?php endif ?>
            <form class="modUser" method="post">
                <?php if (isset($typeChange) || isset($notesChange) || isset($statusChange)): ?>
                    <div class="happy-toast">User's access is updated.</div>
                <?php endif ?>
                    <?php
                        // Provides drop down of the role types to select and change the role
			//other than the person's current role type is displayed
            if ($canModifyRole) {
				$roles = array('volunteer' => 'Volunteer', 'event_manager' => 'Event Manager', 
                                'board_member' => 'Board Member', 'admin' => 'Administrator');
                echo '<label for="role">Change Role</label><select id="role" class="form-select-sm" name="s_role">' ;
                // echo '<option value="" SELECTED></option>' ;
                $currentRole = $thePerson->get_type();
                foreach ($roles as $role => $typename) {
                    if($role != $currentRole) {
                        echo '<option value="'. $role .'">'. $typename .'</option>';
                    } else {
                        echo '<option value="'. $role .'" selected>'. $typename .' (current)</option>';
                    }
                }
                echo '</select>';
            }
        ?>
		<label>Activate / Deactivate Account</label>
		<div class="form-row">
            <?php
                // Check the person's status and check the radio to signal the current status
                // Display the current and other available statuses as well to change the status
		        $currentStatus = $thePerson->get_status();
                if ($currentStatus == "Active") {
                    echo '<input type="radio" name="statsRadio" id = "makeActive" value="Active" checked><label for="makeActive" class="checkbox-label">Active</label>';
                    echo '<input type="radio" name="statsRadio" id = "makeInactive" value="Inactive"><label for="makeInactive" class="checkbox-label">Inactive</label>';
                } elseif ($currentStatus == "Inactive") {
                    echo '<input type="radio" name="statsRadio" id = "makeActive" value="Active"><label for="makeActive" class="checkbox-label">Active</label>';
                    echo '<input type="radio" name="statsRadio" id = "makeInactive" value="Inactive" checked><label for="makeInactive" class="checkbox-label">Inactive</label>';
                }
		    ?>
		</div>

        <label>Modify Qualifications</label>
        <ul>
            <li>
                <div class="form-row">
            
                <label for="cpr_training">CPR Training</label>
                <div>
                    <?php $cpr = $thePerson->get_cpr_training_completion(); ?>
                    <input type="radio" name="cpr_training" id="cpr_yes" value="yes" <?php if ($cpr === 'yes') echo 'checked'; ?>>
                    <label for="cpr_yes" class="checkbox-label">Completed</label>
                    <input type="radio" name="cpr_training" id="cpr_no" value="no" <?php if ($cpr !== 'yes') echo 'checked'; ?>>
                    <label for="cpr_no" class="checkbox-label">Not Completed</label>
                </div>
            </li>
            <li>
                </div>
                <div class="form-row">
                    <label for="aed_training">AED Training</label>
                    <div>
                        <?php $aed = $thePerson->get_aed_training_completion(); ?>
                        <input type="radio" name="aed_training" id="aed_yes" value="yes" <?php if ($aed === 'yes') echo 'checked'; ?>>
                        <label for="aed_yes" class="checkbox-label">Completed</label>
                        <input type="radio" name="aed_training" id="aed_no" value="no" <?php if ($aed !== 'yes') echo 'checked'; ?>>
                        <label for="aed_no" class="checkbox-label">Not Completed</label>
                    </div>
                </div>
            </li>
        </ul>

                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="submit" name="user_access_modified" value="Update">
                <a class="button cancel" href="viewProfile.php?id=<?php echo htmlspecialchars($_GET['id']) ?>">Cancel</a>
		</form>
        </main>
    </body>
</html>
