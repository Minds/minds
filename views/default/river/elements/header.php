<?php
/**
 * Header of river item
 *
 * @uses $vars
 */

$item = $vars['item'];

$timestamp = elgg_view_friendly_time($item->getPostedTime());


$summary = elgg_extract('summary', $vars, elgg_view('river/elements/summary', array('item' => $vars['item'])));
if ($summary === false) {
	$subject = $item->getSubjectEntity();
	$summary = elgg_view('output/url', array(
		'href' => $subject->getURL(),
		'text' => $subject->name,
		'class' => 'river-subject',
		'is_trusted' => true,
	));
}

$group_string = '';
$object = $item->getObjectEntity();
$container = $object->getContainerEntity();
if ($container instanceof ElggGroup && $container->guid != elgg_get_page_owner_guid()) {
	$group_link = elgg_view('output/url', array(
		'href' => $container->getURL(),
		'text' => $container->name,
		'is_trusted' => true,
	));
	$group_string = elgg_echo('river:ingroup', array($group_link));
}


echo elgg_view_menu('river', array(
	'item' => $item,
	'sort_by' => 'priority',
	'class' => 'minds-menu elgg-menu-hz',
));

$body = <<<BODY
<div class="river-summary">
	$summary $group_string 
	<p class="river-timestamp">
		$timestamp
	</>
</div>
BODY;

echo elgg_view('page/components/image_block', array(
	'image' => elgg_view('river/elements/image', $vars),
	'body' => $body, 
	'class' => 'minds-river-header',
));
