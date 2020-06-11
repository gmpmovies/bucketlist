<?php
session_start();
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);

require_once "/var/www/html/bucketlist/resize-image.php";
require_once "/var/www/html/bucketlist/config.php";

class UploadImage{

    private $fileName;
    private $width;
    private $originalFileName;
    private $fileSize;
    private $fileTmpName;
    private $fileType;
    private $tmp;
    private $fileExtension;
    private $converted_file_name;
    private $currentDir;
    private $uploadDirectory = "/uploads/";
    private $uploadPath;
    private $converted_upload_path;
    private $new_file_id;
    private $isProfile;
    private $fileExtensions = ['jpeg','jpg','png'];
    private $errors = [];
    public $file_id;



    public function __construct( $file, $width, $isProfile )
    {
        $this->width = $width;
        $this->isProfile = $isProfile;
        $this->fileName = $file['name'];
        $this->originalFileName = $this->fileName;
        $this->fileSize = $file['size'];
        $this->fileTmpName = $file['tmp_name'];
        $this->fileType = $file['type'];
        $this->tmp = explode('.', $this->fileName);
        $this->fileExtension = strtolower(end($this->tmp));
        $this->converted_file_name = $this->tmp[0];
        $this->currentDir = '/var/www/html/bucketlist';
        if (!in_array($this->fileExtension,$this->fileExtensions)){
            if($this->fileExtension == ""){
                $this->errors[] = "Please upload a JPEG or PNG file.";
            } else{
                $this->errors[] = "File extension " . $this->fileExtension . " not allowed. Please upload a JPEG or PNG file.";
            }
        }
        if ($this->fileSize > 10000000){
            $this->errors[] = "The file uploaded is too large. All files must be smaller than 10MB.";
        }
    }

    public function saveToServer(){
        if($this->checkErrors() == null){
            $this->validateRepeatedFilenames();
            $this->uploadPath = $this->currentDir . $this->uploadDirectory . basename($this->fileName);
            $this->converted_upload_path = $this->currentDir . $this->uploadDirectory . $this->converted_file_name . '.jpeg';
            $didUpload = move_uploaded_file($this->fileTmpName, $this->uploadPath);
            error_log("I AM HERE WITH THE DID UPLOAD");
            if($didUpload){

                if($this->fileExtension != 'jpeg'){
                    $im = new imagick($this->uploadPath);
                    $im->setImageFormat('jpeg');
                    $im->writeImage($this->converted_upload_path);
                    $im->clear();
                    $im->destroy();

                    $resize = new ResizeImage($this->converted_upload_path);
                    $resize->resizeTo($this->width, $this->width, 'maxWidth');
                    $resize->saveImage($this->converted_upload_path);

                    unlink($this->uploadPath);
                } else {
                    $resize = new ResizeImage($this->uploadPath);
                    $resize->resizeTo($this->width, $this->width, 'maxWidth');
                    $resize->saveImage($this->uploadPath);
                }


                if($this->addToDatabase()){
                    return True;
                } else {
                    return False;
                }
            } else {
                return False;
            }

            return True;
        }
    }

    private function addToDatabase() {
        global $mysqli;
        $this->errors[] = "The file " . basename($this->fileName) . " has been uploaded";
        $sql = "INSERT INTO files(filepath, ownerid, filename, original_filename, is_profile_pic) VALUES (?, ?, ?, ?, ?)";

        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("sissi", $param_uploadpath, $param_id, $param_filename, $param_original_filename, $param_is_profile_pic);
            if($this->isProfile){
                $param_is_profile_pic = 1;
            } else {
                $param_is_profile_pic = 0;
            }
            $param_id = $_SESSION["id"];
            $param_original_filename = $this->originalFileName;
            $param_uploadpath = $this->converted_upload_path;
            $param_filename = $this->converted_file_name . '.jpeg';
            if($stmt->execute()){
                $new_id = $mysqli->insert_id;
                $this->file_id = $new_id;
                $this->new_file_id = $mysqli->insert_id;
                if($this->isProfile){
                    $this->makeProfilePhoto();
                }
                return True;
            } else{
                throw new Exception($stmt->error);
                return False;
            }
            $stmt->close();
        } else {
            throw new Exception($mysqli->error);
        }

    }

    public function getNewFileID(){
        return $this->file_id;
    }

    private function makeProfilePhoto(){
        $sql = "UPDATE users
                SET profile_pic = ?
                WHERE id = ?";
        global $mysqli;
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("ii", $param_fileid, $param_userid);

            $param_fileid=$this->new_file_id;
            $param_userid=$_SESSION["id"];
            if($stmt->execute()){
                $stmt->store_result();
            } else {
                echo "There was an error assigning profile photo.";
            }
        }

    }

    private function validateRepeatedFilenames() {
        $stringLen = strlen($this->fileName);
        $sql = "SELECT filepath, filename FROM files WHERE original_filename = ?";
        global $mysqli;
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("s", $param_filename);

            $param_filename = $this->fileName;
            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows>=1){
                    $count = $stmt->num_rows;
                    $this->fileName = $this->tmp[0] . "_" . $count . "." . $this->fileExtension;
                    $this->converted_file_name = $this->converted_file_name . "_" . $count;
                    $this->converted_upload_path = $this->currentDir . $this->uploadDirectory . $this->converted_file_name . "_" . $count . '.jpeg';
                }

            } else {
                throw new Exception($stmt->error);
            }
            $stmt->close();
        } else {
            throw new Exception($mysqli->error);
        }

    }

    public function checkErrors(){
        if (count($this->errors) >= 1){
            return $this->errors;
        } else {
            return null;
        }
    }

}
?>
