<?php
/**
 * Index View
 */
$img_src = elgg_get_site_url() == 'http://www.minds.com/' ? elgg_get_site_url().'mod/minds/graphics/minds_logo.png' : elgg_get_site_url().'mod/minds/graphics/minds_logo_io.png';
?>
<div class='minds_index'>
    <div class="logo">
        <img src="<?php echo $img_src;?>" width="200" height="90" />
    </div>
    
    <?php if(!elgg_is_logged_in()) {
 		echo "<div class='earlyAccess'>";
	    echo elgg_view_form('minds/front_register', array('action'=> '/register'));
    	echo "</div>";
   }
   ?>
   <div class='splash'>
		Free & Open Source
		Social Video
		Revolution
	</div>
   <div class='video'>
		<script type="text/javascript" src="http://www.minds.tv/p/102/sp/10200/embedIframeJs/uiconf_id/6709476/partner_id/100"></script>
		<object id="kaltura_player_1350827151" 
			name="kaltura_player_1350827151" 
			type="application/x-shockwave-flash" 
			allowFullScreen="true" 
			allowNetworking="all" 
			allowScriptAccess="always" 
			height="581" width="980" bgcolor="#000000" 
			xmlns:dc="http://purl.org/dc/terms/" 
			xmlns:media="http://search.yahoo.com/searchmonkey/media/" 
			rel="media:video" 
			resource="http://www.minds.tv/index.php/kwidget/wid/_102/uiconf_id/6709476/entry_id/0_hpag8aj4" 
			data="http://www.minds.tv/index.php/kwidget/wid/_102/uiconf_id/6709476/entry_id/0_hpag8aj4">
				<param name="allowFullScreen" value="true" />
				<param name="allowNetworking" value="all" />
				<param name="allowScriptAccess" value="always" />
				<param name="bgcolor" value="#000000" />
				<param name="flashVars" value="&{FLAVOR}" />
				<param name="movie" value="http://www.minds.tv/index.php/kwidget/wid/_102/uiconf_id/6709476/entry_id/0_hpag8aj4" />
			 <span property="dc:description" content="a remix of some Remixes that discuss Creative Commons, Open Source, and a tidbit about Minds.com in there"></span><span property="media:title" content="Minds Creative Commons Video"></span> <span property="media:width" content="400"></span><span property="media:height" content="300"></span> <span property="media:type" content="application/x-shockwave-flash"></span> 
		</object>
	</div>
   </div>
