<?php
if(elgg_is_logged_in())
        return false;

elgg_load_js('elgg.lightbox');
elgg_load_css('elgg.lightbox');

?>
<div id="minds-signup-popup">
        <a href="<?php echo elgg_get_site_url();?>register" class="elgg-button minds-button-register">
                Sign up
        </a>  
        <span style="clear:both;display:block;"></span>
        <a href="<?php echo elgg_get_site_url();?>login" class="elgg-button minds-button-login">
                Login   
        </a>
	<?php if(elgg_get_site_url() == 'https://www.minds.com/'){?>
	<iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fmindsdotcom&amp;width=200px&amp;height=62&amp;colorscheme=light&amp;show_faces=false&amp;header=false&amp;stream=false&amp;show_border=false&amp;appId=703348649707140" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200pxpx; height:62px;" allowTransparency="true"></iframe>
       	<?php } ?>
	 <span style="clear:both;display:block;"></span>
         <span class="cancel"> - maybe later - </span>
</div>

