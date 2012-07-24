<?php
/**
 * the wire
 *
 */

$title = elgg_view_title(elgg_echo('minds:riverdashboard:addwire'));
$content .= elgg_view_form('thewire/add');

echo elgg_view_module('thewire', $title, $content);

