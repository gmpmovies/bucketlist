<?php
require_once ("/home1/gobinitc/public_html/bucketlist/config.php");
require_once ("/home1/gobinitc/public_html/bucketlist/models/users.php");
require_once ("/home1/gobinitc/public_html/bucketlist/models/content.php");

class Post_Content {
    public $id; //ID (Int)
    public $postID; //FK to Post (Int)
    public $Post; // Obj (Post) -- Don't use this for now.
    public $contentID; //FK to Content (Int)
    public $Content; //Obj (Content)
    public $has_posted; //Bool - True = Posted; False = Not Posted;
    public $date_posted; //Datetime (posts as String to mySql
    public $userID; //FK to User (Int)
    public $User; // Obj (User)
    public $is_deleted; //Bool - True = Deleted; False = Not Deleted;
    public $hide_from_profile; //Bool - True = Hide in Profile; False = Show in Profile;

    function __construct()
    {

    }

    public static function updatePost_Content($post_content_id, $content_id, $has_posted){
        global $mysqli;
        $sql = 'UPDATE post_content
                SET contentID = ?, 
                    has_posted = ?
                WHERE id = ?';
        if($stmt=$mysqli->prepare($sql)){
            $stmt->bind_param('iii', $param_contentID, $param_has_posted, $param_id);
            $param_contentID = $content_id;
            $param_has_posted = $has_posted;
            $param_id = $post_content_id;

            if($stmt->execute()){
                return True;
            } else {
                throw new Exception($stmt->error);
            }
        } else {
            throw new Exception($mysqli->error);
            //error check
        }
        return False;
    }

    //GETS THE POST_CONTENT FOR EACH POSTID
    public static function get_post_content_by_postID($postID){
        global $mysqli;
        $all_content_post = array();
        $sql = "SELECT id, postID, contentID, has_posted, date_posted, userID, is_deleted, hide_from_profile
                FROM post_content
                WHERE postID = ?";
        if($stmt=$mysqli->prepare($sql)){
            $stmt->bind_param('i', $param_postID);
            $param_postID = $postID;

            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows >= 1){
                    $stmt->bind_result($id, $postID, $contentID, $has_posted, $date_posted, $userID, $is_deleted, $hide_from_profile);
                    while($stmt->fetch()){
                        $instance = new self();
                        $instance->set_id($id);
                        $instance->set_postID($postID);
                        $instance->set_contentID($contentID);
                        $instance->set_has_posted($has_posted);
                        $instance->set_date_posted($date_posted);
                        $instance->set_userID($userID);
                        $instance->set_is_deleted($is_deleted);
                        $instance->set_hide_from_profile($hide_from_profile);
                        array_push($all_content_post, $instance);
                    }
                }
            } else {
                echo $stmt->error;
            }
            $stmt->close();
        } else {
            $mysqli->error;
        }
        return $all_content_post;
    }

    public static function get_post_content_by_postID_and_userID($postID, $userID, $show_has_posted = False){
        //Get post_contentID
        global $mysqli;
        $post_content_id = Null;
        $has_posted = Null;
        $sql = "SELECT id, has_posted 
                FROM post_content
                WHERE postID = ? AND userID = ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('ii', $param_postID, $param_userID);
            $param_postID = $postID;
            $param_userID = $userID;

            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows == 1){
                    $stmt->bind_result($id, $has_posted_param);
                    while($stmt->fetch()){
                        $has_posted = $has_posted_param;
                        $post_content_id = $id;
                    }
                }
            } else {
                //Should catch something here
            }
        } else {
            //Should also do some catching of stuff here too maybe.
        }
        if($show_has_posted == True){
            return $has_posted;
        } else {
            return $post_content_id;
        }
    }

    //CREATES A POST_CONTENT RECORD
    public static function createPostContent($postID, $contentID, $has_posted, $userID, $is_deleted=0, $hide_from_profile=0){
        global $mysqli;
        $mysql_date_now = date("Y-m-d H:i:s");
        $sql = "INSERT INTO post_content (postID, contentID, has_posted, date_posted, userID, is_deleted, hide_from_profile) VALUES (?,?,?,?,?,?,?)";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('iiisiii', $param_postID, $param_contentID, $param_has_posted, $param_date_posted, $param_userID, $param_is_deleted, $param_hide_from_profile);
            $param_postID = $postID;
            $param_contentID = $contentID;
            $param_has_posted = $has_posted;
            $param_date_posted = $mysql_date_now;
            $param_userID = $userID;
            $param_is_deleted = $is_deleted;
            $param_hide_from_profile = $hide_from_profile;

            if($stmt->execute()){
                return True;
            } else {
                return False;
            }
            $stmt->close();
        } else {
            return False;
        }
    }

    function set_id($id){
        $this->id = $id;
    }
    function get_id(){
        return $this->id;
    }
    function set_postID($postID){
        $this->postID = $postID;
    }
    function get_postID(){
        return $this->postID;
    }
    function set_Post($Post){
        $this->Post = $Post;
    }
    function get_Post(){
        return $this->Post;
    }
    function set_contentID($contentID){
        $this->contentID = $contentID;
        if($contentID != "" || $contentID != null){
            $this->Content = Content::getContent($contentID);
        }
    }
    function get_contentID(){
        return $this->contentID;
    }
    function set_Content($Content){
        $this->Content = $Content;
    }
    function get_Content(){
        return $this->Content;
    }
    function set_has_posted($has_posted){
        $this->has_posted = $has_posted;
    }
    function get_has_posted(){
        return $this->has_posted;
    }
    function set_date_posted($date_posted){
        $this->date_posted = $date_posted;
    }
    function get_date_posted(){
        return $this->date_posted;
    }
    function set_userID($userID){
        $this->userID = $userID;
        $this->User = Users::getUser($userID);
    }
    function get_userID(){
        return $this->userID;
    }
    function set_User($User){
        $this->User = $User;
    }
    function get_User(){
        return $this->User;
    }
    function set_is_deleted($is_deleted){
        $this->is_deleted = $is_deleted;
    }
    function get_is_deleted(){
        return $this->is_deleted;
    }
    function set_hide_from_profile($hide_from_profile){
        $this->hide_from_profile = $hide_from_profile;
    }
    function get_hide_from_profile(){
        return $this->hide_from_profile;
    }
}

?>