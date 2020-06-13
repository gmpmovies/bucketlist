<div class="modal fade" id="followersModal" tabindex="-1" role="dialog" aria-labelledby="followersModal" data-type="followers" data-clicked="0" data-userid="<?php echo $user->get_id() ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bucketModalTitle">Followers</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body modal-max-body-height js-followers-modal">

                <div class="row js-placeholder-name">
                    <div class="col-3">
                        <a href="">
                            <div class="profile_pic_container_search">
                                <img class="profile_pic" src="/bucketlist/uploads/default.jpeg"/>
                            </div>
                        </a>
                    </div>
                    <div class="col-5">
                        <a href="">
                            <p class="text-ellipsis modal-account-popup-text">Loading Users...</p>
                            <p class="text-ellipsis modal-account-popup-text">Member Since: ...</p>
                        </a>
                    </div>
                    <div class="col-4">
                        <button type="button" data-clicked="False" data-user-id="" data-type="Unfollow" class="btn btn-secondary js_follow_user">Following</button>
                    </div>
                </div>

                <hr>

            </div>
            <div class="modal-footer" style="justify-content: space-between;">

            </div>
        </div>
    </div>
</div>

