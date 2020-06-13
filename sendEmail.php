<?php
$to = "gmpmovies@gmail.com";
$subject = "My subject";
$txt = "Hello world!";
$headers = "From: webmaster@example.com";

mail($to,$subject,$txt,$headers);
?>