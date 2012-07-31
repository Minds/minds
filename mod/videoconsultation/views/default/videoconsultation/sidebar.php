<?php
/**
 * videoconsultation sidebar
 */

echo elgg_view('page/elements/comments_block', array(
	'subtypes' => 'videoconsultation',
	'owner_guid' => elgg_get_page_owner_guid(),
));
