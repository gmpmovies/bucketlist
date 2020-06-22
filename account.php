<?php
session_start();
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
$account_id = $_GET["userid"];
$user_id = $_SESSION["id"];

require_once "/home1/gobinitc/public_html/bucketlist/config.php";
require_once "/home1/gobinitc/public_html/bucketlist/pageload.php";
require_once "/home1/gobinitc/public_html/bucketlist/models/users.php";
require_once "/home1/gobinitc/public_html/bucketlist/models/bucket_users.php";
require_once "/home1/gobinitc/public_html/bucketlist/models/followers.php";

error_reporting(E_ALL);

$user = Users::getUser($account_id);

#Get buckets that user may have in common with another
$common_buckets = array();
if($account_id != $user_id){
    $common_buckets = Bucket_Users::getCommonBuckets($user_id, $account_id);
}

//if($user->get_id()==null){
//    header("location: /error.php");
//}
#Check to see if this is current users profile, or another profile
$isCurrentUser = True;
if($user->get_id() != $_SESSION['id']){
    $isFollowing = Followers::isFollowing($user);
    $isCurrentUser = False;
}
$date = date_create($user->get_created_at());


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Add the slick-theme.css if you want default styling -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <!-- Add the slick-theme.css if you want default styling -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    <title><?php echo $user->get_firstname() . " " . $user->get_lastname(0) ?> Account</title>
    <?php require_once "/home1/gobinitc/public_html/bucketlist/html_style_head.php"?>
    <link rel="stylesheet" href="/bucketlist/assets/styles/account.css"/>
</head>
<body>
<?php require_once "/home1/gobinitc/public_html/bucketlist/navbar.php"; ?>
<?php require_once "/home1/gobinitc/public_html/bucketlist/partial/account/popup-followers-following.php"; ?>
<?php require_once "/home1/gobinitc/public_html/bucketlist/partial/account/popup-following.php"; ?>
<div class="default-margin">
    <div class="row">
        <div class="col-md-4">
            <div class="feed-container">

                <?php
                    echo"
                    <div class='account_container'>
                        <div class='profile_pic_container_account' style='position: relative;'>
                            " . (($isCurrentUser == True)?'<a href="/bucketlist/account_settings.php"><p class=\'update_profile_text\'>Update Image</p><div class=\'update_profile_bg\'></div></a>':'') . "
                            
                            
                            <img class='profile_pic' src=\"/bucketlist/uploads/" . $user->get_File()->get_filename() . "\" />
                            
                        </div>
                        <p class='name'>" . $user->get_firstname() . " " . $user->get_lastname() . "</p>
                        <p class='username'>@" . $user->get_username() . "</p>
                        <p>Member Since: " .date_format($date, 'F d, Y')  . " </p>
                        " . (($isCurrentUser == False)?(($isFollowing==True)?'<button type="button" data-clicked="False" data-user-id="' . $user->get_id() . '" data-type="Unfollow" class="btn btn-secondary js_follow_user">Following</button>': '<button type="button" class="btn btn-primary js_follow_user" data-type="Follow" data-clicked="False" data-user-id="' . $user->get_id() . '">Follow</button>'): '') . "
                    </div>
                    <div class='row' style='margin-top: 5px;'>
                        <div class='col-6 follow-count' style='text-align: center; border-right: solid 1px black;' data-toggle='modal' data-target='#followersModal'>
                            <p class='number-follow' style='font-size: 20px'>" . (($user->get_number_followers()==null)?'0':$user->get_number_followers()) . "</p> 
                            <p class='number-follow'>Followers</p>          
                        </div>
                        <div class='col-6 follow-count' style='text-align: center;' data-toggle='modal' data-target='#followingModal'>
                            <p class='number-follow' style='font-size: 20px'>" . (($user->get_number_following()==null)?'0':$user->get_number_following()) . "</p>
                            <p class='number-follow'>Following</p>  
                        </div>
                    </div>
                    " . (($isCurrentUser == True)?'<div style="text-align: center; margin-top: 5px;"><a href="/bucketlist/account-settings-options.php" style="width: 100%;" class="btn btn-outline-secondary">Account Settings</a></div>':'') . "
                    "

                ?>

            </div>

            <?php if(isMobile() == False){ require_once "/home1/gobinitc/public_html/bucketlist/partial/account/following-followers.php"; } ?>

            <?php
            if(count($common_buckets) <=1){
                foreach($common_buckets as $bucket) {

                    echo "
                    <div class=\"feed-container\">
                        <p class='center_title'>Shared Buckets</p>
                        <div class=\"bucket-container\">
                            <a href=\"/bucketlist/bucket/bucket.php?bucketid=" . $bucket->get_id() . "\">" . $bucket->get_title() . "</a>
                            <p>Owned By: </p>
                            
                            <p style=\"margin: 0px 10px 10px 10px;\">
                                <a class=\"profile_pic_link\" href=\"/bucketlist/account.php?userid=" .$bucket->get_User()->get_id() . "\">
                                    <span class=\"profile_pic_container_search \" style=\"vertical-align: middle;\">
                                        <img src='/bucketlist/uploads/" . $bucket->get_User()->get_File()->get_filename() . "' class=\"profile_pic\"/>
                                    </span> 
                                    <span>" . $bucket->get_User()->get_firstname() . " " . $bucket->get_User()->get_lastname() . "</span>
                                </a>
                            </p>
                        </div>
                    </div>
                    ";
                }
            }
            ?>

        </div>

        <div class="col-md-8">
            <link rel="stylesheet" href="/bucketlist/assets/styles/feed.css">
            <div class="infinite-feed-container"></div>
            <div class="feed-post-container" style="padding: 20px;">
                <div class="feed-text-height feed-loading-indicator">
                    <p>
                        <a class="profile_pic_link" href="#">
                    <span class="profile_pic_container_search" style="vertical-align: middle">
                        <img src="/bucketlist/uploads/default.jpeg" class="profile_pic">
                    </span>
                            <span> ... </span>
                        </a>
                    </p>
                    <div style="text-align: center;">
                        <img src="/bucketlist/assets/icons/Blocks-1s-200px.svg" style="width: 100px"/>
                    </div>

                </div>
                <p style="text-align: center;" class="d-none feed-end-of-feed">You have reached the end of <?php echo $user->get_firstname() . "'s" ?> feed.</p>
            </div>
            <input id="feed-content-data-tags" type="hidden" data-offset="0" data-wait="False" data-loc="profile" data-query="True">
        </div>

    </div>

</div>

<?php require_once "/home1/gobinitc/public_html/bucketlist/html_body_scripts.php"?>
<script src="/bucketlist/assets/scripts/populate-followers-modal.js"></script>
<script src="/bucketlist/assets/scripts/infinite-feed-scroll.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
</body>
</html>
