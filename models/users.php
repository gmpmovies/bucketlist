<?php
require_once ("/var/www/html/bucketlist/config.php");
require_once ("/var/www/html/bucketlist/models/files.php");
class Users {
    // Properties
    public $id;
    public $username;
    public $password;
    public $created_at;
    public $firstname;
    public $lastname;
    public $email;
    public $profile_pic;
    public $File;
    public $number_following;
    public $number_followers;
    public $is_verified;
    public $number_notifications;

    //This is only used occasionally
    public $followFlag;
    public $isOwner;

    function __construct()
    {

    }

    public static function increaseNotifications($user_id, $increase = True){
        global $mysqli;
        $sql = "UPDATE users
                SET number_notifications = ?
                WHERE id = ?";
        $user = Users::getUser($user_id);
        $num_notifications = $user->get_number_notifications();
        if($increase == True){
            $num_notifications = $num_notifications + 1;
        } else {
            $num_notifications = $num_notifications - 1;
            if($num_notifications <= 0){
                $num_notifications = 0;
            }
        }
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('ii', $param_num, $param_id);
            $param_num = $num_notifications;
            $param_id=$user_id;

            if($stmt->execute()){
                return True;
            }
            $stmt->close();
        }

        return False;
    }

    public static function setAll($username, $firstname, $lastname, $email, $created_at, $user_id, $password, $profile_pic){
        $instance = new self();
        $instance->set_username($username);
        $instance->set_firstname($firstname);
        $instance->set_lastname($lastname);
        $instance->set_email($email);
        $instance->set_created_at($created_at);
        $instance->set_id($user_id);
        $instance->set_password($password);
        $instance->set_profile_pic($profile_pic);
        $instance->set_File();
        return $instance;
    }

    public static function getUser($id){
        global $mysqli;
        $instance = new self();
        $sql = "SELECT id, username, password, created_at, firstname, lastname, email, profile_pic, number_following, number_followers, is_verified, number_notifications
                FROM users
                WHERE id = ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('i', $param_id);
            $param_id = $id;
            if($stmt->execute()){

                $stmt->bind_result($id, $username, $password, $created_at, $firstname, $lastname, $email, $profile_pic, $number_following, $number_followers, $is_verified, $number_notifications);
                while($stmt->fetch()){
                    $instance->set_id($id);
                    $instance->set_username($username);
                    $instance->set_password('Hidden');
                    $instance->set_created_at($created_at);
                    $instance->set_firstname($firstname);
                    $instance->set_lastname($lastname);
                    $instance->set_email($email);
                    $instance->set_profile_pic($profile_pic);
                    $instance->set_number_following($number_following);
                    $instance->set_number_followers($number_followers);
                    $instance->set_is_verified($is_verified);
                    $instance->set_number_notifications($number_notifications);
                }
            } else {
                echo "Error in SQL execute models/users";
            }
            $stmt->close();
        } else {
            echo "Eroror preparing SQL in models/users";
        }
        $instance->set_File();
        return $instance;
    }

    // Methods
    function set_id($id) {
        $this->id = $id;
    }
    function get_id() {
        return $this->id;
    }
    function set_is_verified($is_verified) {
        $this->is_verified = $is_verified;
    }
    function get_is_verified() {
        return $this->is_verified;
    }
    function set_username($username) {
        $this->username = $username;
    }
    function get_username() {
        return $this->username;
    }
    function set_password($password) {
        $this->password = $password;
    }
    function get_password() {
        return $this->password;
    }
    function set_number_following($number_following) {
        $this->number_following = $number_following;
    }
    function get_number_following() {
        return $this->number_following;
    }
    function set_number_followers($number_followers) {
        $this->number_followers = $number_followers;
    }
    function get_number_followers() {
        return $this->number_followers;
    }
    function set_created_at($created_at) {
        $this->created_at = $created_at;
    }
    function get_created_at() {
        return $this->created_at;
    }
    function set_firstname($firstname) {
        $this->firstname = $firstname;
    }
    function get_firstname() {
        if($this->firstname != null){
            return $this->firstname;
        } else {
            return 'no firstname';
        }

    }
    function set_lastname($lastname) {
        $this->lastname = $lastname;
    }
    function get_lastname($show_verified = True) {
        if($this->is_verified==True && $show_verified==True){
            $lastnameIcon = $this->lastname . " <img src=\"/assets/icons/verified.svg\" class=\"verified-icon\" />";
            return $lastnameIcon;
        }
        return $this->lastname;
    }
    function set_email($email) {
        $this->email = $email;
    }
    function get_email() {
        return $this->email;
    }
    function set_profile_pic($profile_pic) {
        global $mysqli;
        $this->profile_pic = $profile_pic;
    }
    function get_profile_pic() {
        return $this->profile_pic;
    }
    function set_File(){
        global $mysqli;
        if($this->profile_pic != null){
            $this->File = new Files();
            $sql = "SELECT id, filepath, ownerid, filename, original_filename, is_profile_pic
                    FROM files 
                    WHERE id = ?";
            if($stmt = $mysqli->prepare($sql)){
                $stmt->bind_param("i", $param_id);
                $param_id = $this->profile_pic;

                if($stmt->execute()){
                    $stmt->store_result();
                    if($stmt->num_rows == 1){
                        $stmt->bind_result($id, $filepath, $ownerid, $filename, $original_filename, $is_profile_pic);
                        while($stmt->fetch()){
                            $this->File = Files::setAll($id, $filepath,$ownerid,$filename,$original_filename, $is_profile_pic);
                        }
                    } else {
                        echo("Returned " . $stmt->num_rows . " rows, and expected 1 row in models/users.php set owner id function.");
                    }
                } else {
                    echo "SQL error: " . $mysqli->error;
                }
                $stmt->close();
            } else {
                echo "SQL prepare error: " . $mysqli->error;
            }

        } else {
            $this->File = new Files();
            $this->File->filename = "default.jpeg";
        }
    }

    function get_File(){
        return $this->File;
    }

    function set_follow_flag($flag){
        $this->followFlag = $flag;
    }

    function get_follow_flag(){
        return $this->followFlag;
    }

    function set_owner($owner){
        $this->owner = $owner;
    }

    function get_owner(){
        return $this->owner;
    }
    function set_number_notifications($number_notifications){
        $this->number_notifications = $number_notifications;
    }

    function get_number_notifications(){
        return $this->number_notifications;
    }

}
?>