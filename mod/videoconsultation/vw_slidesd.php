<?php
$room=$_POST['room'];

include_once("incsan.php");
sanV($room);
if (!$room) exit;

$label=$_POST['label'];
$index=$_POST['ix'];

$filename="uploads/$room/slides/slideshow.xml";
if (file_exists($filename)) $txt = implode(file($filename)); else exit;

$txt=preg_replace("/<SLIDE index=\"$index\" label=\"$label\" [^>]+ \/>\r/","",$txt);

//some cleanup
$txt=str_replace("  "," ",$txt);
$txt=str_replace("\r \r","\r",$txt);
$txt=str_replace("\r\r","\r",$txt);

  //assign good order numbers
  preg_match_all("|<SLIDE (.*) />|U",  $txt, $out, PREG_SET_ORDER);
  $k=1;
  for ($i=0;$i<count($out);$i++)
    {
    $repl=preg_replace('/index="(\d+)"/','index="'.sprintf("%02d",$k++).'"',$out[$i][0]);
    $txt=str_replace($out[$i][0],$repl,$txt);
    }

  // save file
  $fp=fopen($filename,"w");
  if ($fp)
  {
    fwrite($fp, $txt);
    fclose($fp);
  }
?>loadstatus=1