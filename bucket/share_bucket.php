<?php
error_reporting(E_ALL);
session_start();

require_once "/var/www/html/bucketlist/config.php";
require_once "/var/www/html/bucketlist/pageload.php";
require_once "/var/www/html/bucketlist/bucket/verify_bucket.php";
require_once "/var/www/html/bucketlist/bucket/add_listitem.php";
require_once "/var/www/html/bucketlist/models/listitem.php";
require_once "/var/www/html/bucketlist/models/followers.php";
require_once "/var/www/html/bucketlist/models/bucket_users.php";
require_once "/var/www/html/bucketlist/models/bucket.php";

$bucket = Bucket::getBucket($bucket_id);
$members = Bucket_Users::getBucketMembers($bucket_id, $bucket->get_ownerid());
$invitees = Followers::getFollowingWhoAreAlsoFollowers($_SESSION["id"], $bucket_id);
$temp = array();

//SELECT following FROM followers AS f1 WHERE user = 4 AND unfollowed = FALSE AND following IN (SELECT following FROM followers AS f2 WHERE f2.following = f1.following);
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
        <div class="col-md-4 col-sm-12">
            <div class="feed-container">
                <?php echo"<a class='btn btn-secondary btn-small' style='margin-left:10px;' href='/bucket/bucket.php?bucketid=". $bucket_id . "'>Back</a>" ?>
                <hr>
                <div class="outline-container" style="text-align: center;">
                    <h1 style="font-size: 22px;">Active Members</h1>

                    <?php
                    foreach($members as $member){
                        echo "
                        <div class=\"profile_container\" style='margin-bottom: 10px;'>
                            <div class=\"profile_pic_container\">
                            <a href='/account.php?userid=" . $member->get_id() . "'>
                                <img src='/uploads/" . $member->get_File()->get_filename() . "' class=\"profile_pic\" />
                            </a>
                            </div>
                            <a class='verticle_align_bottom' href='/account.php?userid=" . $member->get_id() . "' class='profile-name'>" . $member->get_firstname() . " " . $member->get_lastname() . "</a>
                        </div>";
                    }
                    ?>


                </div>

            </div>
        </div>

        <div class="col-md-8 col-sm-12">

            <div class="feed-container">
                <h1 style="font-size: 22px;">Available Users</h1>
                <p>Only users who you follow, and who also follow you back, are displayed for Bucket sharing.</p>
                <?php
                if(count($invitees) == 0){
                    echo"
                        <p style='font-style:italic;'>No users available for sharing, a user must also follow you back as well to enable sharing.</p>
                    ";
                } else {
                    foreach($invitees as $invitee){
                        echo "
                        <div class=\"profile_container\" style='margin-bottom: 10px;'>
                            <div class='row'>
                                <div class='col-2'>
                                    <div class=\"profile_pic_container_search\">
                                    <a href='/account.php?userid=" . $invitee->get_id() . "'>
                                        <img src='/uploads/" . $invitee->get_File()->get_filename() . "' class=\"profile_pic\" />
                                    </a>
                                    </div>
                                </div>
                                <div class='col-6'>
                                    <a class='text-ellipsis' href='/account.php?userid=" . $invitee->get_id() . "'>" . $invitee->get_firstname() . " " . $invitee->get_lastname() . "</a>
                                    <p class='username text-ellipsis'>@" . $invitee->get_username() . "</p>
                                </div>
                                <div class='col-4'>
                                    <button class='btn btn-outline-primary js-add-user-to-bucket' data-clicked='0' data-userid='" . $invitee->get_id() . "' data-bucketid='" . $bucket->get_id() . "'>Add</button>
                                </div>
                            </div>
                        </div>
                        <hr>";
                    }
                }

                ?>
            </div>
        </div>

    </div>

</div>

<?php require_once "/var/www/html/bucketlist/html_body_scripts.php"?>
<script src="/assets/scripts/share_bucket.js"></script>
</body>
</html>

