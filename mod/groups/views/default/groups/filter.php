<?php
echo elgg_view_menu('title', array('class'=>'group-action-button'));
$page_owner=elgg_get_page_owner_entity();
if($page_owner){
	$invitaions_count = groups_get_invited_groups($page_owner->getGUID());
}
$tabs = array(
	'all' => array(
		'title' => elgg_echo('groups:all'),
		'url' => "groups/all",
		'selected' => $vars['selected'] == 'all',
	),
	'owner' => array(
		'title' => elgg_echo('groups:owned'),
		'url' => "groups/owner/$page_owner->username",
		'selected' => $vars['selected'] == 'owner',
	),
	'member' => array(
		'title' =>elgg_echo('groups:yours'),
		'url' => "groups/member/$page_owner->username",
		'selected' => $vars['selected'] == 'mine',
	),
	'invitations'  => array(
		'title' => $invitaions_count ? elgg_echo('groups:invitations:pending', array($count)) : elgg_echo('groups:invitations'),
		'url' => elgg_get_site_url() . "groups/invitations/$page_owner->guid",
		'selected' => $vars['selected'] == 'invitations',
	),
);

echo elgg_view('navigation/tabs', array('tabs' => $tabs));
