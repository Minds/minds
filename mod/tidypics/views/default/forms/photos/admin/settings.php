<?php
/**
 * Tidypics admin settings form body
 *
 * @todo remove original image, group only upload not delete
 */

$plugin = elgg_get_plugin_from_id('tidypics');

$title = elgg_echo('tidypics:settings:main');
$content = elgg_view('forms/photos/admin/settings/main', array('plugin' => $plugin));
echo elgg_view_module('inline', $title, $content);

$title = elgg_echo('tidypics:settings:heading:img_lib');
$content = elgg_view('forms/photos/admin/settings/image_lib', array('plugin' => $plugin));
echo elgg_view_module('inline', $title, $content);

$title = elgg_echo('tidypics:settings:heading:river');
$content = elgg_view('forms/photos/admin/settings/activity', array('plugin' => $plugin));
echo elgg_view_module('inline', $title, $content);

$title = elgg_echo('tidypics:settings:heading:sizes');
$content = elgg_view('forms/photos/admin/settings/thumbnails', array('plugin' => $plugin));
echo elgg_view_module('inline', $title, $content);

echo elgg_view('input/submit', array('value' => elgg_echo("save")));
