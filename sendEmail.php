<?php
$to = "gmpmovies@gmail.com";
$subject = "This is a Test";
$txt = "<html>
<head>
<title>HTML email</title>
</head>
<body>
<p>This email contains HTML Tags!</p>
<table>
<tr>
<th>Firstname</th>
<th>Lastname</th>
</tr>
<tr>
<td>John</td>
<td>Doe</td>
</tr>
</table>
</body>
</html>";
$headers = "From: Gobinit Support <support@gobinit.com>\r\n" . "Content-type:text/html;charset=UTF-8" . "\r\n";;

mail($to,$subject,$txt,$headers);
?>