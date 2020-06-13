<?php
$to = "gmpmovies@gmail.com";
$subject = "My subject";
$txt = "Hello world!";
$headers = "From: support@gobinit.com";

mail($to,$subject,$txt,$headers);
?>