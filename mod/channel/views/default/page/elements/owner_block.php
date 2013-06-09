<?php
/**
 * Elgg owner block
 * Displays page ownership information
 *
 * @package Elgg
 * @subpackage Core
 *
 */
if(elgg_get_context()=='news'){
	return true;
}
elgg_push_context('owner_block');

// groups and other users get owner block
$owner = elgg_get_page_owner_entity();
if ($owner instanceof ElggGroup){
	
	$header = elgg_view_entity_icon($owner, 'large', array('link_class'=>'groups'));
	
	$body = elgg_view_menu('owner_block', array('entity' => $owner));
	
	echo elgg_view('page/components/module', array(
		'header' => $header,
		'body' => $body,
		'class' => 'elgg-owner-block',
	));
	
}elseif($owner instanceof ElggUser) {

	$avatar = elgg_view('output/img', array('src'=>$owner->getIconURL('large')));
	
	$header = elgg_view('output/url', array('href'=>$owner->getUrl(), 'text'=>$avatar . elgg_view_title($owner->name)));

	$body = elgg_view_menu('channel_elements', array('class'=>'owner_block'));

	$body .= elgg_view('page/elements/owner_block/extend', $vars);

	echo elgg_view('page/components/module', array(
		'body' => $header . $body,
		'class' => 'elgg-owner-block',
	));
}

elgg_pop_context();
