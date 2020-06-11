<?php
    session_start();
    require_once "config.php";
    error_reporting(E_ALL);
    $photos= array();
    $sql = "SELECT filepath, ownerid, filename FROM files WHERE ownerid = ?";
    if ($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("i", $param_ownerid);

        $param_ownerid = $_SESSION["id"];
        if($stmt->execute()){
            $stmt->store_result();
            if($stmt->num_rows >= 1){
                $stmt->bind_result($filepath, $ownerid, $filename);
                while($stmt->fetch()){
                    array_push($photos, $filename);
                }
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Photos</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 100%; padding: 20px; }
        .custom-container{border: solid black 1px; border-width: 5px; width: 100%; height: auto;}
        img{height: 100%; width: 100%;}
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Photos</h2>

    <div class="row">
        <?php
        foreach($photos as $photo){
            echo("
            <div class=\"col-md-4\">
                <div class=\"custom-container\">
                    <img src=\"uploads/" . $photo . "\" />
                </div>
            </div>
            ");
        }
        ?>
    </div>

</div>
</body>
</html>
