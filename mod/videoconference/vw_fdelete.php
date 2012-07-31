<?php

$room = $_GET["room"];
$filename = $_GET["filename"];

include_once("incsan.php");
sanV($room);
if (!$room) exit;
sanV($filename);
if (!$filename) exit;

chmod("uploads/$room/$filename", 0766);
unlink("uploads/$room/$filename");
?>loadstatus=1
