<?php

$item = $vars['item'];

if ($item->action_type !== 'create') {
	return true;
}

$object = $vars['item']->getObjectEntity();

$replies_options = array(
	'guid' => $object->getGUID(),
	'annotation_name' => 'group_topic_post',
	'limit' => 3,
	'order_by' => 'n_table.time_created desc'
);

$replies = elgg_get_annotations($replies_options);

if ($replies) {
	// why is this reversing it? because we're asking for the 3 latest
	// replies by sorting desc and limiting by 3, but we want to display
	// these replies with the latest at the bottom.
	$replies = array_reverse($replies);

	$replies_options['count'] = TRUE;
	
	$reply_count = elgg_get_annotations($replies_options); 
	
	// If greater that 3 replies, link to the rest of them
	if ($reply_count > count($replies)) {
		$link = elgg_view('output/url', array(
			'href' => $object->getURL(),
			'text' => elgg_echo('river:replies:all', array($reply_count)),
		));
		
		echo elgg_view_image_block(elgg_view_icon('speech-bubble-alt'), $link, array('class' => 'elgg-river-participation'));
	}
	
	// Display the latest
	echo elgg_view_annotation_list($replies, array('list_class' => 'elgg-river-replies', 'item_class' => 'elgg-river-participation'));

}


if ($object->canAnnotate(0, 'group_topic_post')) {
	// inline reply form
	$form_vars = array('id' => "groups-reply-{$object->getGUID()}", 'class' => 'elgg-form-small elgg-river-participation');
	$body_vars = array('entity' => $object, 'inline' => true);
	echo elgg_view_form('discussion/reply/save', $form_vars, $body_vars);
}