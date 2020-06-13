<div class="modal fade" id="bucketModal" tabindex="-1" role="dialog" aria-labelledby="bucketModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bucketModalTitle">Bucket Title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body modal-max-body-height">
                <p id="modal_item_desc">Item Description: </p>
                <p class="modal_item_created_date">Item Created Date: </p>
            </div>
            <div class="modal-footer" style="justify-content: space-between;">
                <p style="margin: 0px;" class="modal_owner_container"><span class="profile_pic_container_search modal_owner_img" style="vertical-align: middle;"><a class="profile_pic_link" href=""><img src="/bucketlist/uploads/default.jpeg" class="profile_pic"/></span> <span class="modal_owner_name">Owner Name</span></a></p>
                <button type="button" class="btn btn-primary js-bucket-item-modal-post d-none" data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target="#postModal" data-itemid="">Check Off</button>
                <a tabindex="0" class="btn btn-secondary js-modal-checkoff-inactive d-none" data-trigger="focus" role="button" data-toggle="popover" data-placement="left" title="Info" data-content="Only the creator of this item can check it off. Don't worry, after it is checked off, you will be able to add your memories to the post.">Check Off</a>
            </div>
        </div>
    </div>
</div>