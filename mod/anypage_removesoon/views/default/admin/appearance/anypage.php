<?php
/**
 * Settings for anypage
 */
//elgg_push_context('anypage');

$page_guid = get_input('guid');
$page = get_entity($page_guid);

if ($page_guid && !elgg_instanceof($page, 'object', 'anypage')) {
	forward(REFERER, 404);
}

if (!$page_guid) {
	// default to first page if it exists
	$pages = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'anypage',
		'limit' => 1,
	));
	if ($pages) {
		$page = $pages[0];
	}
}

elgg_register_menu_item('title', array(
	'name' => 'anypage:new',
	'href' => "admin/appearance/anypage/new",
	'text' => elgg_echo("anypage:new"),
	'link_class' => 'elgg-button elgg-button-action',
));
$tabs = elgg_view('anypage/admin_tabs', array('current_page' => $page));

if (!$tabs) {
	echo elgg_echo('anypage:no_pages');
} else {
	echo $tabs;

	$form_vars = anypage_prepare_form_vars($page);
	echo elgg_view_form('anypage/save', array(), $form_vars);
}