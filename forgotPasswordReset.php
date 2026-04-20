<?php
session_cache_expire(30);
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);

// Redirect logged-in users away
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    header('Location: index.php');
    die();
}

require_once('database/dbPasswordResets.php');
require_once('include/input-validation.php');

$raw_token = $_GET['token'] ?? ($_POST['token'] ?? '');
$token = preg_replace('/[^a-f0-9]/', '', $raw_token); // tokens are hex only

$token_user_id = $token ? validate_password_reset_token($token) : false;

$error = null;
$success = false;

if (!$token || !$token_user_id) {
    $error = 'This password reset link is invalid or has expired. Please request a new one.';
}

if (!$error && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('database/dbPersons.php');

    $new_password     = $_POST['new-password']        ?? '';
    $confirm_password = $_POST['new-password-reenter'] ?? '';

    if ($new_password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (!isSecurePassword($new_password)) {
        $error = 'Password must be at least 8 characters and contain at least one number, one uppercase, and one lowercase letter.';
    } else {
        // Re-validate token right before consuming it
        $confirmed_user = validate_password_reset_token($token);
        if (!$confirmed_user) {
            $error = 'This reset link has expired. Please request a new one.';
        } else {
            $hash = password_hash($new_password, PASSWORD_BCRYPT);
            change_password($confirmed_user, $hash);
            consume_password_reset_token($token);
            $success = true;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php require_once('universal.inc') ?>
    <title>Gwyneth's Gift | Reset Password</title>
</head>
<body>
    <?php require_once('header.php') ?>
    <h1>Reset Password</h1>
    <main class="login">
        <?php if ($success): ?>
            <div style="width: 24rem; max-width: calc(100vw - 2rem); display: flex; flex-direction: column; gap: 0.5rem;">
                <div class="happy-toast">Your password has been reset. You can now log in.</div>
                <a class="button cancel" href="login.php">Go to Login</a>
            </div>
        <?php elseif ($error): ?>
            <div style="width: 24rem; max-width: calc(100vw - 2rem); display: flex; flex-direction: column; gap: 0.5rem;">
                <p class="error-toast"><?php echo htmlspecialchars($error) ?></p>
                <a class="button cancel" href="forgotPassword.php">Request a New Link</a>
            </div>
        <?php else: ?>
            <form method="post" onsubmit="return validateForm()">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token) ?>">

                <label for="new-password">New Password</label>
                <input type="password" id="new-password" name="new-password" placeholder="Enter new password" required>
                <p id="password-error" class="error hidden">Password needs to be at least 8 characters long, contain at least one number, one uppercase letter, and one lowercase letter!</p>

                <label for="new-password-reenter">Confirm New Password</label>
                <input type="password" id="new-password-reenter" name="new-password-reenter" placeholder="Re-enter new password" required>
                <p id="password-match-error" class="error hidden">Passwords must match!</p>

                <input type="submit" id="submit" value="Reset Password">
                <a class="button cancel" href="login.php">Cancel</a>
            </form>
        <?php endif ?>
    </main>
    <script>
        function validateForm() {
            var newPass  = document.getElementById('new-password').value;
            var reenter  = document.getElementById('new-password-reenter').value;
            var matchErr = document.getElementById('password-match-error');
            var passErr  = document.getElementById('password-error');
            var valid    = true;

            var passRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
            if (!passRegex.test(newPass)) {
                passErr.classList.remove('hidden');
                valid = false;
            } else {
                passErr.classList.add('hidden');
            }

            if (newPass !== reenter) {
                matchErr.classList.remove('hidden');
                valid = false;
            } else {
                matchErr.classList.add('hidden');
            }

            return valid;
        }
    </script>
</body>
</html>
