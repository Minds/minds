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
	$guid = $vars['entity']->getGUID();
	$url = elgg_get_site_url() . "action/thumbs/down?guid={$guid}";
	$already = elgg_annotation_exists($guid, 'thumbs:down');
}elseif($type=='comment'){
	$id = $vars['id'];
	$comment_type = $vars['comment_type'];
	$url = elgg_get_site_url() . "action/thumbs/down?type=comment&comment_type={$comment_type}&id={$id}";
	$already = $vars['already'];
}

elgg_load_js("elgg.thumbs");


// check to see if the user has already liked this
if (elgg_is_logged_in()) {
		
		$params = array(
			'href' => $url,
			'text' => $already ? elgg_view_icon('thumbs-down-alt') : elgg_view_icon('thumbs-down'),
			'title' => elgg_echo('thumbs:down'),
			'class'=>'thumbs-button-down',
			'data-role' => 'none',
			'rel'=>'external',
			'is_action' => true,
			'is_trusted' => true,
		);
		$down_button = elgg_view('output/url', $params);
}

echo $down_button;
