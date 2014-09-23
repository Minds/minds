<?php
$comment = $vars['entity'];
if(!$comment)
	return false;

$owner = $comment->getOwnerEntity();
if(!$owner)
	$owner = get_user_by_username('minds');

if($owner->username == 'minds'){

	$owner->name = 'anonymous';
	$owner->username = 'privacy';

}

$icon = elgg_view_entity_icon($owner, 'tiny');

$author = elgg_view('output/url', array('text' => $owner -> name, 'href' => $owner -> getURL(), 'class' => 'minds-comments-owner'));
	
$menu = elgg_view_menu('comments', array(
	'comment' => $comment,
    'handler' => $handler,
    'class' => 'elgg-menu-hz',
    'sort_by' => 'priority',
));
	
$content .= $menu;
	
$content .= $author . ': ' . minds_filter(htmlspecialchars($comment->description, ENT_QUOTES, 'UTF-8'));
$content .= '<br/><span class="minds-comments-timestamp"' . elgg_view_friendly_time($comment->time_created) . '</span>';
	
echo elgg_view_image_block($icon, $content);
