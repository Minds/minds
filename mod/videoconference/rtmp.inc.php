<?php
//this generates a session file record for rtmp login check

$webKey = "VideoWhisper";

sanV($username);

$ztime=time();
$info = "VideoWhisper=1&login=1&webKey=$webKey&start=$ztime&canKick=$canKick";

$dir="uploads/";
if (!file_exists($dir)) mkdir($dir);
@chmod($dir, 0777);
$dir.="/_sessions";
if (!file_exists($dir)) mkdir($dir);
@chmod($dir, 0777);

$dfile = fopen($dir."/$username","w");
fputs($dfile,$info);
fclose($dfile);

?>