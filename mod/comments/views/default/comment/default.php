<?php
$comment = $vars['entity'];
if(!$comment)
	return false;

$owner = $comment->getOwnerEntity();

$icon = elgg_view_entity_icon($owner, 'tiny');

$author = elgg_view('output/url', array('text' => $owner -> name, 'href' => $owner -> getURL(), 'class' => 'minds-comments-owner'));
	
$menu = elgg_view_menu('comments', array(
	'comment' => $comment,
    'handler' => $handler,
    'class' => 'elgg-menu-hz',
    'sort_by' => 'priority',
));
	
$content .= $menu;
	
$content .= $author . ': ' . minds_filter($comment->description);
$content .= '<br/><span class="minds-comments-timestamp"' . elgg_view_friendly_time($comment->time_created) . '</span>';
	
echo elgg_view_image_block($icon, $content);