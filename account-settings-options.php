<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bucket</title>
    <?php require_once "/home1/gobinitc/public_html/bucketlist/html_style_head.php"?>
    <link rel="stylesheet" href="/assets/styles/settings.css">
</head>
<body>
<?php require_once "/home1/gobinitc/public_html/bucketlist/navbar.php"; ?>
<div class="default-margin">
    <h1 style="text-align: center;">Account Settings</h1>
    <div class="feed-container">
        <a href="/account_settings.php">
            <div class="settings-item">
                <p class="settings-text">Update Profile Picture</p>
                <img class="settings-icon" src="/assets/icons/next.svg"/>
            </div>
        </a>
        <hr>
        <a href="/reset-password.php">
            <div class="settings-item">
                <p class="settings-text">Reset Password</p>
                <img class="settings-icon" src="/assets/icons/next.svg"/>
            </div>
        </a>
        <hr>
        <a href="/logout.php">
            <div class="settings-item">
                <p class="settings-text">Logout</p>
                <img class="settings-icon" src="/assets/icons/next.svg"/>
            </div>
        </a>
        <hr>
    </div>
</div>


<?php require_once "/home1/gobinitc/public_html/bucketlist/html_body_scripts.php"?>
<script src="/assets/scripts/upload-file.js"></script>
</body>
</html>
