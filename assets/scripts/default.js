$('body').on('click', '.js_follow_user', function(){
    console.log('Clicked');
    const $this = $(this);
    var $updatedButton;
    console.log($this.data('clicked'));
    if($this.data('clicked') == 'False'){
        const userid = $this.data('user-id');
        const type = $this.data('type');
        console.log("Following User " + userid);
        $.ajax({
            type: "POST",
            url: "/actions/follow-user.php",
            data: ({userid: userid}),

            beforeSend(jqXHR, settings) {
                $this.data('clicked', 'True');
                if(type==='Unfollow'){
                    $this.text('Follow');
                    $this.removeClass('btn-secondary');
                    $this.addClass('btn-primary');
                    $this.data('type', 'Follow');
                    // $this.append("<button type=\"button\" class=\"btn btn-primary js_follow_user\" id='follow-button-"+userid+"' data-type=\"Follow\" data-clicked=\"True\" data-user-id=\"" + userid + "\">Follow</button>")
                }
                if(type==='Follow'){
                    $this.text('Following');
                    $this.addClass('btn-secondary');
                    $this.removeClass('btn-primary');
                    $this.data('type', 'Unfollow');
                    // $this.append('<button type=\"button\" id="follow-button-'+userid+'\" data-clicked=\"True\" data-user-id=\"' + userid + '\" data-type=\"Unfollow\" class=\"btn btn-secondary js_follow_user\">Following</button>')
                }
            },

            success: function(data){
                console.log('Added');
                console.log(data);
                $this.data('clicked', 'False');
            },

            error: function(){
                console.log('An error occured while adding the userid');
                $('.js-error-message').append("<div class=\"alert alert-danger alert-dismissible fade show\">\n" +
                    "  <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>\n" +
                    "  <strong>Uh oh!</strong> There was a problem processing your request. Please try again later.\n" +
                    "</div>");
            }

        })
    }

});
