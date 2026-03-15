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
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gwyneth's Gift | Discussions</title>
  <link href="css/management_tw.css" rel="stylesheet">

<?php
$tailwind_mode = true;
require_once('header.php');
?>

</head>

<body>


  <!-- Larger Hero Section -->
  <header class="top-bar"></header>


  <!-- Main Content -->
  <main>
    <div class="sections">

      <!-- Buttons Section -->
      <div class="button-section">

      <button onclick="window.location.href='viewDiscussions.php';">
        <div class="button-left-gray"></div>
        <div>View Discussions</div>
        <img class="button-icon h-12 w-12 left-4" src="images/group.svg" alt="Calendar Icon">
      </button>

      <button onclick="window.location.href='createDiscussion.php';">
        <div class="button-left-gray"></div>
        <div>Create Discussion</div>
        <img class="button-icon h-12 w-12 left-4" src="images/plus-solid.svg" alt="Calendar Icon">
      </button>

      <?php if ($accessLevel >= 2): ?>
      <button onclick="window.location.href='viewBoardDiscussions.php';">
          <div class="button-left-gray"></div>
          <div>Board Discussions</div>
          <img class="button-icon h-12 w-12 left-4" src="images/group.svg" alt="Calendar Icon">
      </button>
      <?php endif; ?>

	<div class="text-center mt-6">
        	<a href="index.php" class="return-button">Return to Dashboard</a>
	</div>


     </div>

      <!-- Text Section -->
      <div class="text-section">
        <h1>Discussion Management</h1>
        <div class="div-blue"></div>
        <p>
          Welcome to the discussion management hub. Use the controls on the left to view all discussions or create a new discussion. Everything you need to control and configure your platform is just a click away.
        </p>
      </div>

    </div>
  </main>
</body>
</html>