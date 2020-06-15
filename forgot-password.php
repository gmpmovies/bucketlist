<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <?php require_once "/home1/gobinitc/public_html/bucketlist/html_style_head.php"?>
    <link rel="stylesheet" href="/bucketlist/assets/styles/login.css">
</head>
<body>

<div class="login-wrapper fadeInDown">
    <div id="formContent">

        <h1 style="font-size: 25px; margin-top: 15px;"><img src="/bucketlist/assets/icons/favicon-32x32.png"> Buckets</h1>
        <!-- Tabs Titles -->
        <h2 class="active pointerHover js-sign-in"> Sign In </h2>
        <h2 class="inactive underlineHover pointerHover js-register">Sign Up </h2>

        <!-- Login Form -->
        <h3>Password Recovery</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" id="email" class="fadeIn first" name="email" placeholder="enter your username or email">
            <input type="submit" class="fadeIn second" value="Send Email">
            <span class="help-block d-block" style="color:red;"><?php #echo $status; ?></span>
        </form>

    </div>
</div>




<?php require_once "/home1/gobinitc/public_html/bucketlist/html_body_scripts.php"?>
<script src="/bucketlist/assets/scripts/login.js"></script>
</body>

</html>
