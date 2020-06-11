<?php
function isMobile () {
    return is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "mobile"));
}


if(isMobile()){
    require_once "/home1/gobinitc/public_html/bucketlist/partial/navbar/navbar-mobile.php";
    require_once "/home1/gobinitc/public_html/bucketlist/partial/navbar/navbar-mobile-top.php";
} else {
    require_once "/home1/gobinitc/public_html/bucketlist/partial/navbar/navbar-desktop.php";
}

?>
