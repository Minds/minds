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

$request = $_SERVER['REQUEST_URI'];
$split = explode('/', $request);
$siteURL = elgg_get_site_url();

//if(in_array('channel', $split)){
if(elgg_get_context() == 'channel' || elgg_get_context() == 'profile' || elgg_get_context() == 'news'){
	echo '<style>';

	if($owner->background || $owner->text_colour || $owner->link_colour){
	echo <<<BODY
	
	body{
			
			background-image:url({$siteURL}mod/channel/background.php?guid=$owner->guid&t=$owner->background_timestamp);
			
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
}
		
	
BODY;

	}	
	
	echo '</style>';
}