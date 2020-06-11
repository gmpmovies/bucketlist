<?php
    session_start();
//    ini_set('display_errors', 1);
//    ini_set('display_startup_errors', 1);

    require_once "config.php";
    require_once "/var/www/html/bucketlist/pageload.php";


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Feed</title>
    <!-- Add the slick-theme.css if you want default styling -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <!-- Add the slick-theme.css if you want default styling -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    <?php require_once "/var/www/html/bucketlist/html_style_head.php"?>
</head>
<body>
<?php require_once "/var/www/html/bucketlist/navbar.php"; ?>
<div class="default-margin">
    <div class="row">
        <div class="col-md-5">
           <?php
           if(isMobile()){

           } else {
           require_once "/var/www/html/bucketlist/partial/bucket/all-buckets.php";
           }
           ?>
        </div>

        <div class="col-md-7">
            <?php require_once "/var/www/html/bucketlist/partial/home/feed.php" ?>
        </div>

    </div>

</div>

<?php require_once "/var/www/html/bucketlist/html_body_scripts.php"?>
<script src="/assets/scripts/infinite-feed-scroll.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
</body>
</html>
