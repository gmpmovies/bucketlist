<?php
    session_start();
    require_once "/var/www/html/bucketlist/models/notifications.php";
    require_once "/var/www/html/bucketlist/pageload.php";

    $notifications = Notifications::get_notifications($_SESSION['id']);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Bucket</title>
        <?php require_once "/var/www/html/bucketlist/html_style_head.php"?>
        <link rel="stylesheet" href="/assets/styles/notifications.css">
    </head>
    <body>
        <?php require_once "/var/www/html/bucketlist/navbar.php"; ?>
        <div class="default-margin">
            <div class="notification-container">
            <?php
            foreach($notifications as $notification){
                $time_then = $notification->get_created_date();
                date_default_timezone_set("America/Denver");
                $time_now = date("Y-m-d H:i:s");

                $time_then = strtotime($time_then);
                $time_now = strtotime($time_now);

                $time_diff_display='';
                $time_diff=$time_now - $time_then;
                if($time_diff <= 60){
                    $time_diff_display = $time_diff . " seconds ago";
                } elseif(floor($time_diff / 60 ) <= 60){
                    $time_diff_display = floor($time_diff / 60) . " min ago";
                } elseif(floor($time_diff / (60 * 60)) <= 24){
                    if(floor($time_diff / (60 * 60)) == 1){
                        $time_diff_display = floor($time_diff / (60 * 60)) . " hour ago";
                    } else {
                        $time_diff_display = floor($time_diff / (60 * 60)) . " hours ago";
                    }
                } else {
                    if(floor($time_diff / (60 * 60 * 24)) == 1){
                        $time_diff_display = floor($time_diff / (60 * 60 * 24)) . " day ago";
                    } else {
                        $time_diff_display = floor($time_diff / (60 * 60 * 24)) . " days ago";
                    }
                }

                echo '
                        <div data-active="' . $notification->get_is_active() . '" data-url="' . $notification->get_referral() . '" data-id="' . $notification->get_id() . '" class="single-notification ' . (($notification->get_is_active())?'notification-container-unread':'notification-container-read') . '">
                            <div class="profile_pic_container" style="height: 50px; float: left; margin-right: 10px;">
                                <img class="profile_pic" src="/uploads/' . $notification->get_image() . '">
                            </div>
                            <p class="notification-text">'. $notification->get_notification_title() .'</p>
                            <p style="font-size: 9px; margin: -10px 0px 0px 0px; padding-left: 60px;">'. $time_diff_display .'</p>
                        </div>
                    
                    ';
            }
            ?>
            </div>
        </div>
        <?php require_once "/var/www/html/bucketlist/html_body_scripts.php"?>
        <script src="/assets/scripts/notification.js"></script>
    </body>
</html>

