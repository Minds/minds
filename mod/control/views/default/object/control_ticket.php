<?php
/**
 */

$full = elgg_extract('full_view', $vars, FALSE);
$sidebar = elgg_extract('sidebar', $vars, FALSE);
$ticket = elgg_extract('entity', $vars, FALSE);

if (!$ticket) {
	return TRUE;
}

$owner = $ticket->getOwnerEntity();

$owner_icon = elgg_view_entity_icon($owner, 'small');
$owner_link = elgg_view('output/url', array(
	'href' => "blog/owner/$owner->username",
	'text' => $owner->name,
	'is_trusted' => true,
));

$date = elgg_view_friendly_time($ticket->time_created);

$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'control:tickets',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
	'full_view' => $full
));

$subtitle = "$author_text $date $comments_link $categories";

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

if ($full) {

	$body = elgg_view('output/longtext', array(
		'value' => $ticket->description,
	));

	
	$params = array(
		'entity' => $ticket,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
	);
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	echo elgg_view('object/elements/full', array(
		//'summary' => $summary,
		//'icon' => $owner_icon,
		'body' => $body,
	));


} else {
		
	// brief view
	$title = elgg_view('output/url', array('href'=>$ticket->getURL(), 'text'=>elgg_view_title($ticket->title)));
	$owner_link  = elgg_view('output/url', array('href'=>$owner->getURL(), 'text'=>$owner->name));
	
	$subtitle = '<i>'.
                elgg_echo('by') . ' ' . $owner_link . ' ' .
                elgg_view_friendly_time($ticket->time_created) . '</i>';

    $header = elgg_view_image_block(elgg_view_entity_icon($owner, 'small'), $title . $subtitle);
	
	$body = elgg_view('output/longtext', array(
		'value' => $ticket->description,
	));
	
	echo $metadata;
    echo $header;
	
	echo $body;
}
