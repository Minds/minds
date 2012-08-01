<div class="contentWrapper">

<?php
 	
$owner = elgg_get_page_owner_entity();
  	
?>

<?php
/* @todo 
 * 1) Get a publisher
 * 2) Pass variables, such as stream name, to the publisher
 */
echo "<div align=\" center\">";
echo "  	<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\""
  . "		id=\"flashirc\" width=\"980\" height=\"600\""
  . "		codebase=\"http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab\">"
  . "		<param name=\"movie\" value=\"" . elgg_get_site_url() . "/mod/publisher/publisher_fullscreen/publisher.swf?nick=$irc_nick\" />"
  . "		<param name=\"quality\" value=\"high\" />"
  . "		<param name=\"bgcolor\" value=\"#ffffff\" />"
  . "		<param name=\"FlashVars\" value=\"streamNames=mark\" />"
  
  . "		<embed src=\"" . elgg_get_site_url() . "/mod/publisher/publisher_fullscreen/publisher.swf?nick=$irc_nick\" quality=\"high\" "
  . "gcolor=\"#ffffff\""
  . "			width=\"980\" height=\"600\" name=\"irc\" align=\"center\""
  . "			play=\"true\""
  . "			loop=\"false\""
  . "			quality=\"high\""
  . "			type=\"application/x-shockwave-flash\""
  . "			pluginspage=\"http://www.adobe.com/go/getflashplayer\">"
  . "			FlashVars=\"streamNames=mark\">"
  . "		</embed>"
  . "</object>";
echo "</div>";

?>

</div>
