<?php
/**
 * Minds Inviter Index
 *
 */
elgg_load_library('orientation');

gatekeeper();

setcookie('sidebarOpen', 'true', time()+60, '/');

$orientation = new MindsOrientation();
$orientation->run(get_input('step'));
exit;

$title = elgg_echo('orientation:title', array(elgg_get_site_entity()->name));

$content = elgg_view('orientation/index');

$header = '<div class="elgg-head orientation">' . elgg_view_title($title) . '<div class="progress"><h3>' . orientation_calculate_progress() . '%</h3><p>'.elgg_echo('orientation:progress:blurb') . '</p></div></div>';

$params = array(
	'content' => $content,
	'sidebar' => elgg_view('orientation/sidebar'),
	'title' => $title,
	'class' => 'orientation',
	'filter' => '',
	'header' => $header
);

$body = elgg_view_layout('tiles', $params);

echo elgg_view_page($title, $body);


