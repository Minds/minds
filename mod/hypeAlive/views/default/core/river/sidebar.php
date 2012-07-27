<?php
$user = elgg_get_page_owner_entity();

if (!$user) {
	return TRUE;
}

// content links
$content_menu = elgg_view_menu('owner_block', array(
	'entity' => elgg_get_page_owner_entity()
));

echo <<<HTML
	$content_menu

HTML;

