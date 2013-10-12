<?php
global $CONFIG;
	/* MINDS META
	 *
	 * SEO ENHANCE
	*/
	$description = get_input('description', $CONFIG->site_description);
	$keywords = get_input('keywords', $CONFIG->site_keywords);
	//are we a blog, wire post, event??
	
	if($description){
		echo "\t<meta name=\"description\" content=\"$description\"> \n";
	} else {
		echo "\t<meta name=\"description\" content=\"" . elgg_get_plugin_setting('default_description', 'minds') . "\"> \n";
	}
	
	if($keywords){ 
		echo "\t<meta name=\"keywords\" content=\"$keywords\"> \n";
	} else {
		echo "\t<meta name=\"keywords\" content=\"" . elgg_get_plugin_setting('default_keywords', 'minds') . "\"> \n";
	}

$request = $_SERVER['REQUEST_URI'];
$split = explode('/', $request);
$siteURL = elgg_get_site_url();

//if(in_array('channel', $split)){
//echo elgg_get_context();
if((elgg_get_context() == 'channel' || elgg_get_context() == 'profile' || elgg_get_context() == 'news' || elgg_get_context() == 'blog' || elgg_get_context() == 'archive') && elgg_get_viewtype() == 'default'){
	echo '<style>';

	if($owner->background || $owner->text_colour || $owner->link_colour){
	echo <<<BODY
	
	body{
			
			background-image:url({$siteURL}mod/channel/background.php?guid=$owner->guid&t=$owner->background_timestamp) !important;
			
			background-repeat:$owner->background_repeat;
			
			background-position:$owner->background_pos;
			
			background-color:$owner->background_colour;
			
			background-attachment:$owner->background_attachment;
			
		}
		
	h1,h2,h3,h4,h5, .elgg-module-widget{
			
		color:$owner->text_colour;
	}
	
	a{
		color:$owner->link_colour;
	}
	
	.elgg-module-widget, .elgg-module-widget:hover{
		background:$owner->widget_bg;
	}
	.elgg-module-widget, .elgg-module-widget p{
		color:$owner->widget_body_text;
	}
	.elgg-module-widget > .elgg-head h3 {
		color:$owner->widget_head_title_color;
	}
	.elgg-module-widget:hover h3, .elgg-module-widget:hover{
				background:$owner->widget_bg;
	}
	.elgg-main{
		-moz-box-shadow: 0 0 3px #888;
		-webkit-box-shadow: 0 0 3px#888;
		box-shadow: 0 0 3px #888;	
	}
	.elgg-sidebar{
		-moz-box-shadow: 0 0 3px #888;
		-webkit-box-shadow: 0 0 3px#888;
		box-shadow: 0 0 3px #888;
	}
BODY;

	}	
	
	echo '</style>';
}
