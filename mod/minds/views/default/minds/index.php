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
    <div class="search">
            <form action="<?php echo elgg_get_site_url(); ?>search" method="get">
             
                    <input type="text" name="q" value=""  placeholder="<?php echo elgg_echo('search');?>" />
                    <button type="submit" value="search" class="elgg-button elgg-button-submit">Search</button>
                    <input type="hidden" name="search" value="1" /></td>
    
            </form>
        
    </div>
    <div class='splash'>
		Free &
		Open Source
		Everything
	</div>
		<div class='block'>
			<div class='news-block'>
				<h2>Featured News</h2>
				<?php echo minds_elastic_list_news(array('action_types'=>'create','limit'=>4, 'object_guids'=>minds_get_featured(null, 1000, 'guids')));?>
			</div>
			<div class='side-block'>
				<?php echo elgg_view_form('login');?>
				<object id="kaltura_player_6709481" name="kaltura_player_6709481" type="application/x-shockwave-flash" xmlns:dc="http://purl.org/dc/terms/" xmlns:media="http://search.yahoo.com/searchmonkey/media/" allowfullscreen="true" allowscriptaccess="always" allownetworking="all" height="140" width="250" resource="http://www.minds.tv/index.php/kwidget/wid/_102/uiconf_id/6709481/entry_id/0_alhv657k" data="http://www.minds.tv/index.php/kwidget/wid/_102/uiconf_id/6709481/entry_id/0_alhv657k" rel="media:video" wmode="transparent">
					<param name="allowScriptAccess" value="always"><param name="allowNetworking" value="all">
					<param name="allowFullScreen" value="true"><param name="bgcolor" value="#000000">
					<param name="wmode" value="transparent"><param name="movie" value="http://www.minds.tv/index.php/kwidget/wid/_102/uiconf_id/6709481/entry_id/0_alhv657k">
					<param name="flashVars" value="&amp;{FLAVOR}&amp;autoPlay=false&amp;customAd.path=false">
					
					<span property="media:title" content="video.mov"></span> 
					<span property="media:width" content="250"></span>
					<span property="media:height" content="125"></span> 
					<span property="media:type" content="application/x-shockwave-flash"></span>
				</object>
			</div>
		</div>
  	<div class='featured_wall'>
  		<h2>Featured content</h2>
	   <?php 
	    /**
		 * Video carousel
		 */
	   	$videos = minds_get_featured('kaltura_video', 12);
		echo '<h3>Videos <a href="'.elgg_get_site_url().'archive/all">(more)</a></h3>';
		echo elgg_view('output/carousel', array('id'=>'videos','entities'=>$videos));
		
		/**
		 * Blog carousel
		 */
		elgg_load_library('elgg:blog');
		$blogs = blog_get_featured(12);
		echo '<h3>Blogs <a href="'.elgg_get_site_url().'blog/all">(more)</a></h3>';
		echo elgg_view('output/carousel', array('id'=>'blogs','entities'=>$blogs));
		
		/**
		 * Images
		 */
		//elgg_load_library('elgg:blog');
		$images = minds_get_featured('image',12);
		echo '<h3>Images <a href="'.elgg_get_site_url().'photos/all">(more)</a></h3>';
		echo elgg_view('output/carousel', array('id'=>'images','entities'=>$images));
		
		?>
   </div>
</div>
