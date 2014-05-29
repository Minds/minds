<?php
if(elgg_is_logged_in())
	return false;

elgg_load_js('elgg.lightbox');
elgg_load_css('elgg.lightbox');
 
?>
<div id="minds-signup-popup">
	<div class="elgg-button minds-button-register">
		Sign up
	</div> 
	<span style="clear:both;display:block;"></span>
	<div class="elgg-button minds-button-login">
		Login
	</div>
	<span style="clear:both;display:block;"></span>
	 <span class="cancel"> - maybe later - </span>
</div>