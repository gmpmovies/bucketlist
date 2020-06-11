<?php
session_start();
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
require_once ("/var/www/html/bucketlist/config.php");
require_once ("/var/www/html/bucketlist/models/users.php");
class Followers {
    // Properties
    public $id;
    public $user;
    public $thisUser;
    public $following;
    public $isFollowingUser;
    public $unfollowed;


    function __construct()
    {

    }

    public static function setAll($id, $user = null, $following, $isFollowingUser){
        $instance = new self();
        $instance->id = $id;
        $instance->user = $user;
        $instance->following = $following;
        $instance->isFollowingUser = $isFollowingUser;
        return $instance;
    }

    public static function getAllFollowers($userid, $getFollowing=False, $Limit = 6, $Offset=0){
        $all_users = array();
        $sql = "SELECT id, user, following, unfollowed
                FROM followers
                WHERE unfollowed = FALSE AND user = ?
                LIMIT ?
                OFFSET ?";
        if($getFollowing == False){
            $sql = "SELECT id, user, following, unfollowed
                    FROM followers
                    WHERE unfollowed = FALSE AND following = ?
                    LIMIT ?
                    OFFSET ?";
        }
        global $mysqli;
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('iii', $param_id, $param_limit, $param_offset);
            $param_id = $userid;
            $param_limit = $Limit;
            $param_offset = $Offset;
            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows>=1){
                    $stmt->bind_result($id, $userid, $following, $unfollowed);
                    while($stmt->fetch()){
                        $user = new Users();
                        if($getFollowing == False){
                            $user = $user::getUser($userid);
                        } else {
                            $user = $user::getUser($following);
                        }
                        array_push($all_users, $user);
                    }
                }
            } else {
                echo"Error in SQL execute /folloers.php getAllFollowers()";
            }
            $stmt->close();
        } else {
            echo"Error in SQL prepare /folloers.php getAllFollowers()";
        }
        return $all_users;
    }

    //Returns A Bool if User is following another user or not
    public static function isFollowing($User, $unfollowed = FALSE){
        $sql = "SELECT id, user, following, unfollowed 
                FROM followers
                WHERE user = ? AND following = ? AND unfollowed = ?";

        global $mysqli;
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('iii', $param_user, $param_following, $param_unfollowed);

            $param_user = $_SESSION['id'];
            if(is_object($User)){
                $param_following = $User->get_id();
            } else {
                $param_following = $User;
            }

            $param_unfollowed = $unfollowed;

            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows == 1){
                    $stmt->bind_result($id, $user, $following, $unfollowed);
                    return True;
                } else {
                    return False;
                }
            } else {
                echo "problem in sql execute /models/followers isFollowing()";
            }
            $stmt->close();
        } else {
            echo "problen in sql prepare /models/followers isFollowing()";
        }
    }

    #Get all users who are also following current user
    public static function getFollowingWhoAreAlsoFollowers($user_id, $bucket_id){
        $sql = "SELECT following 
                FROM followers AS f1 
                WHERE user = ? AND unfollowed = FALSE AND following IN 
                    (SELECT following 
                    FROM followers AS f2 
                    WHERE f2.following = f1.following AND f2.following NOT IN 
                        (SELECT userid
                        FROM bucket_users
                        WHERE bucketid = ?))";
        $users = array();
        global $mysqli;
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('ii', $param_user_id, $param_bucket_id);

            $param_user_id = $user_id;
            $param_bucket_id = $bucket_id;

            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows >= 1){
                    $stmt->bind_result($account);
                    while($stmt->fetch()){
                        $user = Users::getUser($account);
                        array_push($users, $user);
                    }
                }
            } else {
                echo "problem in sql execute /models/followers getFollowingWhoAreAlsoFollowers()";
            }
            $stmt->close();
        } else {
            echo "problen in sql prepare /models/followers getFollowingWhoAreAlsoFollowers()";
        }
        return $users;
    }

    // Methods
    function set_id($id) {
        $this->id = $id;
    }
    function get_id() {
        return $this->id;
    }
    function set_user($user) {
        $this->user = $user;
        $this->thisUser = new Users();
        $this->thisUser = Users::getUser($user);
    }
    function get_user() {
        return $this->user;
    }
    function set_following($following) {
        $this->following = $following;
        $this->isFollowingUser = new Users();
        $this->isFollowingUser = Users::getUser($following);
    }
    function get_following() {
        return $this->following;
    }
    function set_unfollowed($unfollowed) {
        $this->unfollowed = $unfollowed;
    }
    function get_unfollowed() {
        return $this->unfollowed;
    }


}
?>