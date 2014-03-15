<?php
/**
 * Multisite global settings
 */

global $CONFIG;

if(!isset($CONFIG->multisite)){
	$CONFIG->multisite = new stdClass();
}

$CONFIG->multisite->hidden_plugins = array('minds');
$CONFIG->multisite->plugin_default_settings = array(
						'minds_social' => array(),
						'archive' => array(),
						'phpmailer' => array()
						);
