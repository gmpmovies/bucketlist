<?php
require_once "/home1/gobinitc/public_html/bucketlist/actions/register.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Sign Up</title>
    <?php require_once "/home1/gobinitc/public_html/bucketlist/html_style_head.php"?>
    <link rel="stylesheet" href="/bucketlist/assets/styles/login.css">
</head>

<body>
    <div class="login-wrapper fadeInDown">
        <div id="formContent">

            <h1 style="font-size: 25px; margin-top: 15px;"><img src="/bucketlist/assets/icons/favicon-32x32.png"> Buckets</h1>
            <!-- Tabs Titles -->
            <h2 class="inactive pointerHover js-sign-in"> Sign In </h2>
            <h2 class="active underlineHover pointerHover js-register">Sign Up </h2>


            <!--                <div class="fadeIn first">-->
            <!--                    <img src="http://danielzawadzki.com/codepen/01/icon.svg" id="icon" alt="User Icon" />-->
            <!--                </div>-->

            <!-- Login Form -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="text" id="login" class="fadeIn second" name="username" placeholder="login">
                <input type="text" id="firstname" class="fadeIn third" name="firstname" placeholder="first name">
                <input type="text" id="lastname" class="fadeIn fourth" name="lastname" placeholder="last name">
                <input type="text" id="email" class="fadeIn fifth" name="email" placeholder="email">
                <input type="password" id="password" class="fadeIn sixth" name="password" placeholder="password">
                <input type="password" id="confirm_password" class="fadeIn seventh" name="confirm_password" placeholder="confirm password">
                <input type="submit" class="fadeIn eighth" value="Sign Up">
                <span class="help-block d-block" style="color:red;"><?php echo $err; ?></span>
            </form>

            <!-- Remind Passowrd -->
            <div id="formFooter">
                <a class="underlineHover" href="/forgot-password.php">Forgot Password?</a>
            </div>

        </div>
    </div>

    <?php require_once "/home1/gobinitc/public_html/bucketlist/html_body_scripts.php"?>
    <script src="/bucketlist/assets/scripts/login.js"></script>
</body>
</html>