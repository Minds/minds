<?php
	/* MINDS META
	 *
	 * SEO ENHANCE
	*/
	$description = get_input('description');
	$keywords = get_input('keywords');
	//are we a blog, wire post, event??
	
	if($description){
		echo "<meta name=\"description\" content=\"$description\" />";
	} else {
		echo "<meta name=\"description\" content=\"" . elgg_get_plugin_setting('default_description', 'minds_theme') . "\" />";
	}
	
	if($keywords){ 
		echo "<meta name=\"keywords\" content=\"$keywords>\"/>";
	} else {
		echo "<meta name=\"keywords\" content=\"" . elgg_get_plugin_setting('default_keywords', 'minds_theme') . "\" />";
	}

	/* JS Extend */
?>
<script>
	//$('.elgg-button.elgg-button-dropdown').live('hover', elgg.ui.popupOpen);
	//$('#login-dropdown-box').live('hover', {);
	////$("#login-dropdown-box").hover(
	
	
</script>