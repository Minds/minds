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

