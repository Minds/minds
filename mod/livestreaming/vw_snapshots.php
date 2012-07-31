<?php
if (isset($GLOBALS["HTTP_RAW_POST_DATA"]))
{
  $stream=$_GET['name'];
  //do not allow uploads to other folders
  if ( strstr($stream,"/") || strstr($stream,"..") ) exit;

	// get bytearray
	$jpg = $GLOBALS["HTTP_RAW_POST_DATA"];

	// save file
  $fp=fopen("snapshots/$stream.jpg","w");
  if ($fp)
  {
    fwrite($fp,$jpg);
    fclose($fp);
  }
}
?>loadstatus=1