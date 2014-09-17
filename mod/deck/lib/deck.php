<?php
/**
 * Common library of functions for the Deck plugin
 *
 * @package elgg-deck_river
 */

/**
 * Return tabs for a user
 */
function deck_river_get_tabs($user_guid){
	$tabs = elgg_get_entities(array('type'=>'object','subtype'=>'deck_tab','owner_guid'=>$user_guid));
	return $tabs;
}
 
/**
 * Get networks account for the currently logged in user.
 */
function deck_river_get_networks_account($network, $user_guid = null, $user_id = null, $shared = false) {

	$cassandra = datalist_get('cassandra'); //for cassandra ported
	
	if (!$network) return false;
	if (!$user_guid) $user_guid = elgg_get_logged_in_user_guid();
	
	if($network != 'all'){
		$subtypes = array($network);
	}
	
	$params = array(
		'type' => 'object',
		'subtypes' => $subtypes,
		'owner_guid' => $user_guid,
		'limit' => 0
	);
	
	if (!$shared) {
		
		if($cassandra){
			/**
			 * THIS IS THE NOSQL APPROACH..
			 */
			if(empty($params['subtypes'])){
				$params['subtypes'] = array('deck_account');
			} 
			$accounts = elgg_get_entities($params);
		} else {
			if(empty($params['subtypes'])){
				unset($params['subtypes']);
				$params['metadata_name'] = 'super_subtype';
				$params['metadata_value'] = 'deck_account';
			}
			$accounts = elgg_get_entities_from_metadata($params);
		}
		
		if ($user_id) {
			foreach($accounts as $k=>$account){
				if($account->user_id != $user_id){
					unset($accounts[$k]);
				}
			}
		}
				
	} else {
		
		if($cassandra){
			/**
			 * Sharing not supported with cassandra yet. 
			 */
			if(empty($params['subtypes'])){
				$params['subtypes'] = array('deck_account');
			} 
			$accounts = elgg_get_entities($params);
		} else {
			$accounts = deck_river_count_networks_account($network, $user_guid);
		}
	}
	
	return $accounts;
}

/**
 * Count networks account for a user.
 */
function deck_river_count_networks_account($network, $user_guid = null, $user_id = null) {
	if(!$user_guid){
		$user__guid = elgg_get_logged_in_user_guid();
	}
	
	$accounts = deck_river_get_networks_account($network, $user_guid, $user_id );
	return count($accounts);
}


/**
 * Return all accounts where user is shared with.
 * @param  [type] $user_guid the user
 * @return [type]          array of guid of accounts
 */
function deck_river_get_shared_accounts($network = 'all', $user_guid = null) {
	global $CONFIG;
	
	$cassandra = datalist_get('cassandra'); //for cassandra ported

	if (!$user_guid) $user_guid = elgg_get_logged_in_user_guid();

	if ($network == 'all') $network = array('twitter_account', 'facebook_account');

	$site_id = $CONFIG->site_guid;
	$hash = $user_guid . $site_id . 'get_shared_accounts';
	$account_array = array();

	if ($SHARED_ACCOUNTS_CACHE[$hash]) {
		$access_array = $cache[$hash];
	} else {
	
		if($cassandra){
			//SHARED ACCOUNTS TO BE SUPPORTS
		} else {
			// Get ACL memberships
			$query = "SELECT am.access_collection_id"
				. " FROM {$CONFIG->dbprefix}access_collection_membership am"
				. " LEFT JOIN {$CONFIG->dbprefix}access_collections ag ON ag.id = am.access_collection_id"
				. " WHERE am.user_guid = $user_guid AND (ag.site_guid = $site_id OR ag.site_guid = 0) AND ag.name = 'shared_network_acl'";
	
			$collections = get_data($query);
			if ($collections) {
				foreach ($collections as $collection) {
					if (!empty($collection->access_collection_id)) {
						$access_array[] = (int)$collection->access_collection_id;
					}
				}
	
				$a = elgg_set_ignore_access(true);
				$account_array = elgg_get_entities(array(
					'type' => 'object',
					'subtype' => $network,
					'limit' => 0,
					'wheres' => array("(e.access_id IN (" . implode(",", $access_array) . "))")
				));
				elgg_set_ignore_access($a);
			}
		}
		$SHARED_ACCOUNTS_CACHE[$hash] = $account_array;
	}

	return $account_array;
}


/**
 * Return a list of scheduled posts
 */
function deck_get_scheduled_list($owner_guid = 0,$limit = 0, $offset = ""){
	$options = array(	'type'=>'object', 
						'subtype'=>'deck_post', 
						'limit'=>$limit, 
						'offset'=>$offset
					);
	if($owner_guid != 0){
		$options['owner_guid'] = $owner_guid;
	}	
	$posts = elgg_get_entities($options);
	return $posts;
}
