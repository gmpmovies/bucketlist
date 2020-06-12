<?php
session_start();
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);

require_once "/home1/gobinitc/public_html/bucketlist/config.php";
require_once "/home1/gobinitc/public_html/bucketlist/pageload.php";

$bucket_name = $bucket_desc = "";
$bucket_err = "";

error_reporting(E_ALL);
if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["isprivate"])){
        $isPrivate = True;
    } else {
        $isPrivate = False;
    }
    if($_POST["bucket"] == ""){
        $bucket_err = "Please enter a bucket title.";
    } else {
        $bucket_name = $_POST["bucket"];
    }

    if($bucket_err==""){
        $bucket_desc = $_POST["bucketdesc"];
        $owner_id = $_SESSION["id"];

        $sql = "INSERT INTO bucket (ownerid, title, description, isPrivate) VALUES (?,?,?,?)";

        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("issi", $param_ownerid, $param_title, $param_description, $param_isPrivate);

            $param_ownerid = $owner_id;
            $param_description = $bucket_desc;
            $param_title = $bucket_name;
            $param_isPrivate = $isPrivate;

            if($stmt->execute()){
                echo "Get ID";
                $new_id = $mysqli->insert_id;
                echo "after get ID";
//                $stmt->close();
                $sql2 = "INSERT INTO bucket_users (bucketid, userid, isAdmin) VALUES (?, ?, ?)";
                echo $new_id;
                if($stmt2 = $mysqli->prepare($sql2)){
                    $stmt2->bind_param("iii", $param_bucketid, $param_userid, $param_isAdmin);
                    $param_bucketid = $new_id;
                    $param_userid = $owner_id;
                    $param_isAdmin = True;

                    if($stmt2->execute()){
                        echo("ececute");
                    } else {
                        echo("There was an issue with the second SQL command");
                    }
                } else {
                    echo "There was an issue with the second SQL prepare statement";
                }
                header("location: /bucketlist/bucket/bucket.php?bucketid=" . $new_id);
            } else {
                echo "Something went wrong while executing the query";
            }
            $stmt2->close();
            $stmt->close();
        } else{
            echo "Something went wrong with mysqli. Please try again later";
        }


    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Bucketlist</title>
    <?php require_once "/home1/gobinitc/public_html/bucketlist/html_style_head.php"?>
    <link rel="stylesheet" href="/bucketlist/assets/styles/bucket.css"/>
</head>
<body>
<?php require_once "/home1/gobinitc/public_html/bucketlist/navbar.php"; ?>
<div class="default-margin">
    <div class="feed-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo ($bucket_err != "") ? 'has-error' : ''; ?>">
                <label>Bucket Name</label>
                <input type="text" name="bucket" class="form-control" value="<?php echo $bucket_name; ?>">
                <span class="help-block" style="color: red;"><?php echo $bucket_err; ?></span>
            </div>
            <div class="form-group">
                <label>Bucket Description</label>
                <input type="text" name="bucketdesc" class="form-control" value="<?php echo $bucket_desc; ?>">
            </div>
            <div class="form-group">
                <p class="form-label-custom">Make Private</p>
                <p class="form-label-custom" style="margin-left: 15px; color: #444; margin-bottom: 5px;">When enabled, the bucket and all generated posts will be viewable only by members of the bucket.</p>
                <label class="switch">
                    <input type="checkbox" name="isprivate" value="true">
                    <span class="slider round"></span>
                </label>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
        </form>
    </div>

</div>

<?php require_once "/home1/gobinitc/public_html/bucketlist/html_body_scripts.php"?>
</body>
</html>

