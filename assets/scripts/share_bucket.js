$('.js-add-user-to-bucket').on('click', function(){
    $this = $(this);
    const userid = $this.data('userid');
    const bucketid = $this.data('bucketid');
    const isAdmin = 0;
    const clicked = $this.data('clicked');
    console.log(userid);
    console.log(bucketid);

    if(clicked==0){
        $this.data('clicked', 1);
        $.ajax({
            type: "POST",
            url: "/actions/share-bucket.php",
            data: ({userid: userid, bucketid: bucketid, isAdmin: isAdmin}),

            beforeSend(jqXHR, settings) {
                $this.removeClass('btn-outline-primary');
                $this.addClass('btn-secondary');
                $this.text('Added');
            },

            success: function(data){
                console.log(data);

            },

            error: function(data){
                $this.data('clicked', 0);
                $this.addClass('btn-outline-primary');
                $this.removeClass('btn-outline-secondary');
                $this.text('Add');
                console.log('An error occured: ' + data);
                $('.js-error-message').append("<div class=\"alert alert-danger alert-dismissible fade show\">\n" +
                    "  <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>\n" +
                    "  <strong>Uh oh!</strong> There was a problem processing your request. Please try again later.\n" +
                    "</div>");
            }

        });
    }
})