<?php
    session_start();
    require_once "/home1/gobinitc/public_html/bucketlist/config.php";
    $bucket_id = $_GET["bucketid"];
    $sql = "SELECT userid 
            FROM bucket_users
            WHERE userid = ? AND bucketid = ?";
    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("ii", $param_userid, $param_bucketid);
        $param_bucketid = $bucket_id;
        $param_userid = $_SESSION["id"];

        if($stmt->execute()){
            $stmt->store_result();
            if($stmt->num_rows == 1){

            }
            else {
                header("location: /error.php");
            }
        }

        $stmt->close();
    }
