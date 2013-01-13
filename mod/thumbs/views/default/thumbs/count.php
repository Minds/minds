<?php
/**
 * Count thumbs up
 *
 *  @uses $vars['entity']
 */

$type = elgg_extract('type', $vars, 'entity');

if($type=='entity'){
	$show = elgg_list_annotations(array(
		'guid' => $guid,
		'annotation_name' => 'thumbs:up',
		'limit' => 99,
		'list_class' => 'elgg-list-likes'
	));
	$votes_up = thumbs_up_count($vars['entity']);
	$votes_down = thumbs_down_count($vars['entity']);
	$id = $vars['entity']->getGUID();
} elseif($type=='comment'){
	$show = elgg_list_entities(array(
		'guids' => $vars['thumbsUP'],
		'class' => 'elgg-list-likes'
	));
	$votes_up = count($vars['thumbsUP']);
	$votes_down = count($vars['thumbsDOWN']);
	$id = $vars['id'];
}



if ($votes_up || $votes_down) {
	// display the number of likes
	
	$string = elgg_echo('thumbs:up:count', array($votes_up-$votes_down));

	$params = array(
		'text' => $string,
		'title' => elgg_echo('likes:see'),
		'rel' => 'popup',
		'href' => "#thumbs-up-$id"
	);
	$list = elgg_view('output/url', $params);
	$list .= "<div class='elgg-module elgg-module-popup elgg-likes hidden clearfix' id='thumbs-up-$id'>";
	$list .= $show;
	$list .= "</div>";
	echo $list;
}
