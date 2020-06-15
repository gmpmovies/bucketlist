<?php
// Include config file
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once "/home1/gobinitc/public_html/bucketlist/config.php";
require_once "/home1/gobinitc/public_html/bucketlist/lib/password.php";

$timezone = date_default_timezone_get();
date_default_timezone_set($timezone);

// Define variables and initialize with empty values
$username = $password = $confirm_password = $firstname = $lastname = $email = "";
$username_err = $password_err = $confirm_password_err = $firstname_err = $lastname_err = $email_err = "";
$err = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate username
    if(!isset($_POST["username"])){
        $err = "Please enter a username.";
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
                    $err = "This username is already taken.";
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
        $err = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(!isset($_POST["confirm_password"])){
        $err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(isset($err) && ($password != $confirm_password)){
            $err = "Password did not match.";
        }
    }

    if(!isset($_POST["firstname"])){
        $err = "Please enter your first name.";
    } else {
        $firstname = trim($_POST["firstname"]);
    }

    if(!isset($_POST["lastname"])){
        $err = "Please enter your last name.";
    } else {
        $lastname = trim($_POST["lastname"]);
    }

    if(!isset($_POST["email"])){
        $err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }
    // Check input errors before inserting in database
    if($err == ""){
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
                header("location: /bucketlist/login.php");
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