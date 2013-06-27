<?php
/**
 * River item footer
 */

$item = $vars['item'];
$object = $item->getObjectEntity();

// annotations do not have comments
if (!$object || $item->annotation_id) {
	return true;
}


$comment_count = $object->countComments();

$comments = elgg_get_annotations(array(
	'guid' => $object->getGUID(),
	'annotation_name' => 'generic_comment',
	'limit' => 3,
	'order_by' => 'n_table.time_created desc'
));

if ($comments) {
	// why is this reversing it? because we're asking for the 3 latest
	// comments by sorting desc and limiting by 3, but we want to display
	// these comments with the latest at the bottom.
	$comments = array_reverse($comments);

	if ($comment_count > count($comments)) {
		$link = elgg_view('output/url', array(
			'href' => $object->getURL(),
			'text' => elgg_echo('river:comments:all', array($comment_count)),
		));
		
		echo elgg_view_image_block(elgg_view_icon('speech-bubble-alt'), $link, array('class' => 'elgg-river-participation'));
	}
	
	echo elgg_view_annotation_list($comments, array('list_class' => 'elgg-river-comments', 'item_class' => 'elgg-river-participation'));

}

if ($object->canAnnotate(0, 'generic_comment')) {
	// inline comment form
	echo elgg_view_form('comments/add', array(
		'id' => "comments-add-{$object->getGUID()}",
		'class' => 'elgg-river-participation elgg-form-small',
	), array('entity' => $object, 'inline' => true));
}