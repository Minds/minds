<?php
/**
 * FOAF
 * 
 * @package ElggProfile
 * 
 */

$owner = elgg_get_page_owner_entity();

if (elgg_instanceof($owner, 'user')) {
?>
	<link rel="meta" type="application/rdf+xml" title="FOAF" href="<?php echo full_url(); ?>?view=foaf" />
<?php

}

$request = $_SERVER['REQUEST_URI'];
$split = explode('/', $request);
$siteURL = elgg_get_site_url();

if(in_array('channel', $split)){

	echo '<style>';

	if($owner->background || $owner->text_colour || $owner->link_colour)
	echo <<<BODY
	
	body{
			
			background-image:url({$siteURL}mod/channel/background.php?guid=$owner->guid);
			
			background-repeat:$owner->background_repeat;
			
			background-position:$owner->background_pos;
			
			background-color:$owner->background_colour;
			
		}
		
	body, p, h1,h2,h3,h4,h5, label{
			
		color:$owner->text_colour;
	}
	
	a{
		color:$owner->link_colour;
	}
	
BODY;

	echo '</style>';
	
}