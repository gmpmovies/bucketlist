<?php
$to = "gmpmovies@gmail.com";
$subject = "Gobinit Password Reset";
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
$headers = "From: Gobinit Support <support@gobinit.com>\r\n";

mail($to,$subject,$txt,$headers);
?>