<?php
session_start();
require_once "/home1/gobinitc/public_html/bucketlist/pageload.php";
require_once "/home1/gobinitc/public_html/bucketlist/upload-image.php";
require_once "/home1/gobinitc/public_html/bucketlist/models/users.php";
$upload_errors = [];

if (isset($_POST['submit'])) {
    $uploader = new UploadImage($_FILES['myfile'], 100, True);
    $upload_errors = $uploader->checkErrors();
    $uploader->saveToServer();
}

$user = new Users();
$user = $user::getUser($_SESSION['id']);
$all_images = Files::getAllImagesFromUser($_SESSION['id'], True);

foreach($all_images as $key=>$image){
    if($image->get_filename() == $user->get_File()->get_filename()){
        $image->set_is_current_profile_pic(True);
        $all_images[$key] = $image;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bucket</title>
    <?php require_once "/home1/gobinitc/public_html/bucketlist/html_style_head.php"?>
    <link rel="stylesheet" href="/assets/styles/upload-file.css">
</head>
<body>
<?php require_once "/home1/gobinitc/public_html/bucketlist/navbar.php"; ?>
<div class="default-margin">
    <div class="row">
        <div class="col-md-5">
            <div style="text-align: center;">
                <div class="profile_pic_container_account">
                    <img class="profile_pic js_current_user_pic" src="/uploads/<?php echo $user->get_File()->get_filename()?>"/>
                </div>
                <p style="margin-bottom: 10px; margin-top: -6px;" class="name"><?php echo $user->get_firstname() . ' ' . $user->get_lastname()?></p>
            </div>
            <div class="upload-container" style="margin-bottom: 10px;">
                <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <div class="file-upload-wrapper" data-text="Select your file!">
                        <input name="myfile" type="file" class="file-upload-field js-upload-profile-pic" value="">
                    </div>
                    <p class="warning d-none"></p>
                    <div style="text-align: left" class="text-danger">
                        <span class="help-block"><?php if(count($upload_errors)>=1){foreach($upload_errors as $error){echo($error . ' ');}} ?></span>
                    </div>
                    <div class="image-preview-container-account d-none">
                        <div class="profile_pic_container_account">
                            <img class="profile_pic" id="image-preview-post" src="#">
                        </div>
                        <p>Preview</p>
                    </div>
                    <div style="text-align: center;">
                        <input type="submit" value="Upload Image" name="submit" class="btn btn-upload">
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-7">
            <div class="feed-container">
                <h2>Saved Profile Pictures</h2>
                <div class="row">
                    <?php
                        if(count($all_images)>=1){
                            foreach($all_images as $image){
                                echo'
                                <div class="col-md-4 col-sm-6 col-6 js-update-profile-pic" style="text-align: center; margin-bottom: 5px;" data-imageid="' . $image->get_id() . '">
                                    <div style="cursor: pointer;" class="profile_pic_container_account ' . (($image->get_is_current_profile_pic()==True)?"is_active_profile":"") . '">
                                        <img class="profile_pic" src="/uploads/' . $image->get_filename() . '"/>
                                    </div>
                                </div>
                            ';
                            }
                        } else {
                            echo'<p>No saved photos.</p>';
                        }

                    ?>

                </div>
            </div>
        </div>
    </div>
</div>


<?php require_once "/home1/gobinitc/public_html/bucketlist/html_body_scripts.php"?>
<script src="/assets/scripts/upload-file.js"></script>
</body>
</html>

