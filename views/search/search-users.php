<?php
session_start();
require_once "/home1/gobinitc/public_html/bucketlist/pageload.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Search</title>
    <?php require_once "/home1/gobinitc/public_html/bucketlist/html_style_head.php"?>
</head>
<body>
<?php require_once "/home1/gobinitc/public_html/bucketlist/navbar.php"; ?>
<div class="default-margin">
    <input class="form-control mr-sm-2 js-user-search-form" name="search" type="search" placeholder="Search for Users" aria-label="Search" required autocomplete="off">
    <div class="feed-container">
        <div class="js-search-results">


        </div>
    </div>
</div>

<?php require_once "/home1/gobinitc/public_html/bucketlist/html_body_scripts.php"?>
<script src="/bucketlist/assets/scripts/user-search.js"></script>
</body>
</html>
