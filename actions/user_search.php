<?php
    require_once "/var/www/html/bucketlist/models/users.php";
    require_once "/var/www/html/bucketlist/models/followers.php";
    require_once "/var/www/html/bucketlist/config.php";
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    $search_results = array();
    $found_users = array();
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $search_term = strtolower($_POST['searchterm']);
        $sql = "SELECT id, username, password, created_at, firstname, lastname, email, profile_pic
                FROM users
                WHERE LOWER(username) LIKE CONCAT('%', ?, '%')  OR LOWER(firstname) LIKE CONCAT('%', ?, '%') OR LOWER(lastname) LIKE CONCAT('%', ?, '%') OR LOWER(CONCAT(firstname, ' ', lastname)) LIKE CONCAT('%', ?, '%')";

        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("ssss", $s1, $s2, $s3, $s4);
            $s1 = $search_term;
            $s2 = $search_term;
            $s3 = $search_term;
            $s4 = $search_term;

            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows >= 1){
                    $stmt->bind_result($id, $username, $password, $created_at, $firstname, $lastname, $email, $profile_pic);
                    while($stmt->fetch()){
                        if($id != $_SESSION['id']){
                            $user = Users::getUser($id);
                            array_push($search_results, $user);
                        }
                    }
                }
            } else {
                echo 'Problem in SQL execute search.php ' . $stmt->error;
                new Exception();
            }
            $stmt->close();
        } else {
            echo 'problem in sql prepare search.php ' . $mysqli->error;
            new Exception();
        }

        foreach($search_results as $user){
            $isFollowing = Followers::isFollowing($user);
            if($isFollowing){
                $user->set_follow_flag(True);
            } else {
                $user->set_follow_flag(False);
            }
            array_push($found_users, $user);
        }
    }
    echo json_encode($found_users);
?>