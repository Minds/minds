<?php
/**
 * Embed a youtube video via the embed interface
 */

$form_vars = array(
	'class' => 'elgg-form-embed-youtube',
);
$body_vars = array('container_guid' => elgg_get_page_owner_guid());
echo elgg_view_form('embed/youtube', $form_vars, $body_vars);

// the tab we want to be forwarded to after upload is complete
echo elgg_view('input/hidden', array(
	'name' => 'embed_forward',
	'value' => 'all',
));
