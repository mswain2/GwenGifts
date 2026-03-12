<?php
session_cache_expire(30);
session_start();

$loggedIn = false;
$accessLevel = 0;
$userID = null;
if (isset($_SESSION['_id'])) {
    $loggedIn = true;
    $accessLevel = $_SESSION['access_level'];
    $userID = $_SESSION['_id'];
}

// admin-only access
if ($accessLevel < 2) {
    header('Location: index.php');
    die();
}

require_once 'database/dbPersons.php';
require_once 'domain/Person.php';
require_once 'database/dbEvents.php';
require_once 'domain/Event.php';

$no_shows = fetch_no_shows() ?? null;



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Gwyneth's Gift | No Shows</title>
  	<link href="css/normal_tw.css" rel="stylesheet">

<?php
$tailwind_mode = true;
require_once('header.php');
?>
<h1>No Shows</h1>

</head>
<body>
    
    <main>
        <div class="main-content-box w-[80%] p-6">
            <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>

            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Number of No Shows</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($no_shows)): ?>
                        <?php 
                        $num_no_shows = count($no_shows);
                        ?>
                        <?php for ($i=0; $i<$num_no_shows; $i++) {
                            $userID = $no_shows[$i][0];
                            $user = retrieve_person($userID);
                            $name = get_name_from_id($userID);
                            $no_sho = $no_shows[$i][1];
                            
                            echo "
                            <tr>
                                <td>$userID</td>
                                <td>$name</td>
                                <td>$no_sho</td>
                            </tr>";
                        }?>
                            



                    <?php else: ?>
                        <tr><td colspan="3">No no-shows found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>



        </div>
        <div class="text-center mt-4">
                <a href="index.php" class="return-button">Back to Dashboard</a>
            </div>

        <div class="info-section">
            <div class="blue-div"></div>
            <p class="info-text">
            </p>
        </div>
    </main>
</body>
</html>
