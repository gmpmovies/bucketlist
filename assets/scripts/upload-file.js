$("form").on("change", ".file-upload-field", function(){
    $(this).parent(".file-upload-wrapper").attr("data-text", $(this).val().replace(/.*(\/|\\)/, '') );
});

$('.js-update-profile-pic').on('click', function(){
    console.log('Clicked');
    const $this = $(this);
    const imageid = $this.data('imageid');
    const imgSrc = $this.find('img.profile_pic').attr('src');
    console.log(imgSrc);
    console.log("Uploading image " + imageid);

    $.ajax({
        type: "POST",
        url: "/bucketlist/actions/update-profile-pic.php",
        data: ({imageid: imageid}),

        beforeSend(jqXHR, settings) {
            const $other_container = $('.is_active_profile');
            $other_container.removeClass('is_active_profile');

            $this.children().addClass('is_active_profile');
            console.log($this.children());
        },

        success: function(data){
            console.log(data);
            console.log('Added');
            $('.js_current_user_pic').attr('src', imgSrc);
        },

        error: function(data){
            console.log('An error occured while adding the imageid: ' + data);
            $('.js-error-message').append("<div class=\"alert alert-danger alert-dismissible fade show\">\n" +
                "  <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>\n" +
                "  <strong>Uh oh!</strong> There was a problem processing your request. Please try again later.\n" +
                "</div>");
        }

    })


});

$("form").on("change", ".js-upload-profile-pic", function(){
    const parent = $(this).parent().parent();
    const validExensions = ['jpeg', 'jpg', 'png'];
    const filename = $(this).val().replace(/.*(\/|\\)/, '');
    const extension = filename.replace(/^.*\./, '');
    var warning = parent.find('.warning');
    const imgPreview = $('#image-preview-post');
    const impPreviewContainer = $('.image-preview-container-account');
    if (jQuery.inArray(extension, validExensions) === -1){
        warning.text(extension.toUpperCase() + " file type not supported. Please upload a JPG, JPEG, or PNG image.")
        warning.removeClass('d-none');
        warning.addClass('d-block');
        impPreviewContainer.removeClass('d-block');
        impPreviewContainer.addClass('d-none');
    }  else {
        warning.removeClass('d-block');
        warning.addClass('d-none');
        var reader = new FileReader();
        reader.onload = function(e) {
            imgPreview.attr('src', e.target.result);
            impPreviewContainer.addClass('d-block');
            impPreviewContainer.removeClass('d-none');
        }

        reader.readAsDataURL(this.files[0]);
    }

});

