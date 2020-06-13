//Show the bucket item info
$('#bucketModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal

    var bucket_title = button.find('.bl_title').text();
    var bl_desc = button.find('.bl_desc').text();
    var img_path = button.find('img').attr('src');

    var hidden_data = button.find('.modal_data_fields');
    var created_date = hidden_data.data('created-date');
    var item_id = hidden_data.data('item-id');
    var owner_name = hidden_data.data('owner-name');
    var username = hidden_data.data('username');
    var owner_id = hidden_data.data('owner-id');
    const userid = hidden_data.data('user-id');

    const has_posted = hidden_data.data('has-posted');
    const add_memory = hidden_data.data('add-memory');

    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this);
    modal.find('.modal-title').text(bucket_title);
    modal.find('#modal_item_desc').text('Description: ' + bl_desc);

    modal.find('.modal_item_created_date').text('Created On: ' + created_date);
    modal.find('img').attr('src', img_path);
    modal.find('.modal_owner_container a').attr('href', '/bucketlist/account.php?userid=' + owner_id);
    modal.find('.modal_owner_name').text(owner_name);
    modal.find('.js-bucket-item-modal-post').data('itemid', item_id);
    modal.find("#js-is-post-video").val("photo");

    if(add_memory === 1){
        if(has_posted === 0){
            modal.find('.js-bucket-item-modal-post').addClass('d-block').removeClass('d-none').text('Add Memory');
        } else {
            modal.find('.js-bucket-item-modal-post').addClass('d-none').removeClass('d-block');
        }
        modal.find('.js-modal-checkoff-inactive').removeClass('d-block').addClass('d-none');
    } else {
        if(owner_id === userid){
            modal.find('.js-bucket-item-modal-post').addClass('d-block').removeClass('d-none').text('Check Off');
            modal.find('.js-modal-checkoff-inactive').removeClass('d-block').addClass('d-none');
        } else {
            modal.find('.js-modal-checkoff-inactive').addClass('d-block').removeClass('d-none');
            modal.find('.js-bucket-item-modal-post').removeClass('d-block').addClass('d-none').text('Check Off');
        }
    }

});

//Remove post content if the 'X' is clicked when posting
$('#close-item-post').on('click', function(){
    const modal = $('#postModal');
    modal.find('#bucket-item-post').trigger("reset");
    modal.find('#js-video-id').val("");
    modal.find(".file-upload-wrapper").attr("data-text", "Select an Image");
    modal.find("#js-is-post-video").val("photo");
    modal.find("#js-video-link-input").val("");
    modal.find('#js-video-link').addClass('d-none').removeClass('d-block');
    modal.find('#js-video-thumbnail-text').addClass('d-none').removeClass('d-block');
    modal.find('#js-photo-upload-text').removeClass('d-none').addClass('d-block');
    modal.find('#yt-video-demo').attr('src', "").removeClass('d-block').addClass('d-none');
    modal.find('.image-preview-container').addClass('d-none').removeClass('d-block');
});

//Show the modal that lets you post about completing the item
$('#postModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget).parent().parent(); // Button that triggered the modal
    const title = button.find('#bucketModalTitle').text();
    const item_id = button.find('.js-bucket-item-modal-post').data('itemid');
    const imgSrc = button.find('.profile_pic').attr('src');
    const ownerName = button.find('.modal_owner_name').text();

    //Update Modal Contents
    var modal = $(this);
    modal.find("#bucketModalTitle").text(title);
    modal.find("#hiddenfieldforpost").val(item_id);
    modal.find(".profile_pic").attr("src", imgSrc);
    modal.find('.modal_owner_name').text(ownerName);

});

