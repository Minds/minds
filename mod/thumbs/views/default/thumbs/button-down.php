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
$url = elgg_get_site_url() . "action/thumbs/down?guid={$guid}";
$already = elgg_annotation_exists($guid, 'thumbs:down');

// check to see if the user has already liked this
if (elgg_is_logged_in() && $vars['entity']->canAnnotate(0, 'thumbs:down')) {
		
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
		$up_button = elgg_view('output/url', $params);
}

echo $up_button;
