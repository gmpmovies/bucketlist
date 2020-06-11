<?php
$current_server_ip = '162.241.253.195';

if(substr(php_uname(),0,12) == "Linux bucketNOPE") {
    define('DB_SERVER', 'localhost');
}
else {
    define('DB_SERVER', $current_server_ip);
}
define('DB_USERNAME', 'gobinitc_admin');
define('DB_PASSWORD', 'Mydbisnumber1!');
define('DB_NAME', 'tremac');

/* Attempt to connect to MySQL database */
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
// Check connection
if($mysqli === false){
    echo 'did not connect please contact the system admin.';
    echo'<br>';
    die("ERROR: Could not connect. " . $mysqli->connect_error);
} 

?>