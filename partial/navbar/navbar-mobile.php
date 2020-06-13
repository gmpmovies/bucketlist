<link rel="stylesheet" href="/bucketlist/assets/styles/navbar-mobile.css">

<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
session_start();
require_once "/home1/gobinitc/public_html/bucketlist/models/users.php";
$mobile_navbar_user = new Users();

$active_notification = False;

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){

} else {
    $navbar_id = $_SESSION['id'];
    $mobile_navbar_user = Users::getUser($navbar_id);
}

$current_page = $_SERVER['REQUEST_URI'];
if (substr($current_page, 0, 18) == "/bucketlist/bucket"){
    $current_page = "/bucket";
}
if(substr($current_page, 0, 23) == "/bucketlist/account.php"){
    if($navbar_id == $account_id){
        $current_page = "self";
    }
}
if (substr($current_page, 0, 24) == "/bucketlist/views/search"){
    $current_page = "/search";
}
if (substr($current_page, 0, 31) == "/bucketlist/views/notifications"){
    $current_page = "/notifications";
}

if($mobile_navbar_user->get_number_notifications() >= 1){
    $active_notification = True;
    $num_notifications = $mobile_navbar_user->get_number_notifications();
    if($num_notifications >9){
        $num_notifications = "9+";
    }
}

?>
<div class="navbar-mobile-container">
    <div class="row navbar-margins">
        <?php
        if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){

        } else {
            echo "
                    <div class=\"col-2 item_container mobile-navbar-js-loading\">
                        <a href=\"/bucketlist/feed.php\" class='mobile-navbar-js-loading navbar-mobile-link'>
                            " . (($current_page == '/bucketlist/feed.php')?'<img class="navbar-icon" src="/bucketlist/assets/icons/home_active.svg"/>':'<img class="navbar-icon" src="/bucketlist/assets/icons/home.svg"/>') . "
                        </a>
                    </div>
            
            
                    <div class=\"col-2 item_container mobile-navbar-js-loading\">
                        <a href=\"/bucketlist/views/search/search-users.php\" class='navbar-mobile-link mobile-navbar-js-loading'>
                            " . (($current_page == "/search")?'<img class="navbar-icon" src="/bucketlist/assets/icons/search_active.svg"/>':'<img class="navbar-icon" src="/bucketlist/assets/icons/search.svg"/>') . "
                        </a>
                    </div>
            
            
                    <div class=\"col-4 item_container mobile-navbar-js-loading\">
                        <a href=\"/bucketlist/bucket/mobile-all-buckets.php\" class='navbar-mobile-link mobile-navbar-js-loading'>
                            " . (($current_page == '/bucket')?'<img class="navbar-icon" src="/bucketlist/assets/icons/pail_active.svg"/>':'<img class="navbar-icon" src="/bucketlist/assets/icons/pail.svg"/>') . "
                        </a>
                    </div>
            
            
                    <div class=\"col-2 item_container mobile-navbar-js-loading\">
                        <a href=\"/bucketlist/views/notifications/notifications.php\" class='navbar-mobile-link mobile-navbar-js-loading'>
                            " . (($active_notification)?'<p class=\'notification-indicator\'>' . $num_notifications . '</p>':'') . "
                            " . (($current_page == "/notifications")?'<img class="navbar-icon" src="/bucketlist/assets/icons/alarm_active.svg"/>':'<img class="navbar-icon" src="/bucketlist/assets/icons/alarm.svg"/>') . "
                        </a>
                    </div>
            
            
                    <div class=\"col-2 item_container mobile-navbar-js-loading\" style=\"border-right: none;\">
                        <a href=\"/bucketlist/account.php?userid=" . $navbar_id . "?>\" class='navbar-mobile-link mobile-navbar-js-loading'>
                            <div style='padding-top: 6px;'>
                                <div class=\"navbar-photo-container " . (($current_page == "self")?'profile_active':'') . "\">
                                    <img class=\"navbar-profile-photo\" src=\"/bucketlist/uploads/" . $mobile_navbar_user->get_File()->get_filename() . "\"/>
                                </div>
                            </div>
                        </a>
                    </div>
            ";
        }
        ?>

    </div>
</div>

<div style="max-width: 800px; margin: auto;" class="js-error-message"></div>
<div class="d-none js-mobile-navbar-loading-show" style="">
    <div style="z-index: 9999999999999999999999999999999999999999999999999999999999; position: fixed; background-color: #fff; border-radius: 8px; height: 100px; width: 100px; top: 50%; left: 50%; transform: translateY(-50%) translateX(-50%); "></div>
    <img alt="loading icon" src="/bucketlist/assets/icons/spinner.svg" style="z-index: 9999999999999999999999999999999999999999999999999999999999; position: fixed; width: 85px; top: 50%; left: 50%; transform: translateY(-50%) translateX(-50%);"/>
</div>
