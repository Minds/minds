<?php
/**
 * Minds Web Services
 * 
 * @package Webservice
 * @author Mark Harding
 *
 */
function minds_web_services_init() {
	
	
	require_once(dirname(dirname(__FILE__))."/kaltura_video/kaltura/api_client/includes.php");
	
	//Core library
	elgg_register_library('webservice:core', elgg_get_plugins_path() . 'Minds_WebServices/lib/core.php');
	elgg_load_library('webservice:core');
	elgg_register_library('webservice:blog', elgg_get_plugins_path() . 'Minds_WebServices/lib/blog.php');
	elgg_load_library('webservice:blog');
	elgg_register_library('webservice:file', elgg_get_plugins_path() . 'Minds_WebServices/lib/file.php');
	elgg_load_library('webservice:file');
	elgg_register_library('webservice:group', elgg_get_plugins_path() . 'Minds_WebServices/lib/group.php');
	elgg_load_library('webservice:group');
	elgg_register_library('webservice:message', elgg_get_plugins_path() . 'Minds_WebServices/lib/message.php');
	elgg_load_library('webservice:message');
	elgg_register_library('webservice:user', elgg_get_plugins_path() . 'Minds_WebServices/lib/user.php');
	elgg_load_library('webservice:user');
	elgg_register_library('webservice:wire', elgg_get_plugins_path() . 'Minds_WebServices/lib/wire.php');
	elgg_load_library('webservice:wire');
	elgg_register_library('webservice:kaltura', elgg_get_plugins_path() . 'Minds_WebServices/lib/kaltura.php');
	elgg_load_library('webservice:kaltura');
}


elgg_register_event_handler('init', 'system', 'minds_web_services_init');
