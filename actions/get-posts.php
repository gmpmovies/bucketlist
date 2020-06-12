<?php
require_once "/home1/gobinitc/public_html/bucketlist/models/post.php";
$offset = $_GET['offset'];
$loc = $_GET['loc'];
$batch_id = $offset;
$limit = 7;
$offset = $offset * $limit;
if($loc == "feed"){
    $feed = Post::getFeed($limit, $offset);
} elseif($loc == "profile") {
    $account_id = $_GET["userid"];
    $feed = Post::getProfileFeed($limit, $offset, $account_id);
}

$allContent = "";

$index = 0;

if(count($feed) >= 1){
    foreach($feed as $item){
        $post_users = array();
        $popover = "";
        foreach($item->get_Post_Content() as $post_content){
            $user = $post_content->get_User();
            if($post_content->get_has_posted() == True){
                array_push($post_users, $user);
                $popoverContent = "
                    <a class='profile_pic_link' href='/bucketlist/account.php?userid=" . $user->get_id() . "'>
                        <div>
                            <span class='profile_pic_container_search' style='vertical-align: middle'>
                                <img src='/bucketlist/uploads/" . $user->get_File()->get_filename() . "' class='profile_pic'>
                            </span>
                        
                            <span>" . $user->get_firstname() . " " . $user->get_lastname() . " </span>
                        </div>
                    </a>
                ";
                $popover = $popover . " " . $popoverContent;
            }

        }
        $unique_post_id = $batch_id . $index;
        $index = $index + 1;

        $all_unique_posts = '';

        foreach($item->get_Post_Content() as $unique_post){
            $post = '';
            if($unique_post->get_has_posted() == True){
                //IF THE POST IS A VIDEO
                if($unique_post->get_Content()->get_is_video() == True){
                    $post = '
                    <div class="post unique-post-' . $unique_post_id . '">
                        <div class="feed-text-height">
                            <p class="feed-item-poster-name">
                                <a class="profile_pic_link" href="/bucketlist/account.php?userid=' . $unique_post->get_User()->get_id() . '">
                                    <span class="profile_pic_container_search" style="vertical-align: middle">
                                        <img src="/bucketlist/uploads/' . $unique_post->get_User()->get_File()->get_filename() . '" class="profile_pic">
                                    </span>
                                    <span>' . $unique_post->get_User()->get_firstname() . ' ' . $unique_post->get_User()->get_lastname() . ' </span>
                                </a>
                            </p>
                            <p class="post-text">' . $unique_post->get_Content()->get_post() . '</p>
                        </div>
                        <div class="feed-video-container">
                            <div class="feed-video-thumbnail-container">
                                <img class="feed-video-play-button" src="/bucketlist/assets/icons/play-button.svg"/>
                                <div class="feed-video-dark"></div>
                                <img class="feed-image" src="/bucketlist/uploads/' . $unique_post->get_Content()->get_File()->get_filename() . '"/>
                            </div>
                            <div class="feed-video-embed d-none" data-videoid="' . $unique_post->get_Content()->get_videoURL() . '" data-postid="unique-post-' . $unique_post_id . '">
                               
                            </div>
                        </div>
                    </div>
                    ';
                //IF THE POST IS A PHOTO
                } else {
                    $post = '
                    <div class="post unique-post-' . $unique_post_id . '" style="height: auto;">
                        <div style="height: auto;">
                            <div class="feed-text-height">
                                <p class="feed-item-poster-name">
                                    <a class="profile_pic_link" href="/bucketlist/account.php?userid=' . $unique_post->get_User()->get_id() . '">
                                        <span class="profile_pic_container_search" style="vertical-align: middle">
                                            <img src="/bucketlist/uploads/' . $unique_post->get_User()->get_File()->get_filename() . '" class="profile_pic">
                                        </span>
                                        <span>' . $unique_post->get_User()->get_firstname() . ' ' . $unique_post->get_User()->get_lastname() . ' </span>
                                    </a>
                                </p>
                                <p class="post-text">' . $unique_post->get_Content()->get_post() . '</p>
                            </div>
                            <div class="feed-image-container">
                                <div class="feed-expand-container" data-postid="unique-post-' . $unique_post_id . '">
                                    <div class="feed-expand-icon-container"></div>
                                    <img class="feed-expand-icon" src="/bucketlist/assets/icons/expand.svg">
                                </div>
                                <img class="feed-image" src="/bucketlist/uploads/' . $unique_post->get_Content()->get_File()->get_filename() . '"/>
                            </div>
                        </div>
                    </div>
                    ';
                }
            }

            $all_unique_posts = $all_unique_posts . ' ' . $post;
        }
        $count_users = (int)count($post_users);
        $count_users = $count_users - 1;
        if($count_users >= 1){
            $users_modal = '
            <a href="/bucketlist/account.php?userid=' . $post_users[0]->get_id() . '">' . $post_users[0]->get_firstname() . ' ' . $post_users[0]->get_lastname() . ' </a>
            and
            <a role="button" tabindex="0" data-trigger="focus" class="" data-toggle="popover" data-html="true" data-placement="bottom" data-content="' . htmlspecialchars($popover) . '"> ' . $count_users . ' ' . ($count_users >= 2 ? 'others':'other') . ' </a>
            checked off ' . $item->get_Item()->get_item_name();
        } else {
            $users_modal = '
            <a href="/bucketlist/account.php?userid=' . $post_users[0]->get_id() . '">' . $post_users[0]->get_firstname() . ' ' . $post_users[0]->get_lastname() . ' </a>
            checked off ' . $item->get_Item()->get_item_name();
        }

        $feedItem = '
            <div class="feed-post-container">
                <p class="feed-item-poster-name all-posters">
                    ' . $users_modal . '
                </p>
                <hr>
                <div class="all-posts unique-post-full-post-' . $unique_post_id . '">
                    ' . $all_unique_posts . '
                </div>
                <hr class="no-margin">
                <div class="row" style="height: 40px;">
                    <div class="col-4 feed-post-buttons" style="border-right: solid 1px;">
                        <p class="feed-post-button-text">Like</p>
                    </div>
                    <div class="col-4 feed-post-buttons" style="border-right: solid 1px;">
                        <p class="feed-post-button-text">Comment</p>
                    </div>
                    <div class="col-4">
                        <p class="feed-post-button-text">Another setting</p>
                    </div>
                </div>
            </div>
            ';

        $allContent = $allContent . " " . $feedItem;
    }

    $finalContent = '
        <div id="feed-batch-' . $batch_id . '">
        ' . $allContent . '
        </div>
    ';


//echo json_encode($feed);
echo $finalContent;
} else {
    echo "False";
}




?>