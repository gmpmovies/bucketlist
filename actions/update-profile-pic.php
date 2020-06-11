<?php
require_once "/var/www/html/bucketlist/models/files.php";
session_start();
$following_id = $_SESSION['id'];
$imageId = $_POST['imageid'];

$file = new Files();
$file = Files::updateProfilePic($imageId, $following_id);
if($file == True){
    echo "Finished updating: " . $imageId . " for user: " . $following_id;
} else {
    new Exception("Error: " . $file);
    echo($file);
}
?>