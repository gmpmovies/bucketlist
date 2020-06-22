<?php
require_once "/home1/gobinitc/public_html/bucketlist/config.php";
require_once "/home1/gobinitc/public_html/bucketlist/actions/send_email.php";
require_once "/home1/gobinitc/public_html/bucketlist/models/password_reset.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $to_email = "";
    $status = "";
    $firstname = "";
    $url_code = false;
    $acc_id = "";
    $valid_config = false;
    if($_POST["email"] == "" || !isset($_POST["email"])){
        $status = "Please enter an email";
    } else {
        $valid_config = true;
    }

    if($valid_config){
        $sql = "SELECT id, username, email, firstname FROM users WHERE email = ? OR username = ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("ss", $param_email, $param_username);
            $param_email = $_POST["email"];
            $param_username = $_POST["email"];
            if($stmt->execute()){
                $stmt->store_result();

                if($stmt->num_rows == 1){
                    $stmt->bind_result($id, $username, $email, $first_name);
                    while($stmt->fetch()){
                        $to_email = $email;
                        $firstname = $first_name;
                        $url_code = PasswordReset::requestResetCode($id, $username);
                        $acc_id = $id;
                    }
                } else {
                    $status = "An existing account was not found with that info.";
                    $valid_config = false;
                }
            } else {
                $status = "An error occurred fetching your account";
                $valid_config = false;
                error_log("An error occurred fetching the account for " . $param_email);
            }
        } else {
            $status = "An error occurred fetching your account";
            $valid_config = false;
        }
    }

    if($valid_config && $to_email != "" && $url_code != false){
        $headers = "From: Gobinit Support <support@gobinit.com>\r\n" . "Content-type:text/html;charset=UTF-8" . "\r\n";
        $subject = "Gobinit Password Reset";
        $from = "support@gobinit.com";
        $url = "https://www.gobinit.com/bucketlist/views/settings/password_reset?val=" . $url_code . "&acc=" . $acc_id;
        $email = new SendEmail($email, $subject, $from, $headers, $firstname, $url);
        $email->SendEmailFromTemplate();
    } else {
        $status = "Something went wrong";
    }
}
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
        <h2 class="inactive pointerHover js-sign-in"> Sign In </h2>
        <h2 class="inactive underlineHover pointerHover js-register">Sign Up </h2>

        <!-- Login Form -->
        <h3>Password Recovery</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" id="email" class="fadeIn first" name="email" placeholder="enter your username or email">
            <input type="submit" class="fadeIn second" value="Send Email">
            <span class="help-block d-block" style="color:red;"><?php echo $status; ?></span>
        </form>

    </div>
</div>




<?php require_once "/home1/gobinitc/public_html/bucketlist/html_body_scripts.php"?>
<script src="/bucketlist/assets/scripts/login.js"></script>
</body>

</html>
