<?php
/**
 * Pay - pay_basket object view
 *
 * @package Pay
 */

$item = elgg_extract('entity', $vars, FALSE);


if (!$item) {
	return TRUE;
}

$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'pay_basket',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

	$params = array(
		'entity' => $item,
		'metadata' => $metadata,
		'title' => $item->title,
		'tags' => $tags,
		'subtitle' =>	$item->description,
		'content' => '',
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	echo elgg_view_image_block($file_icon, $list_body);

