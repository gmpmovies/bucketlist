<?php
session_start();
require_once "/home1/gobinitc/public_html/bucketlist/pageload.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Buckets</title>
    <?php require_once "/home1/gobinitc/public_html/bucketlist/html_style_head.php"?>
</head>
    <body>
        <?php require_once "/home1/gobinitc/public_html/bucketlist/navbar.php"; ?>
        <div class="default-margin">
            <?php require_once "/home1/gobinitc/public_html/bucketlist/partial/bucket/all-buckets.php"; ?>
        </div>
        <?php require_once "/home1/gobinitc/public_html/bucketlist/html_body_scripts.php"?>
    </body>
</html>
