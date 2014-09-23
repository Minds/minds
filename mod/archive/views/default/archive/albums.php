<?php
if(get_input('ajax')){
	return false;
}
$group = $vars['entity'];

$albums = elgg_list_entities(array('subtype'=>'album', 'limit'=>0, 'container_guid'=>$group->guid, 'full_view'=>false, 'pagination'=>false));

$upload_btn = elgg_view('output/url', array('href'=>elgg_get_site_url().'archive/upload?container_guid='.$group->guid, 'text'=>'Upload', 'class'=>'elgg-button elgg-button-action'));

$body = $albums;

echo elgg_view_module('aside', 'Albums' . $upload_btn, $body);