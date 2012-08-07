<?php
/**
 * Count thumbs up
 *
 *  @uses $vars['entity']
 */


$list = '';
$votes_up = thumbs_up_count($vars['entity']);
$votes_down = thumbs_down_count($vars['entity']);
$guid = $vars['entity']->getGUID();

if ($votes_up) {
	// display the number of likes
	
	$string = elgg_echo('thumbs:up:count', array($votes_up));

	$params = array(
		'text' => $string,
		'title' => elgg_echo('likes:see'),
		'rel' => 'popup',
		'href' => "#thumbs-up-$guid"
	);
	$list = elgg_view('output/url', $params);
	$list .= "<div class='elgg-module elgg-module-popup elgg-likes hidden clearfix' id='thumbs-up-$guid'>";
	$list .= elgg_list_annotations(array(
		'guid' => $guid,
		'annotation_name' => 'thumbs:up',
		'limit' => 99,
		'list_class' => 'elgg-list-likes'
	));
	$list .= "</div>";
	echo $list;
}
