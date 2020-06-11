<?php
require_once ("/home1/gobinitc/public_html/bucketlist/config.php");
require_once ("/home1/gobinitc/public_html/bucketlist/models/users.php");

class Bucket {
    // Properties
    public $id;
    public $ownerid;
    public $User; #This is the owner of the Bucket
    public $title;
    public $description;
    public $isPrivate;


    function __construct(){
        $this->User = new Users();
    }

    public static function setAll( $id, $ownerid, $title, $description, $isPrivate = False) {
        $instance = new self();
        $instance->set_id( $id );
        $instance->set_ownerid($ownerid);
        $instance->set_title($title);
        $instance->set_description($description);
        $instance->set_isPrivate($isPrivate);
        return $instance;
    }

    public static function getBucket($id){
        global $mysqli;
        $bucket = new self();
        $sql="SELECT id, ownerid, title, description, isPrivate
            FROM bucket
            WHERE id = ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('i', $param_id);
            $param_id = $id;

            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows == 1){
                    $stmt->bind_result($id, $ownerid, $title, $description, $isPrivate);
                    while($stmt->fetch()){
                        $bucket = Bucket::setAll($id, $ownerid, $title, $description, $isPrivate);
                    }
                } else {
                    echo "Expected 1 result. /models/buckets getBucket()";
                }
            } else {
                echo "Failed in sql execute /models/buckets getBucket()";
            }
            $stmt->close();
        } else {
            echo "Failed in sql prepare /models/buckets getBucket()";
        }

        return $bucket;
    }

    // Methods
    function set_ownerid($owner_id) {
        $this->ownerid = $owner_id;
        $this->User = Users::getUser($owner_id);
    }
    function get_ownerid() {
        return $this->ownerid;
    }
    function set_title($title) {
        $this->title = $title;

    }
    function get_title() {
        return $this->title;
    }
    function set_description($description) {
        $this->description = $description;
    }
    function get_description() {
        return $this->description;
    }
    function set_id($id) {
        $this->id = $id;
    }
    function get_id() {
        return $this->id;
    }
    function set_isPrivate($isPrivate) {
        $this->isPrivate = $isPrivate;
    }
    function get_isPrivate() {
        return $this->isPrivate;
    }
    function set_User(Users $User) {
        $this->User = Users::setAll($User->username, $User->firstname, $User->lastname, $User->email, $User->created_at, $User->user_id, $User->password);
    }
    function get_User() {
        return $this->User;
    }
}
?>