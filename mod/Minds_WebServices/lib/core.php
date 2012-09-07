<?php
/**
 * Elgg Webservices plugin 
 * 
 * @package Webservice
 * @author Mark Harding
 *
 */

/**
 * Heartbeat web service
 *
 * @return string $response Hello
 */
function site_test() {
	$response['success'] = true;
	$response['message'] = "Hello";
	return $response;
} 

expose_function('site.test',
				"site_test",
				array(),
				"Get site information",
				'GET',
				false,
				false);

/**
 * Web service to get site information
 *
 * @return string $url URL of Elgg website
 * @return string $sitename Name of Elgg website
 * @return string $language Language of Elgg website
 * @return string $enabled_services List of enabled services
 */
function site_getinfo() {
	$site = elgg_get_config('site');

	$siteinfo['url'] = elgg_get_site_url();
	$siteinfo['sitename'] = $site->name;
	$siteinfo['language'] = elgg_get_config('language');
	$siteinfo['enabled_services'] = $enabled = unserialize(elgg_get_plugin_setting('enabled_webservices', 'web_services'));
	
	//return OAuth info
	if(elgg_is_active_plugin('oauth',0) == true){
		$siteinfo['OAuth'] = "running";
	} else {
		$siteinfo['OAuth'] = "no";
	}
	
	return $siteinfo;
} 

expose_function('site.getinfo',
				"site_getinfo",
				array(),
				"Get site information",
				'GET',
				false,
				false);
				
/**
 * Retrive river feed
 *
 * @return array $river_feed contains all information for river
 */			
function site_river_feed($limit, $offset){
	
	global $jsonexport;
	
	elgg_list_river(array(
							'limit' => $limit,
							'offset' => $offset
						));

	return $jsonexport['activity'];
	
}
expose_function('site.river_feed',
				"site_river_feed",
				array('limit' => array('type' => 'int', 'required' => false, 'default' => 10),
						'offset' => array('type' => 'int', 'required' => false, 'default' => 0)),
				"Get river feed",
				'GET',
				false,
				false);
				
/**
 * Performs a search of the elgg site
 *
 * @return array $results search result
 */
 
function site_search($query, $offset, $limit, $sort, $order, $search_type, $entity_type, $entity_subtype, $owner_guid, $container_guid){
	
	$params = array(
					'query' => $query,
					'offset' => $offset,
					'limit' => $limit,
					'sort' => $sort,
					'order' => $order,
					'search_type' => $search_type,
					'type' => $entity_type,
					'subtype' => $entity_subtype,
				//	'tag_type' => $tag_type,
					'owner_guid' => $owner_guid,
					'container_guid' => $container_guid,
					);
					
	$types = get_registered_entity_types();
	
	foreach ($types as $type => $subtypes) {

		$results = elgg_trigger_plugin_hook('search', $type, $params, array());
		if ($results === FALSE) {
			// someone is saying not to display these types in searches.
			continue;
		}
		
		if($results['count']){
			foreach($results['entities'] as $single){
		
				//search matched critera
				/*
				$result['search_matched_title'] = $single->getVolatileData('search_matched_title');
				$result['search_matched_description'] = $single->getVolatileData('search_matched_description');
				$result['search_matched_extra'] = $single->getVolatileData('search_matched_extra');
				*/
				if($type == 'group' || $type== 'user'){
				$result['title'] = $single->name;	
				} else {
				$result['title'] = $single->title;
				}
				$result['guid'] = $single->guid;
				$result['type'] = $single->type;
				$result['subtype'] = get_subtype_from_id($single->subtype);
				
				$result['avatar_url'] = get_entity_icon_url($single,'small');
				
				$return[$type] = $result;
			}
		}
	}

	return $return;
}
expose_function('site.search',
				"site_search",
				array(	'query' => array('type' => 'string'),
						'offset' =>array('type' => 'int', 'required'=>false, 'default' => 0),
						'limit' =>array('type' => 'int', 'required'=>false, 'default' => 10),
						'sort' =>array('type' => 'string', 'required'=>false, 'default' => 'relevance'),
						'order' =>array('type' => 'string', 'required'=>false, 'default' => 'desc'),
						'search_type' =>array('type' => 'string', 'required'=>false, 'default' => 'all'),
						'entity_type' =>array('type' => 'string', 'required'=>false, 'default' => ELGG_ENTITIES_ANY_VALUE),
						'entity_subtype' =>array('type' => 'string', 'required'=>false, 'default' => ELGG_ENTITIES_ANY_VALUE),
						'owner_guid' =>array('type' => 'int', 'required'=>false, 'default' => ELGG_ENTITIES_ANY_VALUE),
						'container_guid' =>array('type' => 'int', 'required'=>false, 'default' => ELGG_ENTITIES_ANY_VALUE),
						),
				"Perform a search",
				'GET',
				false,
				false);