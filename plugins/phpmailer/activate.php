<?php
/**
 * Set default plugin settings for PHPMailer
 */

$plugin = elgg_get_plugin_from_id('phpmailer');
if ($plugin) {

	$defaults = array(
		'phpmailer_override' => 'enabled',
		'phpmailer_smtp' => 0,
		'phpmailer_host' => '',
		'phpmailer_smtp_auth' => 0,
		'phpmailer_username' => '',
		'phpmailer_password' => '',
		'ep_phpmailer_ssl' => 0,
		'ep_phpmailer_port' => 465,
		'nonstd_mta' => 0,
	);

	foreach ($defaults as $name => $value) {
		if (!isset($plugin->$name)) {
			$plugin->$name = $value;
		}
	}
}
