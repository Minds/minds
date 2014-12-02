<?php
/**
 * Channels index
 *
 */

elgg_load_library('channels:suggested');

$page_owner = elgg_get_logged_in_user_entity();
elgg_set_page_owner_guid($page_owner->guid);

$friends = get_input('friends', 10);
$groups = get_input('groups', 10);

$num_members = get_number_users();

$limit = get_input('limit', 20);
$offset = get_input('offset', '');

$title = elgg_echo('channels');

$options = array('type' => 'user', 'full_view' => false, 'limit'=>$limit, 'masonry'=>false, 'list_class'=>'users-list');
switch ($vars['page']) {
	case 'subscribers':
		$db = new minds\core\data\call('friendsof');
		$subscriptions = $db->getRow($pageowner->guid, array('limit'=>get_input('limit', 12), 'offset'=>get_input('offset', '')));
		$users = array();
		foreach($subscribers as $guid => $subscription){
			if(is_numeric($subscribers)){
				//this is a local, old style subscription
				$users[] = new minds\entities\user($guid);
				continue;
			} 
			
			$users[] = new minds\entities\user(json_decode($subscribers,true));
		}
		
		$content = elgg_view_entity_list($users,$options, $offset, $limit, false, false,false);
		break;
	case 'subscriptions':
		$db = new minds\core\data\call('friends');
		$subscriptions = $db->getRow($page_owner->guid, array('limit'=>get_input('limit', 12), 'offset'=>get_input('offset', '')));
		$users = array();
		foreach($subscriptions as $guid => $subscription){
			if(is_numeric($subscription)){
				//this is a local, old style subscription
				$users[] = new minds\entities\user($guid);
				continue;
			} 
			
			$users[] = new minds\entities\user(json_decode($subscription,true));
		}
		$content = elgg_view_entity_list($users,$options, $offset, $limit, false, false,false);
		break;
	case 'featured':
		$guids = minds\core\data\indexes::fetch('user:featured', array('limit'=>$limit, 'offset'=>$offset));
		if(!$guids){
			$content = ' ';
			break;
		}
		$options['guids'] = $guids;
		$options['load-next'] = end(array_keys($guids));		
		$content = elgg_list_entities($options);
		break;
	case 'popular':
		$options['limit'] = $limit;
		$options['newest_first'] = false;
		$content = elgg_list_entities($options);
		break;
	case 'trending':
		//trending
       		$opts = array(
                	'timespan' => get_input('timespan', 'day')
       	 	);
	        $trending = new MindsTrending(null, $opts);
		$guids = $trending->getList(array('type'=>'user', 'limit'=>$limit, 'offset'=>(int) $offset));
		$options['guids'] = $guids;
		if($guids){
			$content = elgg_list_entities($options);
		}
		break;
	/*case 'suggested':
		$people = suggested_friends_get_people($page_owner->guid, $friends, $groups);
		$entities = array();
		foreach($people as $person){
			$entities[] = $person['entity'];
		}
		$content = elgg_view_entity_list($entities);
		break;
	case 'online':
		$content = get_online_users($limit);
		break;
	case 'collections':
		elgg_register_title_button('collections', 'add');
		$content = elgg_view_access_collections(elgg_get_logged_in_user_guid());
		break;*/
	default:
	case 'newest':
                $options['newest_first'] = true;
                $content = elgg_list_entities($options);
		break;
}

$params = array(
	'content' => $content,
	'sidebar' => elgg_view('channels/sidebar'),
	'title' => $title,
	'filter_override' => elgg_view('channels/nav', array('selected' => $vars['page'])),
	'class'=> 'channels'
);

$body = elgg_view_layout('tiles', $params);

echo elgg_view_page($title, $body);
