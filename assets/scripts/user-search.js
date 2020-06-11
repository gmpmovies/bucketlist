
$('.js-user-search-form').on('keyup', function(){
    const $this = $(this);
    const searchterm = $this.val();
    console.log($this.val());
    if(searchterm != ""){
        $.ajax({
            type: "POST",
            url: "/actions/user_search.php",
            data: ({searchterm: searchterm}),

            beforeSend(jqXHR, settings) {

            },

            success: function(data){
                var users = jQuery.parseJSON(data);
                var items = new Array();
                $('.js-search-results').children().each(function (key, getBlocks) {
                    const id = $(getBlocks).data('result-id');
                    //Append if item is NOT already in array
                    if(jQuery.inArray(id, items) === -1) {
                        items.push(id);
                    }
                });
                $.each(users, function(key,value) {

                    //Skip adding user if already present (in array)
                    if(jQuery.inArray(value["id"], items) !== -1){
                        var filteredAry = items.filter(function(e) { return e !== value["id"] });
                        items = filteredAry;
                    } else {
                        var resultBlock = "<div class=\"js-result\" data-result-id=\"" + value["id"] + "\">\n" +
                            "              <div class='row'>\n" +
                            "                <div class=\"col-sm-2 col-2 \" style=\"text-align: center;\">\n" +
                            "                    <div class=\"profile_pic_container_search verticle_align\">\n" +
                            "                        <a href=\"/account.php?userid=" + value["id"] + "\">\n" +
                            "                            <img class=\"profile_pic\" src=\"/uploads/" + value['File']['filename'] + "\">\n" +
                            "                        </a>\n" +
                            "                    </div>\n" +
                            "                </div>\n" +
                            "                <div class=\"col-sm-6 col-6\">\n" +
                            "                    <div class=\"search-info\">\n" +
                            "                        <a class='mobile-search-text' href=\"/account.php?userid=" + value["id"] + "\">"+ value["firstname"] + " " + value["lastname"] + "</a>\n" +
                            "                        <p style='font-size: 12px; margin-top: -4px;' class=\"search-text mobile-search-text\">@" + value["username"] + "</p>\n" +
                            "                        <p class=\"search-text mobile-search-text\">Member Since: " + value["created_at"] + "</p>\n" +
                            "                    </div>\n" +
                            "                </div>\n" +
                            "                <div class=\"col-sm-2 col-4\" style=\"text-align: center; -webkit-writing-mode: vertical-lr;\">\n" +
                            "                    " + (value["followFlag"] == true ? '<button type="button" data-clicked="False" data-user-id="' + value["id"] + '" data-type="Unfollow" class="btn btn-secondary js_follow_user">Following</button>':'<button type="button" class="btn btn-primary js_follow_user" data-type="Follow" data-clicked="False" data-user-id="'+ value["id"] +'">Follow</button>' ) + "\n" +
                            "                </div>\n" +
                            "              </div>" +
                            "            <hr></div>";

                        $('.js-search-results').append(resultBlock);

                    }
                });

                $(".js-search-results").children().each(function(key, value) {
                    if(jQuery.inArray($(value).data('result-id'), items) !== -1){
                        $(value).remove();
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
    } else {
        $('.js-search-results').children().remove();
    }



});