<?php


class Likes {
    public $id; //PK (Int)
    public $userID;//FK (Int)
    public $User;//Obj (User)
    public $postID;//FK (Int)
    public $Post;//Obj (Post)
    public $is_redacted;//Bool - True = Redacted; False = Applied;

    function __construct()
    {

    }

    function set_id($id){
        $this->id = $id;
    }
    function get_id(){
        return $this->id;
    }
    function set_userID($userID){
        $this->userID = $userID;
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
    function set_is_redacted($is_redacted){
        $this->is_redacted = $is_redacted;
    }
    function get_is_redacted(){
        return $this->is_redacted;
    }
}

?>