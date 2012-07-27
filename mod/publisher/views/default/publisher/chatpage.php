<div class="contentWrapper">

<?php
 	
$owner = elgg_get_page_owner_entity();
  	
?>
	  	
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js'></script>
  	
<div id='container'>The player will be placed here</div>
	  	
<script type="text/javascript">
 var flashvars = {
	  	
 'streamer':'rtmp://www.minds.tv/oflaDemo',
  	
   'file':'<?php echo elgg_get_logged_in_user_entity()->username;?>',
	   'type':'camera',
	   'controlbar':'bottom',	  	
   'stretching':'exactfit',
	  	
 'id': 'jwplayer',
	  	
  'autostart':  'true'
 };
  var params =
    {
       'allowfullscreen':              'true',
       'allowscriptaccess':            'always',
        'bgcolor':                      '#000'
    };
   var attributes =
    {
       'id':                           'jwplayer',
      'name':                         'jwplayer'
      };
  swfobject.embedSWF('<?php echo elgg_get_site_url();?>mod/publisher/jwpublisher.swf','container','750','475','9.0.115','false', flashvars, params,
  attributes
 );
  	
</script>

<?php

//Create the HTML calling the wigget
echo "<div align=\" center\">";
echo "  	<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\""
  . "		id=\"flashirc\" width=\"980\" height=\"600\""
  . "		codebase=\"http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab\">"
  . "		<param name=\"movie\" value=\"" . elgg_get_site_url() . "/mod/publisher/publisher_fullscreen/publisher.swf?nick=$irc_nick\" />"
  . "		<param name=\"quality\" value=\"high\" />"
  . "		<param name=\"bgcolor\" value=\"#ffffff\" />"
  . "		<embed src=\"" . elgg_get_site_url() . "/mod/publisher/publisher_fullscreen/publisher.swf?nick=$irc_nick\" quality=\"high\" "
  . "gcolor=\"#ffffff\""
  . "			width=\"800\" height=\"980\" name=\"irc\" align=\"center\""
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
