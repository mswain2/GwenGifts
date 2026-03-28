<?php
    // Template for new VMS pages. Base your new page on this one

    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();

    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
        $accessLevel = $_SESSION['access_level'];
        $userID = $_SESSION['_id'];
    }
    // admin-only access
    if ($accessLevel < 2) {
        header('Location: index.php');
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Gwyneth's Gift | Search Users</title>
    <link href="css/normal_tw.css" rel="stylesheet">
<?php
$tailwind_mode = true;
require_once('header.php');
?>

</head>
<body>


<h1>Search Users</h1>


<main>
    <div class="main-content-box w-[80%] p-8">

        <div class="text-center mb-8">
            <h2>Find a User</h2>
            <p class="sub-text">Use the filters below to search and create mailing lists.</p>
        </div>

        <form id="person-search" class="space-y-6" method="get">

        <?php
            $type = 'volunteer'; // search for volunteer roles by default
            $status = 'Active'; // search for active users by default

            if (isset($_GET['name']) || isset($_GET['id']) || isset($_GET['phone']) || isset($_GET['zip']) || isset($_GET['type']) || isset($_GET['status']) || isset($_GET['email'])) {
                require_once('include/input-validation.php');
                require_once('database/dbPersons.php');
                $args = sanitize($_GET);
                $required = ['name', 'id', 'phone', 'zip', 'type', 'status', 'email'];

                if (!wereRequiredFieldsSubmitted($args, $required, true)) {
                    echo '<div class="error-block">Missing expected form elements.</div>';
                }

                $name = $args['name'];
                $id = $args['id'];
                $phone = preg_replace("/[^0-9]/", "", $args['phone']);
                $zip = $args['zip'];
                $type = $args['type'];
                $status = $args['status'];
                // $photo_release = $args['photo_release'];
                $email = $args['email'];

                if (!($name || $id || $phone || $zip || $type || $status || $email)) {
                    echo '<div class="error-block">At least one search criterion is required.</div>';
                } else if (!valueConstrainedTo($type, ['admin', 'participant', 'superadmin', 'volunteer', 'event_manager', 'board_member', ''])) {
                    echo '<div class="error-block">The system did not understand your request.</div>';
                } else if (!valueConstrainedTo($status, ['Active', 'Inactive', 'All', ''])) {
                    echo '<div class="error-block">The system did not understand your request.</div>';
                // } else if (!valueConstrainedTo($photo_release, ['Restricted', 'Not Restricted', ''])) {
                //     echo '<div class="error-block">The system did not understand your request.</div>';
                } else {
                    echo "<h3>Search Results</h3>";
                    $persons = find_users($name, $id, $phone, $zip, $type, $status, $email);
                    require_once('include/output.php');

                    if (count($persons) > 0) {
                        echo '
                        <div class="overflow-x-auto">
                            <table>
                                <thead>
                                    <tr>
                                        <th>First</th>
                                        <th>Last</th>
                                        <th>Username</th>
                                        <th>Phone</th>
                                        <th>Zip Code</th>
                                        <th>Role</th>
                                        <th>Archive Status</th>
                                        <th>Profile</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>';
                        $mailingList = '';
                        $notFirst = false;
                        foreach ($persons as $person) {
                            if ($notFirst) {
                                $mailingList .= ', ';
                            } else {
                                $notFirst = true;
                            }
                            $mailingList .= $person->get_email();
                            echo '
                                    <tr>
                                        <td>' . $person->get_first_name() . '</td>
                                        <td>' . $person->get_last_name() . '</td>
                                        <td>' . $person->get_id() . '</td>
                                        <td>' . formatPhoneNumber($person->get_phone1()) . '</a></td>
                                        <td>' . $person->get_zip_code() . '</td>
                                        <td>' . ucfirst($person->get_type_formatted()) . '</td>
                                        <td>' . ucfirst($person->get_status()) . '</td>
                                        <td><a href="viewProfile.php?id=' . $person->get_id() . '" class="text-blue-700 underline">Profile</a></td>
                                        <td><a href="modifyUserRole.php?id=' . $person->get_id() . '" class="text-blue-700 underline">Update Status</a></td>
                                    </tr>';
                        }
                        echo '
                                </tbody>
                            </table>
                        </div>';

                        echo '
                        <div class="mt-4">
                            <label>Result Mailing List:</label>
                            <p class="text-gray-700 break-words">' . $mailingList . '</p>
                        </div>';
                    } else {
                        echo '<div class="error-block">Your search returned no results.</div>';
                    }
                    echo '<h3>Search Again</h3>';
                }
            }
        ?>

            <div>
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="w-full" value="<?php if (isset($name)) echo htmlspecialchars($_GET['name']); ?>" placeholder="Enter the user's first and/or last name">
            </div>

            <div>
                <label for="id">Username</label>
                <input type="text" id="id" name="id" class="w-full" value="<?php if (isset($id)) echo htmlspecialchars($_GET['id']); ?>" placeholder="Enter the user's username (login ID)">
            </div>

            <div>
                <label for="type">Role</label>
                <select id="type" name="type">
                    <option value="all" <?= isset($type) && $type === 'all' ? 'selected' : '' ?>>All</option>
                    <option value="none" <?= isset($type) && $type === 'none' ? 'selected' : '' ?>>None</option>
                    <option value="volunteer" <?= isset($type) && $type === 'volunteer' ? 'selected' : '' ?>>Volunteer</option>
                    <option value="event_manager" <?= isset($type) && $type === 'event_manager' ? 'selected' : '' ?>>Event Manager</option>
                    <option value="board_member" <?= isset($type) && $type === 'board_member' ? 'selected' : '' ?>>Board Member</option>
                    <option value="admin" <?= isset($type) && $type === 'admin' ? 'selected' : '' ?>>Administrator</option>
                </select>
            </div>

            <div>
                <label for="email">Email</label>
                <input type="text" id="email" name="email" class="w-full" value="<?php if (isset($email)) echo htmlspecialchars($_GET['email']); ?>" placeholder="Enter the user's email">
            </div>

            <div>
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="All" <?= isset($status) && $status === 'All' ? 'selected' : '' ?>>All</option>
                    <option value="Active" <?= isset($status) && $status === 'Active' ? 'selected' : '' ?>>Active</option>
                    <option value="Inactive" <?= isset($status) && $status === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>

            <div>
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" class="w-full" value="<?php if (isset($phone)) echo htmlspecialchars($_GET['phone']); ?>" placeholder="Enter the user's phone number">
            </div>

            <div>
                <label for="zip">Zip Code</label>
                <input type="text" id="zip" name="zip" class="w-full" value="<?php if (isset($zip)) echo htmlspecialchars($_GET['zip']); ?>" placeholder="Enter the user's zip code">
            </div>

            

            <div class="text-center pt-4">
                <input type="submit" value="Search" class="submit-button">
            </div>

        </form>
    </div>

    <div class="text-center mt-6">
        <a href="index.php" class="return-button">Return to Dashboard</a>
    </div>

    <div class="info-section">
        <div class="blue-div"></div>
        <p class="info-text">
            Use this tool to filter and search for volunteers or participants by their type, zip code, phone, archive status, and more. Mailing list support is built in.
        </p>
    </div>
</main>

</body>
</html>

