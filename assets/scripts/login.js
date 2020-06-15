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