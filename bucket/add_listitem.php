<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
require_once ("/home1/gobinitc/public_html/bucketlist/models/bucket_users.php");
require_once ("/home1/gobinitc/public_html/bucketlist/models/notifications.php");
require_once ("/home1/gobinitc/public_html/bucketlist/models/users.php");

$bucket_id = $_GET["bucketid"];
$item_name = $item_desc = "";
$item_name_err = "";

$now = new DateTime();

$dt = $now->format('Y-m-d');
if($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["item"])) {
        $item_name_err = "Please enter an item.";
    } else {
        $item_name = $_POST["item"];
    }
    if($item_name_err == ""){
        $item_desc = $_POST["itemdesc"];
        $owner_id = $_SESSION["id"];

        $sql = "INSERT INTO listitem (bucketid, item_name, item_desc, is_done, date, ownerid) VALUES(?,?,?,?,?,?)";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("issisi", $param_bucketid, $param_item_name, $param_item_desc, $param_is_done, $param_date, $param_ownerid);
            $param_bucketid=$bucket_id;
            $param_item_name = $item_name;
            $param_item_desc = $item_desc;
            $param_is_done = 0;
            $param_date = $dt;
            $param_ownerid = $owner_id;

            if($stmt->execute()){
                $new_item_id = $stmt->insert_id;
                //Send notifications to all Users
                $all_users_notification = Bucket_Users::getBucketMembers($bucket_id);
                foreach($all_users_notification as $notify_user){
                    if($notify_user->get_id() != $_SESSION['id']){
                        $notify = Notifications::generate_notification(2, $notify_user->get_id(), $_SESSION['id'], $new_item_id);
                        $increase_notification_cnt = Users::increaseNotifications($notify_user->get_id());
                    }
                }
                header("location: /bucketlist/bucket/bucket.php?bucketid=" . $bucket_id);
            }
            else {
                echo("There was an issue executing the SQL statment: " . $mysqli->error);
            }
            $stmt->close();
        } else {
            echo "There was an issue with the SQL prepare statement: " . $mysqli->error;
        }
    }



}
?>