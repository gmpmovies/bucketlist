<?php
require_once "/home1/gobinitc/public_html/bucketlist/models/bucket_users.php";
require_once "/home1/gobinitc/public_html/bucketlist/config.php";
$user_id = strtolower($_POST['userid']);
$bucket_id = strtolower($_POST['bucketid']);
$isAdmin = strtolower($_POST['isAdmin']);

$add_user = Bucket_Users::addUserToBucket($user_id, $bucket_id, $isAdmin);
if($add_user==False){
    throw new Exception("There was an issue saving the user");
} else {
    echo True;
}
?>