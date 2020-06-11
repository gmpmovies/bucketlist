<?php
require_once ("/var/www/html/bucketlist/config.php");
require_once ("/var/www/html/bucketlist/models/users.php");
require_once ("/var/www/html/bucketlist/models/content.php");
require_once ("/var/www/html/bucketlist/models/post.php");
require_once ("/var/www/html/bucketlist/models/listitem.php");

class Notifications {
    public $id; //ID (Int)
    public $notification_type;
    public $user_id;
    public $triggered_by;
    public $Trigger;
    public $is_active;
    public $User;
    public $created_date;
    public $content_id;
    public $Content;

    public $notification_title;
    public $referral;
    public $image;

    function __construct()
    {

    }

    //NOTIFICATION TYPES:
    //1 - User checked off an item, notify all related users to add their own post.
    //2 - User created an item, notify all related users of the new item.

    public static function get_notifications($user_id, $limit = 20, $offset = 0){
        global $mysqli;
        $all_notifications = array();
        $sql = "SELECT id, notification_type, user_id, triggered_by, is_active, content_id, created_date
                FROM notifications
                WHERE user_id = ?
                ORDER BY created_date DESC
                LIMIT ? OFFSET ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('iii', $param_user_id, $param_limit, $param_offset);
            $param_user_id = $user_id;
            $param_limit = $limit;
            $param_offset = $offset;

            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows >= 1){
                    $stmt->bind_result($id, $notification_type, $user_id, $triggered_by, $is_active, $content_id, $created_date);
                    while($stmt->fetch()){
                        $instance = new self();
                        $instance->set_id($id);
                        $instance->set_notification_type($notification_type);
                        $instance->set_user_id($user_id);
                        $instance->set_triggered_by($triggered_by);
                        $instance->set_is_active($is_active);
                        $instance->set_content_id($content_id);
                        $instance->set_notification_title($notification_type);
                        $instance->set_created_date($created_date);

                        array_push($all_notifications, $instance);
                    }
                }
            }
            $stmt->close();
        }
        return $all_notifications;
    }

    public static function deactivate_notification($id){
        global $mysqli;
        $sql = "UPDATE notifications
                SET is_active = ?
                WHERE id = ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('ii', $param_is_active, $param_id);
            $param_is_active = False;
            $param_id = $id;
            error_log("I'M HERE DEACTIVATING STUFF");
            if($stmt->execute()){
                return True;
            }
            $stmt->close();
        }
        else {
            throw new Exception($mysqli->error);
        }
        return False;
    }

    public static function generate_notification($notification_type, $user_id, $triggered_by, $content_id){
        global $mysqli;
        date_default_timezone_set("America/Denver");
        $mysql_date_now = date("Y-m-d H:i:s");
        $is_active = True;
        $sql = "INSERT INTO notifications(notification_type, user_id, triggered_by, is_active, created_date, content_id) VALUES (?,?,?,?,?,?)";
        if($stmt=$mysqli->prepare($sql)){
            $stmt->bind_param('iiiisi', $param_notification_type, $param_user_id, $param_triggered_by, $param_is_active, $param_created_date, $param_content_id);
            $param_notification_type = $notification_type;
            $param_user_id = $user_id;
            $param_triggered_by = $triggered_by;
            $param_is_active = $is_active;
            $param_created_date = $mysql_date_now;
            $param_content_id = $content_id;

            if($stmt->execute()){
                return True;
            }
            $stmt->close();
        }
        return False;
    }


    function set_id($id){
        $this->id = $id;
    }
    function get_id(){
        return $this->id;
    }
    function set_notification_type($notification_type){
        $this->notification_type = $notification_type;
    }
    function get_notification_type(){
        return $this->notification_type;
    }
    function set_user_id($user_id){
        $this->user_id = $user_id;
        $this->User = Users::getUser($user_id);
    }
    function get_user_id(){
        return $this->user_id;
    }
    function set_User($User){
        $this->User = $User;
    }
    function get_User(){
        return $this->User;
    }
    function set_Trigger($Trigger){
        $this->Trigger = $Trigger;
    }
    function get_Trigger(){
        return $this->Trigger;
    }
    function set_content_id($content_id){
        $this->content_id = $content_id;
    }
    function get_content_id(){
        return $this->content_id;
    }
    function set_Content($Content){
        $this->Content = $Content;
    }
    function get_Content(){
        return $this->Content;
    }
    function set_created_date($created_date){
        $this->created_date = $created_date;
    }
    function get_created_date(){
        return $this->created_date;
    }

    function set_triggered_by($triggered_by){
        $this->triggered_by = $triggered_by;
    }
    function get_triggered_by(){
        return $this->triggered_by;
    }
    function set_is_active($is_active){
        $this->is_active = $is_active;
    }
    function get_is_active(){
        return $this->is_active;
    }
    function set_notification_title($notification_type){
        $notification_title = '';
        if($notification_type == 1){
            $this->Trigger = Users::getUser($this->triggered_by);
            $this->Content = Post::getPost($this->content_id);
            $notification_title = "<strong>" . $this->Trigger->get_firstname() . " " . $this->Trigger->get_lastname() . "</strong> checked off " . $this->Content->get_Item()->get_item_name() . " from " . $this->Content->get_Item()->get_Bucket()->get_title() . ". Click here to add your memory now!";
            $referral = "/bucket/bucket.php?bucketid=" . $this->Content->get_Item()->get_Bucket()->get_id() . "&addmemory=" . $this->Content->get_itemID();
            $this->set_referral($referral);
            $this->set_image($this->Trigger->get_File()->get_filename());
        } elseif($notification_type == 2) {
            $this->Trigger = Users::getUser($this->triggered_by);
            $this->Content = Listitem::getListItem($this->content_id);
            $notification_title = "<strong>" . $this->Trigger->get_firstname() . " " . $this->Trigger->get_lastname() . "</strong> added " . $this->Content->get_item_name() . " to <strong>" . $this->Content->get_Bucket()->get_title() . "</strong>.";
            $referral = "/bucket/bucket.php?bucketid=" . $this->Content->get_Bucket()->get_id() . "&addmemory=" . $this->Content->get_id();
            $this->set_referral($referral);
            $this->set_image($this->Trigger->get_File()->get_filename());
        }

        $this->notification_title = $notification_title;
    }
    function get_notification_title(){
        return $this->notification_title;
    }
    function set_referral($referral){
        $this->referral = $referral;
    }
    function get_referral(){
        return $this->referral;
    }
    function set_image($image){
        $this->image = $image;
    }
    function get_image(){
        return $this->image;
    }

}

?>