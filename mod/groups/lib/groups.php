<?php
/**
 * Groups function library
 */

/**
 * List all groups
 */
function groups_handle_all_page() {

	// all groups doesn't get link to self
	elgg_pop_breadcrumb();
	elgg_push_breadcrumb(elgg_echo('groups'));
	
	elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());

//	if (elgg_get_plugin_setting('limited_groups', 'groups') != 'yes' || elgg_is_admin_logged_in()) {
		elgg_register_title_button();
//	}

	$selected_tab = get_input('filter', 'newest');

	switch ($selected_tab) {
		case 'popular':
			$content = elgg_list_entities_from_relationship_count(array(
				'type' => 'group',
				'relationship' => 'member',
				'inverse_relationship' => false,
				'full_view' => false,
			));
			if (!$content) {
				$content = elgg_echo('groups:none');
			}
			break;
		case 'discussion':
			$content = elgg_list_entities(array(
				'type' => 'object',
				'subtype' => 'groupforumtopic',
				'order_by' => 'e.last_action desc',
				'limit' => 40,
				'full_view' => false,
			));
			if (!$content) {
				$content = elgg_echo('discussion:none');
			}
			break;
		case 'newest':
		default:
			$content = elgg_list_entities(array(
				'type' => 'group',
				'full_view' => false,
				'masonry'=>false,
				'list_class'=>'minds-group-list',
				'limit' => get_input('limit', 10),
				'offset' => get_input('offset', ''),
			));
			if (!$content) {
				$content = elgg_echo('groups:none');
			}
			break;
	}

	$title = elgg_echo('groups:all');
	$nav = elgg_view('groups/filter', array('selected'=>'all'));

	$params = array(
		'header' => elgg_view_title($title) . $nav,
		'content' => $content,
		'sidebar' => $sidebar,
		'class' => 'groups'
	);
	$body = elgg_view_layout('one_column', $params);

	echo elgg_view_page($title, $body);
}

function groups_handle_featured_page(){
	$title = "Featured";

	 elgg_register_title_button();
	
	$guids = minds\core\data\indexes::fetch("group:featured", array('limit'=>get_input('limit', 10), 'offset'=>get_input('offset', '')));
	if(is_array($guids)){
		$entities = minds\core\entities::get(array('guids'=>$guids));
		usort($entities, function($a, $b){
			return $a->featured_id - $b->featured_id;
		});
		$content = elgg_view_entity_list($entities, array('full_view'=>false));
	}
	
	$nav = elgg_view('groups/filter', array('selected'=>'featured'));
	$params = array(
		'header' => elgg_view_title($title) . $nav,
		'content' => $content,
		'class' => 'groups'
	);
	$body = elgg_view_layout('one_column', $params);

	echo elgg_view_page($title, $body);
}

