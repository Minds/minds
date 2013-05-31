<?php
/**
 * Index View
 */
$img_src = elgg_get_site_url() == 'http://www.minds.com/' ? elgg_get_site_url().'mod/minds/graphics/minds_logo.png' : elgg_get_site_url().'mod/minds/graphics/minds_logo_io.png';
elgg_load_js('uiVideoInline');
?>
<div class='minds_index'>
    <div class="logo">
        <img src="<?php echo $img_src;?>" width="200" height="90" />
    </div>
    <div class="search">
            <form action="<?php echo elgg_get_site_url(); ?>search" method="get">
             
                    <input type="text" name="q" value=""  placeholder="<?php echo elgg_echo('search');?>" />
                    <button type="submit" value="search" class="elgg-button elgg-button-submit">Search</button>
                    <input type="hidden" name="search" value="1" />
    
            </form>
        
    </div>
    <div class='splash'>
		Free & Open Source Social Media
	</div>
    
    
    <div class="signup-options">
        <div class="option left">
            <div class="signup-button-row">
                <div class="signup-button launch-channel elgg-button"><a href="<?php echo elgg_get_site_url(); ?>register/">Launch A Channel</a></div>
            </div>
            
            <div class="video">VIDEO HERE</div>
            
            <div class="blurb">
                <ul>
                    <li>Forever Free</li>
                    <li>Minds.com/Yourbrand</li>
                    <li>Guarantee Frontpage Feature</li>
                    <li>Run Your Own Ads</li>
                    <li>Record Video, Music, Images, Files
                    Video Conference. Blogs. Events
                    Market, Messages, RSS, News Feed</li>	
                </ul>
            </div>
        </div>
        
        <div class="option right">
            <div class="signup-button-row">
                <div class="signup-button launch-node elgg-button"><a href="<?php echo elgg_get_site_url(); ?>register/node/">Launch A Network</a></div>
            </div>
            
            <div class="video">VIDEO HERE</div>
            
            <div class="blurb">
                <ul>
                    <li>Imagine This Entire Site With Your Brand!</li>
                    <li>Free To 100 Channels Or Self-Host It!</li>
                    <li>Yourbrand.minds.com Or Your Own Domain</li>
                    <li>Enterprise Mobile Apps For Android & iOS</li>
                    <li>Run Your Own Ads and API</li>
                    <li>Protect Your Fans</li>
                    <li>Cross-Post To All Of Your Social Networks</li>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    
    
		<div class='block'>
			<div class='news-block'>
				<h2>Featured News</h2>
				<?php echo minds_elastic_list_news(array('action_types'=> 'feature', 'limit'=>4));?>
			</div>
			<div class='side-block'>
				<?php if(!elgg_is_logged_in()) {
				 echo elgg_view_form('login');
				 echo '<p>Minds is a universal network to search, create and share free information.</p>';
				 } else {
				 	elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
					echo elgg_view('page/elements/miniprofile');
					echo elgg_view('page/elements/friends');
				}
				echo "<br/><h3>Featured Content</h3>";	
				/**
				 * Video carousel
				 */ 
				 $videos = minds_get_featured('kaltura_video', 4);
				 echo '<h3>Videos <a href="'.elgg_get_site_url().'archive/all">(more)</a></h3>';
				 echo elgg_view('output/carousel', array('id'=>'videos','entities'=>$videos));
				 
				/**
				 * Blog carousel
				 */
				 elgg_load_library('elgg:blog');
				 $blogs = blog_get_featured(4);
				 echo '<h3>Blogs <a href="'.elgg_get_site_url().'blog/all">(more)</a></h3>';
				 echo elgg_view('output/carousel', array('id'=>'blogs','entities'=>$blogs));
				
				/**
				 * Images
				 */
				 //elgg_load_library('elgg:blog');
				 $images = minds_get_featured('image',4);
				 echo '<h3>Images <a href="'.elgg_get_site_url().'photos/all">(more)</a></h3>';
				 echo elgg_view('output/carousel', array('id'=>'images','entities'=>$images));
				 
				 echo elgg_view('page/elements/sidebar', $vars);
				?>
			</div>
</div>
