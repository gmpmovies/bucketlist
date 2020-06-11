<?php
//    ini_set('display_errors', 1);
//    ini_set('display_startup_errors', 1);

    session_start();

    require_once "/home1/gobinitc/public_html/bucketlist/config.php";
    require_once "/home1/gobinitc/public_html/bucketlist/pageload.php";
    require_once "/home1/gobinitc/public_html/bucketlist/bucket/verify_bucket.php";
    require_once "/home1/gobinitc/public_html/bucketlist/models/listitem.php";
    require_once "/home1/gobinitc/public_html/bucketlist/models/followers.php";
    require_once "/home1/gobinitc/public_html/bucketlist/models/bucket_users.php";
    require_once "/home1/gobinitc/public_html/bucketlist/models/bucket.php";
    require_once "/home1/gobinitc/public_html/bucketlist/bucket/add_listitem.php";
    require_once "/home1/gobinitc/public_html/bucketlist/models/post.php";

    $update_date = Bucket_Users::updateLastViewed($_SESSION['id'], $bucket_id);
    $items = Listitem::getAllInstances($bucket_id); //Get all items for the bucket
    $bucket = Bucket::getBucket($bucket_id); //Get the bucket details
    $members = Bucket_Users::getBucketMembers($bucket_id, $bucket->get_ownerid()); // Get the members of the bucket

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bucket</title>
    <?php require_once "/home1/gobinitc/public_html/bucketlist/html_style_head.php"?>
    <link rel="stylesheet" href="/assets/styles/upload-file.css">
    <link rel="stylesheet" href="/assets/styles/bucket.css">
</head>
<body>

<?php require_once "/home1/gobinitc/public_html/bucketlist/partial/bucket/model-bucket-item.php"; ?>
<?php require_once "/home1/gobinitc/public_html/bucketlist/partial/bucket/model-post-item.php"; ?>

<?php require_once "/home1/gobinitc/public_html/bucketlist/navbar.php"; ?>
<div class="default-margin">
    <div class="feed-container" style="padding: 5px;">
        <?php
            echo "
             <h1 class=\"bl_main_title\">". $bucket->get_title() ."</h1>
             <p class='bl_title' style='text-align:center;'>". $bucket->get_description() ."</p>
            "
        ?>
    </div>
    <div class="row">
        <div class="col-md-4 col-sm-12">
            <div class="feed-container">
                <a class='btn btn-secondary btn-small' href='/feed.php'>Back</a>
                <hr>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?bucketid=' . $bucket_id; ?>" method="post">
                    <div class="form-group <?php echo (!empty($item_name_err)) ? 'has-error' : ''; ?>">
                        <label>New Bucketlist Item</label>
                        <input type="text" name="item" class="form-control" autocomplete="off" required value="<?php echo $item_name; ?>">
                        <span class="help-block"><?php echo $item_name_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" name="itemdesc" class="form-control" autocomplete="off" value="<?php echo $item_desc; ?>">
                    </div>

                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <input type="reset" class="btn btn-default" value="Reset">
                    </div>
                </form>
                <div style="text-align: center;">
                    <?php echo"<a style='margin-top: 10px;' class='btn-small btn-outline-dark btn' href='/bucket/share_bucket.php?bucketid=" . $bucket_id . "'>Add Members</a>" ?>
                </div>
                <div class="outline-container" style="text-align: center;">
                    <h1 style="font-size: 22px;">Members</h1>

                    <?php
                        foreach($members as $member){
                            echo "
                        <p style=\"margin: 0px 10px 10px 10px;\">
                            <a class=\"profile_pic_link\" href=\"/account.php?userid=" . $member->get_id() . "\">
                                <span class=\"profile_pic_container_search \" style=\"vertical-align: middle;\">
                                    <img src='/uploads/" . $member->get_File()->get_filename() . "' class=\"profile_pic\"/>
                                </span> 
                                <span>" . $member->get_firstname() . " " . $member->get_lastname() . "</span>
                            </a>
                        </p>
                        
                         ";
                        }
                    ?>


                </div>

            </div>
        </div>

        <div class="col-md-8 col-sm-12">
            <div class="onoffswitch">
                <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" data-show="1" id="myonoffswitch" checked>
                <label class="onoffswitch-label" for="myonoffswitch">
                    <span class="onoffswitch-inner"></span>
                    <span class="onoffswitch-switch"></span>
                </label>
            </div>

            <div class="current-items js-current-items d-block">
                <div class="row">
                    <?php
                        foreach($items as $item){
                            $date = date_create($item->get_date());
                            if($item->get_is_done() == False){
                                echo"
                            
                                <div class=\"col-md-6 unique-item-container-" . $item->get_id() . "\" data-toggle=\"modal\" data-target=\"#bucketModal\">
                                    <div class=\"bl_item_container\">
                                        <div class='overflow_hidden' style='text-align: center;'>
                                            <p class='bl_title'>" . $item->get_item_name() . "</p>
                                            <p class='bl_desc'>" . $item->get_item_desc() . "</p>
                                            <div class='profile_pic_container_xsm right-corner'>
                                                <img class='profile_pic' src='/uploads/" . $item->get_Owner()->get_File()->get_filename() . "'>
                                            </div>
                                            <input class='modal_data_fields' type='hidden' data-user-id='" . $_SESSION['id'] . "' data-owner-id='" . $item->get_Owner()->get_id() . "' data-has-posted='NONE' data-add-memory='0' data-owner-name='" . $item->get_Owner()->get_firstname() . " " . htmlspecialchars($item->get_Owner()->get_lastname(False)) . "' data-username='" . $item->get_Owner()->get_username() . "' data-created-date='" . date_format($date, 'F d, Y') . "' data-item-id='" . $item->get_id() . "'>
                                        </div>
                                    </div>
                                </div>
                            
                            ";
                            }

                        }
                    ?>
                </div>
            </div>
            <div class="completed-items js-completed-items d-none">
                <div class="row">
                    <?php
                    foreach($items as $item){
                        $date = date_create($item->get_date());
                        $has_posted = $item->get_current_user_has_created_post();
                        if($item->get_is_done() == True){
                            echo"
                            
                                <div class=\"col-md-6 unique-item-container-" . $item->get_id() . "\" data-toggle=\"modal\" data-target=\"#bucketModal\">
                                    <div class=\"bl_item_container\">
                                        <div class='overflow_hidden' style='text-align: center;'>
                                            <p class='bl_title'>" . $item->get_item_name() . "</p>
                                            <p class='bl_desc'>" . $item->get_item_desc() . "</p>
                                            " . (($has_posted)?'':'<p class=\'bl_desc bl_desc_add_memory\' style="color: red;">Click to add your memory!</p>') . "
                                            <div class='profile_pic_container_xsm right-corner'>
                                                <img class='profile_pic' src='/uploads/" . $item->get_Owner()->get_File()->get_filename() . "'>
                                            </div>
                                            <input class='modal_data_fields' type='hidden' data-user-id='" . $_SESSION['id'] . "' data-has-posted='" . $has_posted . "' data-add-memory='1' data-owner-id='" . $item->get_Owner()->get_id() . "' data-owner-name='" . $item->get_Owner()->get_firstname() . " " . htmlspecialchars($item->get_Owner()->get_lastname(False)) . "' data-username='" . $item->get_Owner()->get_username() . "' data-created-date='" . date_format($date, 'F d, Y') . "' data-item-id='" . $item->get_id() . "'>
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
    </div>

</div>

<?php require_once "/home1/gobinitc/public_html/bucketlist/html_body_scripts.php"?>
<script src="/assets/scripts/modal.js"></script>
</body>
</body>
</html>

