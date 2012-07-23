<?php
/**
 * View for chat objects
 *
 * @package Chat
 */

$full = elgg_extract('full_view', $vars, FALSE);
$chat = elgg_extract('entity', $vars, FALSE);

if (!$chat) {
	return TRUE;
}

$subtitle = elgg_view('chat/members', $vars);

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
} else {
	$metadata = elgg_view_menu('entity', array(
		'entity' => $vars['entity'],
		'handler' => 'chat',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));
}

if ($full) {
	// User is viewing the chat so reset the number of unread messages.
	$chat->resetUnreadMessageCount();
	
	// The actual chat is not shown, just the messages.
	// @todo Should the chat creation time be visible?
	echo "<div class=\"elgg-subtext\">$subtitle</div>";
} else {
	// brief view

	$params = array(
		'entity' => $chat,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
	);
	
	// View short title in message preview
	if (elgg_in_context('chat_preview')) {
		$params['title'] = elgg_view('output/url', array(
			'text' => elgg_get_excerpt($chat->title, 35),
			'href' => $chat->getURL(),
		));
	}
	
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	echo elgg_view_image_block($owner_icon, $list_body);
}