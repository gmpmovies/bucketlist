<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: /bucketlist/login.php");
    exit;
}

// Include config file
require_once "/home1/gobinitc/public_html/bucketlist/config.php";

// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate new password
    if(!isset($_POST["new_password"])){
        $new_password_err = "Please enter the new password.";
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Password must have atleast 6 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }

    // Validate confirm password
    if(!isset($_POST["confirm_password"])){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(isset($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before updating the database
    if($new_password_err == "" && $confirm_password_err == ""){
        // Prepare an update statement
        $sql = "UPDATE users SET password = ? WHERE id = ?";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("si", $param_password, $param_id);

            // Set parameters
            error_log($new_password);
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Password updated successfully. Destroy the session, and redirect to login page

                //Destroy cookies
                $hour=time()-3600*24*36000;
                setcookie('userid', "", $hour);
                setcookie('username', "", $hour);
                setcookie('firstname', "", $hour);
                setcookie('lastname', "", $hour);
                setcookie('email', "", $hour);
                setcookie('active', 1, $hour);

                session_destroy();
                header("location: /bucketlist/login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password</title>
    <?php require_once "/home1/gobinitc/public_html/bucketlist/html_style_head.php"?>
</head>
<body>
<?php require_once "/home1/gobinitc/public_html/bucketlist/navbar.php"; ?>
<div class="default-margin">
    <div class="wrapper" style="margin:auto;">
        <h2 style="text-align: center;">Reset Password</h2>
        <p style="text-align: center;">Please fill out this form to reset your password.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!isset($new_password_err)) ? 'has-error' : ''; ?>">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
                <span class="help-block"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!isset($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link" href="/feed.php">Cancel</a>
            </div>
        </form>
    </div>
</div>


<?php require_once "/home1/gobinitc/public_html/bucketlist/html_body_scripts.php"?>
</body>
</html>