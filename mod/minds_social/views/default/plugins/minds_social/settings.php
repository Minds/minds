<?php
/**
 * Settings
 */
 
$facebook_appId_label = elgg_echo('Facebook App Id');
$facebook_appId_input = elgg_view('input/text', array(
	'name' => 'params[facebook_appId]',
	'value' => elgg_get_plugin_setting('facebook_app_id', 'minds_social')
));

$facebook_secret_label = elgg_echo('Facebook Secret Key');
$facebook_secret_input = elgg_view('input/text', array(
        'name' => 'params[facebook_secret]',
        'value' => elgg_get_plugin_setting('facebook_secret', 'minds_social')
));

echo <<<__HTML

	<label>$facebook_appId_label</label>
	<div>$facebook_appId_input</label>

	<label>$facebook_secret_label</label>
        <div>$facebook_secret_input</label>

__HTML;
