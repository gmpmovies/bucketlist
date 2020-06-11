<?php
require_once ("/var/www/html/bucketlist/config.php");
require_once ("/var/www/html/bucketlist/models/files.php");

class Content {
    public $id;
    public $fileID;
    public $File;
    public $post;
    public $videoURL;
    public $is_video;

    function __construct()
    {

    }

    public static function getContent($contentID){
        global $mysqli;
        $instance = new self();
        $sql = "SELECT id, fileID, post, videoURL, is_video
                FROM content
                WHERE id = ?";

        if($stmt=$mysqli->prepare($sql)){
            $stmt->bind_param('i', $param_id);
            $param_id = $contentID;

            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows == 1){
                    $stmt->bind_result($id, $fileID, $post, $videoURL, $is_video);
                    while($stmt->fetch()){
                        $instance->set_id($id);
                        $instance->set_fileID($fileID);
                        $instance->set_post($post);
                        $instance->set_videoURL($videoURL);
                        $instance->set_is_video($is_video);
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

    public static function addContent($fileID, $post, $videoURL, $is_video){
        global $mysqli;
        $sql = "INSERT INTO content (fileID, post, videoURL, is_video) VALUES (?,?,?,?)";
        if($stmt=$mysqli->prepare($sql)){
            $stmt->bind_param('issi', $param_fileID, $param_post, $param_videoURL, $param_is_video);
            $param_fileID = $fileID;
            $param_post = $post;
            $param_videoURL = $videoURL;
            $param_is_video = $is_video;

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

    function set_id($id){
        $this->id = $id;
    }
    function get_id(){
        return $this->id;
    }
    function set_fileID($fileID){
        $this->fileID = $fileID;
        $this->File = Files::getImage($fileID);
    }
    function get_fileID(){
        return $this->fileID;
    }
    function set_File($File){
        $this->File = $File;
    }
    function get_File(){
        return $this->File;
    }
    function set_post($post){
        $this->post = $post;
    }
    function get_post(){
        return $this->post;
    }
    function set_videoURL($videoURL){
        $this->videoURL = $videoURL;
    }
    function get_videoURL(){
        return $this->videoURL;
    }
    function set_is_video($is_video){
        $this->is_video = $is_video;
    }
    function get_is_video(){
        return $this->is_video;
    }
}

?>