<?php
require_once ("/home1/gobinitc/public_html/bucketlist/config.php");
require_once ("/home1/gobinitc/public_html/bucketlist/models/bucket.php");
require_once ("/home1/gobinitc/public_html/bucketlist/models/users.php");
require_once ("/home1/gobinitc/public_html/bucketlist/models/post.php");
require_once ("/home1/gobinitc/public_html/bucketlist/models/post_content.php");

class Listitem {
    // Properties
    public $id;
    public $bucketid;
    public $Bucket;
    public $item_name;
    public $item_desc;
    public $is_done;
    public $date;
    public $ownerid;
    public $Owner;

    public $current_user_has_created_post;

    function __construct()
    {
        $this->Owner = new Users();
        $this->Bucket = new Bucket();
    }

    public static function setAll($id, $bucketid, $item_name, $item_desc, $is_done, $date, $ownerid){
        $instance = new self();
        $instance->set_id($id);
        $instance->set_bucketid($bucketid);
        $instance->set_item_name($item_name);
        $instance->set_item_desc($item_desc);
        $instance->set_is_done($is_done);
        $instance->set_date($date);
        $instance->set_ownerid($ownerid);
        return $instance;
    }

    public static function checkOffItem($itemID){
        global $mysqli;
        $sql = "UPDATE listitem
                SET is_done = TRUE 
                WHERE id = ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('i', $param_id);
            $param_id = $itemID;

            if($stmt->execute()){
                return True;
            } else {
                new Exception("Issue executing SQL query " . $stmt->error);
                return $stmt->error;
            }
        } {
            new Exception("Error preparying SQL query " . $mysqli->error);
            return $mysqli->error;
        }
    }

    public static function getListItem($itemID){
        global $mysqli;
        $instance = new self();
        $sql="SELECT id, bucketid, item_name, item_desc, is_done, date, ownerid
              FROM listitem
              WHERE id = ?";

        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('i', $param_itemID);
            $param_itemID = $itemID;

            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows == 1){
                    $stmt->bind_result($id, $bucketid, $item_name, $item_desc, $is_done, $date, $ownerid);
                    while($stmt->fetch()){
                        $instance->set_id($id);
                        $instance->set_bucketid($bucketid);
                        $instance->set_item_name($item_name);
                        $instance->set_item_desc($item_desc);
                        $instance->set_is_done($is_done);
                        $instance->set_date($date);
                        $instance->set_ownerid($ownerid);
                    }
                }
            } else {
                echo $stmt->error;
            }
            $stmt->close();
        } else {
            echo $mysqli->error;
        }

        return $instance;
    }

    public static function validateItem($itemId, $userId){
        global $mysqli;
        $sql = "SELECT listitem.id, bucket.isPrivate, bucket.id
                FROM listitem INNER JOIN bucket ON listitem.bucketid = bucket.id
                WHERE listitem.id = ? and listitem.ownerid = ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('ii', $param_id, $param_ownerId);
            $param_id = $itemId;
            $param_ownerId = $userId;

            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows == 1){
                    $stmt->bind_result($id, $private, $bucketid);
                    while($stmt->fetch()){
                        return array(True, $private, $bucketid);
                    }
                } else {
                    return array (False, None, None);
                }
            } else {
                new Exception($stmt->error);
            }
        } else {
            new Exception($mysqli->error);
        }
    }

    public static function getAllInstances($bucketid){
        global $mysqli;
        $items= array();

        $sql = "SELECT id, bucketid, item_name, item_desc, is_done, date, ownerid
                FROM listitem
                WHERE bucketid = ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('i', $param_bucketid);
            $param_bucketid = $bucketid;

            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows >= 1){
                    $stmt->bind_result($id, $bucketid, $item_name, $item_desc, $is_done, $date, $ownerid);
                    while($stmt->fetch()){
                        $instance = new self();
                        $instance->set_id($id);
                        $instance->set_bucketid($bucketid);
                        $instance->set_item_name($item_name);
                        $instance->set_item_desc($item_desc);
                        $instance->set_is_done($is_done);
                        $instance->set_date($date);
                        $instance->set_ownerid($ownerid);
                        $instance->set_current_user_has_created_post(0);
                        if($is_done == True){
                            $post = Post::isPostCreated($id);
                            if($post[0] == True){
                                $is_posted = Post_Content::get_post_content_by_postID_and_userID($post[1], $_SESSION['id'], True);
                                if($is_posted == True){
                                    $instance->set_current_user_has_created_post(True);
                                }
                            }
                        }
                        array_push($items, $instance);
                    }
                }
            } else {
                $stmt->close();
            }
        } else {
            $mysqli->close();
        }

        return $items;
    }

    // Methods
    function set_id($id) {
        $this->id = $id;
    }
    function get_id() {
        return $this->id;
    }
    function set_bucketid($bucketid) {
        $this->bucketid = $bucketid;
        global $mysqli;
        $sql = "SELECT id, ownerid, title, description FROM bucket WHERE id = ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("i", $param_id);
            $param_id = $bucketid;

            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows == 1){
                    $stmt->bind_result($id, $ownerid, $title, $description);
                    while($stmt->fetch()){
                        $this->Bucket = Bucket::getBucket($id);
                    }

                }

                $stmt->close();
            } else {
                echo "An error occured in the Execute " . $mysqli->error;
            }
        } else {
            echo "An error occured in the Prepare " . $mysqli->error;
        }

    }
    function get_bucketid() {
        return $this->bucketid;
    }
    function set_item_name($item_name) {
        $this->item_name = $item_name;
    }
    function get_item_name() {
        return $this->item_name;
    }
    function set_item_desc($item_desc) {
        $this->item_desc = $item_desc;
    }
    function get_item_desc() {
        return $this->item_desc;
    }
    function set_is_done($is_done) {
        $this->is_done = $is_done;
    }
    function get_is_done() {
        return $this->is_done;
    }
    function set_current_user_has_created_post($current_user_has_created_post) {
        $this->current_user_has_created_post = $current_user_has_created_post;
    }
    function get_current_user_has_created_post() {
        return $this->current_user_has_created_post;
    }
    function set_date($date) {
        $this->date = $date;
    }
    function get_date() {
        return $this->date;
    }
    function set_ownerid($ownerid) {
        $this->ownerid = $ownerid;
        global $mysqli;
        $sql = "SELECT id, username, password, created_at, firstname, lastname, email, profile_pic
                FROM users
                WHERE id = ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("i", $param_id);
            $param_id = $ownerid;

            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows == 1){
                    $stmt->bind_result($id, $username, $password, $created_at, $firstname, $lastname, $email, $profile_pic);
                    while($stmt->fetch()){
                        $this->Owner = Users::getUser($id);
                    }
                } else {
                    echo("Returned " . $stmt->num_rows . " rows, and expected only one in models/bucket.php set owner id function.");
                }
            } else {
                echo "SQL error: " . $mysqli->error;
            }

            $stmt->close();
        }
    }
    function get_ownerid() {
        return $this->ownerid;
    }
    function set_Owner(Users $User){
        $this->Owner = Users::setAll($User->username, $User->firstname, $User->lastname, $User->email, $User->created_at, $User->user_id, $User->password);
    }
    function get_Owner() {
        return $this->Owner;
    }
    function set_Bucket(Bucket $Bucket){
        $this->Bucket = Bucket::setAll($Bucket->id, $Bucket->ownerid, $Bucket->title, $Bucket->description);
    }
    function get_Bucket() {
        return $this->Bucket;
    }

}
?>