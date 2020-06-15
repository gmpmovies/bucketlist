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
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="text" id="login" class="fadeIn second" name="username" placeholder="login">
                    <input type="password" id="password" class="fadeIn third" name="password" placeholder="password">
                    <input type="submit" class="fadeIn fourth" value="Log In">
                    <span class="help-block d-block" style="color:red;"><?php echo $username_err; ?></span>
                    <span class="help-block d-block" style="color:red;"><?php echo $password_err; ?></span>
                </form>

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