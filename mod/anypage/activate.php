<?php
/**
 * Anypage
 *
 * Register classes
 * Add example pages
 * Add admin notice
 */

if (get_subtype_id('object', 'anypage')) {
	update_subtype('object', 'anypage', 'AnyPage');
} else {
	add_subtype('object', 'anypage', 'AnyPage');
}

// add example if no pages
$count = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'anypage',
	'count' => true
));

if (!$count) {
	$page = new AnyPage();
	$page->title = elgg_echo('anypage:example:view:title');
	$page->setPagePath('/example/test_view');
	$page->setUseView(true);
	$page->save();

	$page = new AnyPage();
	$page->title = elgg_echo('anypage:example:title');
	$page->setPagePath('/anypage/example');
	$page->description = elgg_echo('anypage:example_page:description');
	$page->save();

	elgg_add_admin_notice('anypage', elgg_echo('anypage:activate:admin_notice',
			array(elgg_normalize_url('admin/appearance/anypage'))));
}