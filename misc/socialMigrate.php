<?php
/**
 * Elgg index page for web-based applications
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * Start the Elgg engine
 */
require_once(dirname(__FILE__) . "/engine/start.php");

//get all the users
$users = elgg_get_entities( array('type' => 'user', 'limit' => 20000 ));

foreach($users as $user){

	$guid = $user->guid;
	
	$data = array('type'=>'user_index_to_guid', $guid => time());

	//facebook plugin setting?
	$fb_id = elgg_get_plugin_user_setting('minds_social_facebook_uid', $guid, 'minds_social');
	if($fb_id){
		db_insert('fb:'.$fb_id, $data);//move this into the user class
	}
	//twitter plugin setting?
	$twitter_id = elgg_get_plugin_user_setting('twitter_id', $guid, 'minds_social');
	if($twitter_id){
		db_insert('twitter:id:'.$twitter_id, $data);//move this into the user class
	}

	$twitter_name = elgg_get_plugin_user_setting('twitter_name', $guid, 'minds_social');
	if($twitter_name){
		db_insert('twitter:name:'.$twitter_name, $data);//move this into the user class
	}

	echo "$guid  twitter:$twitter_id fb: $fb_id \n------\n";
}