//Show demo image when image is uploaded
$("form").on("change", ".file-upload-field", function(){
    const parent = $(this).parent().parent();
    const validExensions = ['jpeg', 'jpg', 'png']
    const filename = $(this).val().replace(/.*(\/|\\)/, '')
    $(this).parent(".file-upload-wrapper").attr("data-text", filename );
    const extension = filename.replace(/^.*\./, '').toLowerCase();
    var warning = parent.find('.warning');
    const imgPreview = $('#image-preview-post');
    const impPreviewContainer = $('.image-preview-container');
    if (jQuery.inArray(extension, validExensions) === -1){
        warning.text(extension.toUpperCase() + " file type not supported. Please upload a JPG, JPEG, or PNG image.")
        warning.removeClass('d-none');
        warning.addClass('d-block');
        impPreviewContainer.removeClass('d-block');
        impPreviewContainer.addClass('d-none');
        $(this).val("");
    }
    else {
        if($('.js-post-upload-field')[0].files[0].size <= 10485760) {
            warning.removeClass('d-block');
            warning.addClass('d-none');
            var reader = new FileReader();
            reader.onload = function (e) {
                imgPreview.attr('src', e.target.result);
                impPreviewContainer.addClass('d-block');
                impPreviewContainer.removeClass('d-none');
            }
            reader.readAsDataURL(this.files[0]);
        } else {
            warning.text("File size too large. File must be smaller than 10MB");
            warning.removeClass('d-none').addClass('d-block');
            $(this).val("");
        }
    }

});

//When photo icon is clicked
$("#js-photo-selection").on('click', function(event){
    const parent = $(this).parent().parent().parent();
    parent.find("#js-is-post-video").val("photo");
    parent.find("#js-video-link-input").val("");
    parent.find('#js-video-link').addClass('d-none').removeClass('d-block');
    parent.find('#js-video-thumbnail-text').addClass('d-none').removeClass('d-block');
    parent.find('#js-photo-upload-text').removeClass('d-none').addClass('d-block');
    parent.find('#yt-video-demo').attr('src', "").removeClass('d-block').addClass('d-none');
    parent.find('#js-video-id').val("");
    ValidateForm();
});

//When video icon is clicked
$("#js-video-selection").on('click', function(event){
    const parent = $(this).parent().parent().parent();
    parent.find("#js-is-post-video").val("video");
    parent.find('#js-video-link').removeClass('d-none').addClass('d-block');
    parent.find('#js-video-thumbnail-text').removeClass('d-none').addClass('d-block');
    parent.find('#js-photo-upload-text').addClass('d-none').removeClass('d-block');
    ValidateForm();
});

//when video link is changed (when focus is lost)
$("#js-video-link-input").on('change', function(){
    const parent = $(this).parent();
    const videoId = ytVidId($(this).val());
    if(videoId !== false){ //If link is a valid YouTube URL
        parent.parent().find('#js-video-id').val(videoId);
    } else { //If Link is Invalid
        $(this).val("");
        parent.find('.yt-warning').addClass('d-block').removeClass('d-none');
    }


});

//detects if url is valid on each keyup event
$("#js-video-link-input").on('keyup', function(){
    const parent = $(this).parent();
    const videoId = ytVidId($(this).val());

    if(videoId !== false){ //If link is a valid YouTube URL
        parent.find('.yt-warning').removeClass('d-block').addClass('d-none');
        const url = "//www.youtube.com/embed/" + videoId;
        parent.parent().find('#yt-video-demo').attr('src', url).addClass('d-block').removeClass('d-none');
        parent.parent().find('#js-video-id').val(videoId);
    } else { //If link is not a valid YouTube URL
        parent.parent().find('#yt-video-demo').attr('src', "").removeClass('d-block').addClass('d-none');
    }


});

function ytVidId(url) {
    var p = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
    return (url.match(p)) ? RegExp.$1 : false;
}

// Perform form validation and submit form when clicked
$('#postModal').on('keyup mouseup change', function(){
    ValidateForm();
});

//Function to enable and disable the submit form button
function ValidateForm(){
    const modal = $('#postModal');
    const postType = modal.find('#js-is-post-video').val();
    const allowChange = modal.find('#submit-item-post-inactive').data('allowclick');
    if(allowChange === 0){
        var error = "";
        if (modal.find('.js-post-upload-field').get(0).files.length === 0) {
            error = "Please upload a photo. " + error;
        }
        if (modal.find('#message-text').val() === ""){
            error = "Your post is empty. Please write something. " + error;
        }
        if(postType === "photo"){

        }

        if (postType === "video"){
            if(modal.find('#js-video-link-input').val() === "" || modal.find('#js-video-id').val() === ""){
                error = "Please add a valid YouTube URL. " + error;
            }
        }
        if(error === ""){ //If there are no errors
            // modal.find('#bucket-item-post').submit();
            modal.find("#submit-item-post-active").addClass('d-block').removeClass('d-none');
            modal.find("#submit-item-post-inactive").removeClass('d-block').addClass('d-none');
            return true;
        } else {
            modal.find("#submit-item-post-inactive").addClass('d-block').removeClass('d-none');
            modal.find("#submit-item-post-active").removeClass('d-block').addClass('d-none');
            return false;
        }
    }

}


