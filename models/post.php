<?php
session_start();
require_once ("/home1/gobinitc/public_html/bucketlist/config.php");
require_once ("/home1/gobinitc/public_html/bucketlist/models/post_content.php");
require_once ("/home1/gobinitc/public_html/bucketlist/models/listitem.php");

class Post {
    public $id;
    public $itemID; //FK to listitem
    public $Item;
    public $isPrivate;
    public $timestamp;
    public $likes;

    public $Post_Content;

    function __construct()
    {

    }

    public static function createPost($itemID, $isPrivate){
        global $mysqli;
        $sql = "INSERT INTO post (itemID, isPrivate) VALUES (?,?)";
        if($stmt=$mysqli->prepare($sql)){
            $stmt->bind_param('ii', $param_itemID, $param_isPrivate);
            $param_itemID = $itemID;
            $param_isPrivate = $isPrivate;

            if($stmt->execute()){
                return $stmt->insert_id;
            } else {

                return False;
            }
            $stmt->close();
        } else {
            return False;
        }
    }

    public static function isPostCreated($itemID){
        global $mysqli;
        $sql = "SELECT id 
                FROM post
                WHERE itemID = ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('i', $param_itemID);
            $param_itemID = $itemID;

            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows == 1){
                    $stmt->bind_result($postID);
                    while($stmt->fetch()){
                        return array(True, $postID);
                    }
                } else {
                    return array(False, Null);
                }
            }
            $stmt->close();
        }
        $mysqli->close();
    }

    public static function getPost($postID, $getPost_Contents = False){
        global $mysqli;
        $instance = new self();
        $sql = "SELECT id, itemID, isPrivate, timestamp, likes
                FROM post
                WHERE id = ?";
        if($stmt=$mysqli->prepare($sql)){
            $stmt->bind_param('i', $param_postID);
            $param_postID = $postID;

            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows == 1){
                    $stmt->bind_result($postID, $itemID, $isPrivate, $timestamp, $likes);
                    while($stmt->fetch()){
                        $instance->set_id($postID);
                        $instance->set_itemID($itemID);
                        $instance->set_isPrivate($isPrivate);
                        $instance->set_timestamp($timestamp);
                        $instance->set_likes($likes);
                        if($getPost_Contents == True){
                            $postContents = Post_Content::get_post_content_by_postID($postID);
                            $instance->set_Post_Content($postContents);
                        }
                    }
                }
            } else {
                echo $stmt->error;
            }
            $stmt->close();
        } else {
            $mysqli->error;
        }
        return $instance;
    }

    public static function getProfileFeed($limit, $offset, $userid){
        global $mysqli;
        $postIDs = array();
        $allPosts = array();
        //First two params are $userid, third is $currentuser and the last two are limit and offset
        $sql="SELECT DISTINCT p1.id
              FROM post as p1
              WHERE (p1.id IN 
              (SELECT pc1.postID
              FROM post_content AS pc1
              WHERE pc1.userID = ?) AND p1.isPrivate = False) OR 
              (p1.id IN (SELECT pc2.postID
              FROM post_content AS pc2
              WHERE pc2.userID = ? AND pc2.postID IN (
              SELECT pc3.postID 
              FROM post_content AS pc3
              WHERE pc3.postID = pc2.postID AND pc3.userID = ?)))
              ORDER BY timestamp DESC
              LIMIT ? OFFSET ?";

            if($stmt=$mysqli->prepare($sql)){
                $stmt->bind_param('iiiii', $param_user_id, $param_user_id, $param_current_user, $param_limit, $param_offset);
                $param_current_user = $_SESSION['id'];
                $param_user_id = $userid;
                $param_limit = $limit;
                $param_offset = $offset;

                if($stmt->execute()){
                    $stmt->store_result();

                    if($stmt->num_rows>=1){
                        $stmt->bind_result($postID);
                        while($stmt->fetch()){
                            array_push($postIDs, $postID);
                        }
                    }
                } else {
                    echo ($stmt->error);
                }
                $stmt->close();
            } else {
                echo ($mysqli->error);
            }

            //Get the post_content items for each postID
            foreach($postIDs as $postID){
                $fullPost = Post::getPost($postID, True);
                array_push($allPosts, $fullPost);
            }
            return $allPosts;
    }

    public static function getFeed($limit, $offset){
        global $mysqli;
        $postIDs = array();
        $allPosts = array();
        //GET THE POST ID FOR EACH POST THAT CONTAINS A USER ID THAT IS IN
        //THE USERS FOLLOWING LIST. OR GET ALL POST ID'S FOR ALL POSTS THAT
        //THE USER BELONGS TO.
        $sql = "SELECT DISTINCT p1.id
                FROM post as p1
                WHERE (p1.id IN 
                    (SELECT pc1.postID
                    FROM post_content AS pc1
                    WHERE pc1.userID IN
                        (SELECT f1.following
                        FROM followers AS f1
                        WHERE f1.user = ? AND unfollowed = FALSE) AND pc1.has_posted = TRUE) 
                AND p1.isPrivate = False) OR 
                (p1.id IN 
                    (SELECT pc2.postID
                    FROM post_content AS pc2
                    WHERE pc2.userID = ?))    
                ORDER BY timestamp DESC 
                LIMIT ? OFFSET ?
                ";
        if($stmt=$mysqli->prepare($sql)){
            $stmt->bind_param('iiii', $param_user_id, $param_user_id, $param_limit, $param_offset);
            $param_user_id = $_SESSION['id'];
            $param_limit = $limit;
            $param_offset = $offset;

            if($stmt->execute()){
                $stmt->store_result();

                if($stmt->num_rows>=1){
                    $stmt->bind_result($postID);
                    while($stmt->fetch()){
                        array_push($postIDs, $postID);
                    }
                }
            } else {
                echo ($stmt->error);
            }
            $stmt->close();
        } else {
            echo ($mysqli->error);
        }

        //Get the post_content items for each postID
        foreach($postIDs as $postID){
            $fullPost = Post::getPost($postID, True);
            array_push($allPosts, $fullPost);
        }

        return $allPosts;

    }

    function set_id($id){
        $this->id = $id;
    }
    function get_id(){
        return $this->id;
    }
    function set_Post_Content($Post_Content){
        $this->Post_Content = $Post_Content;
    }
    function get_Post_Content(){
        return $this->Post_Content;
    }
    function set_itemID($itemID){
        $this->itemID = $itemID;
        $this->Item = Listitem::getListItem($itemID);
    }
    function get_itemID(){
        return $this->itemID;
    }
    function set_Item($Item){
        $this->Item = $Item;
    }
    function get_Item(){
        return $this->Item;
    }
    function set_isPrivate($isPrivate){
        $this->isPrivate = $isPrivate;
    }
    function get_isPrivate(){
        return $this->isPrivate;
    }
    function set_timestamp($timestamp){
        $this->timestamp = $timestamp;
    }
    function get_timestamp(){
        return $this->timestamp;
    }
    function set_likes($likes){
        $this->likes = $likes;
    }
    function get_likes(){
        return $this->likes;
    }
}

?>