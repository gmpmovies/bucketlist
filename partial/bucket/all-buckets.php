<?php
session_start();
require_once "/home1/gobinitc/public_html/bucketlist/models/bucket.php";
require_once "/home1/gobinitc/public_html/bucketlist/models/users.php";
require_once "/home1/gobinitc/public_html/bucketlist/models/bucket_users.php";
require_once "/home1/gobinitc/public_html/bucketlist/pageload.php";

error_reporting(E_ALL);
$buckets = Bucket_Users::GetAllBucketsForUser($_SESSION['id']);

?>

<div class="feed-container">
    <a href="/bucketlist/bucket/create-bucketlist.php" class="btn btn-success">Create Bucket List</a>
    <?php
    foreach($buckets as $bucket) {
        $isPrivate = $bucket->get_Bucket()->get_isPrivate();
        if($isPrivate == True){
            $bucketPrivacyIcon = '/bucketlist/assets/icons/lock.svg';
        } else {
            $bucketPrivacyIcon = '/bucketlist/assets/icons/unlock.svg';
        }
        echo "
            <div class=\"bucket-container\">
                " . (($isPrivate == True)?"<div class='bucket-privacy-icon-container'><img class='bucket-privacy-icon' src='" . $bucketPrivacyIcon . "'/></div>":"") . "
                <a href=\"/bucketlist/bucket/bucket.php?bucketid=" . $bucket->get_Bucket()->get_id() . "\">" . $bucket->get_Bucket()->get_title() . "</a>
                <p>Owned By: </p>
                
                <p style=\"margin: 0px 10px 10px 10px;\">
                    <a class=\"profile_pic_link\" href=\"/account.php?userid=" . $bucket->get_Bucket()->get_User()->get_id() . "\">
                        <span class=\"profile_pic_container_search \" style=\"vertical-align: middle;\">
                            <img src='/bucketlist/uploads/" . $bucket->get_Bucket()->get_User()->get_File()->get_filename() . "' class=\"profile_pic\"/>
                        </span> 
                        <span>" . $bucket->get_Bucket()->get_User()->get_firstname() . " " . $bucket->get_Bucket()->get_User()->get_lastname() . "</span>
                    </a>
                </p>
                
                
            </div>
            ";
    }

    ?>

</div>
