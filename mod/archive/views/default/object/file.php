<?php
/**
 * File renderer.
 *
 * @package ElggFile
 */

$full = elgg_extract('full_view', $vars, FALSE);

$file = elgg_extract('entity', $vars, FALSE);

if (!$file) {
	return TRUE;
}

$owner = $file->getOwnerEntity();
$container = $file->getContainerEntity();
$categories = elgg_view('output/categories', $vars);
$excerpt = elgg_get_excerpt($file->description);
$mime = $file->mimetype;
$base_type = substr($mime, 0, strpos($mime,'/'));

$menu = elgg_view_menu('entity', array(
                'entity' => $file,
                'handler' => 'archive',
                'sort_by' => 'priority',
                'class' => 'elgg-menu-hz',
        ));

$owner_link = elgg_view('output/url', array(
	'href' => $owner->getURL(),
	'href' => "file/owner/$owner->username",
	'text' => $owner->name,
	'is_trusted' => true,
));
$author_text = elgg_echo('byline', array($owner_link));

$file_icon = elgg_view_entity_icon($file, 'small');

$tags = elgg_view('output/tags', array('tags' => $file->tags));
$date = elgg_view_friendly_time($file->time_created);

$subtitle = "$author_text $date $comments_link $categories";

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

if ($full) {

	$extra = '';
	if (elgg_view_exists("file/specialcontent/$mime")) {
		$extra = elgg_view("file/specialcontent/$mime", $vars);
	} else if (elgg_view_exists("file/specialcontent/$base_type/default")) {
		$extra = elgg_view("file/specialcontent/$base_type/default", $vars);
	}
	

	$params = array(
		'entity' => $file,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
	);
	$params = $params + $vars;
	$download = elgg_view('output/url', array(	'href'=>'/action/archive/download?guid='.$file->guid,
												'text'=> elgg_echo('minds:archive:download'),
												'is_action' => true,
												'class'=> 'elgg-button elgg-button-action archive-button archive-button-right'
										));
	$text = elgg_view('output/longtext', array('value' => $file->description));
		
	$license =  elgg_view('minds/license', array('license'=>$file->license)); 
	if($file->access_id == 2){
		$social_links = elgg_view('minds_social/social_footer');
	}
	$body = "$text $extra $download $license $social_links";

	echo elgg_view('object/elements/full', array(
		'entity' => $file,
		'title' => false,
		'body' => $body,
	));

} elseif (elgg_in_context('gallery')) {
	echo '<div class="file-gallery-item">';
	echo "<h3>" . $file->title . "</h3>";
	echo elgg_view_entity_icon($file, 'medium');
	echo "<p class='subtitle'>$owner_link $date</p>";
	echo '</div>';
} else {
	$image = elgg_view('output/img', array('src'=>$file->getIconURL('large'), 'class'=>'rich-image'));
	$title = elgg_view('output/url', array('href'=>$file->getURL(), 'text'=>elgg_view_title($file->title)));
	$extras = '<span class="extras"> <p class="time">'. $date . '</p></span>';
	
	$body = '<span class="info">' . $title . $extras . '<span>';
	
	$content = $image . $body;
	echo $menu;
	$header = elgg_view_image_block(elgg_view_entity_icon($file->getOwnerEntity(), 'small'), $title . $subtitle);
        echo $header;
        echo elgg_view('output/url', array('href'=>$file->getURL(), 'text'=>$image));
}
