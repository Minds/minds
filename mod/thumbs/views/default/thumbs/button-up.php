<?php
/**
 * Elgg thumbs
 *
 * @uses $vars['entity']
 */

if (!isset($vars['entity'])) {
	return true;
}

elgg_load_js("elgg.thumbs");

$guid = $vars['entity']->getGUID();
$url = elgg_get_site_url() . "action/thumbs/up?guid={$guid}";
$already = elgg_annotation_exists($guid, 'thumbs:up');

// check to see if the user has already liked this
if (elgg_is_logged_in() && $vars['entity']->canAnnotate(0, 'thumbs:up')) {
		
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
