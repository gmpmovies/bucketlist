<?php
$to = "gmpmovies@gmail.com";
$subject = "Gobinit Password Reset";
$txt = "Hello world!";
$headers = "From: Gobinit Support <support@gobinit.com>\r\n";

mail($to,$subject,$txt,$headers);
?>