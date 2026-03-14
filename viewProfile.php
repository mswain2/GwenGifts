<?php
    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();

    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    $isAdmin = false;
    if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 1) {
        header('Location: login.php');
        die();
    }
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
        $accessLevel = $_SESSION['access_level'];
        $isAdmin = $accessLevel >= 2;
        $userID = $_SESSION['_id'];
    } else {
        header('Location: login.php');
        die();
    }
    if ($isAdmin && isset($_GET['id'])) {
        require_once('include/input-validation.php');
        $args = sanitize($_GET);
        $id = strtolower($args['id']);
    } else {
        $id = $userID;
    }
    require_once('database/dbPersons.php');
    //if (isset($_GET['removePic'])) {
     // if ($_GET['removePic'] === 'true') {
       // remove_profile_picture($id);
      //}
    //}

   $user = retrieve_person($id);
  $verified_ids = get_verified_ids($user->get_id());

   if ($isAdmin && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_hours'])) {
    require_once('database/dbPersons.php'); // already required, so you can just remove the duplicate
    $con = connect();

    $newHours = floatval($_POST['new_hours']);
    $safeID = mysqli_real_escape_string($con, $id);

    $update = mysqli_query($con, "
        UPDATE dbpersons 
        SET total_hours_volunteered = $newHours 
        WHERE id = '$safeID'
    ");

    if ($update) {
        $user = retrieve_person($id); // refresh with updated hours
        echo '
        <div id="success-message" class="absolute left-[40%] top-[15%] z-50 bg-green-800 p-4 text-white rounded-xl text-xl">
          Hours updated successfully!
        </div>
        <script>
          setTimeout(() => {
            const msg = document.getElementById("success-message");
            if (msg) msg.remove();
          }, 3000);
        </script>
        ';
    } else {
        echo '<div class="absolute left-[40%] top-[15%] z-50 bg-red-800 p-4 text-white rounded-xl text-xl">Failed to update hours.</div>';
    }
  
}

    $viewingOwnProfile = $id == $userID;
    $loggedInUser = $viewingOwnProfile ? $user : retrieve_person($userID);
    $canSearchUsers = in_array($loggedInUser->get_type(), ['admin', 'superadmin', 'event_manager', 'board_member']);
    $canEditUsers = in_array($loggedInUser->get_type(), ['admin', 'superadmin']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['url'])) {
        if (!update_profile_pic($id, $_POST['url'])) {
          header('Location: viewProfile.php?id='.$id.'&picsuccess=False');
        } else {
          header('Location: viewProfile.php?id='.$id.'&picsuccess=True');
        }
      }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gwyneth's Gift | Profile Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="css/base.css" />
  <script>
    function showSection(sectionId) {
      const sections = document.querySelectorAll('.profile-section');
      sections.forEach(section => section.classList.add('hidden'));
      document.getElementById(sectionId).classList.remove('hidden');

      const tabs = document.querySelectorAll('.tab-button');
      tabs.forEach(tab => {
        tab.style.borderBottom = '';
        tab.style.marginBottom = '';
      });

      const activeTab = document.querySelector(`[data-tab="${sectionId}"]`);
      activeTab.style.borderBottom = '4px solid #2f4159';
      activeTab.style.marginBottom = '-4px';
    }

    window.onload = () => showSection('personal');
  </script>

  <?php 
    require_once('header.php'); 
    require_once('include/output.php');
  ?>

  <script>

    function openModal(modalID) {
        document.getElementById(modalID).classList.remove('hidden');
    }

    function closeModal(modalID) {
        document.getElementById(modalID).classList.add('hidden');
    }

    window.onload = () => showSection('personal');
</script>

</head>

<?php if ($id == 'vmsroot'): ?>
		<div class="absolute left-[40%] top-[20%] bg-red-800 p-4 text-white rounded-xl text-xl">The root user does not have a profile.</div>
    </main></body></html>
    <?php die() ?>
