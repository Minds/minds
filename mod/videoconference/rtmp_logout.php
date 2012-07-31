<?php
//rtmp server notifies client disconnect here
$session = $_GET['s'];

echo "logout=";
$filename1 = "uploads/_sessions/$session";
if (file_exists($filename1)) 
{
	echo unlink($filename1);
}
?>