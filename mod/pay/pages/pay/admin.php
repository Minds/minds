<?php
/**
 * Pay - see account overview
 *
 * @package Pay
 */
elgg_load_library('elgg:pay');

admin_gatekeeper();

$limit = get_input("limit", 10);

$title = elgg_echo('pay:admin:withdraw');

set_context('pay_admin');
$content = elgg_list_entities_from_metadata(array(
	'types' => 'object',
	'subtypes' => 'pay',
	'limit' => $limit,
	'full_view' => FALSE,
	'metadata_name_value_pairs' => array('name' => 'withdraw', 'value' => true),
));
elgg_pop_context();
set_context('pay');

if (!$content) {
	$content = elgg_echo('pay:account:none');
}

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
));


echo elgg_view_page($title, $body);

?>