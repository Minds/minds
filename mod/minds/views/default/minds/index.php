<?php
/**
 * Index View
 */
?>
<div class='minds_index'>
  <?php if(!elgg_is_logged_in()) {
   		echo "<div class='earlyAccess'>";
	    echo elgg_view_form('minds/front_register', array('action'=> '/register'));
    	echo "</div>";
  }
   ?>
    <script type="text/javascript" src="http://local.minds.tv/p/100/sp/10000/embedIframeJs/uiconf_id/6709457/partner_id/100"></script>
    
    <object id="kaltura_player_1340819580" name="kaltura_player_1340819580" type="application/x-shockwave-flash" allowFullScreen="true" allowNetworking="all" allowScriptAccess="always" height="402" width="985" bgcolor="#000000" xmlns:dc="http://purl.org/dc/terms/" xmlns:media="http://search.yahoo.com/searchmonkey/media/" rel="media:video" resource="http://local.minds.tv/index.php/kwidget/cache_st/1340819580/wid/_100/uiconf_id/6709457" data="http://local.minds.tv/index.php/kwidget/cache_st/1340819580/wid/_100/uiconf_id/6709457"><param name="allowFullScreen" value="true" /><param name="allowNetworking" value="all" /><param name="allowScriptAccess" value="always" /><param name="bgcolor" value="#000000" /><param name="flashVars" value="playlistAPI.autoInsert=true&playlistAPI.kpl0Name=Frontend&playlistAPI.kpl0Url=http%3A%2F%2Flocal.minds.tv%2Findex.php%2Fpartnerservices2%2Fexecuteplaylist%3Fuid%3D%26partner_id%3D100%26subp_id%3D10000%26format%3D8%26ks%3D%7Bks%7D%26playlist_id%3D0_uqz6dqyh&{FLAVOR}" /><param name="movie" value="http://local.minds.tv/index.php/kwidget/cache_st/1340819580/wid/_100/uiconf_id/6709457" />
    <param name="wmode" value="transparent" />
    <a href="http://corp.kaltura.com">video platform</a> <a href="http://corp.kaltura.com/video_platform/video_management">video management</a> <a href="http://corp.kaltura.com/solutions/video_solution">video solutions</a> <a href="http://corp.kaltura.com/video_platform/video_publishing">video player</a> {SEO} </object>
</div>