<div class="contentWrapper">

<?php

$irc_nick = "".$_SESSION['user']->username."";

//Replace the nick spaces with _
$irc_nick = ereg_replace(' ', '_', $irc_nick);

//Create the HTML calling the wigget
echo "<div align=\" center\">";
echo "  	<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\""
  . "		id=\"flashirc\" width=\"800\" height=\"600\""
  . "		codebase=\"http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab\">"
  . "		<param name=\"movie\" value=\"tiramisu.swf?nick=$irc_nick\" />"
  . "		<param name=\"quality\" value=\"high\" />"
  . "		<param name=\"bgcolor\" value=\"#ffffff\" />"
  . "		<embed src=\"tiramisu.swf?nick=$irc_nick\" quality=\"high\" "
  . "gcolor=\"#ffffff\""
  . "			width=\"800\" height=\"600\" name=\"irc\" align=\"center\""
  . "			play=\"true\""
  . "			loop=\"false\""
  . "			quality=\"high\""
  . "			type=\"application/x-shockwave-flash\""
  . "			pluginspage=\"http://www.adobe.com/go/getflashplayer\">"
  . "		</embed>"
  . "</object>";
echo "</div>";

?>

</div>
