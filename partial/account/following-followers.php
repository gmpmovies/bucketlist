<?php
require_once "/var/www/html/bucketlist/models/followers.php";
#Get all followers
$followers = Followers::getAllFollowers($account_id, False);
#Get all following
$following = Followers::getAllFollowers($account_id, True);
?>

<div class="feed-container">
    <p class="center_title">Followers</p>
    <div class="row">
        <?php
        if(count($followers) >= 1){
            foreach($followers as $follower){
                echo "
                        <div class=\"col-md-4\">
                            <a href='/account.php?userid=" . $follower->get_id() . "'>
                                <div class=\"profile_pic_container_search\" style=\"display: block;\">
                                    <img class=\"profile_pic\" src=\"/uploads/" . $follower->get_File()->get_filename() . "\"/>
                                </div>
                                <p class=\"center_name_text\">" . $follower->get_firstname() . " " . $follower->get_lastname() . "</p>
                            </a>
                        </div>
                        
                        ";
            }
        } else {
            echo"
                    <p style='margin: auto;;'>" . $user->get_firstname() . " " . $user->get_lastname() . " currently has no followers.</p>
                    ";
        }
        ?>

    </div>
</div>

<div class="feed-container">
    <p class="center_title">Following</p>
    <div class="row">
        <?php
        if(count($following) >= 1){
            foreach($following as $follows){
                echo "
                                <div class=\"col-md-4\">
                                    <a href='/account.php?userid=" . $follows->get_id() . "'>
                                        <div class=\"profile_pic_container_search\" style=\"display: block;\">
                                            <img class=\"profile_pic\" src=\"/uploads/" . $follows->get_File()->get_filename() . "\"/>
                                        </div>
                                        <p class=\"center_name_text\">" . $follows->get_firstname() . " " . $follows->get_lastname() . "</p>
                                    </a>
                                </div>
                                
                                ";
            }
        } else {
            echo"
                            <p style='text-align: center;'>" . $user->get_firstname() . " is not following anybody.</p>
                            ";
        }
        ?>

    </div>
</div>
