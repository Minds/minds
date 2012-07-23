<div class="contentWrapper">

<?php

$irc_nick = "".$_SESSION['user']->username."";

//Replace the nick spaces with _
$irc_nick = ereg_replace(' ', '_', $irc_nick);

$root = elgg_get_site_url() . 'mod/publisher/';

//Create the HTML calling the wigget
echo "<div align=\" center\">";
echo "  	<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\""
  . "		id=\"flashirc\" width=\"1000\" height=\"600\" allowFullScreen=\"true\" allowScriptAccess=\"always\" allowNetworking=\"all\""
  . "		codebase=\"http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab\">"
  . "		<param name=\"movie\" value=\"{$root}publisher_fullscreen/publisher.swf?nick=$irc_nick\" />"
  . "		<param name=\"quality\" value=\"high\" />"
  . "		<param name=\"bgcolor\" value=\"#ffffff\" />"
  . " 		<param name=\"allowfullscreen\" value=\"true\"/>"
  . "		<embed src=\"{$root}publisher_fullscreen/publisher.swf?nick=$irc_nick\" quality=\"high\" "
  . "gcolor=\"#ffffff\""
  . "			width=\"1000\" height=\"600\" name=\"irc\" align=\"center\""
  . "			play=\"true\""
  . "			loop=\"false\""
  . "			quality=\"high\""
  . "			type=\"application/x-shockwave-flash\""
  . "			pluginspage=\"http://www.adobe.com/go/getflashplayer\" allowfullscreen=\"true\">"
  . "		</embed>"
  . "</object>";
echo "</div>";

?>

</div>
