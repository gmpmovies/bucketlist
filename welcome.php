<?php
// Initialize the session
session_start();
require "file-upload.php";

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
    <body>
        <div class="page-header">
            <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["firstname"]); ?></b>. Welcome to our site.</h1>
        </div>
        <p>
            <a href="view-photos.php" class="btn btn-success">View Photos</a>
            <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
            <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
        </p>
        <p>Name: <?php echo htmlspecialchars($_SESSION["firstname"]) . " " . htmlspecialchars($_SESSION["lastname"]); ?></p>
        <p>Email: <?php echo htmlspecialchars($_SESSION["email"]) ?></p>
        <p>Username: <?php echo htmlspecialchars($_SESSION["username"])?></p>
        <p>Id: <?php echo htmlspecialchars($_SESSION["id"])?></p>
        <form action="" method="post" enctype="multipart/form-data">
            Upload a File:
            <div style="padding-left: 30px; padding-right: 30px;">
                <div class="row">
                    <div class="col-md-6">
                        <input type="file" name="myfile" id="fileToUpload" style="margin: auto;">
                    </div>
                    <div class="col-md-6">
                        <input type="submit" name="submit" value="Upload File Now" >
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>