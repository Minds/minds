<?php
/**
 * videochat sidebar
 */

echo elgg_view('page/elements/comments_block', array(
	'subtypes' => 'videochat',
	'owner_guid' => elgg_get_page_owner_guid(),
));
