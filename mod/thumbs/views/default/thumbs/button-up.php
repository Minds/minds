<?php
/**
 * Elgg thumbs
 *
 * @uses $vars['entity']
 */

 
$type = elgg_extract('type', $vars, 'entity');
if($type=='entity'){
	if (!isset($vars['entity'])) {
		return true;
	}
	$entity = $vars['entity'];
	$guid = $vars['entity']->getGUID();
	$url = elgg_get_site_url() . "thumbs/actions/$guid/up";
	
	$already = minds\plugin\thumbs\helpers\buttons::hasThumbed($entity, 'up');
	
    $count = $vars['entity']->{'thumbs:up:count'};
  
} elseif($type=='comment'){
	$id = $vars['id'];
	$comment_type = $vars['comment_type'];
	$url = elgg_get_site_url() . "action/thumbs/up?type=comment&comment_type={$comment_type}&id={$id}";
	$already = $vars['already'];
}
elgg_load_js("elgg.thumbs");


// check to see if the user has already liked this
if (elgg_is_logged_in()) {
		
		$params = array(
			'href' => $url,
			'text' => '<span class="entypo">&#128077;</span>',
			'title' => elgg_echo('thumbs:up'),
			'class'=> $already ? 'thumbs-button-up selected' : 'thumbs-button-up',
			'guid' => $guid,
			'data-role' => 'none',
			'rel'=>'external',
			'is_trusted' => true,
		);
		$up_button = elgg_view('output/url', $params);

		echo $up_button;
}
