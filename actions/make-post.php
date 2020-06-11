<?php
session_start();
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);

require_once "/home1/gobinitc/public_html/bucketlist/upload-image.php";
require_once "/home1/gobinitc/public_html/bucketlist/models/listitem.php";
require_once "/home1/gobinitc/public_html/bucketlist/models/post.php";
require_once "/home1/gobinitc/public_html/bucketlist/models/users.php";
require_once "/home1/gobinitc/public_html/bucketlist/models/content.php";
require_once "/home1/gobinitc/public_html/bucketlist/models/bucket_users.php";
require_once "/home1/gobinitc/public_html/bucketlist/models/post_content.php";
require_once "/home1/gobinitc/public_html/bucketlist/models/notifications.php";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    //Get variables from AJAX request -- Some of these need to be validated as they could have been modified by the user.
    $imageFile = $_FILES['file'];
    $post = $_POST['post'];
    $videoID = $_POST['videoID'];
    $itemID = $_POST['itemID']; //Specifically this itemID should be validated--at least make sure it's owned by the user.
    $isVideo = $_POST['isVideo'];

    $originalPostReturnType = True;

    if($isVideo == "video"){
        $isVideo = True;
    } else {
        $videoID = null;
    }

    //is_post_created returns an array with 2 indices:
    //[0] - Bool - True = Post is already created; False = No post created
    //[1] - Int - The ID of the post that the item belongs to
    $is_post_created = Post::isPostCreated($itemID);

    if($is_post_created[0] == False){

        //validateItem returns an array with 3 indices:
        //[0] - Bool - True = Item belongs to user; False = Item does not belong to user
        //[1] - Bool - True = Post should be private; False = Post should be public
        //[2] - Int - The ID of the bucket that the item belongs to
        $validateListItem = Listitem::validateItem($itemID, $_SESSION['id']);
        $isValidated = $validateListItem[0];


        //Make sure the file code is 0.
        //1 = the upload size was too big
        //2 = the file upload size exceeds the max file size specified in HTML form
        //3 = the file was only partially uploaded
        //4 = no file was uploaded
        //6 = Server missing temp folder
        //7 = Failed to write to disk
        if($_FILES['file']['error'] == 0 && $isValidated){
            $check_off_item = Listitem::checkOffItem($itemID); //Set is_done to True in listitem table

            $isPrivate = $validateListItem[1]; //Get the bucket privacy setting

            //SAVE IMAGE TO SERVER AND TO DATABASE
            $imageUpload = new UploadImage($imageFile, 500, False);
            $image_upload_errors = $imageUpload->checkErrors();
            if($imageUpload->saveToServer()){
                //I should make an else statement validating that the file info was written to the DB correctly too
            }
            $newImageID = $imageUpload->getNewFileID();

            //ADD TO POST TABLE IN DATABASE
            $postID = Post::createPost($itemID, $isPrivate);

            //ADD TO CONTENT TABLE IN DATABASE
            $contentID = Content::addContent($newImageID, $post, $videoID, $isVideo);

            //ADD TO POST_CONTENT FOR EACH USER
            $all_users = Bucket_Users::getBucketMembers($validateListItem[2]);
            foreach($all_users as $user){
                $has_posted = False;
                $tempContentID = null;
                if($user->get_id() == $_SESSION['id']){ //If the userid == current user, then add the post flag
                    $has_posted = True;
                    $tempContentID = $contentID;
                } else { //If the userid is not the current user then set the contentID to Null
                    $tempContentID = null;
                    $notification = Notifications::generate_notification(1, $user->get_id(), $_SESSION['id'], $postID);
                    $increase_notification_number = Users::increaseNotifications($user->get_id());
                }
                $created_post_content = Post_Content::createPostContent($postID, $tempContentID, $has_posted, $user->get_id());
            }

        } else {
            error_log("THIS IS THE FILE UPLOAD CODE: ". $_FILES["file"]["error"] . " AND THIS IS THE STATUS OF THE VALIDATED ITEM QUERY: " . $validateListItem);
            http_response_code(404);

            die("Error saving to server");
        }
    }

    if($is_post_created[0] == True){
        //GET THE POST_CONTENT_ID
        $post_content_id = Post_Content::get_post_content_by_postID_and_userID($is_post_created[1], $_SESSION['id']);
        //SAVE IMAGE TO SERVER AND TO DATABASE
        $imageUpload = new UploadImage($imageFile, 500, False);
        $image_upload_errors = $imageUpload->checkErrors();
        if($imageUpload->saveToServer()){
            //I should make an else statement validating that the file info was written to the DB correctly too
        }
        $newImageID = $imageUpload->getNewFileID();

        //ADD TO CONTENT TABLE IN DATABASE
        $contentID = Content::addContent($newImageID, $post, $videoID, $isVideo);

        //UPDATE THE POST_CONTENT WITH contentID and ACTIVATE
        $update_post_content = Post_Content::updatePost_Content($post_content_id, $contentID, True);
        $originalPostReturnType = False;
    }

echo $originalPostReturnType;

}
?>