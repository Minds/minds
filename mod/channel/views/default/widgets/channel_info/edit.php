<?php
/**
 * Edit
 */

$actions = elgg_extract('action', $menu, array());

$profile_actions = '';
if (elgg_is_logged_in() && $actions) {
	$profile_actions = '<ul class="elgg-menu profile-action-menu mvm">';
	foreach ($actions as $action) {
		$profile_actions .= '<li>' . $action->getContent(array('class' => 'elgg-button elgg-button-action')) . '</li>';
	}
	$profile_actions .= '</ul>';
}

echo $profile_actions;

$user = elgg_get_page_owner_entity();

echo elgg_view('output/url', array(	'href' => '/channel/' . $user->username .'/edit',
										'text' => elgg_echo('profile:edit'),
										'class' => 'elgg-button elgg-button-action widget'
									));
