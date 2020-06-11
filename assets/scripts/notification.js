$('.single-notification').on('click', function(){
    const url = $(this).data('url');
    const id = $(this).data('id');
    const active = $(this).data('active');
    if(active === 1){
        $.ajax({
            type: "POST",
            url: "/actions/navigate_notification.php",
            data: ({url: url, id: id}),

            beforeSend(jqXHR, settings) {

            },

            success: function(data){
                console.log(data);
                window.location.href = url;
            },

            error: function(data){
                console.log(data);
                $('.js-error-message').append("<div class=\"alert alert-danger alert-dismissible fade show\">\n" +
                    "  <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>\n" +
                    "  <strong>Uh oh!</strong> There was a problem processing your request. Please try again later.\n" +
                    "</div>");
            }
        })
    } else {
        window.location.href = url;
    }

});