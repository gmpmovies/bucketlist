
<div id="overlay" class="d-none js-loading-overlay"></div>
<div class="overlay-complete-container d-none js-loading-overlay">
    <div id="overlay-icon-container" class="">
        <p id="overlay-loading-icon-percent"></p>
    </div>
</div>



<div class="modal fade" id="postModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="postModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bucketModalTitle">Bucket Title</h5>
                <button type="button" class="close" id="close-item-post" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body modal-max-body-height">
<!--                POST FORM-->
                <form action="" id="bucket-item-post" method="post">
<!--                    THE ACTUAL POST TEXT-->
                    <div class="form-group" style="margin-bottom: 0px">
                        <a tabindex="0" role="button" class="" data-trigger="focus" data-toggle="popover" data-html="true" data-placement="right" title="Posting" data-content="When posting about a finished item, there are a few things to consider: <ul><li>Every user included in this bucket will have an opportunity to add their stories to this post.</li><li>Your post should contain a small and unique memory or experience in regards to completing this item.</li><li>You must include either a photo or video (currently we only accept YouTube URL's) with your post. But choose wisely, each user in the bucket only gets one image or video to post!</li></ul>">Help? </a>
                        <textarea name="postcontent" placeholder="Share your Experience!" class="form-control" id="message-text" style="min-height: 150px;" required aria-required="true" autocomplete="off"></textarea>
                    </div>
<!--                    POST BOTTOM MENU-->
                    <div class="photo-option-container">
                        <div class="row">
                            <div class="col-4 photo-selection" id="js-photo-selection" style="border-right: #999 solid 1px;">
                                <img class="post-camera-icon" src="/assets/icons/photo-camera.svg" />
                            </div>
                            <div class="col-4 photo-selection" id="js-video-selection" style="border-right: #999 solid 1px;">
                                <img class="post-camera-icon" src="/assets/icons/video-camera.svg" />
                            </div>
                            <div class="col-4 photo-selection">

                            </div>
                        </div>
                    </div>

<!--                    VIDEO UPLOAD LINK-->
                    <div class="d-none" id="js-video-link">
                        <label for="videolink">Input a Valid Youtube Link:</label>
                        <input style="margin-bottom: 10px;" type="text" class="form-control" placeholder="Youtube Video Link" name="videolink" id="js-video-link-input" autocomplete="off"/>
                        <p style="color:red;" class="yt-warning d-none">The link provided must be a YouTube link.</p>
                    </div>
                    <iframe id="yt-video-demo" class="yt-video-demo d-none" src="" frameborder="0" allowfullscreen>
                    </iframe>
<!--                    FILE UPLOAD -->
                    <label class="d-none" id="js-video-thumbnail-text">Video Thumbnail:</label>
                    <label class="" id="js-photo-upload-text">Upload an Image:</label>
                    <div class="file-upload-wrapper js-input-image-on-post" data-text="Upload an Image">
                        <input name="myfile" type="file" class="file-upload-field js-post-upload-field" value="" required aria-required="true">
                    </div>
                    <p class="warning d-none"></p>

<!--                    IMAGE/VIDEO PREVIEW-->
                    <div class="image-preview-container d-none">
                        <img src="#" id="image-preview-post" alt="your image" class="image-preview"/>
                    </div>

                    <input type="hidden" name="videoId" id="js-video-id" value="">
                    <input type="hidden" name="isVideo" id="js-is-post-video" value="photo">
                    <input type="hidden" name="itemid" id="hiddenfieldforpost" value="">
                </form>
                <p style="color: red" class="d-none js-ajax-fail-warn"></p>
            </div>
            <div class="modal-footer" style="justify-content: space-between;">
                <p style="margin: 0px;" class="modal_owner_container"><span class="profile_pic_container_search modal_owner_img" style="vertical-align: middle;"><a class="profile_pic_link" href=""><img src="/uploads/default.jpeg" class="profile_pic"/></span> <span class="modal_owner_name">Owner Name</span></a></p>

                <a tabindex="0" data-allowclick="0" role="button" class="btn btn-secondary" form="bucket-item-post" id="submit-item-post-inactive" data-trigger="focus" data-toggle="popover" data-placement="left" title="Warning" data-content="Please fill out all fields.">Post</a>
                <button type="button" class="btn btn-primary d-none" form="bucket-item-post" id="submit-item-post-active">Post</button>
            </div>
        </div>
    </div>
</div>