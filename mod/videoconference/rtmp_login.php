<?php
//rtmp server should check login like rtmp_login.php?s=$session
$session = $_GET['s'];

$filename1 = "uploads/_sessions/$session";
if (file_exists($filename1)) 
{
	echo implode('', file($filename1));
}
else 
{
	echo "VideoWhisper=1&login=0";
}
?>