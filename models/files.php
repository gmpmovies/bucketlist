<?php

require_once ("/home1/gobinitc/public_html/bucketlist/config.php");
require_once ("/home1/gobinitc/public_html/bucketlist/models/users.php");
class Files {
    // Properties
    public $id;
    public $filepath;
    public $ownerid;
    public $Owner;
    public $filename;
    public $original_filename;
    public $is_profile_pic;

    public $is_current_profile_pic;

    function __construct()
    {
        $this->is_current_profile_pic=False;
    }

    public static function updateProfilePic($imageid, $userid){
        global $mysqli;
        $sql = "UPDATE users
                SET profile_pic = ?
                WHERE id = ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('ii', $param_profile_pic, $param_id);
            $param_profile_pic = $imageid;
            $param_id= $userid;

            if($stmt->execute()){
                return True;
            } else {
                new Exception("Issue executing SQL query " . $stmt->error);
                return $stmt->error;
            }
            $stmt->close();
        } else {
            new Exception("Error preparying SQL query " . $mysqli->error);
            return $mysqli->error;
        }
    }

    public static function getAllImagesFromUser($userid, $isProfile = False) {
        global $mysqli;
        $all_images = array();
        $sql = 'SELECT id, filepath, ownerid, filename, original_filename, is_profile_pic
                FROM files
                WHERE ownerid = ? AND is_profile_pic = ?
                ORDER BY id DESC';
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('ii', $param_userid, $param_isProfile);
            $param_userid = $userid;
            $param_isProfile = $isProfile;

            if($stmt->execute()){
                $stmt->bind_result($id, $filepath, $ownerid, $filename, $original_filename, $is_profile_pic);
                while($stmt->fetch()){
                    $instance = new self();
                    $instance->set_id($id);
                    $instance->set_filename($filename);
                    $instance->set_ownerid($ownerid);
                    $instance->set_filepath($filepath);
                    $instance->set_original_filename($original_filename);
                    $instance->set_is_profile_pic($is_profile_pic);
                    array_push($all_images, $instance);
                }
            } else {
                echo "Problem in sql execute /models/files.php getAllImagesFromUser";
            }
            $stmt->close();
        } else {
            echo "Problem in sql prepare /models/files.php getAllImagesFromUser";
        }

        return $all_images;

    }

    public static function setAll($id, $filepath, $ownerid, $filename, $original_filename, $is_profile_pic){
        $instance = new self();
        $instance->set_id($id);
        $instance->set_filepath($filepath);
        $instance->set_ownerid($ownerid);
        $instance->set_filename($filename);
        $instance->set_original_filename($original_filename);
        $instance->set_is_profile_pic($is_profile_pic);
        return $instance;
    }

    public static function getImage($file_id){
        global $mysqli;
        $instance = new self();
        $sql = "SELECT id, filepath, ownerid, filename, original_filename, is_profile_pic
                FROM files
                WHERE id = ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('i', $param_file_id);
            $param_file_id = $file_id;

            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows == 1){
                    $stmt->bind_result($id, $filepath, $ownerid, $filename, $original_filename, $is_profile_pic);
                    while($stmt->fetch()){
                        $instance->set_id($id);
                        $instance->set_filepath($filepath);
                        $instance->set_ownerid($ownerid);
                        $instance->set_filename($filename);
                        $instance->set_original_filename($original_filename);
                        $instance->set_is_profile_pic($is_profile_pic);
                    }
                }
            } else {
                echo $stmt->error;
            }
            return $instance;
        } else {
            echo $mysqli->error;
        }
    }

    // Methods
    function set_id($id) {
        $this->id = $id;
    }
    function get_id() {
        return $this->id;
    }
    function set_is_profile_pic($is_profile_pic) {
        $this->is_profile_pic = $is_profile_pic;
    }
    function get_is_profile_pic() {
        return $this->is_profile_pic;
    }
    function set_filepath($filepath) {
        $this->filepath = $filepath;
    }
    function get_filepath() {
        return $this->filepath;
    }
    function set_ownerid($ownerid) {
        $this->ownerid = $ownerid;
    }
    function get_ownerid() {
        return $this->ownerid;
    }
    function set_is_current_profile_pic($is_current_profile_pic) {
        $this->is_current_profile_pic = $is_current_profile_pic;
    }
    function get_is_current_profile_pic() {
        return $this->is_current_profile_pic;
    }
    function set_filename($filename) {
        $this->filename = $filename;
    }
    function get_filename() {
        if($this->filename == null){
            return "default.jpeg";
        } else {
            return $this->filename;
        }
    }
    function set_original_filename($original_filename) {
        $this->original_filename = $original_filename;
    }
    function get_original_filename() {
        return $this->original_filename;
    }
    function get_Owner() {
        return $this->Owner;
    }
    function set_Owner($Owner) {
        $this->Owner = $Owner;
//        $this->Owner = new Users();
//        global $mysqli;
//        $sql = "SELECT id, username, password, created_at, firstname, lastname, email, profile_pic
//                FROM users
//                WHERE id = ?";
//        if($stmt = $mysqli->prepare($sql)){
//            $stmt->bind_param("i", $param_id);
//            $param_id = $this->ownerid;
//
//            if($stmt->execute()){
//                $stmt->store_result();
//                if($stmt->num_rows == 1){
//                    $stmt->bind_result($id, $username, $password, $created_at, $firstname, $lastname, $email, $profile_pic);
//                    while($stmt->fetch()){
//                        $this->Owner = Users::setAll($username, $firstname, $lastname, $email, $created_at, $id, $password, $profile_pic);
//                    }
//                } else {
//                    echo("Returned " . $stmt->num_rows . " rows, and expected only one in models/bucket.php set owner id function.");
//                }
//            } else {
//                echo "SQL error: " . $mysqli->error;
//            }
//
//            $stmt->close();
//        }
    }


}
?>