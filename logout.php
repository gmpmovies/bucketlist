<?php
// Initialize the session
session_start();

// Unset all of the session variables
$_SESSION = array();

//Destroy the cookies
$hour=time()-3600*24*36000;
setcookie('userid', $this->id, $hour);
setcookie('username', $this->username, $hour);
setcookie('firstname', $this->firstname, $hour);
setcookie('lastname', $this->lastname, $hour);
setcookie('email', $this->email, $hour);
setcookie('active', 1, $hour);

// Destroy the session.
session_destroy();

// Redirect to login page
header("location: /bucketlist/login.php");
exit;
?>