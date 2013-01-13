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
	$url = elgg_get_site_url() . "action/thumbs/up?guid={$guid}";
	$already = elgg_annotation_exists($guid, 'thumbs:up');
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
			'text' => $already ? elgg_view_icon('thumbs-up-alt') : elgg_view_icon('thumbs-up'),
			'title' => elgg_echo('thumbs:up'),
			'class'=>'thumbs-button-up',
			'data-role' => 'none',
			'rel'=>'external',
			'is_action' => true,
			'is_trusted' => true,
		);
		$up_button = elgg_view('output/url', $params);
}

echo $up_button;
