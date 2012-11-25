<?php
/**
 * Minds Inviter Settings
 */

echo '<div><h2>Gmail</h2><br/>';
 
echo '<div><h3>Client Id</h3><br/>';

echo elgg_view("input/text", array("name"=>"params[gmail_client_id]", "value"=>elgg_get_plugin_setting('gmail_client_id', 'minds_inviter')));
 
echo '</div>';

echo '<div><h3>Client Id Secret</h3><br/>';

echo elgg_view("input/text", array("name"=>"params[gmail_client_secret]", "value"=>elgg_get_plugin_setting('gmail_client_secret', 'minds_inviter')));
 
echo '</div>';

echo '<br/>';

echo '<div><h2>Yahoo</h2><br/>';
 
echo '<div><h3>App Id</h3><br/>';

echo elgg_view("input/text", array("name"=>"params[yahoo_app_id]", "value"=>elgg_get_plugin_setting('yahoo_app_id', 'minds_inviter')));
 
echo '</div>';

echo '<div><h3>Yahoo Consumer Key</h3><br/>';

echo elgg_view("input/text", array("name"=>"params[yahoo_consumer_key]", "value"=>elgg_get_plugin_setting('yahoo_consumer_key', 'minds_inviter')));
 
echo '</div>';

echo '<div><h3>Yahoo Consumer Secret</h3><br/>';

echo elgg_view("input/text", array("name"=>"params[yahoo_consumer_secret]", "value"=>elgg_get_plugin_setting('yahoo_consumer_secret', 'minds_inviter')));
 
echo '</div>';