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

	elgg_register_plugin_hook_handler('cron', 'minute', 'analytics_fetch');
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
	
	// set assertion credentials
	$client->setAssertionCredentials(
  		new Google_AssertionCredentials(

			'81109256529-7204tgap3gkaf3gmeuji4k9r408m76m8@developer.gserviceaccount.com', // email you added to GA

			array('https://www.googleapis.com/auth/analytics.readonly'),

   	 		file_get_contents('/key.p12')  // keyfile you downloaded

	));
	
	$client->setClientId('81109256529-7204tgap3gkaf3gmeuji4k9r408m76m8.apps.googleusercontent.com');
	$client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));
	$client->setAccessType('offline_access');

	
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
 * Retrieve analytic information, based on a info
 */
function analytics_retrieve(array $options = array()){

	$db = new DatabaseCall('entities_by_time');
	
	$g = new GUID();
	
	$defaults = array(
		'context'=> '',
		'filter' => 'trending',
		'timeframe' => 'day',
		'limit' => 12,
		'offset' => ''
	);
	$options = array_merge($defaults, $options);
	
	if($options['filter'] == 'trending'){
		try{
			//try from cache. all trending caches are valid for 1 hour
			$context = $options['context'] != '' ? $options['context'] : 'all';
			$count = $db->countRow('trending:'.$context);
			if((int) $options['offset'] >= $count){
				return false;
			} elseif($options['offset'] > 0){
				$options['limit']++;
			}
			
			$guids = $db->getRow('trending:'.$context, array('offset'=>$options['offset'], 'limit'=>$options['limit'], 'reversed'=>false));			

			return $guids;
		} catch(Exception $e){
		//	register_error($e->getMessage());
			//show featured instead...
			//return minds_get_featured($options['context'], $offset['limit'], 'guids');	
			return minds_get_featured('',12,'guids');
		}
	} else {
		return "this type is not supported at the this time";
	}
}

//Gather the analytics data and store in a row
function analytics_fetch(){
	
	$client = analytics_register_client();
	$analytics = new Google_AnalyticsService($client);	

	$today = date('o-m-d', time());
	$yesterday = date('o-m-d', time() - 60 * 60 * 24);

	$profile_id = 'ga:' . elgg_get_plugin_setting('profile_id', 'analytics');
	try{
	$optParams = array(
		'dimensions' => 'ga:pagePath',
		'sort' => '-ga:pageviews',
		'filters' => 'ga:pagePath=~/view/',
		'max-results' => 100000
	);
	$results = $analytics->data_ga->get(
		$profile_id,
		$yesterday,
		$today,
		'ga:pageviews',
		$optParams);
	$guids = array();
	$user_guids = array();
	foreach ($results->getRows() as $row) {
		$url = $row[0];
		$guid = analytics_get_guid_from_url($url);
	    $entity = get_entity($guid,'object');
		if($entity->access_id != ACCESS_PUBLIC || !$entity){
			continue;
		}
		$views = $row[2];
		//echo $entity->title . ' GUID:' . $guid . ' - Views: ' . $views . '<br/>';
		if(in_array($guid, $guids) || !elgg_instanceof($entity,'object')){
			//duplicate
			echo "GUID $guid failed, probably because it doesn't exists \n";
			continue;
		} elseif(!in_array($entity->subtype,array('blog', 'kaltura_video'))){
			continue;
		}
		array_push($guids, $guid);//now add to the list
		$objects['all'][] = $guid;
		$objects[$entity->subtype][] = $guid;
		if(in_array($entity->subtype, array('image','file','kaltura_video'))){
                	 $objects['archive'][] = $guid;
		}
		
		$user_guids[] = $entity->owner_guid;
	}	
	} catch(Exception $e) {
		//get the feature list if something went wrong with analytics...
	/*	$featured = minds_get_featured('',250);
		foreach($featured as $entity){
			$guid = $entity->guid;
			$objects['all'][] = $guid;
	                $objects[$entity->subtype][] = $guid;
        	        if(in_array($entity->subtype, array('image','album','kaltura_video'))){
                	         $objects['archive'][] = $guid;
                	}
		}*/
	}
	
	//add an all row
	foreach($objects as $subtype => $guids){
		$data = $guids;
		
		if($data){
			//we want to start removing old ones soon...
			$db = new DatabaseCall('entities_by_time');
			$db->removeRow('trending:'.$subtype);
			$db->insert('trending:'.$subtype, $data);
			var_dump($data);
			echo "Successfuly imported '$subtype' to trending \n";
		}
	}
	
	$user_occurances = array_count_values($user_guids);
	arsort($user_occurances);
	$user_guids = array_keys($user_occurances);

	$db = new DatabaseCall('entities_by_time');
	$db->removeRow('trending:users', $user_guids);
			
	return;
}
/** 
 * Naming conventions for subtypes
 */
function analytics_get_guid_from_url($url){
	$g = new GUID();
	$segments = explode( '/', $url);
	if($guid = intval($segments[3])){
		return $g->migrate($guid);
	} else {
		return 0;
	}
}
