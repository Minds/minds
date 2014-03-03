<?php
/**
 * Webinar plugin settings
 */
$server_url_label = elgg_echo('gatherings:server_url');
$server_url_input = elgg_view('input/text', array(
		'name' => 'params[server_url]',
		'id' => 'gatherings_server_url',
		'value' => $vars['entity']->server_url
));
	
echo <<<___HTML
<div>
	<label for="server_url">$server_url_label </label>
	$server_url_input
</div>

___HTML;

