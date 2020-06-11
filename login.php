<?php
// Initialize the session
session_start();
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: bucketlist/feed.php");
    exit;
}

// Include config file
require_once "/home1/gobinitc/public_html/bucketlist/config.php";
require_once "/home1/gobinitc/public_html/lib/password.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username is empty
    if (!isset($_POST["username"])) {
        $username_err = "Please enter username.";
    } else{
        $username = strtolower(trim($_POST["username"]));
    }

    // Check if password is empty
    if(!isset($_POST["password"]))  {
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if($username_err=="" && $password_err=="") {
        // Prepare a select statement
        $sql = "SELECT id, username, password, firstname, lastname, email FROM users WHERE username = ?";
        if($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if($stmt->execute()) {
                // Store result
                $stmt->store_result();
                // Check if username exists, if yes then verify password
                if($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($id, $username, $hashed_password, $firstname, $lastname, $email);
                    if($stmt->fetch()) {
                        if(password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["firstname"] = $firstname;
                            $_SESSION["lastname"] = $lastname;
                            $_SESSION["email"] = $email;

                            // Redirect user to welcome page
                            header("location: /feed.php");
                        } else {
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else {
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
    <title>Login</title>
    <?php require_once "/var/www/html/bucketlist/html_style_head.php"?>
</head>
<body>
<?php require_once "/var/www/html/bucketlist/navbar.php"; ?>
<div class="default-margin">
    <div class="wrapper" style="margin: auto;">
        <h2 style="text-align: center;">Login</h2>
        <p style="text-align: center;">Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!isset($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!isset($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>
</div>


<?php require_once "/var/www/html/bucketlist/html_body_scripts.php"?>
</body>
<footer>
    <div style="text-align: center;">
        <span id="siteseal"><script async type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=XQAmFujOlCPX5aXIpb7Vf0eGBwWWSCobORzzjjg0r5lzKMrEtaoadLJGPrd0"></script></span>
    </div>
</footer>
</html>