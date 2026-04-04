<?php
    session_cache_expire(30);
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>Gwyneth's Gift | Event Created</title>
        <style>
            .happy-toast {
                background: #d4edda;
                color: #155724;
                padding: 14px 24px;
                border-radius: 8px;
                font-weight: 700;
                font-size: 18px;
                display: inline-block;
                margin-top: 20px;
            }
            .redirect-msg {
                color: #828282;
                font-size: 14px;
                margin-top: 10px;
            }
            main {
                text-align: center;
                padding-top: 40px;
            }
        </style>
        <script>
            setTimeout(function() {
                window.location.href = 'calendar.php';
            }, 3000);
        </script>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <main class="date">
            <div class="happy-toast">✓ Event created successfully!</div>
            <p class="redirect-msg">Redirecting you to the calendar in 3 seconds...</p>
        </main>
    </body>
</html>
