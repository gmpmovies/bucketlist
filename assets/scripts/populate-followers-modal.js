$('#followersModal, #followingModal').on('show.bs.modal', function (event) {
    const $this = $(this);
    const userid = $this.data('userid');
    const type = $this.data('type');
    const clicked = $this.data('clicked');

    if(clicked==0){
        $this.data('clicked', 1);
        $.ajax({
            type: "POST",
            url: "/bucketlist/actions/get_followers_modal.php",
            data: ({userid: userid, type: type}),

            beforeSend(jqXHR, settings) {
                $('.js-placeholder-name').addClass('show');
                $('.js-placeholder-name').removeClass('hide');
            },

            success: function(data){
                $('.js-placeholder-name').addClass('hide');
                $('.js-placeholder-name').removeClass('show');
                var users = jQuery.parseJSON(data);

                $.each(users, function(key,value) {
                    var resultBlock = "";

                    var followButton = "";
                    if(value["followFlag"] == true){
                        followButton = ' <button type="button" data-clicked="False" data-user-id="' + value["id"] + '" data-type="Unfollow" class="btn btn-secondary js_follow_user">Following</button>';
                    } else if (value["followFlag"]==false){
                        followButton = '<button type=\"button\" class=\"btn btn-primary js_follow_user\" data-type=\"Follow\" data-clicked=\"False\" data-user-id=\"' + value["id"] + '\">Follow</button>'
                    }
                    resultBlock = "<div class=\"row\">\n" +
                        "                    <div class=\"col-3\">\n" +
                        "                        <a href=\"/bucketlist/account.php?userid=" + value["id"] + "\">\n" +
                        "                            <div class=\"profile_pic_container_search\">\n" +
                        "                                <img class=\"profile_pic\" src=\"/bucketlist/uploads/" + value["File"]["filename"] + "\"/>\n" +
                        "                            </div>\n" +
                        "                        </a>\n" +
                        "                    </div>\n" +
                        "                    <div class=\"col-5\">\n" +
                        "                        <a href=\"/bucketlist/account.php?userid=" + value["id"] + "\">\n" +
                        "                            <p class=\"text-ellipsis modal-account-popup-text\">" + value["firstname"] + " " + value["lastname"] + "</p>\n" +
                        "                            <p class=\"text-ellipsis modal-account-popup-text\">Member Since: " + value["created_at"] + " </p>\n" +
                        "                        </a>\n" +
                        "                    </div>\n" +
                        "                    <div class=\"col-4\">\n" +
                        "                        " + followButton + " \n" +
                        "                    </div>\n" +
                        "                </div>\n" +
                        "\n" +
                        "                <hr>";


                    if(type=="followers"){
                        $('.js-followers-modal').append(resultBlock);
                    } else {
                        $('.js-following-modal').append(resultBlock);
                    }


                });

            },

            error: function(data){
                console.log('An error occured: ' + data);
                $('.js-error-message').append("<div class=\"alert alert-danger alert-dismissible fade show\">\n" +
                    "  <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>\n" +
                    "  <strong>Uh oh!</strong> There was a problem processing your request. Please try again later.\n" +
                    "</div>");
            }

        });
    }



});