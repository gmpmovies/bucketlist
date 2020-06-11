<?php
require_once ("/home1/gobinitc/public_html/bucketlist/config.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

class ApiData{
    private $apiKey;

    function __construct($key){
        $this->apiKey=$key;
    }

    public static function getTotalUserCount(){
        global $mysqli;
        $sql = "SELECT id FROM users";
        if($stmt = $mysqli->prepare($sql)){
            if($stmt->execute()){
                $stmt->store_result();
                return $stmt->num_rows;
            } else {
                throw new Exception($stmt->error);
                return False;
            }
        } else {
            throw new Exception($mysqli->error);
            return False;
        }
    }

    public static function getTotalBucketCount(){
        global $mysqli;
        $sql = "SELECT id FROM bucket";
        if($stmt = $mysqli->prepare($sql)){
            if($stmt->execute()){
                $stmt->store_result();
                return $stmt->num_rows;
            } else {
                throw new Exception($stmt->error);
                return False;
            }
        } else {
            throw new Exception($mysqli->error);
            return False;
        }
    }

    public static function getTotalBucketItemCount(){
        global $mysqli;
        $sql = "SELECT id FROM listitem";
        if($stmt = $mysqli->prepare($sql)){
            if($stmt->execute()){
                $stmt->store_result();
                return $stmt->num_rows;
            } else {
                throw new Exception($stmt->error);
                return False;
            }
        } else {
            throw new Exception($mysqli->error);
            return False;
        }
    }

    public static function getMostPopularBucket(){
        global $mysqli;
        $result = "";
        $sql = "SELECT title, 
                (SELECT COUNT(id) FROM 
                    bucket_users as bu
                    WHERE bu.bucketid = bucket.id) as num_users
                FROM bucket
                ORDER BY num_users DESC
                LIMIT 1;";
        if($stmt = $mysqli->prepare($sql)){
            if($stmt->execute()){
                $stmt->store_result();
                $stmt->bind_result($bucket_name, $num_users);
                while($stmt->fetch()){
                    $result = new MostPopularBucket($bucket_name, $num_users);
                }
                return $result;
            } else {
                echo $stmt->error;
                throw new Exception($stmt->error);
                return False;
            }
        } else {
            echo $mysqli->error;
            throw new Exception($mysqli->error);
            return False;
        }
    }

}


class MostPopularBucket{
    public $num_users;
    public $bucket_name;

    function __construct($bucket_name = "", $num_users = 0)
    {
        $this->set_bucket_name($bucket_name);
        $this->set_num_users($num_users);
    }

    function set_num_users($num_users) {
        $this->num_users = $num_users;
    }
    function get_num_users() {
        return $this->num_users;
    }
    function set_bucket_name($bucket_name) {
        $this->bucket_name = $bucket_name;
    }
    function get_bucket_name() {
        return $this->bucket_name;
    }
}