function groups_search_page() {
	elgg_push_breadcrumb(elgg_echo('search'));

	$tag = get_input("tag");
	$title = elgg_echo('groups:search:title', array($tag));

	// groups plugin saves tags as "interests" - see groups_fields_setup() in start.php
	$params = array(
		'metadata_name' => 'interests',
		'metadata_value' => $tag,
		'type' => 'group',
		'full_view' => FALSE,
	);
	$content = elgg_list_entities_from_metadata($params);
	if (!$content) {
		$content = elgg_echo('groups:search:none');
	}

	//$sidebar = elgg_view('groups/sidebar/find');
	//$sidebar .= elgg_view('groups/sidebar/featured');

	$params = array(
		'content' => $content,
		'sidebar' => $sidebar,
		'filter' => false,
		'title' => $title,
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * List owned groups
 */
function groups_handle_owned_page() {

	$page_owner = elgg_get_page_owner_entity() ?: elgg_get_logged_in_user_entity();

	if ($page_owner->guid == elgg_get_logged_in_user_guid()) {
		$title = elgg_echo('groups:owned');
	} else {
		$title = elgg_echo('groups:owned:user', array($page_owner->name));
	}
	elgg_push_breadcrumb($title);

	elgg_register_title_button();

	$content = elgg_list_entities(array(
		'type' => 'group',
		'owner_guid' => $page_owner->guid,
		'full_view' => false,
	));
	if (!$content) {
		$content = elgg_echo('groups:none');
	}

	$nav = elgg_view('groups/filter', array('selected'=>'owner'));

	$params = array(
		'header' => elgg_view_title($title) . $nav,
		'content' => $content,
		'title' => $title,
		'class' => 'groups'
	);
	$body = elgg_view_layout('one_column', $params);

	echo elgg_view_page($title, $body);
}

/**
 * List groups the user is memober of
 */
function groups_handle_mine_page() {

	$page_owner = elgg_get_page_owner_entity();
	if(!$page_owner){ 
		$username = get_input('username');
		$user = get_user_by_username($username);
		elgg_set_page_owner_guid($user->guid);
		$page_owner = $user;
	}

	if ($page_owner->guid == elgg_get_logged_in_user_guid()) {
		$title = elgg_echo('groups:yours');
	} else {
		$title = elgg_echo('groups:user', array($page_owner->name));
	}
	elgg_push_breadcrumb($title);

	elgg_register_title_button();

	$content = elgg_list_entities_from_relationship(array(
		'full_view' => false,
		'relationship_guid' => $page_owner->guid,
		'relationship' => 'member',
		'offset'=>get_input('offset','')
		//'inverse_relationship' => true
	)); 
	
	if (!$content) {
		$content = elgg_echo('groups:none');
	}

	$nav = elgg_view('groups/filter', array('selected'=>'mine'));

	$params = array(
		'header' => elgg_view_title($title) . $nav,
		'content' => $content,
		'title' => $title,
		'class' => 'groups'
	);
	$body = elgg_view_layout('one_column', $params);

	echo elgg_view_page($title, $body);
}

/**
 * Create or edit a group
 *
 * @param string $page
 * @param int $guid
 */
function groups_handle_edit_page($page, $guid = 0) {
	gatekeeper();
	
	$header = false;
	if ($page == 'add') {
		elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
		$title = elgg_echo('groups:add');
		elgg_push_breadcrumb($title);
		if (elgg_get_plugin_setting('limited_groups', 'groups') != 'yes' || elgg_is_admin_logged_in()) {
			$content = elgg_view('groups/edit');
		} else {
			$content = elgg_echo('groups:cantcreate');
		}
	} else {
		$title = elgg_echo("groups:edit");
		$group = get_entity($guid, 'group');

		if ($group && $group->canEdit()) {
			elgg_set_page_owner_guid($group->getGUID());
			elgg_push_breadcrumb($group->name, $group->getURL());
			elgg_push_breadcrumb($title);
			$content = elgg_view("groups/edit", array('entity' => $group));
		} else {
			$content = elgg_echo('groups:noaccess');
		}

	if($group->banner){
			$header = elgg_view('carousel/carousel', 
				array('items'=> array(
					new \ElggObject(array('ext_bg' => elgg_get_site_url().'groups/banner/'.$group->guid, 'top_offset'=>$group->banner_position))
				)));
			$class = "group-banner group-banner-editable";
		}
	}
	
	$params = array(
		'content_header' => $header,
		'content' => $content,
		'title' => $title,
		'filter' => '',
		'class' => $class
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body, 'default', array('class'=>$class));
}

/**
 * Group invitations for a user
 */
function groups_handle_invitations_page() {
	gatekeeper();

	$user = elgg_get_page_owner_entity();

	$title = elgg_echo('groups:invitations');
	elgg_push_breadcrumb($title);

	// @todo temporary workaround for exts #287.
	$invitations = groups_get_invited_groups(elgg_get_logged_in_user_guid());
	$content = elgg_view('groups/invitationrequests', array('invitations' => $invitations));

	$nav = elgg_view('groups/filter', array('selected'=>'invitiations'));

	$params = array(
		'header' => elgg_view_title($title) . $nav,
		'content' => $content,
		'title' => $title,
	);
	$body = elgg_view_layout('one_sidebar', $params);

	echo elgg_view_page($title, $body);
}

/**
 * Group profile page
 *
 * @param int $guid Group entity GUID
 */
function groups_handle_profile_page($guid) {
	elgg_set_page_owner_guid($guid);
	
	// turn this into a core function
	global $autofeed;
	$autofeed = true;
	
	$header = false;

	elgg_push_context('group_profile');

	$group = get_entity($guid);
	if (!$group) {
		forward('groups/all');
	}
	
	//set up for facebook
	minds_set_metatags('og:type', 'profile');
	minds_set_metatags('og:url',$group->getURL());
	minds_set_metatags('og:title',$group->title);


	elgg_push_breadcrumb($group->name);

	groups_register_profile_buttons($group);

	
	if (group_gatekeeper(false)) {
		$content = elgg_view('groups/profile/activity', array('entity' => $group));
	} else {
		$content = elgg_view('groups/profile/closed_membership');
	}
	
	
	$sidebar = elgg_view('groups/sidebar', array('entity'=>$group));
	
	if (group_gatekeeper(false)) {	
		$sidebar .= elgg_view('groups/sidebar/members', array('entity' => $group));

	}
	
	if($group->banner){
		$header = elgg_view('carousel/carousel', 
			array('items'=> array(
				new \ElggObject(array('ext_bg' => elgg_get_site_url().'groups/banner/'.$group->guid, 'top_offset'=>$group->banner_position))
			)));
		$class = "group-banner";
	}

	$params = array(
		'content_header' => $header, 
		'content' => $content,
		'sidebar' => $sidebar,
		'title' => $group->name,
		'filter' => '',
		'class' => 'group-profile ' . $class,
		'hide_ads' => true
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($group->name, $body, 'default', array('class'=>$class));
}

/**
 * Group activity page
 *
 * @param int $guid Group entity GUID
 */
function groups_handle_activity_page($guid) {

	elgg_set_page_owner_guid($guid);

	$group = get_entity($guid,'group');
	if (!$group || !elgg_instanceof($group, 'group')) {
		forward();
	}

	group_gatekeeper();

	$title = elgg_echo('groups:activity');

	elgg_push_breadcrumb($group->name, $group->getURL());
	elgg_push_breadcrumb($title);

	$db_prefix = elgg_get_config('dbprefix');
	//we want to grab activity from entities where this group is the container guid
	$entities = elgg_get_entities(array('container_guid'=>$guid, 'limit'=>0));
	foreach($entities as $entity){
		$entity_guids[] = $entity->getGUID();
	}
	/*$content = elgg_list_river(array(
		'joins' => array("JOIN {$db_prefix}entities e ON e.guid = rv.object_guid"),
		'wheres' => array("e.container_guid = $guid")
	));*/
	$content = minds_elastic_list_news(array('object_guids'=>$entity_guids));
	if (!$content) {
		$content = '<p>' . elgg_echo('groups:activity:none') . '</p>';
	}
	
	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * Group members page
 *
 * @param int $guid Group entity GUID
 */
function groups_handle_members_page($guid) {

	elgg_set_page_owner_guid($guid);

	$group = get_entity($guid, 'group');
	if (!$group || !elgg_instanceof($group, 'group')) {
		forward();
	}

	group_gatekeeper();

	$title = elgg_echo('groups:members:title', array($group->name));

	elgg_push_breadcrumb($group->name, $group->getURL());
	elgg_push_breadcrumb(elgg_echo('groups:members'));

	$members = $group->getMembers(100,get_input('offset', ''));

	$content =elgg_view_entity_list($members, array('full_view'=>false, 'list_class'=>'x2'));	

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
		'list_class' => 'x2',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * Invite users to a group
 *
 * @param int $guid Group entity GUID
 */
function groups_handle_invite_page($guid) {
	gatekeeper();

	elgg_set_page_owner_guid($guid);

	$group = get_entity($guid, 'group');

	$title = elgg_echo('groups:invite:title');

	elgg_push_breadcrumb($group->name, $group->getURL());
	elgg_push_breadcrumb(elgg_echo('groups:invite'));

	if ($group && $group->canEdit()) {
		$content = elgg_view_form('groups/invite', array(
			'id' => 'invite_to_group',
			'class' => 'elgg-form-alt mtm',
		), array(
			'entity' => $group,
		));
	} else {
		$content .= elgg_echo('groups:noaccess');
	}

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * Manage requests to join a group
 * 
 * @param int $guid Group entity GUID
 */
function groups_handle_requests_page($guid) {

	gatekeeper();

	elgg_set_page_owner_guid($guid);

	$group = get_entity($guid, 'group');

	$title = elgg_echo('groups:membershiprequests');

	if ($group && $group->canEdit()) {
		elgg_push_breadcrumb($group->name, $group->getURL());
		elgg_push_breadcrumb($title);
		
		$requests = elgg_get_entities_from_relationship(array(
			'type' => 'user',
			'relationship' => 'membership_request',
			'relationship_guid' => $guid,
			'inverse_relationship' => true,
			'limit' => 0,
		));
		$content = elgg_view('groups/membershiprequests', array(
			'requests' => $requests,
			'entity' => $group,
		));

	} else {
		$content = elgg_echo("groups:noaccess");
	}

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * Registers the buttons for title area of the group profile page
 *
 * @param ElggGroup $group
 */
function groups_register_profile_buttons($group) {

	$actions = array();

	// group owners
	if ($group->canEdit()) {
		// edit and invite
		$url = elgg_get_site_url() . "groups/edit/{$group->getGUID()}";
		$actions[$url] = 'groups:edit';
		$url = elgg_get_site_url() . "groups/invite/{$group->getGUID()}";
		$actions[$url] = 'groups:invite';
	}

	// group members
	if ($group->isMember(elgg_get_logged_in_user_entity())) {
		if ($group->getOwnerGUID() != elgg_get_logged_in_user_guid()) {
			// leave
			$url = elgg_get_site_url() . "action/groups/leave?group_guid={$group->getGUID()}";
			$url = elgg_add_action_tokens_to_url($url);
			$actions[$url] = 'groups:leave';
		}
	} elseif (elgg_is_logged_in()) {
		// join - admins can always join.
		$url = elgg_get_site_url() . "action/groups/join?group_guid={$group->getGUID()}";
		$url = elgg_add_action_tokens_to_url($url);
		if ($group->isPublicMembership() || $group->canEdit()) {
			$actions[$url] = 'groups:join';
		} else {
			// request membership
			$actions[$url] = 'groups:joinrequest';
		}
	}

	if ($actions) {
		foreach ($actions as $url => $text) {
			elgg_register_menu_item('title', array(
				'name' => $text,
				'href' => $url,
				'text' => elgg_echo($text),
				'link_class' => 'elgg-button elgg-button-action',
			));
		}
	}
}

/**
 * Prepares variables for the group edit form view.
 *
 * @param mixed $group ElggGroup or null. If a group, uses values from the group.
 * @return array
 */
function groups_prepare_form_vars($group = null) {
	$values = array(
		'name' => '',
		'membership' => ACCESS_PUBLIC,
		'vis' => ACCESS_PUBLIC,
		'guid' => null,
		'entity' => null
	);

	// handle customizable profile fields
	$fields = elgg_get_config('group');

	if ($fields) {
		foreach ($fields as $name => $type) {
			$values[$name] = '';
		}
	}

	// handle tool options
	$tools = elgg_get_config('group_tool_options');
	if ($tools) {
		foreach ($tools as $group_option) {
			$option_name = $group_option->name . "_enable";
			$values[$option_name] = $group_option->default_on ? 'yes' : 'no';
		}
	}

	// get current group settings
	if ($group) {
		foreach (array_keys($values) as $field) {
			if (isset($group->$field)) {
				$values[$field] = $group->$field;
			}
		}

		if ($group->access_id != ACCESS_PUBLIC && $group->access_id != ACCESS_LOGGED_IN) {
			// group only access - this is done to handle access not created when group is created
			$values['vis'] = ACCESS_PRIVATE;
		} else {
			$values['vis'] = $group->access_id;
		}

		$values['entity'] = $group;
	}

	// get any sticky form settings
	if (elgg_is_sticky_form('groups')) {
		$sticky_values = elgg_get_sticky_values('groups');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('groups');

	return $values;
}
