<?php
session_start();
require_once "config.php";
require_once "resize-image.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '10M');
ini_set('max_input_time', 300);
ini_set('max_execution_time', 300);

$currentDir = getcwd();
$uploadDirectory = "/uploads/";

$errors = []; // Store all foreseen and unforseen errors here

$fileExtensions = ['jpeg','jpg','png', 'heic']; // Get all the file extensions


if (isset($_POST['submit'])) {

    $fileName = $_FILES['myfile']['name'];
    $originalFileName = $fileName;
    $fileSize = $_FILES['myfile']['size'];
    $fileTmpName  = $_FILES['myfile']['tmp_name'];
    $fileType = $_FILES['myfile']['type'];
    $tmp = explode('.', $fileName);
    $fileExtension = strtolower(end($tmp));
    $converted_file_name = $tmp[0];
    echo($fileExtension);


    if (! in_array($fileExtension,$fileExtensions)) {
        $errors[] = "This file extension is not allowed. Please upload a JPEG or PNG file";
    }

    if ($fileSize > 5000000) {
        $errors[] = "This file is more than 5MB. Sorry, it has to be less than or equal to 5MB";
    }



    if (isset($errors)) {
        $stringLen = strlen($fileName);
        $sql = "SELECT filepath, filename FROM files WHERE original_filename = ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("s", $param_filename);

            $param_filename = $fileName;
            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows>=1){
                    $count = $stmt->num_rows;
                    $fileName = $tmp[0] . "_" . $count . "." . $fileExtension;
                    $converted_file_name = $converted_file_name . "_" . $count;
                }

            }
        }

        $uploadPath = $currentDir . $uploadDirectory . basename($fileName);
        $converted_upload_path = $currentDir . $uploadDirectory . $converted_file_name . '.jpeg';
        $didUpload = move_uploaded_file($fileTmpName, $uploadPath);


        $im = new imagick($uploadPath);
        $im->setImageFormat('jpeg');
        $im->writeImage($converted_upload_path);
        $im->clear();
        $im->destroy();

//        convertImage($converted_upload_path, $converted_upload_path, 10);
        $resize = new ResizeImage($converted_upload_path);
        $resize->resizeTo(100, 100, 'maxWidth');
        $resize->saveImage($converted_upload_path);

        unlink($uploadPath);

        if ($didUpload) {
            echo "The file " . basename($fileName) . " has been uploaded";
            $sql = "INSERT INTO files(filepath, ownerid, filename, original_filename) VALUES (?, ?, ?, ?)";
            if($stmt = $mysqli->prepare($sql)){
                $stmt->bind_param("siss", $param_uploadpath, $param_id, $param_filename, $param_original_filename);
                $param_id = $_SESSION["id"];
                $param_original_filename = $originalFileName;
                $param_uploadpath = $converted_upload_path;
                $param_filename = $converted_file_name . '.jpeg';
                echo"<br>" . $param_uploadpath . " " . $param_id . "<br>";
                echo "    I'm here in the sql    ";
                if($stmt->execute()){

                    header("Refresh: 0");
                } else{
                    echo "Something went wrong with the SQL. Please try again later.";
                }

                $stmt->close();
            }
        } else {
            echo "An error occurred somewhere. Try again or contact the admin";
        }
    } else {
        foreach ($errors as $error) {
            echo $error . "These are the errors" . "\n";
        }
    }
}


?>