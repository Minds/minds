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

//echo '<meta property="og:image" content="http://www.minds.com/_graphics/placeholder.png"/>';

$request = $_SERVER['REQUEST_URI'];
$split = explode('/', $request);
$siteURL = elgg_get_site_url();

$owner = elgg_get_page_owner_entity();
//if(in_array('channel', $split)){
//echo elgg_get_context();
//var_dump(elgg_get_page_owner_entity(), elgg_get_logged_in_user_entity(), get_user_by_username('mark')); exit;
if((elgg_get_context() == 'channel' || elgg_get_context() == 'profile' || elgg_get_context() == 'news' || elgg_get_context() == 'blog' || elgg_get_context() == 'archive') && elgg_get_viewtype() == 'default'){
	echo '<style>';

	if($owner && ($owner->background || $owner->text_colour || $owner->link_colour)){

	echo <<<BODY
	
	body{
			background-color: $owner->background_colour;
			
			background-image:  url({$siteURL}mod/channel/background.php?guid=$owner->guid&t=$owner->background_timestamp) !important;
			
			background-repeat:$owner->background_repeat;
			
			background-position:$owner->background_pos;
									
			background-attachment:$owner->background_attachment;
			
		}
	
	/** HEADER (h1) **/	
	.channel-header h1{
		color:$owner->h1_colour;
	}
	/** HEADER (h3) **/
	.channel-header h3{
		color:$owner->h3_colour;
	}
	/** MENU LINK COLOURS **/
	.channel-filter-menu> ul > li > a{
			
		color:$owner->menu_link_colour;
	}

	/**
	 * BLOG/CONTENT VIEWS
	 */	
	.elgg-main{
		/*-moz-box-shadow: 0 0 3px #888;
		-webkit-box-shadow: 0 0 3px#888;
		box-shadow: 0 0 3px #888;*/	
	}
	.elgg-sidebar{
		/*-moz-box-shadow: 0 0 3px #888;
		-webkit-box-shadow: 0 0 3px#888;
		box-shadow: 0 0 3px #888;*/
	}
BODY;

	}	
	
	echo '</style>';
}
?>

