<?php
/**
 * Elgg thumbs
 *
 * @uses $vars['entity']
 */

elgg_load_js("elgg.thumbs");

if (!elgg_is_logged_in()){
	return false;
}

$type = elgg_extract('type', $vars, 'entity');
if (!isset($vars['entity'])) {
	return true;
}
$entity = $vars['entity'];
$guid = $entity->guid;

$already = minds\plugin\thumbs\helpers\buttons::hasThumbed($entity, 'up');

if($already)
	$url = elgg_get_site_url() . "thumbs/actions/$guid/up-cancel";
else
	$url = elgg_get_site_url() . "thumbs/actions/$guid/up";

$count = $vars['entity']->{'thumbs:up:count'};
  

$params = array(
	'href' => $url,
	'text' => "<span class=\"entypo\">&#128077; <span class=\"count\"> $count </span></span>",
	'title' => elgg_echo('thumbs:up'),
	'class'=> $already ? 'thumbs-button thumbs-button-up selected' : 'thumbs-button thumbs-button-up',
	'guid' => $guid,
	'data-action' => $already ? 'up-cancel' : 'up', 
	'is_trusted' => true,
);
$up_button = elgg_view('output/url', $params);

echo $up_button;
