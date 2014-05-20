<?php
/**
 * Multisite global settings
 */

global $CONFIG;

/**
 * Where multisite admin endpoint is installed
 */
$CONFIG->multisite_endpoint = "http://minds-multi.minds.io/minds/"; // Where the Web services endpoint is
$CONFIG->multisite_server_ip = "54.236.202.136"; // IP address to prompt people to set DNS to
$CONFIG->minds_multisite_root_domain = '.minds.com'; // Suffix for new nodes

if(!isset($CONFIG->multisite)){
	$CONFIG->multisite = new stdClass();
}

$CONFIG->multisite->hidden_plugins = array('minds');
$CONFIG->multisite->plugin_default_settings = array(
						'minds_social' => array(),
						'archive' => array(),
						'phpmailer' => array()
						);
