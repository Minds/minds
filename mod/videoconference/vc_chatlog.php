<?php
//Public and private chat logs
$private=$_POST['private']; //private chat username, blank if public chat
$username=$_POST['u'];
$session=$_POST['s'];
$room=$_POST['r'];
$message=$_POST['msg'];
$time=$_POST['msgtime'];

//do not allow uploads to other folders
include_once("incsan.php");
sanV($room);
sanV($private);
sanV($session);
if (!$room) exit;

//generate same private room folder for both users
if ($private) 
{
	if ($private>$session) $proom=$session ."_". $private; else $proom=$private ."_". $session;
}

$dir="uploads";
if (!file_exists($dir)) mkdir($dir);
@chmod($dir, 0777);
$dir.="/$room";
if (!file_exists($dir)) mkdir($dir);
@chmod($dir, 0777);
if ($proom) $dir.="/$proom";
if (!file_exists($dir)) mkdir($dir);
@chmod($dir, 0777);

$day=date("y-M-j",time());

$dfile = fopen($dir."/Log$day.html","a");
fputs($dfile,$message."<BR>");
fclose($dfile);
?>loadstatus=1