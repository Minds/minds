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
	elgg_extend_view('page/elements/head','analytics/trackingcode', 90000); //such a large number so it is always at the bottom
	
	//page handler to listen for auth callbacks
	elgg_register_page_handler('analytics','analytics_page_handler');
}

/*
 * Analytics page handler for the callback function
 */
function analytics_page_handler($page) {
	if($page[0] == 'callback'){
		return anayltics_authenticate_google();
	}
	
	return false; 
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
	$client->setRedirectUri(elgg_get_site_url() . 'analytics/callback');
	//$client->setDeveloperKey('insert_your_developer_key');
	$client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));
	
	//already authenticated
	if($token = elgg_get_plugin_setting('token', 'analytics')){
		//var_dump($token); exit;
		//$access_token = $client->refreshToken($token['refresh_token']);
		//var_dump($access_token); exit;
		$client->setAccessToken($token);
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
		$redirect = elgg_get_site_url() . 'admin/plugin_settings/anayltics';
  		forward($redirect);
	} elseif(elgg_get_plugin_setting('token', 'analytics')){
		$client->authenticate();
	}
}
/**
 * Retrieve analytic information, based on a query
 */
function analytics_retrieve(array $options = array()){
	$client = analytics_register_client();
	$analytics = new Google_AnalyticsService($client);	
	
	$defaults = array(
		'context'=> '',
		'filter' => 'trending',
		'timeframe' => 'day',
		'limit' => 10,
		'offset' => 0,
		'cache' => true
	);
	$options = array_merge($defaults, $options);
	
	$today = date('o-m-d', time());
	$yesterday = date('o-m-d', time() - 60 * 60 * 24);

	if($options['filter'] == 'trending'){
		try{
			//try from cache. all trending caches are valid for 1 hour
			$CACHE = new ElggFileCache('/tmp/analytics/trending_'.$options['context'].'/', 360);
			if($guids = $CACHE->load('trending')){
				return json_decode($guids, true);
			} else {
				$profile_id = 'ga:' . elgg_get_plugin_setting('profile_id', 'analytics');
				$optParams = array(
					'dimensions' => 'ga:pagePath',
					'sort' => '-ga:pageviews',
					'filters' => 'ga:pagePath=~' . $options['context'] . '/view/*',
					'max-results' => 10
				);
				$results = $analytics->data_ga->get(
      					$profile_id,
      					$yesterday,
      					$today,
      					'ga:pageviews',
					$optParams);
				$guids = array();
				foreach ($results->getRows() as $row) {
					$url = $row[0];
					$guid = analytics_get_guid_from_url($url);
					$entity = get_entity($guid);
					$views = $row[2];
					//echo $entity->title . ' GUID:' . $guid . ' - Views: ' . $views . '<br/>';
					$guids[] = $guid;
				}
				//save to cache for 1 hour
				$CACHE->save('trending', json_encode($guids));			
				return $guids;
			}
		} catch(Exception $e){
			register_error($e);
			var_dump($e);
		}
	} else {
		return "this type is not supported at the this time";
	}
}

/** 
 * Naming conventions for subtypes
 */
function analytics_get_guid_from_url($url){
	$segments = explode( '/', $url);
	if($guid = intval($segments[3])){
		return $guid;
	} else {
		return 0;
	}
}
