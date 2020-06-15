$(".js-sign-in").on('click', function(){
    $(this).addClass('active').removeClass('inactive');
    $('.js-register').addClass('inactive').removeClass('active');
    $('.js-login-form').addClass('d-block').removeClass('d-none');
    $('.js-register-form').addClass('d-none').removeClass('d-block');
});

$(".js-register").on('click', function(){
    $(this).addClass('active').removeClass('inactive');
    $('.js-sign-in').addClass('inactive').removeClass('active');
    $('.js-register-form').addClass('d-block').removeClass('d-none');
    $('.js-login-form').addClass('d-none').removeClass('d-block');
});

$('#login-form').on('submit', function(e){
    e.preventDefault();

    var form = $(this);
    var url = '/bucketlist/actions/login.php'

    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        success: function(data)
        {
            alert(data);
            console.log(data);
        }
    })
})