<?php elseif (!$user): ?>
  <div class="absolute left-[40%] top-[20%] bg-red-800 p-4 text-white rounded-xl text-xl">User does not exist.</div>
  </main></body></html>
  <?php die() ?>
<?php endif ?>

<?php if (isset($_GET['editSuccess'])): ?>
  <div id="success-toast" class="absolute left-[40%] top-[15%] z-50 bg-green-800 p-4 text-white rounded-xl text-xl transition-opacity duration-500">Profile updated successfully!</div>
  <script>
    setTimeout(function() {
      var toast = document.getElementById('success-toast');
      toast.style.opacity = '0';
      setTimeout(function() { toast.remove(); }, 500);
    }, 500);
  </script>
<?php endif ?>

<?php if (isset($_GET['rscSuccess'])): ?>
  <div class="absolute left-[40%] top-[15%] z-50 bg-green-800 p-4 text-white rounded-xl text-xl">User role/status updated successfully!</div>
<?php endif ?>

<h1>View Profile</h1>

<body class="bg-gray-100">
  <!-- Hero Section -->
  <!--
  <div class="h-48 relative" style="background-color: var(--page-background-color);">
  </div>
  -->

  <!-- Profile Content -->
  <div class="max-w-6xl mx-auto px-4 mt-6 flex flex-col md:flex-row md:items-start gap-6">
    <!-- Left Box -->
    <div class="w-full md:w-1/3 bg-white border border-gray-300 rounded-2xl shadow-lg p-6 flex flex-col justify-between">
      <div>
        <div class="flex justify-between items-center">
        <?php if ($viewingOwnProfile): ?>
          <h2 class="text-xl font-semibold mb-4">My Profile</h2>
        <?php else: ?>
          <h2 class="text-xl font-semibold mb-4">Name: <?php echo $user->get_first_name() . ' ' . $user->get_last_name() ?></h2>
        <?php endif ?>
        </div>
        <div class="space-y-2 divide-y divide-gray-300">
          <div class="flex justify-between py-2">
            <span class="font-medium">Role</span><span><?php echo ucfirst($user->get_type())?></span>
          </div>
          <div class="flex justify-between py-2">
            <span class="font-medium">Status</span><span><?php echo ucfirst($user->get_status())?></span>
          </div>
          <div class="flex justify-between py-2">
            <span class="font-medium">Age</span><span><?php echo get_age($user->get_birthday()) ?></span>
          </div>
        </div>
      </div>
      <div class="mt-6 space-y-2">
        <?php if ($canEditUsers || $viewingOwnProfile): ?>
          <button onclick="window.location.href='editProfile.php<?php if ($id != $userID) echo '?id=' . $id ?>';" class="text-lg font-medium w-full px-4 py-2 bg-[#2f4159] text-[#FFFFFF] rounded-md hover:bg-[#f5c16e] hover:text-[#FFFFFF] cursor-pointer">Edit Profile</button>
        <?php endif ?>
          <?php if (!$viewingOwnProfile): ?>
          <button onclick="window.location.href='personSearch.php';" class="text-lg font-medium w-full px-4 py-2 bg-[#2f4159] text-[#FFFFFF] border-2 rounded-md cursor-pointer">Search Users</button>
        <?php endif ?>
        <button onclick="window.location.href='index.php<?php if ($id != $userID) echo '?id=' . $id ?>';" class="text-lg font-medium w-full px-4 py-2 bg-[#f6a4b5] text-[#FFFFFF] rounded-md hover:bg-[#f5c16e] hover:text-[#FFFFFF] cursor-pointer">Return to Dashboard</button>
      </div>
    </div>

    <!-- Right Box -->
    <div class="w-full md:w-2/3 bg-white rounded-2xl shadow-lg border border-gray-300 p-6">
      <!-- Tabs -->
      <div class="flex border-b-4 border-gray-300 mb-4">
        <button class="tab-button px-4 py-2 text-lg font-medium text-[#2B2B2E]" data-tab="personal" onclick="showSection('personal')">Personal Information</button>
        <button class="tab-button px-4 py-2 text-lg font-medium text-[#2B2B2E]" data-tab="contact" onclick="showSection('contact')">Contact Information</button>
        <button class="tab-button px-4 py-2 text-lg font-medium text-[#2B2B2E]" data-tab="notifications" onclick="showSection('notifications')">Email Notifications</button>
        <button class="tab-button px-4 py-2 text-lg font-medium text-[#2B2B2E]" data-tab="additional" onclick="showSection('additional')">Additional Information</button> 
        <button class="tab-button px-4 py-2 text-lg font-medium text-[#2B2B2E]" data-tab="account" onclick="showSection('account')">Account Security</button>       
      </div>

      <!-- Personal Section -->
      <div id="personal" class="profile-section space-y-4">
        <div>
          <span class="block text-sm font-medium text-[#1F1F21]">Name</span>
          <p class="text-gray-900 font-medium text-xl"><?php echo $user->get_first_name() ?> <?php echo $user->get_last_name() ?></p>
        </div>
        <div>
          <span class="block text-sm font-medium text-[#1F1F21]">Gender</span>
          <p class="text-gray-900 font-medium text-xl"><?php echo $user->get_gender() ?></p>          
        </div>
        <div>
          <span class="block text-sm font-medium text-[#1F1F21]">T-shirt Size</span>
          <p class="text-gray-900 font-medium text-xl"><?php echo $user->get_t_shirt_size() ?></p>
        </div>
        <div>
          <span class="block text-sm font-medium text-[#1F1F21]">Date of Birth</span>
          <p class="text-gray-900 font-medium text-xl"><?php echo date('F d, Y', strtotime($user->get_birthday())) ?></p>
        </div>
        <div>
          <span class="block text-sm font-medium text-[#1F1F21]">Address</span>
          <p class="text-gray-900 font-medium text-xl"><?php echo nl2br($user->get_street_address() . "\n" . $user->get_city() . ', ' . $user->get_state() . ' ' . $user->get_zip_code()) ?></p>
        </div>
        <div>
          <span class="block text-sm font-medium text-[#1F1F21]">Personal Notes</span>
          <p class="text-gray-900 font-medium text-xl"><?php echo $user->get_notes() ?></p>
        </div>
      </div>

      <!-- Contact Section -->
      <div id="contact" class="profile-section space-y-4 hidden">
        <div>
          <span class="block text-sm font-medium text-[#1F1F21]">Email</span>
          <p class="text-gray-900 font-medium text-xl"><?php echo $user->get_email() ?></p>
        </div>
        <div>
          <span class="block text-sm font-medium text-[#1F1F21]">Phone Number</span>
          <p class="text-gray-900 font-medium text-xl"><?php echo formatPhoneNumber($user->get_phone1()) . ' (' . ucfirst($user->get_phone1type()) . ')' ?></p>
        </div>
        <div>
          <span class="block text-sm font-medium text-[#1F1F21]">Emergency Contact Name</span>
          <?php if ($user->get_emergency_contact_first_name()):?>
            <p class="text-gray-900 font-medium text-xl"><?php echo $user->get_emergency_contact_first_name() . " " . $user->get_emergency_contact_last_name()?></p>
          <?php else: ?>
            <p class="text-gray-900 font-medium text-xl">N/A</p>
          <?php endif ?>
        </div>
        <div>
          <span class="block text-sm font-medium text-[#1F1F21]">Emergency Contact Relation</span>
          <?php if ($user->get_emergency_contact_relation()):?>
            <p class="text-gray-900 font-medium text-xl"><?php echo $user->get_emergency_contact_relation()?></p>
          <?php else: ?>
            <p class="text-gray-900 font-medium text-xl">N/A</p>
          <?php endif ?>
        </div>
        <div>
          <span class="block text-sm font-medium text-[#1F1F21]">Emergency Contact Phone Number</span>
          <?php if ($user->get_emergency_contact_phone()): ?>
            <p class="text-gray-900 font-medium text-xl"><?php echo formatPhoneNumber($user->get_emergency_contact_phone()) . ' (' . ucfirst($user->get_emergency_contact_phone_type()) . ')' ?></p>
          <?php else: ?>
            <p class="text-gray-900 font-medium text-xl">N/A</p>
          <?php endif ?>
        </div>
      </div>

      <!-- Email Notifications Section -->
      <div id="notifications" class="profile-section space-y-4 hidden">
        <div>
          <span class="block text-sm font-medium text-[#1F1F21]">Email</span>
          <p class="text-gray-900 font-medium text-xl"><?php echo $user->get_email() ?></p>
        </div>
        <div>
          <span class="block text-sm font-medium text-[#1F1F21]">Receive Emails?</span>
          <?php if ($user->get_email_prefs()):?>
            <p class="text-gray-900 font-medium text-xl"> Yes </p>
          <?php else: ?>
            <p class="text-gray-900 font-medium text-xl"> No </p>
          <?php endif ?>
        </div>
      </div>

      <!-- Additional Information -->
      <div id="additional" class="profile-section space-y-4 hidden">
          <h2 class="text-xl font-semibold mb-4 border-b border-gray-300 pb-2">Availability</h2>
          <table class="text-left text-xl font-medium text-gray-900 whitespace-nowrap">
            <tr><td class="pr-4">Sunday</td><td class="text-right">6:00 pm - 7:00 pm</td></tr>
            <tr><td class="pr-4">Monday</td><td class="text-right">11:00 am - 12:00 pm</td></tr>
            <tr><td class="pr-4">Tuesday</td><td class="text-right">N/A</td></tr>
            <tr><td class="pr-4">Wednesday</td><td class="text-right">N/A</td></tr>
            <tr><td class="pr-4">Thursday</td><td class="text-right">N/A</td></tr>
            <tr><td class="pr-4">Friday</td><td class="text-right">N/A</td></tr>
            <tr><td class="pr-4">Saturday</td><td class="text-right">N/A</td></tr>
          </table>

          <h2 class="text-xl font-semibold mb-4 border-b border-gray-300 pb-2">Languages</h2>
          <div>
            <span class="block text-sm font-medium text-[#1F1F21]">English</span>
            <p class="text-gray-900 font-medium text-xl">Speaking: Fluent</p>
            <p class="text-gray-900 font-medium text-xl">Writing: Fluent</p>
            <p class="text-gray-900 font-medium text-xl">Reading: Advanced</p>
            <p class="text-gray-900 font-medium text-xl">Listening: Fluent</p>
          </div>
          <div>
            <span class="block text-sm font-medium text-[#1F1F21]">Spanish</span>
            <p class="text-gray-900 font-medium text-xl">Speaking: Intermediate</p>
            <p class="text-gray-900 font-medium text-xl">Writing: Beginner</p>
            <p class="text-gray-900 font-medium text-xl">Reading: Intermediate</p>
            <p class="text-gray-900 font-medium text-xl">Listening: Intermediate</p>
          </div>

          <h2 class="text-xl font-semibold mb-4 border-b border-gray-300 pb-2">Skills</h2>
          <ul class="list-inside text-gray-900 font-medium text-xl">
            <li style="list-style-type: disc;">First Aid</li>
            <li style="list-style-type: disc;">Public Speaking</li>
            <li style="list-style-type: disc;">Event Planning</li>
          </ul>

          <h2 class="text-xl font-semibold mb-4 border-b border-gray-300 pb-2">Experience</h2>
          <ul class="list-inside text-gray-900 font-medium text-xl">
            <li style="list-style-type: disc;">3 years volunteering at local food bank</li>
            <li style="list-style-type: disc;">Youth mentorship program coordinator</li>
          </ul>

          <h2 class="text-xl font-semibold mb-4 border-b border-gray-300 pb-2">Access</h2>
          <div>
            <span class="block text-sm font-medium text-[#1F1F21]">Has Computer?</span>
            <?php if ($user->get_computer_access() == "yes"):?>
              <p class="text-gray-900 font-medium text-xl"> Yes </p>
            <?php elseif ($user->get_computer_access() == "no"): ?>
              <p class="text-gray-900 font-medium text-xl"> No </p>
            <?php else: ?>
              <p class="text-gray-900 font-medium text-xl"> Unknown </p>
            <?php endif ?>
          </div>
          <div>
            <span class="block text-sm font-medium text-[#1F1F21]">Has Camera?</span>
            <?php if ($user->get_camera_access() == "yes"):?>
              <p class="text-gray-900 font-medium text-xl"> Yes </p>
            <?php elseif ($user->get_camera_access() == "no"): ?>
              <p class="text-gray-900 font-medium text-xl"> No </p>
            <?php else: ?>
              <p class="text-gray-900 font-medium text-xl"> Unknown </p>
            <?php endif ?>
          </div>
          <div>
            <span class="block text-sm font-medium text-[#1F1F21]">Has Transportation Access?</span>
            <?php if ($user->get_transportation_access() == "yes"):?>
              <p class="text-gray-900 font-medium text-xl"> Yes </p>
            <?php elseif ($user->get_transportation_access() == "no"): ?>
              <p class="text-gray-900 font-medium text-xl"> No </p>
            <?php else: ?>
              <p class="text-gray-900 font-medium text-xl"> Unknown </p>
            <?php endif ?>
            </div>
      </div>

      <!-- Account Security Section -->
      <div id="account" class="profile-section space-y-4 hidden">
        <div>
          <span class="block text-sm font-medium text-[#1F1F21]">Username</span>
          <p class="text-gray-900 font-medium text-xl"><?php echo $user->get_id() ?></p>
        </div>
        <div>
          <span class="block text-sm font-medium text-[#1F1F21]">Password</span>
          <button onclick="window.location.href='changePassword.php<?php if ($id != $userID) echo '?id=' . $id ?>';" class="w-fit text-sm font-medium px-4 py-2 bg-[#2f4159] text-[#FFFFFF] rounded-md hover:bg-[#f5c16e] hover:text-[#FFFFFF] cursor-pointer">Change Password</button>
        </div>
      </div>

    </div>
  </div>

  <div id="verifiedIdsModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 1000;">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-xl font-medium text-gray-900">Verified IDs for <?php echo htmlspecialchars($user->get_first_name()); ?></h3>
            <button class="text-black close-modal cursor-pointer font-bold text-2xl w-20" onclick="closeModal('verifiedIdsModal')">&times;</button>
        </div>

        <div class="mt-4">
            <?php if (empty($verified_ids)): ?>
                <p class="text-gray-600 italic">No verified IDs found for this user.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm font-light">
                        <thead class="border-b font-medium">
                            <tr>
                                <th scope="col" class="px-6 py-4">ID Type</th>
                                <th scope="col" class="px-6 py-4">Date Verified</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($verified_ids as $vid): ?>
                                <tr class="border-b hover:bg-gray-100">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium text-green-700">
                                        ✓ <?php echo htmlspecialchars($vid['id_type']); ?>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-gray-700">
                                        <?php echo date("M j, Y", strtotime($vid['approved_at'])); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-6 flex justify-end">
            <button class="px-4 py-2 bg-[#2f4159] text-white text-base font-medium rounded-md w-auto shadow-sm hover:bg-[#f5c16e] focus:outline-none focus:ring-2 focus:ring-gray-300" onclick="closeModal('verifiedIdsModal')">
                Close
            </button>
        </div>
        
    </div>
  </div>
</body>
</html>