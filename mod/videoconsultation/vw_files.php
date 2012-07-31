<?php
if ($_GET["room"]) $room=$_GET["room"];
if ($_POST["room"]) $room=$_POST["room"];

include_once("incsan.php");
sanV($room);
if (!$room) exit;
?>
<files>
<?php

$dir="uploads";
if (!file_exists($dir)) @mkdir($dir);
@chmod($dir, 0755);
$dir.="/$room";
if (!file_exists($dir)) @mkdir($dir);
@chmod($dir, 0755);

$handle=opendir($dir);
while
(($file = readdir($handle))!==false)
{
if (($file != ".") && ($file != "..") && (!is_dir("$dir/".$file))) echo "<file file_name=\"".$file."\" file_size=\"".filesize("$dir/".$file)."\" />";
}
closedir($handle);
?>
</files>