<?php
    require_once "/home1/gobinitc/public_html/bucketlist/config.php";

    class UpdateCounts{
        private $type;

        function __construct($type){
            $this->type=$type;
        }

        public function updateFollowers($id){
            global $mysqli;
            $sql = $this->getFollowerType();
            if($stmt = $mysqli->prepare($sql)){
                $stmt->bind_param('ii', $param_user, $param_id);
                $param_id = $id;
                $param_user = $id;

                if($stmt->execute()){
                    return True;
                } else {
                    throw new Exception($stmt->error);
                    error_log($stmt->error);
                    return False;
                }
                $stmt->close();
            } else {
                throw new Exception($mysqli->error);
                error_log($mysqli->error);
                return False;
            }
        }

        private function getFollowerType(){
            if($this->type=="Following"){
                $sql = "
                UPDATE users
                SET number_following = (
                    SELECT COUNT(id)
                    FROM followers
                    WHERE user = ? AND unfollowed = FALSE
                )
                WHERE id = ? 
            ";
            } else {
                $sql = "
                UPDATE users
                SET number_followers = (
                    SELECT COUNT(id)
                    FROM followers
                    WHERE following = ? AND unfollowed = FALSE
                )
                WHERE id = ? 
            ";
            }
            return $sql;
        }
    }
?>