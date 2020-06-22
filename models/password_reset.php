<?php
    require_once "/home1/gobinitc/public_html/bucketlist/config.php";
    require_once "/home1/gobinitc/public_html/bucketlist/lib/password.php";
    class PasswordReset {
        public $id;
        public $user_id;
        public $expiration;
        public $code;

        public static function isValidResetCode($account_id, $code){
            global $mysqli;
            $sql = "SELECT id, user_id, code, experation
                    FROM password_reset
                    WHERE user_id = ? AND code = ?";

            if($stmt = $mysqli->prepare($sql)){
                $stmt->bind_param("is", $param_id, $param_code);
                $param_id = $account_id;
                $param_code = $code;

                error_log($param_code);
                error_log($param_id);

                if($stmt->execute()){
                    $stmt->store_result();
                    if($stmt->num_rows == 1){
                        return true;
                    }
                } else {
                    error_log($stmt->error);
                }
            } else {
                error_log($mysqli->error);
            }
            return false;
        }

        public static function requestResetCode($id, $username){
            global $mysqli;
            $success = false;
            $url_code = password_hash($username . random_int(1000000, 100000000), PASSWORD_DEFAULT);
            $sql = "SELECT id, user_id, expiration, code 
                    FROM password_reset
                    WHERE user_id = ?";
            if($stmt = $mysqli->prepare($sql)){
                $stmt->bind_param('i', $param_user_id);
                $param_user_id = $id;

                if($stmt->execute()){
                    $stmt->store_result();
                    if($stmt->num_rows == 1){
                        $success = self::updateResetCode($url_code, $id);
                    } else {
                        $success = self::createNewUpdateCode($id, $url_code);
                    }
                }
            }

            return $success;
        }

        public static function createNewUpdateCode($id, $url_code){
            global $mysqli;
            $expiration_time = date("Y/m/d H:i:s", strtotime("+30 minutes"));
            $sql = "INSERT INTO password_reset (user_id, expiration, code) VALUES (?,?,?)";

            if($stmt = $mysqli->prepare($sql)){
                $stmt->bind_param("iss", $param_id, $param_exp, $param_code);
                $param_id = $id;
                $param_exp = $expiration_time;
                $param_code = $url_code;

                if($stmt->execute()){
                    return $url_code;
                }
            } else {
                error_log($stmt->error);
            }

            return false;
        }

        public static function updateResetCode($url_code, $user_id){
            global $mysqli;
            $expiration_time = date("Y/m/d H:i:s", strtotime("+30 minutes"));
            $sql = "UPDATE password_reset SET expiration = ?, code = ? WHERE user_id = ?";
            if($stmt = $mysqli->prepare($sql)){
                $stmt->bind_param('ssi', $param_expiration, $param_code, $param_user_id);
                $param_expiration = $expiration_time;
                $param_code = $url_code;
                $param_user_id = $user_id;

                if($stmt->execute()){
                    return $url_code;
                }
            } else {
                error_log($stmt->error);
            }
            return false;
        }

        function set_user_id($user_id) {
            $this->user_id = $user_id;
        }
        function get_user_id() {
            return $this->user_id;
        }
        function set_expiration($expiration) {
            $this->expiration = $expiration;
        }
        function get_expiration() {
            return $this->expiration;
        }
        function set_id($id) {
            $this->id = $id;
        }
        function get_id() {
            return $this->id;
        }
        function set_code($code) {
            $this->code = $code;
        }
        function get_code() {
            return $this->code;
        }

    }
?>