<?php
require_once ("/home1/gobinitc/public_html/bucketlist/config.php");
require_once ("/home1/gobinitc/public_html/bucketlist/models/bucket.php");
require_once ("/home1/gobinitc/public_html/bucketlist/models/users.php");

class Bucket_Users {
    // Properties
    public $id;
    public $bucketid;
    public $Bucket;
    public $userid;
    public $User;
    public $isAdmin;
    public $lastViewed;

    function __construct()
    {
        $this->User = new Users();
        $this->Bucket = new Bucket();
    }

    public static function setAll($id, $bucketid, $userid){
        $instance = new self();
        $instance->set_bucketid($bucketid);
        $instance->set_id($id);
        $instance->set_userid($userid);
        return $instance;
    }

    public static function GetAllBucketsForUser($userid, $isPrivate=3){
        global $mysqli;
        $buckets = array();
        if($isPrivate == 3){ //Get all buckets for user regardless of privacy setting
            $sql = "SELECT bucket_users.id 
                    FROM bucket_users INNER JOIN bucket ON bucket_users.bucketid = bucket.id
                    WHERE bucket_users.userid = ? AND bucket.isPrivate != ?
                    ORDER BY bucket_users.lastViewed DESC";
        } else { //Get all buckets for user by privacy setting
            $sql = "SELECT bucket_users.id 
                    FROM bucket_users INNER JOIN bucket ON bucket_users.bucketid = bucket.id
                    WHERE bucket_users.userid = ? AND bucket.isPrivate = ?
                    ORDER BY bucket_users.lastViewed DESC";
        }

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("ii", $param_userid, $param_isPrivate);
            $param_userid = $userid;
            $param_isPrivate = $isPrivate;

            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows >= 1) {
                    $stmt->bind_result($id);
                    while($stmt->fetch()){
                        $instance = new self();
                        $instance = $instance::GetBucketUser($id);
                        array_push($buckets, $instance);
                    }
                }
            } else {
                echo "Error in executing the SQL.";
            }
            $stmt->close();
        } else {
            echo "Error in preparing the SQL.";
        }
        return $buckets;
    }

    //Get the bucket_user record by id
    public static function GetBucketUser($id){
        global $mysqli;
        $instance = new self();
        $sql = "SELECT id, bucketid, userid, isAdmin, lastViewed
                FROM bucket_users
                WHERE id = ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('i', $param_id);
            $param_id = $id;

            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows == 1){
                    $stmt->bind_result($id, $bucketid, $userid, $isAdmin, $lastViewed);
                    while($stmt->fetch()){
                        $instance->set_id($id);
                        $instance->set_bucketid($bucketid);
                        $instance->set_userid($userid);
                        $instance->set_isAdmin($isAdmin);
                        $instance->set_lastViewed($lastViewed);
                    }
                } else {
                    throw new Exception("SQL error: " . $stmt->error);
                    return False;
                }

            } else {
                throw new Exception("SQL error: " . $stmt->error);
                return False;
            }
            $stmt->close();
        } else {
            throw new Exception("SQL error: " . $mysqli->error);
            return False;
        }
        return $instance;
    }

    public static function addUserToBucket($uid, $bucketid, $isAdmin = 0){
        $sql = "INSERT INTO bucket_users (bucketid, userid, isAdmin) VALUES(?,?,?)";
        global $mysqli;
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('iii', $param_bucketid, $param_userid, $param_isAdmin);
            $param_userid = $uid;
            $param_bucketid = $bucketid;
            $param_isAdmin = $isAdmin;

            if($stmt->execute()){
                return True;
            } else {
                throw new Exception("SQL error: " . $stmt->error);
                return False;
            }
        } else {
            throw new Exception("SQL error: " . $mysqli->error);
            return False;
        }
    }

    public static function updateLastViewed($id, $bucket_id){
        global $mysqli;
        $mysql_date_now = date("Y-m-d H:i:s");
        $sql = "UPDATE bucket_users
                SET lastViewed = ?
                WHERE userid = ? AND bucketid = ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('sii', $param_date, $param_id, $param_bucketid);
            $param_date = $mysql_date_now;
            $param_id = $id;
            $param_bucketid = $bucket_id;
            if($stmt->execute()){
                return True;
            } else {
                echo "error";
                throw new Exception("SQL error: " . $stmt->error);
                return False;
            }
        } else {
            echo "error2";
            throw new Exception("SQL error: " . $mysqli->error);
            return False;
        }
    }

    //Find common buckets between two users
    public static function getCommonBuckets($uid1, $uid2){
        global $mysqli;
        $buckets = array();
        $retrieved_buckets = array();
        $sql = "SELECT bucketid 
                FROM bucket_users 
                WHERE userid = ? and bucketid IN 
                (SELECT bucketid AS b2 
                FROM bucket_users as bu2 
                WHERE userid = ?)";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('ii', $param_uid1, $param_uid2);
            $param_uid1 = $uid1;
            $param_uid2 = $uid2;

            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows>=1){
                    $stmt->bind_result($bucketid);
                    while($stmt->fetch()){
                        array_push($buckets, $bucketid);
                    }
                }
            } else {
                echo "Failed in execute /models/bucket_users getCommonBuckets()";
            }
            $stmt->close();
        } else {
            echo "Failed in prepare /models/bucket_users getCommonBuckets()";
        }

        foreach($buckets as $bucket){
            $new_bucket = Bucket::getBucket($bucket);
            array_push($retrieved_buckets, $new_bucket);
        }

        return $retrieved_buckets;
    }

    //Get members of a given bucket
    public static function getBucketMembers($bucketid, $ownerid=null){
        global $mysqli;
        $allUsers = array();
        $users = array();
        $sql = "SELECT userid 
                FROM bucket_users
                WHERE bucketid = ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("i", $param_bucketid);
            $param_bucketid = $bucketid;

            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows>= 1){
                    $stmt->bind_result($userid);
                    while($stmt->fetch()){
                        array_push($users, $userid);
                    }
                }
            } else {
                echo "Problem executing sql /models/bucket_users getBucketMembers()";
            }
            $stmt->close();
        } else {
            echo "Problem preparing sql /models/bucket_users getBucketMembers()";
        }

        if($users != null){
            foreach($users as $userid){
                $newUser = Users::getUser($userid);
                if($newUser->get_id() == $ownerid){
                    $newUser->set_owner(True);
                } else {
                    $newUser->set_owner(False);
                }
                array_push($allUsers, $newUser);
            }
        }
        return $allUsers;
    }

    // Methods
    function set_id($id) {
        $this->id = $id;
    }
    function get_id() {
        return $this->id;
    }

    function set_lastViewed($lastViewed) {
        $this->lastViewed = $lastViewed;
    }
    function get_lastViewed() {
        return $this->lastViewed;
    }

    function set_bucketid($bucketid) {
        $this->bucketid = $bucketid;
        $this->Bucket = Bucket::getBucket($bucketid);
    }

    function get_bucketid() {
        return $this->bucketid;
    }
    function set_userid($userid) {
        $this->userid = $userid;
        $this->User = Users::getUser($userid);
    }
    function get_userid() {
        return $this->userid;
    }
    function set_User(Users $User) {
        $this->User = Users::setAll($User->username, $User->firstname, $User->lastname, $User->email, $User->created_at, $User->user_id, $User->password);
    }
    function get_User() {
        return $this->User;
    }
    function set_Bucket(Bucket $Bucket) {
        $this->Bucket = Bucket::setAll($Bucket->id, $Bucket->ownerid, $Bucket->User, $Bucket->title, $Bucket->description);
    }
    function get_Bucket() {
        return $this->Bucket;
    }
    function set_isAdmin($isAdmin) {
        $this->isAdmin = $isAdmin;
    }
    function get_isAdmin() {
        return $this->isAdmin;
    }
}
?>