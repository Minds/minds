<?php
/**
 * Analytics plugin
 *
 * @package analytics
 */

elgg_register_event_handler('init', 'system', 'analytics_init', 1);

function analytics_init() {
	
	//register google library
	elgg_register_library('google:client', elgg_get_plugins_path() . 'analytics/vendors/google-api-php-client/src/Google_Client.php');
	elgg_load_library('google:client');
	elgg_register_library('google:analytics', elgg_get_plugins_path() . 'analytics/vendors/google-api-php-client/src/contrib/Google_AnalyticsService.php');
	elgg_load_library('google:analytics');
	
	
	//extend footer to add google analytics code
	
	//page handler to listen for auth callbacks

}

/*
 * Register analytics google client
 */
function analytics_register_client(){
	$client = new Google_Client();
	$client->setApplicationName('Minds analytics reporter');
	$client->setAccessType('offline');
	$client->setClientId('81109256529-40nvse91toa46vad51glec1fkpu35ikq.apps.googleusercontent.com');
	$client->setClientSecret('T5cA-u3vZbhIgXq-t3r7ubN5');
	$client->setRedirectUri(elgg_get_site_url() . 'anayltics/callback');
	//$client->setDeveloperKey('insert_your_developer_key');
	$client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));
	
	//already authenticated
	if($token = elgg_get_plugin_setting('token', 'analytics')){
		$access_token = $client->refreshToken($token);
		$client->setAccessToken($access_token);
	}
	
	
	$client->setUseObjects(true);
	
	return $client;
}

/**
 * Authenticate the google analytics account
 */
function anayltics_authenticate_google(){
	$client = analytics_register_client();
	
	if (isset($_GET['code'])) {
		$client->authenticate();
		$token = $client->getAccessToken();
		elgg_set_plugin_setting('token', $token, 'analytics');
		$redirect = elgg_get_site_url() . 'admin/plugins/anayltics';
  		forward($redirect);
	} elseif(elgg_get_plugin_setting('refresh_token', 'analytics')){
		$client->authenticate();
	}
}

/**
 * Retrieve analytic information, based on a query
 */
function analytics_retrieve($options){
	
}
