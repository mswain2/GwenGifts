<?php
session_cache_expire(30);
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once __DIR__ . '/email/PHPMailer/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/email/PHPMailer/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/email/PHPMailer/PHPMailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Redirect logged-in users away
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    header('Location: index.php');
    die();
}

$sent = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('include/input-validation.php');
    require_once('database/dbPasswordResets.php');

    $args = sanitize($_POST, ['email']);
    $email = trim($args['email'] ?? '');

    if (!empty($email)) {
        $user_id = retrieve_person_id_by_email($email);
        if ($user_id) {
            $token = create_password_reset_token($user_id);
            if ($token) {
                $scheme   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                $base_url = $scheme . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $reset_link = $base_url . '/forgotPasswordReset.php?token=' . urlencode($token);

                $env = [];
                $env_file = __DIR__ . '/email/.env';
                if (file_exists($env_file)) {
                    foreach (file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                        $line = trim($line);
                        if ($line === '' || strpos($line, '#') === 0) continue;
                        [$k, $v] = explode('=', $line, 2);
                        $env[trim($k)] = trim($v);
                    }
                }

                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = $env['SMTP_HOST'];
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $env['SMTP_USER'];
                    $mail->Password   = $env['SMTP_PASS'];
                    $mail->SMTPSecure = 'tls';
                    $mail->Port       = $env['SMTP_PORT'];
                    $mail->setFrom($env['SMTP_USER'], $env['SMTP_FROM_NAME']);
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->Subject = "Gwyneth's Gift Password Reset";
                    $mail->Body    = "
                        <p>Hello,</p>
                        <p>We received a request to reset your password. Click the link below to set a new password. This link expires in <strong>15 minutes</strong>.</p>
                        <p><a href=\"$reset_link\">Reset my password</a></p>
                        <p>If you did not request this, you can safely ignore this email.</p>
                        <p>— Gwyneth's Gift</p>
                    ";
                    $mail->send();
                } catch (Exception $e) {
                    // Silently fail — don't reveal send errors to the user
                    error_log('Password reset email failed: ' . $mail->ErrorInfo);
                }
            }
        }
    }

    // Always show the same message regardless of whether the email was found
    $sent = true;
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php require_once('universal.inc') ?>
    <title>Gwyneth's Gift | Forgot Password</title>
</head>
<body>
    <?php require_once('header.php') ?>
    <h1>Forgot Password</h1>
    <main class="login">
        <?php if ($sent): ?>
            <div style="width: 24rem; max-width: calc(100vw - 2rem); display: flex; flex-direction: column; gap: 0.5rem;">
                <div class="happy-toast">If an account with that email exists, a reset link has been sent. The link expires in 15 minutes.</div>
                <a class="button cancel" href="login.php">Back to Login</a>
            </div>
        <?php else: ?>
            <p>Enter the email address associated with your account and we'll send you a link to reset your password.</p>
            <form method="post">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
                <input type="submit" value="Send Reset Link">
                <a class="button cancel" href="login.php">Back to Login</a>
            </form>
        <?php endif ?>
    </main>
</body>
</html>
