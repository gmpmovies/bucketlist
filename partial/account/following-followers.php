<?php
require_once "/home1/gobinitc/public_html/bucketlist/models/followers.php";
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
                            <a href='/bucketlist/account.php?userid=" . $follower->get_id() . "'>
                                <div class=\"profile_pic_container_search\" style=\"display: block;\">
                                    <img class=\"profile_pic\" src=\"/bucketlist/uploads/" . $follower->get_File()->get_filename() . "\"/>
                                </div>
                                <p class=\"center_name_text\">" . $follower->get_firstname() . " " . $follower->get_lastname() . "</p>
                            </a>
                        </div>
                        
                        ";
            }
            echo '<div style="text-align: center; margin-top: 5px; width: 100%;"><a href="" data-toggle="modal" data-target="#followersModal" style="width: 100%;" class="btn btn-outline-secondary">See All</a></div>';
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
                                    <a href='/bucketlist/account.php?userid=" . $follows->get_id() . "'>
                                        <div class=\"profile_pic_container_search\" style=\"display: block;\">
                                            <img class=\"profile_pic\" src=\"/bucketlist/uploads/" . $follows->get_File()->get_filename() . "\"/>
                                        </div>
                                        <p class=\"center_name_text\">" . $follows->get_firstname() . " " . $follows->get_lastname() . "</p>
                                    </a>
                                </div>
                                
                                ";
            }
            echo '<div style="text-align: center; margin-top: 5px; width: 100%;"><a href="" data-toggle="modal" data-target="#followingModal" style="width: 100%;" class="btn btn-outline-secondary">See All</a></div>';
        } else {
            echo"
                            <p style='text-align: center;'>" . $user->get_firstname() . " is not following anybody.</p>
                            ";
        }
        ?>

    </div>
</div>
