<?php

//$title_block = elgg_view_title(elgg_echo('Launch node'), array('class' => 'elgg-heading-main'));
$buttons = elgg_is_logged_in() ? elgg_view_menu('title', array(
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz minds-tiers-buttons',
	)) : '';

$register_url = elgg_get_site_url() . 'action/select_tier';
$form_params = array(
	'action' => $register_url,
	'class' => 'elgg-form-launch',
);
$body_params = array();
$content .= elgg_view_form('select_tier', $form_params, $body_params);

$body = elgg_view_layout("one_column", array(	
					'header' => $buttons != '' ? $buttons : false,
					'content'=>  $content ,
				));

echo elgg_view_page($title,$body); 
