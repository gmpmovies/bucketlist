<?php
    require_once "/home1/gobinitc/public_html/bucketlist/models/password_reset.php";

    $account_id = $_GET["acc"];
    $verification_key = $_GET["val"];

    $is_valid = PasswordReset::isValidResetCode($account_id, $verification_key);

    echo "is valid " . $is_valid;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Password Reset</title>
    <?php require_once "/home1/gobinitc/public_html/bucketlist/html_style_head.php"?>
    <link rel="stylesheet" href="/bucketlist/assets/styles/login.css">
</head>
<body>

<div class="login-wrapper fadeInDown">
    <div id="formContent">

        <h1 style="font-size: 25px; margin-top: 15px;"><img src="/bucketlist/assets/icons/favicon-32x32.png"> Buckets</h1>
        <!-- Tabs Titles -->

        <!-- Login Form -->
        <h3>Password Reset</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <?php
                if($is_valid){
                    echo "
                    <input type=\"text\" id=\"password1\" class=\"fadeIn first\" name=\"password1\" placeholder=\"enter a new password\">
                    <input type=\"text\" id=\"password2\" class=\"fadeIn second\" name=\"password2\" placeholder=\"enter a new password\">
                    <input type=\"submit\" class=\"fadeIn third\" value=\"Reset\">
                    <span class=\"help-block d-block\" style=\"color:red;\"></span>
                    ";
                } else {
                    echo "
                        <h1>Ooops, looks like the link you entered has expired or doesn't exist</h1>
                    ";
                }

            ?>

        </form>

    </div>
</div>




<?php require_once "/home1/gobinitc/public_html/bucketlist/html_body_scripts.php"?>
<script src="/bucketlist/assets/scripts/login.js"></script>
</body>

</html>
