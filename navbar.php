<?php
function isMobile () {
    return is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "mobile"));
}


if(isMobile()){
    require_once "/var/www/html/bucketlist/partial/navbar/navbar-mobile.php";
    require_once "/var/www/html/bucketlist/partial/navbar/navbar-mobile-top.php";
} else {
    require_once "/var/www/html/bucketlist/partial/navbar/navbar-desktop.php";
}

?>
