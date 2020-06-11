<?php
    $referer = $_SERVER['HTTP_REFERER'];
    $display_back_nav = True;
    if($current_page == '/feed.php' || $current_page =='/login.php' || $current_page =='/register.php'){
        $display_back_nav=False;
    }
?>
<link rel="stylesheet" href="/assets/styles/navbar-mobile.css">
<div class="navbar-mobile-container-top">
    <?php
    if($display_back_nav == True){
        echo'
        <a href="' . $referer . '">
            <img class="navbar-back-arrow" src="/assets/icons/back.svg"/>
        </a>
        ';
    }
    ?>
    <div class="navbar-img-container">
        <p><img src="/favicon-32x32.png"> BLit</p>
    </div>

</div>