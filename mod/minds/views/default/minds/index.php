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
    
 <script type="text/javascript" src="http://local.minds.tv/p/100/sp/10000/embedIframeJs/uiconf_id/6709458/partner_id/100"></script>
	<object id="kaltura_player_1350827151" 
		name="kaltura_player_1350827151" 
		type="application/x-shockwave-flash" 
		allowFullScreen="true" 
		allowNetworking="all" 
		allowScriptAccess="always" 
		height="250" width="445" bgcolor="#000000" 
		xmlns:dc="http://purl.org/dc/terms/" 
		xmlns:media="http://search.yahoo.com/searchmonkey/media/" 
		rel="media:video" 
		resource="http://local.minds.tv/index.php/kwidget/cache_st/1350827151/wid/_100/uiconf_id/6709458/entry_id/0_kdt8luvc" 
		data="http://local.minds.tv/index.php/kwidget/cache_st/1350827151/wid/_100/uiconf_id/6709458/entry_id/0_kdt8luvc">
			<param name="allowFullScreen" value="true" />
			<param name="allowNetworking" value="all" />
			<param name="allowScriptAccess" value="always" />
			<param name="bgcolor" value="#000000" />
			<param name="flashVars" value="&{FLAVOR}" />
			<param name="movie" value="http://local.minds.tv/index.php/kwidget/cache_st/1350827151/wid/_100/uiconf_id/6709458/entry_id/0_kdt8luvc" />
			<a rel="media:thumbnail" href="http://local.minds.tv/p/100/sp/10000/thumbnail/entry_id/0_kdt8luvc/width/120/height/90/bgcolor/000000/type/2"></a> <span property="dc:description" content="a remix of some Remixes that discuss Creative Commons, Open Source, and a tidbit about Minds.com in there"></span><span property="media:title" content="Minds Creative Commons Video"></span> <span property="media:width" content="400"></span><span property="media:height" content="300"></span> <span property="media:type" content="application/x-shockwave-flash"></span> 
	</object>
	<div class='splash'>
		Free & Open Source
		Social Video
		Revolution
	</div>
   </div>