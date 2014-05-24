<?php

elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());


if (!elgg_get_widgets(elgg_get_logged_in_user_guid(), 'admin')) {
	elgg_add_admin_widgets('make_admin_repair', 'user', elgg_get_logged_in_user_entity());
}

$params = array(
	'num_columns' => 2,
	'context' => 'admin',
	'widgets' => elgg_get_widgets(elgg_get_logged_in_user_guid(), 'admin'),
	'exact_match' => true,
	'show_access' => false,
);

$widgets = elgg_view('page/layouts/widgets/add_button', $params);
$widgets .= elgg_view('page/layouts/widgets/add_panel', $params);
$widgets .= elgg_view_layout('widgets', $params);

echo $widgets; 
