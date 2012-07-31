<?php

if ($_GET["room"]) $room=$_GET["room"];
if ($_POST["room"]) $room=$_POST["room"];
$filename=$_FILES['vw_file']['name'];

include_once("incsan.php");
sanV($room);
if (!$room) exit;
sanV($filename);
if (!$filename) exit;


//do not allow uploads to other folders
if ( strstr($room,"/") || strstr($room,"..") ) exit;
if ( strstr($filename,"/") || strstr($filename,"..") ) exit;

$destination="uploads/".$room."/";
if ($_GET["slides"]) $destination .= "slides/";

$ext=strtolower(substr($filename,-4));
$allowed=array(".swf",".zip",".rar",".jpg","jpeg",".png",".gif",".txt",".doc","docx",".htm","html",".pdf",".mp3",".flv",".avi",".mpg",".ppt",".pps");

if (in_array($ext,$allowed)) move_uploaded_file($_FILES['vw_file']['tmp_name'], $destination . $filename);
?>loadstatus=1
