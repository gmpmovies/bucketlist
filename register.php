<?php
// Include config file
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once "/var/www/html/bucketlist/config.php";
require_once "/var/www/html/bucketlist/lib/password.php";

$timezone = date_default_timezone_get();
date_default_timezone_set($timezone);
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = $firstname = $lastname = $email = "";
$username_err = $password_err = $confirm_password_err = $firstname_err = $lastname_err = $email_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate username
    if(!isset($_POST["username"])){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);
            
            // Set parameters
            $param_username = strtolower(trim($_POST["username"]));
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = strtolower(trim($_POST["username"]));
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        } else {
            echo "Oops! Something went wrong in the SQL prepare";
        }
    }
    
    // Validate password
    if(!isset($_POST["password"])){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(!isset($_POST["confirm_password"])){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(isset($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    if(!isset($_POST["firstname"])){
        $firstname_err = "Please enter your first name.";
    } else {
        $firstname = trim($_POST["firstname"]);
    }

    if(!isset($_POST["lastname"])){
        $lastname_err = "Please enter your last name.";
    } else {
        $lastname = trim($_POST["lastname"]);
    }

    if(!isset($_POST["email"])){
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }
    // Check input errors before inserting in database
    if($username_err=="" && $password_err=="" && $confirm_password_err=="" && $firstname_err=="" && $lastname_err=="" && $email_err==""){
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, firstname, lastname, email, created_at) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters

            $stmt->bind_param("ssssss", $param_username, $param_password, $param_firstname, $param_lastname, $param_email, $param_created_at);
            
            // Set parameters
            $param_username = strtolower($username);
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_firstname = $firstname;
            $param_lastname = $lastname;
            $param_email = $email;
            $param_created_at = date("Y-m-d h:i:sa");
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: /login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        } else {
            echo "Something went wrong " . $mysqli->error;
        }
    }
    
    // Close connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Sign Up</title>
    <?php require_once "/var/www/html/bucketlist/html_style_head.php"?>
</head>

<body>
<?php require_once "/var/www/html/bucketlist/navbar.php"; ?>
    <div class="default-margin">
        <div class="wrapper" style="margin:auto;">
            <h2 style="text-align: center;">Sign Up</h2>
            <p style="text-align: center;">Please fill this form to create an account.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!isset($username_err)) ? 'has-error' : ''; ?>">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>
                <div class="form-group <?php echo (!isset($firstname_err)) ? 'has-error' : ''; ?>">
                    <label>First Name</label>
                    <input type="text" name="firstname" class="form-control" value="<?php echo $firstname; ?>">
                    <span class="help-block"><?php echo $firstname_err; ?></span>
                </div>
                <div class="form-group <?php echo (!isset($lastname_err)) ? 'has-error' : ''; ?>">
                    <label>Last Name</label>
                    <input type="text" name="lastname" class="form-control" value="<?php echo $lastname; ?>">
                    <span class="help-block"><?php echo $lastname_err; ?></span>
                </div>
                <div class="form-group <?php echo (!isset($email_err)) ? 'has-error' : ''; ?>">
                    <label>Email</label>
                    <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                    <span class="help-block"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group <?php echo (!isset($password_err)) ? 'has-error' : ''; ?>">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group <?php echo (!isset($confirm_password_err)) ? 'has-error' : ''; ?>">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control"
                           value="<?php echo $confirm_password; ?>">
                    <span class="help-block"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <input type="reset" class="btn btn-default" value="Reset">
                </div>
                <p>Already have an account? <a href="/login.php">Login here</a>.</p>
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