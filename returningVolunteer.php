<?php

  session_cache_expire(30);
  session_start();

  ini_set("display_errors",1);
  error_reporting(E_ALL);

  // redirect to index if already logged in
  if (isset($_SESSION['_id'])) {
    header('Location: index.php');
    die();
  }

  $error = false;
  $errorMessage = '';

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once('include/input-validation.php');
    require_once('domain/Person.php');
    require_once('database/dbPersons.php');

    $ignoreList = array();
    $args = sanitize($_POST, $ignoreList);

    if (!wereRequiredFieldsSubmitted($args, array('email'))) {
        $error = true;
        $errorMessage = 'Please enter your email address.';
    } else {
        $email = strtolower(trim($args['email']));
        $user = retrieve_person($email);  // email IS the id for imported users

        if (!$user) {
            $error = true;
            $errorMessage = 'No account found with that email address.';
        } else if (!$user->get_force_password_change()) {
            $error = true;
            $errorMessage = 'This account has already been activated. Please use the regular login page.';
        } else if (!password_verify('Welcome1', $user->get_password())) {
            $error = true;
            $errorMessage = 'Unable to verify your account. Please contact an administrator.';
        } else {
            // Success — log them in with access_level 0 (locked)
            $_SESSION['logged_in'] = true;
            $_SESSION['access_level'] = 0;
            $_SESSION['f_name'] = $user->get_first_name();
            $_SESSION['l_name'] = $user->get_last_name();
            $_SESSION['type'] = strtolower($user->get_type());
            $_SESSION['_id'] = $user->get_id();
            $_SESSION['change-password'] = true;
            header('Location: returningVolunteerForm.php');
            die();
        }
    }
  }
?>
<!DOCTYPE html>
<html>
    <head>
	<script src="https://cdn.tailwindcss.com"></script>
    	<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap" rel="stylesheet">
  <style>

/* Found this on codepen :D */
.wave {
  animation-name: wave-animation;
  animation-duration: 2.5s;
  animation-iteration-count: infinite;
  transform-origin: 70% 70%;
  display: inline-block;
}

.login {
    color: white;
    background-color: #2f4159;
    padding: var(--button-padding);
    border: 3px solid rgba(255, 255, 255, 0.295);
    border-radius: 10px;
    font-weight: 500;
    width: 100%;
    height: 20%;
    transition: background-color .3s;
    cursor: pointer;
    text-align: center;
    margin: 10px 0px;
}

.login:hover {
  background-color: #f5c16e;
}

@keyframes wave-animation {
    0% { transform: rotate( 0.0deg) }
   10% { transform: rotate(14.0deg) }
   20% { transform: rotate(-8.0deg) }
   30% { transform: rotate(14.0deg) }
   40% { transform: rotate(-4.0deg) }
   50% { transform: rotate(10.0deg) }
   60% { transform: rotate( 0.0deg) }
  100% { transform: rotate( 0.0deg) }
}
* { font-family: Quicksand, sans-serif; }
	</style>
        <title>Gwyneth's Gift | Returning Volunteer</title>
    </head>
    <body>
<div class="h-screen flex">

  <!-- Left: Image Section (Hidden on small screens) -->
  <div class="hidden md:block md:w-1/2 bg-center rounded-r-[50px] bg-[#1F1F21]">
      <img src="images/table.jpg"
            alt="A group of GG volunteers"
            style="max-height: 100%; min-width: 100%; object-fit: cover; border-radius: 0px 50px 50px 0px;">
  </div>

  <!-- Right: Form Section -->

  <div class="w-full md:w-1/2 flex flex-col justify-center items-center bg-white relative ">


    <div class="w-2/3 max-w-md flex flex-col items-center">

      <!-- Logo -->
      <div class="w-full flex justify-center mb-6">
        <img src="images/gwenythsGiftLogo.png"
             alt="Logo"
             class="w-full max-w-xs">
      </div>

      <h2 class="text-3xl font-bold mb-6 text-gray-800 text-center">
	<span class="wave">👋</span> Welcome back!
      </h2>
      <p class="text-center text-gray-600 mb-4">
        Enter the email address you used as a volunteer.
      </p>

      <form class="w-full" method="post">
                <?php
                    if ($error) {
                        echo '<span class="text-white bg-red-700 text-center block p-2 rounded-lg mb-2">' . htmlspecialchars($errorMessage) . '</span>';
                    }
                ?>
        <div class="mb-4">
          <label class="block text-gray-700 font-medium mb-2" for="email">Email Address</label>
          <input class="w-full p-3 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400" type="email" name="email" placeholder="Enter your email address" required
              value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>
        <button class="login">Continue</button>
      </form>

      <!-- Divider -->
      <div class="flex items-center my-6 w-full">
        <div class="flex-grow border-t border-gray-300"></div>
        <span class="mx-4 text-gray-500">or</span>
        <div class="flex-grow border-t border-gray-300"></div>
      </div>

      <!-- Back to Login -->
      <p class="text-center text-gray-700">
        <a href="login.php" class="text-[#22654D] font-semibold hover:underline">Back to Login</a>
      </p>

    </div>
  </div>

</div>

    </body>
</html>
