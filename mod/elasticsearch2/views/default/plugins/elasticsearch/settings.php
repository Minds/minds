<?php
/**
 * elasticsearch
 *
 * @package elasticsearch
 */

echo '<div><h3>Server Url</h3><br/>';

echo elgg_view("input/text", array("name"=>"params[server]", "value"=>elgg_get_plugin_setting('server', 'elasticsearch')));
 
echo '</div>';

echo '<div><h3>Index Name</h3><br/>';

echo elgg_view("input/text", array("name"=>"params[index]", "value"=>elgg_get_plugin_setting('index', 'elasticsearch')));
 
echo '</div>';

