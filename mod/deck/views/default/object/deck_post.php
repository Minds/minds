<?php

$post = elgg_extract('entity', $vars);
$owner = $post->getOwnerEntity();

$metadata = elgg_view_menu('entity', array(
	'entity' => $post,
	'handler' => 'post',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));



$friendly_time = date('D jS F Y - H:i', $post->ts);
$content = "<p><b>$friendly_time</b></p>";

foreach($post->getAccounts() as $account){
	$content .= elgg_view_entity($account, array('view_type'=>'in_network_box'));
}
foreach($post->getSubAccounts() as $account){
	$content .= elgg_view_entity($account, array('view_type'=>'in_network_box'));
}

$content .= "<p>$post->message</p>";

$header = elgg_view_image_block(elgg_view_entity_icon($owner, 'small'), $content);

echo $metadata;
echo $header;
