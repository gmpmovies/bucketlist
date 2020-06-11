<?php
session_start();
require_once "/home1/gobinitc/public_html/bucketlist/models/notifications.php";
require_once "/home1/gobinitc/public_html/bucketlist/models/users.php";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = $_POST['id'];
    $deactivate = Notifications::deactivate_notification($id);
    $lower_count = Users::increaseNotifications($_SESSION['id'], False);
    echo "Done";
}
?>