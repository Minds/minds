<?php

if (isset($GLOBALS["HTTP_RAW_POST_DATA"]))
{
  $room=$_GET['room'];
  $stream=$_GET['name'];
  
  include_once("incsan.php");
  sanV($stream);
  sanV($room);
  if (!$stream) exit;
  if (!$room) exit;

  //create folder to store logs
  $dir="uploads";
  if (!file_exists($dir)) mkdir($dir);
  @chmod($dir, 0777);
  $dir.="/$room";
  if (!file_exists($dir)) mkdir($dir);
  @chmod($dir, 0777);

  // get bytearray
  $jpg = $GLOBALS["HTTP_RAW_POST_DATA"];

  // save file
  $filename=$stream.".".time().".jpg";
  $picture="uploads/$room/".$filename;
  $fp=fopen($picture,"w");
  if ($fp)
  {
    fwrite($fp,$jpg);
    fclose($fp);
  }
  
    //add it to chat log
    $message="<IMG SRC=\"$filename\" ALT=\"$stream\" TITLE=\"$stream\" ALIGN=\"RIGHT\">";
	//get daily log name
	$day=date("y-M-j",time());
	
	$chat="uploads/$room/Log$day.html";
	$dfile = fopen($chat,"a");
	fputs($dfile,$message."<BR>");
	fclose($dfile);
	
	$chat=urlencode($chat);
	$picture=urlencode($picture);
}
?>chat=<?=$chat?>&picture=<?=$pic?>&loadstatus=1