<?php
/**
 * elasticsearch
 *
 * @package elasticsearch
 */
 
echo '<div><h3>Enable Elastic Search</h3><br/>';

echo elgg_view("input/dropdown", array("name"=>"params[elasticsearch_enabled]", "options"=>array('Yes', 'No'), "value"=>elgg_get_plugin_setting('elasticsearch_enabled', 'minds_search')));
 
echo '</div>';

echo '<div><h3>Server Url</h3><br/>';

echo elgg_view("input/text", array("name"=>"params[server]", "value"=>elgg_get_plugin_setting('server', 'minds_search')));
 
echo '</div>';

echo '<div><h3>Index Name</h3><br/>';

echo elgg_view("input/text", array("name"=>"params[index]", "value"=>elgg_get_plugin_setting('index', 'minds_search')));
 
echo '</div>';

