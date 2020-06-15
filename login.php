<?php
require_once "/home1/gobinitc/public_html/bucketlist/actions/login.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <?php require_once "/home1/gobinitc/public_html/bucketlist/html_style_head.php"?>
    <link rel="stylesheet" href="/bucketlist/assets/styles/login.css">
</head>
<body>
<?php //require_once "/home1/gobinitc/public_html/bucketlist/navbar.php"; ?>

        <div class="login-wrapper fadeInDown">
            <div id="formContent">

                <h1 style="font-size: 25px; margin-top: 15px;"><img src="/bucketlist/assets/icons/favicon-32x32.png"> Buckets</h1>
                <!-- Tabs Titles -->
                <h2 class="active pointerHover js-sign-in"> Sign In </h2>
                <h2 class="inactive underlineHover pointerHover js-register">Sign Up </h2>


<!--                <div class="fadeIn first">-->
<!--                    <img src="http://danielzawadzki.com/codepen/01/icon.svg" id="icon" alt="User Icon" />-->
<!--                </div>-->

                <!-- Login Form -->
                <div class="js-login-form d-block">
                    <form id="login-form">
                        <input type="text" id="login" class="fadeIn second" name="username" placeholder="login">
<!--                        <span class="help-block">--><?php //echo $username_err; ?><!--</span>-->
                        <input type="password" id="password" class="fadeIn third" name="password" placeholder="password">
<!--                        <span class="help-block">--><?php //echo $password_err; ?><!--</span>-->
                        <input type="submit" class="fadeIn fourth js-submit-login" value="Log In">
                    </form>
                </div>

                <!-- Register Form -->
                <div class="js-register-form d-none">
                    <form id="register-form">
                        <input type="text" id="register-username" class="fadeIn second" name="username" placeholder="login">
<!--                        <span class="help-block">--><?php //echo $username_err; ?><!--</span>-->
                        <input type="password" id="register-password" class="fadeIn third" name="password" placeholder="password">
<!--                        <span class="help-block">--><?php //echo $password_err; ?><!--</span>-->
                        <input type="submit" class="fadeIn fourth" value="Register">
                    </form>
                </div>

                <!-- Remind Passowrd -->
                <div id="formFooter">
                    <a class="underlineHover" href="#">Forgot Password?</a>
                </div>

            </div>
        </div>




<?php require_once "/home1/gobinitc/public_html/bucketlist/html_body_scripts.php"?>
<script src="/bucketlist/assets/scripts/login.js"></script>
</body>

</html>