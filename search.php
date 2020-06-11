<?php
session_start();
require_once "/var/www/html/bucketlist/config.php";
require_once "/var/www/html/bucketlist/pageload.php";
require_once "/var/www/html/bucketlist/models/users.php";
require_once "/var/www/html/bucketlist/models/followers.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$search_results = array();
$found_users = array();
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_term = strtolower($_POST["search"]);
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
            echo 'Problem in SQL execute search.php';
        }
        $stmt->close();
    } else {
        echo 'problem in sql prepare search.php';
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bucket</title>
    <?php require_once "/var/www/html/bucketlist/html_style_head.php"?>

</head>
<body>
<?php require_once "/var/www/html/bucketlist/navbar.php"; ?>
<div class="default-margin">
    <div class="row">
        <div class="search-container">
            <?php
                if(count($search_results) == 0){
                    echo "
                       <p style='text-align: center;'>No results found for \"" . $search_term . "\".</p>
                    ";
                } else {
                    foreach($found_users as $user){
                        $date = date_create($user->get_created_at());
                        echo "
                        <div class='space-container'>
                            <div class=\"search-result\">
                                <div class=\"row\">
                                    <div class=\"col-sm-2 col-xs-3\" style=\"text-align: center;\">
                                        <div class=\"profile_pic_container_search verticle_align\">
                                            <a href=\"/account.php?userid=" . $user->get_id() . "\">
                                                <img class=\"profile_pic\" src=\"/uploads/" . $user->get_File()->get_filename() . "\">
                                            </a>
                                        </div>
                                    </div>
                                    <div class=\"col-sm-6 col-xs-9\">
                                        <div class=\"search-info\">
                                            <a href=\"/account.php?userid=" . $user->get_id() . "\"> " . $user->get_firstname() . " " . $user->get_lastname() . "</a>
                                            <p style='font-size: 12px; margin-top: -4px;' class=\"search-text\">@" . $user->get_username() . "</p>
                                            <p class=\"search-text\">Member Since: " . date_format($date, 'F d, Y') . "</p>
                                        </div>
                                    </div>
                                    <div class=\"col-sm-2 col-xs-12\" style=\"text-align: center; -webkit-writing-mode: vertical-lr;\">
                                        " . (($user->get_follow_flag()==True)?'<button type="button" data-clicked="False" data-user-id="' . $user->get_id() . '" data-type="Unfollow" class="btn btn-secondary js_follow_user">Following</button>': '<button type="button" class="btn btn-primary js_follow_user" data-type="Follow" data-clicked="False" data-user-id="' . $user->get_id() . '">Follow</button>') . "
                                        
                                    </div>
                
                                </div>
                
                            </div>
                        </div>
                        ";
                    }
                }
            ?>


        </div>
    </div>

</div>

<?php require_once "/var/www/html/bucketlist/html_body_scripts.php"?>
</body>
</html>
