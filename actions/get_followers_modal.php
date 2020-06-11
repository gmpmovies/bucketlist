<?php
require_once "/home1/gobinitc/public_html/bucketlist/models/users.php";
require_once "/home1/gobinitc/public_html/bucketlist/models/followers.php";
require_once "/home1/gobinitc/public_html/bucketlist/config.php";

session_start();
$user_id = strtolower($_POST['userid']);
$type = $_POST['type'];

$Limit = 1000;
$Offset = 0;

if($type == "followers"){
    $followers = Followers::getAllFollowers($user_id, False, $Limit, $Offset);
} else {
    $followers = Followers::getAllFollowers($user_id, True, $Limit, $Offset);
}
$all_followers = array();

foreach($followers as $follower){
    if($follower->get_id() != $_SESSION['id']){
        $isFollowing = Followers::isFollowing($follower);
        if($isFollowing == True){
            $follower->set_follow_flag(True);
        } else {
            $follower->set_follow_flag(False);
        }
    }
    array_push($all_followers, $follower);
}


if(count($followers) >= 1){
    echo json_encode($all_followers);
} else {
    echo "There was an error in the call";
}


?>