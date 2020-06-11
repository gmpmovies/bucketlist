<?php
session_start();
require_once "/var/www/html/bucketlist/models/notifications.php";
require_once "/var/www/html/bucketlist/models/users.php";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = $_POST['id'];
    $deactivate = Notifications::deactivate_notification($id);
    $lower_count = Users::increaseNotifications($_SESSION['id'], False);
    echo "Done";
}
?>