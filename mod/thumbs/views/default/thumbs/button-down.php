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

$already = minds\plugin\thumbs\helpers\buttons::hasThumbed($entity, 'down');

if($already)
	$url = elgg_get_site_url() . "thumbs/actions/$guid/down-cancel";
else
	$url = elgg_get_site_url() . "thumbs/actions/$guid/down";

$count = $vars['entity']->{'thumbs:down:count'};
  

$params = array(
	'href' => $url,
	'text' => "<span class=\"entypo\">&#128078; <span class=\"count\"> $count </span></span>",
	'title' => elgg_echo('thumbs:up'),
	'class'=> $already ? 'thumbs-button thumbs-button-down selected' : 'thumbs-button thumbs-button-down',
	'guid' => $guid,
	'is_trusted' => true,
);
$up_button = elgg_view('output/url', $params);

echo $up_button;
