<?php
/**
 * Webinar renderer.
 *
 * @package Elgg.Webinar
 *
 * @uses $vars['entity']    The page object
 * @uses $vars['full_view'] Whether to display the full view
 */

elgg_load_library('elgg:webinar');

$full = elgg_extract('full_view', $vars, FALSE);
$webinar = elgg_extract('entity', $vars, FALSE);

if (!$webinar) {
	return TRUE;
}

$owner = $webinar->getOwnerEntity();
$owner_icon = elgg_view_entity_icon($owner, 'small');
$container = $webinar->getContainerEntity();
$description = elgg_view('output/longtext', array('value' => $webinar->description, 'class' => 'pbl'));

$owner_link = elgg_view('output/url', array(
		'href' => "profile/$owner->username",
		'text' => $owner->name,
		'is_trusted' => true,
));
$author_text = elgg_echo('byline', array($owner_link));

$tags = elgg_view('output/tags', array('tags' => $webinar->tags));
$date = elgg_view_friendly_time($webinar->time_created);

$members = elgg_view_entity_list(webinar_get_members($webinar->guid),array('list_type' => 'gallery',
                'gallery_class' => 'elgg-gallery-users gathering'), "", 10, false, false, false);

$metadata = elgg_view_menu('entity', array(
		'entity' => $vars['entity'],
		'handler' => 'webinar',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
));

$subtitle = "$author_text $date $comments_link $categories";

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

if ($full && !elgg_in_context('gallery')) {

	$params = array(
			'entity' => $webinar,
			'title' => false,
			'metadata' => $metadata,
			'subtitle' => $subtitle,
			'tags' => $tags,
	);
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	echo $summary;
} elseif (elgg_in_context('gallery')) {
	echo <<<HTML
<div class="webinar-gallery-item">
	<h3>$webinar->title</h3>
	<p class='subtitle'>$owner_link $date</p>
</div>
HTML;
} else {
	// brief view
	$content = elgg_get_excerpt($webinar->description);
//	echo $metadata;	
	$params = array(
			'entity' => $webinar,
			'subtitle' => $subtitle,
			'tags' => $tags,
			'content' => $content,
		);
	$params = $params + $vars;
	$body = elgg_view('object/elements/summary', $params);

	echo elgg_view_image_block($owner_icon, $body);
	echo $members;
}
