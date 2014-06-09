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
        <span style="clear:both;display:block;"></span>
         <span class="cancel"> - maybe later - </span>
</div>

