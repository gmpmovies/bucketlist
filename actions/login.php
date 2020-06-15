<?php
// Initialize the session
session_start();
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: /bucketlist/feed.php");
    exit;
}

// Include config file
require_once "/home1/gobinitc/public_html/bucketlist/config.php";
require_once "/home1/gobinitc/public_html/bucketlist/lib/password.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

if(isset($_COOKIE['username'])){
    $setSession = new setSessionVars($_COOKIE['id'], $_COOKIE['username'], $_COOKIE['firstname'], $_COOKIE['lastname'], $_COOKIE['email']);
    $setSession->setSessionVariables();
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username is empty
    if (!isset($_POST["username"]) || $_POST["password"] == "") {
        $username_err = "Please enter your username.";
    } else {
        $username = strtolower(trim($_POST["username"]));
    }

    // Check if password is empty
    if (!isset($_POST["password"]) || $_POST["password"] == "") {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if ($username_err == "" && $password_err == "") {
        // Prepare a select statement
        $sql = "SELECT id, username, password, firstname, lastname, email FROM users WHERE username = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();
                // Check if username exists, if yes then verify password
                if ($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($id, $username, $hashed_password, $firstname, $lastname, $email);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            $setSession = new setSessionVars($id, $username, $firstname, $lastname, $email);
                            $setSession->setSessionVariables();

                        } else {
                            // Display an error message if password is not valid
                            $password_err = "The username or password did not match our records.";
                        }
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $username_err = "The username or password did not match our records.";
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

class setSessionVars{
    private $id;
    private $username;
    private $firstname;
    private $lastname;
    private $email;

    public function __construct($id, $username, $firstname, $lastname, $email){
        $this->id = $id;
        $this->username =$username;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
    }

    public function setSessionVariables(){
        session_start();
        // Store data in session variables
        $_SESSION["loggedin"] = true;
        $_SESSION["id"] = $this->id;
        $_SESSION["username"] = $this->username;
        $_SESSION["firstname"] = $this->firstname;
        $_SESSION["lastname"] = $this->lastname;
        $_SESSION["email"] = $this->email;

        // Set cookies
        $hour=time()+3600*24*36000;
        setcookie('id', $this->id, $hour);
        setcookie('username', $this->username, $hour);
        setcookie('firstname', $this->firstname, $hour);
        setcookie('lastname', $this->lastname, $hour);
        setcookie('email', $this->email, $hour);
        setcookie('active', 1, $hour);

        // Redirect user to welcome page
        header("location: /bucketlist/feed.php");
    }
}
?>