$(function () {
    $('[data-toggle="popover"]').popover();
});

//When active post button is clicked
$('#submit-item-post-active').on('click', function(){
    $(".js-loading-overlay").addClass("d-block").removeClass("d-none");
    $('#bucket-item-post').submit();
});

//Upload form to server
$('#bucket-item-post').on('submit', function(e){
    e.preventDefault();
    var formData = new FormData(this);
    if(ValidateForm()){
        const modal = $('#postModal');
        const videoID = modal.find("#js-video-id").val();
        const itemID = modal.find("#hiddenfieldforpost").val();
        const isPhotoVideo = modal.find('#js-is-post-video').val();
        const postText = modal.find("#message-text").val();
        const formData = new FormData();
        formData.append('file', $('.js-post-upload-field')[0].files[0]);
        formData.append('videoID', videoID);
        formData.append('itemID', itemID);
        formData.append('isVideo', isPhotoVideo);
        formData.append('post', postText);
        if($('.js-post-upload-field')[0].files[0].size <= 10485760){
            $.ajax({
                type: "POST",
                url: "/bucketlist/actions/make-post.php",
                // data: ({userid: userid, bucketid: bucketid, isAdmin: isAdmin}),
                data: formData,
                enctype: 'multipart/form-data',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    if(myXhr.upload){
                        myXhr.upload.addEventListener('progress',progress, false);
                    }
                    return myXhr;
                },
                processData: false,
                contentType: false,

                beforeSend(jqXHR, settings) {
                    modal.find('#submit-item-post-inactive').data('allowclick', '1');
                    modal.find("#submit-item-post-inactive").addClass('d-block').removeClass('d-none');
                    modal.find("#submit-item-post-active").removeClass('d-block').addClass('d-none');
                },

                success: function(data){
                    console.log(data);
                    $(".js-loading-overlay").removeClass("d-block").addClass("d-none");
                    $('#postModal').find('#close-item-post').click();
                    if(data == true){
                        $('.unique-item-container-' + itemID).remove();
                    } else {
                        $('.unique-item-container-' + itemID).find('.modal_data_fields').data('has-posted', 1);
                        $('.unique-item-container-' + itemID).find('.bl_desc_add_memory').remove();
                    }
                },

                error: function(data){
                    $("#overlay-loading-icon-percent").text("Error") ;
                    modal.find('.js-ajax-fail-warn').text("A server error occurred, please try again later.").addClass("d-block").removeClass("d-none");
                    $(".js-loading-overlay").removeClass("d-block").addClass("d-none");
                    modal.find("#submit-item-post-active").addClass('d-block').removeClass('d-none');
                    modal.find("#submit-item-post-inactive").removeClass('d-block').addClass('d-none');
                    console.log('An error occured: ' + data);
                    $('.js-error-message').append("<div class=\"alert alert-danger alert-dismissible fade show\">\n" +
                        "  <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>\n" +
                        "  <strong>Uh oh!</strong> There was a problem processing your request. Please try again later.\n" +
                        "</div>");
                }

            });
        }
    }
});



function progress(e){

    if(e.lengthComputable){
        var max = e.total;
        var current = e.loaded;

        var Percentage = (current * 100)/max;
        $("#overlay-loading-icon-percent").text(Math.ceil(Percentage) + "%") ;


        if(Percentage >= 100)
        {
            $("#overlay-loading-icon-percent").text("Uploaded") ;
        }
    }
}

$('.onoffswitch-checkbox').on('click', function(){
    const data = $(this).data('show');
    toggleAllItems(data);
});

function toggleAllItems(data){
    if(data===1){
        $('.js-current-items').removeClass('d-block').addClass('d-none');
        $('.js-completed-items').removeClass('d-none').addClass('d-block');
        $('.onoffswitch-checkbox').data('show', 2);
    }
    if(data===2){
        $('.js-completed-items').addClass('d-none').removeClass('d-block');
        $('.js-current-items').removeClass('d-none').addClass('d-block');
        $('.onoffswitch-checkbox').data('show', 1);
    }
}

$(document).ready(function(){
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has('addmemory')){
        const id = urlParams.get('addmemory');
        $('.unique-item-container-' + id).click();
    }
});