<?php
/**
 * Elgg Webservices plugin 
 * 
 * @package Elgg Standardised Web Services
 * @author Mark Harding
 *
 */
 /**
 * Web service to retrieve list of groups
 *
 * @param string $username Username
 * @param string $limit    Number of users to return
 * @param string $offset   Indexing offset, if any
 *
 * @return array
 */    				
function group_get_groups($context, $username, $limit, $offset){
	if(!$username){
		$user = get_loggedin_user();
	} else {
		$user = get_user_by_username($username);
	}
	
	if($context == "all"){
		$groups = elgg_get_entities(array(
											'types' => 'group',
											'limit' => $limit,
											'full_view' => FALSE,
											));
	}
	if($context == "mine" || $context ==  "user"){
		$groups = $user->getGroups();
	}
	if($context == "owned"){
		$groups = elgg_get_entities(array(
											'types' => 'group',
											'owner_guid' => $user->guid,
											'limit' => $limit,
											'full_view' => FALSE,
											));
	}
	if($context == "featured"){
		$groups = elgg_get_entities_from_metadata(array(
														'metadata_name' => 'featured_group',
														'metadata_value' => 'yes',
														'types' => 'group',
														'limit' => 10,
														));
	}
	
	
	if($groups){
	foreach($groups as $single){
		$group['guid'] = $single->guid;
		$group['name'] = $single->name;
		$group['members'] = count($single->getMembers($limit=0));
		$group['avatar_url'] = get_entity_icon_url($single,'small');
		$return[] = $group;
	}
	} else {
		$msg = elgg_echo('groups:none');
		throw new InvalidParameterException($msg);
	}
	return $return;
}
expose_function('group.get_groups',
				"group_get_groups",
				array(	'context' => array ('type' => 'string', 'required' => false, 'default' => elgg_is_logged_in() ? "user" : "all"),
						'username' => array ('type' => 'string', 'required' => false),
						'limit' => array ('type' => 'int', 'required' => false),
						'offset' => array ('type' => 'int', 'required' => false),
					),
				"Get groups use is a member of",
				'GET',
				false,
				false);	
				
 /**
 * Web service for joining a group
 *
 * @param string $username username of author
 * @param string $groupid  GUID of the group
 *
 * @return bool
 */
function get_group($guid) {
	$group = get_entity($guid);
	if(!$group){
		throw new InvalidParameterException('groups:notfound');
	}
	$owner = $group->getOwnerEntity();
	
	$group_fields = elgg_get_config('group');
	
	foreach ($group_fields as $key => $type) {
			$group_field[$key]['label'] = elgg_echo('profile:'.$key);
			$group_field[$key]['type'] = $type;
			if(is_array($group->$key)){
			$group_field[$key]['value'] = $user->$key;

			} else {
			$group_field[$key]['value'] = strip_tags($group->$key);
			}
	}
	
	
	
	$group_info['name'] = $group->name;
	$group_info['owner_name'] = $owner->name;
	$group_info['members_count'] = count($group->getMembers($limit=0));
	$group_info['fields'] = $group_field;
	$group_info['avatar_url'] = get_entity_icon_url($group,'medium');
	global $CONFIG;
	$group_info['enabled_options'] = $CONFIG->group_tool_options;	
	return $group_info;
	
} 
			
expose_function('group.get',
				"get_group",
				array('guid' => array ('type' => 'int'),
					),
				"Get group",
				'GET',
				false,
				false);
 /**
 * Web service for joining a group
 *
 * @param string $username username of author
 * @param string $groupid  GUID of the group
 *
 * @return bool
 */
function group_join($username, $groupid) {
	$user = get_user_by_username($username);
	if (!$user) {
		throw new InvalidParameterException('registration:usernamenotvalid');
	}
	
	$group = get_entity($groupid);
	$return['success'] = false;
	if (($user instanceof ElggUser) && ($group instanceof ElggGroup)) {
		// join or request
		$join = false;
		if ($group->isPublicMembership() || $group->canEdit($user->guid)) {
			// anyone can join public groups and admins can join any group
			$join = true;
		} else {
			if (check_entity_relationship($group->guid, 'invited', $user->guid)) {
				// user has invite to closed group
				$join = true;
			}
		}

		if ($join) {
			if (groups_join_group($group, $user)) {
				$return['success'] = true;
				$return['message'] = elgg_echo("groups:joined");
			} else {
				$return['message'] = elgg_echo("groups:cantjoin");
			}
		} else {
			add_entity_relationship($user->guid, 'membership_request', $group->guid);

			// Notify group owner
			$url = "{$CONFIG->url}mod/groups/membershipreq.php?group_guid={$group->guid}";
			$subject = elgg_echo('groups:request:subject', array(
				$user->name,
				$group->name,
			));
			$body = elgg_echo('groups:request:body', array(
				$group->getOwnerEntity()->name,
				$user->name,
				$group->name,
				$user->getURL(),
				$url,
			));
			if (notify_user($group->owner_guid, $user->getGUID(), $subject, $body)) {
				$return['success'] = true;
				$return['message'] = elgg_echo("groups:joinrequestmade");
			} else {
				$return['message'] = elgg_echo("groups:joinrequestnotmade");
			}
		}
	} else {
		$return['message'] = elgg_echo("groups:cantjoin");
	}
	return $return;
} 
				
expose_function('group.join',
				"group_join",
				array('username' => array ('type' => 'string'),
						'groupid' => array ('type' => 'string'),
					),
				"Join a group",
				'POST',
				true,
				false);
				
 /**
 * Web service for leaving a group
 *
 * @param string $username username of author
 * @param string $groupid  GUID of the group
 *
 * @return bool
 */
function group_leave($username, $groupid) {
	if(!$username){
		$user = get_loggedin_user();
	} else {
		$user = get_user_by_username($username);
	}
	if (!$user) {
		throw new InvalidParameterException('registration:usernamenotvalid');
	}
	
	$group = get_entity($groupid);
	$return['success'] = false;
	set_page_owner($group->guid);
	if (($user instanceof ElggUser) && ($group instanceof ElggGroup)) {
		if ($group->getOwnerGUID() != elgg_get_logged_in_user_guid()) {
			if ($group->leave($user)) {
				$return['success'] = true;
				$return['message'] = elgg_echo("groups:left");
			} else {
				$return['message'] = elgg_echo("groups:cantleave");
			}
		} else {
			$return['message'] = elgg_echo("groups:cantleave");
		}
	} else {
		$return['message'] = elgg_echo("groups:cantleave");
	}
	return $return;
} 
				
expose_function('group.leave',
				"group_leave",
				array('username' => array ('type' => 'int'),
						'groupid' => array ('type' => 'string'),
					),
				"leave a group",
				'POST',
				true,
				false);

function group_save($groupid, $username){
	if(!$username){
		$user = get_loggedin_user();
	} else {
		$user = get_user_by_username($username);
	}
	if (!$user) {
		throw new InvalidParameterException('registration:usernamenotvalid');
	}
}
expose_function('group.save',
				"group_save",
				array(
						'groupid' => array ('type' => 'int'),
						'username' => array ('type' => 'string', 'required' => false),
					),
				"leave a group",
				'POST',
				true,
				false);

			
 /**
 * Web service for posting a new topic to a group
 *
 * @param string $username       username of author
 * @param string $groupid        GUID of the group
 * @param string $title          Title of new topic
 * @param string $description    Content of the post
 * @param string $status         status of the post
 * @param string $access_id      Access ID of the post
 *
 * @return bool
 */
function group_forum_save_post($groupid, $title, $desc, $tags, $status, $access_id ,$username) {
	if(!$username){
		$user = get_loggedin_user();
	} else {
		$user = get_user_by_username($username);
	}
	if (!$user) {
		throw new InvalidParameterException('registration:usernamenotvalid');
	}
	$group = get_entity($groupid);
	if (!$group) {
		throw new InvalidParameterException('group:notfound');
	}
	$return['success'] = false;
	// make sure user has permissions to write to container
	if (!can_write_to_container($user->guid, $groupid, "all", "all")) {
		$return['message'] = elgg_echo('groups:permissions:error');
	}
	
	$topic = new ElggObject();
	$topic->subtype = 'groupforumtopic';
	$topic->owner_guid = $user->guid;
	$topic->title = $title;
	$topic->description = $desc;
	$topic->status = $status;
	$topic->access_id = $access_id;
	$topic->container_guid = $groupid;
			
	$tags = explode(",", $tags);
	$topic->tags = $tags;

	$result = $topic->save();

	if (!$result) {
		$return['message'] = elgg_echo('discussion:error:notsaved');
	} else {
		$return['success'] = true;
		$return['message'] = elgg_echo('discussion:topic:created');
	}
	return $return;
} 
				
expose_function('group.forum.save_post',
				"group_forum_save_post",
				array(
						'groupid' => array ('type' => 'int'),
						'title' => array ('type' => 'string'),
						'desc' => array ('type' => 'string'),
						'tags' => array ('type' => 'string', 'required' => false, 'default'=>' '),
						'status' => array ('type' => 'string', 'required' => false, 'default'=>"published"),
						'access_id' => array ('type' => 'int', 'required' => false, 'default'=>ACCESS_DEFAULT),
						'username' => array ('type' => 'string', 'required' =>false),
					),
				"Post to a group",
				'POST',
				true,
				true);
				
/**
 * Web service for deleting a topic from a group
 *
 * @param string $username username of author
 * @param string $topicid  Topic ID
 *
 * @return bool
 */
function group_forum_delete_post( $topicid, $username) {
	$topic = get_entity($topicid);
	
	if (!$topic || !$topic->getSubtype() == "groupforumtopic") {
		$msg = elgg_echo('discussion:error:notdeleted');
		throw new InvalidParameterException($msg);
	}

	if(!$username){
		$user = get_loggedin_user();
	} else {
		$user = get_user_by_username($username);
	}
	if (!$user) {
		throw new InvalidParameterException('registration:usernamenotvalid');
	}

	if (!$topic->canEdit($user->guid)) {
		$msg = elgg_echo('discussion:error:permissions');
		throw new InvalidParameterException($msg);
	}

	$container = $topic->getContainerEntity();

	$result = $topic->delete();
	if ($result) {
		$return['success'] = true;
		$return['message'] = elgg_echo('discussion:topic:deleted');
	} else {
		$msg = elgg_echo('discussion:error:notdeleted');
		throw new InvalidParameterException($msg);
	}
	return $return;
} 
				
expose_function('group.forum.delete_post',
				"group_forum_delete_post",
				array(
						'topicid' => array ('type' => 'int'),
						'username' => array ('type' => 'string', 'required'=>false),
					),
				"Post to a group",
				'POST',
				true,
				true);
				
/**
 * Web service get latest post in a group
 *
 * @param string $groupid GUID of the group
 * @param string $limit   (optional) default 10
 * @param string $offset  (optional) default 0
 *
 * @return bool
 */
function group_forum_get_posts($guid, $limit = 10, $offset = 0) {
	$group = get_entity($guid);
	if (!$group) {
		return elgg_echo('group:notfound');
	}
	
	$options = array(
		'type' => 'object',
		'subtype' => 'groupforumtopic',
		'container_guid' => $guid,
		'limit' => $limit,
		'offset' => $offset,
		'full_view' => false,
		'pagination' => false,
	);
	$content = elgg_get_entities($options);
	if($content) {
		foreach($content as $single ) {
			$post['guid'] = $single->guid;
			$post['title'] = $single->title;
			$post['description'] = strip_tags($single->description);
			$user = get_entity($single->owner_guid);
			$post['owner']['guid'] = $user->guid;
			$post['owner']['name'] = $user->name;
			$post['owner']['username'] = $user->username;
			$post['owner']['avatar_url'] = get_entity_icon_url($user,'small');
			$post['container_guid'] = $single->container_guid;
			$post['access_id'] = $single->access_id;
			$post['time_created'] = (int)$single->time_created;
			$post['time_updated'] = (int)$single->time_updated;
			$post['last_action'] = (int)$single->last_action;
			$return[] = $post;
		}
	}
	else {
		$msg = elgg_echo('discussion:topic:notfound');
		throw new InvalidParameterException($msg);
	}
	return $return;
} 
				
expose_function('group.forum.get_posts',
				"group_forum_get_posts",
				array('guid' => array ('type' => 'int'),
					  'limit' => array ('type' => 'int', 'required' => false),
					  'offset' => array ('type' => 'int', 'required' => false),
					),
				"Get posts from a group",
				'GET',
				false,
				false);
/**
 * Web service get single post from a group forum
 *
 * @param string $groupid GUID of the group
 * @param string $limit   (optional) default 10
 * @param string $offset  (optional) default 0
 *
 * @return bool
 */
function group_forum_get_post($guid, $limit = 10, $offset = 0) {
	$discussion = get_entity($guid);
	
			$post['guid'] = $discussion->guid;
			$post['title'] = $discussion->title;
			$post['description'] = strip_tags($discussion->description);
			$user = get_entity($discussion->owner_guid);
			$post['owner']['guid'] = $user->guid;
			$post['owner']['name'] = $user->name;
			$post['owner']['username'] = $user->username;
			$post['owner']['avatar_url'] = get_entity_icon_url($user,'small');
			$post['container_guid'] = $discussion->container_guid;
			$post['access_id'] = $discussion->access_id;
			$post['time_created'] = (int)$discussion->time_created;
			$post['time_updated'] = (int)$discussion->time_updated;
			$post['last_action'] = (int)$discussion->last_action;
	
	if(!$discussion) {
		$msg = elgg_echo('discussion:topic:notfound');
		throw new InvalidParameterException($msg);
	}
	return $post;
} 
				
expose_function('group.forum.get_post',
				"group_forum_get_post",
				array('guid' => array ('type' => 'int'),
					  'limit' => array ('type' => 'int', 'required' => false),
					  'offset' => array ('type' => 'int', 'required' => false),
					),
				"Get a single post from a group forum",
				'GET',
				false,
				false);

/**
 * Web service get replies on a post
 *
 * @param string $postid GUID of the group
 * @param string $limit   (optional) default 10
 * @param string $offset  (optional) default 0
 *
 * @return bool
 */
function group_forum_get_replies($guid, $limit = 10, $offset = 0) {
	$topic = get_entity($guid);
	$options = array(
		'guid' => $guid,
		'annotation_name' => 'group_topic_post',
		'limit' => $limit,
		'offset' => $offset,
	);
	$content = elgg_get_annotations($options);
	if($content) {
		foreach($content as $single ) {
			$post['id']= $single->id;
			$post['value'] = strip_tags($single->value);
			$post['name'] = $single->name;
			$post['enabled'] = $single->enabled;
			$user = get_entity($single->owner_guid);
			$post['owner']['guid'] = $user->guid;
			$post['owner']['name'] = $user->name;
			$post['owner']['username'] = $user->username;
			$post['owner']['avatar_url'] = get_entity_icon_url($user,'small');
			$post['entity_guid'] = $single->entity_guid;
			$post['access_id'] = $single->access_id;
			$post['time_created'] =(int) $single->time_created;
			$post['name_id'] = $single->name_id;
			$post['value_id'] = $single->value_id;
			$post['value_type'] = $single->value_type;
			$return[] = $post;
		}
	}
	else {
		$msg = elgg_echo('discussion:reply:noreplies');
		throw new InvalidParameterException($msg);

	}
	return $return;
} 
				
expose_function('group.forum.get_replies',
				"group_forum_get_replies",
				array('guid' => array ('type' => 'int'),
					  'limit' => array ('type' => 'int', 'required' => false),
					  'offset' => array ('type' => 'int', 'required' => false),
					),
				"Get posts from a group",
				'GET',
				false,
				false);
				
/**
 * Web service post a reply
 *
 * @param string $username username
 * @param string $postid   GUID of post
 * @param string $text     text of reply
 *
 * @return bool
 */
function group_forum_save_reply( $postid, $text, $username) {
	$topic = get_entity($postid);
	if (!$topic) {
		$msg = elgg_echo('grouppost:nopost');
		throw new InvalidParameterException($msg);
	}

	if(!$username){
		$user = get_loggedin_user();
	} else {
		$user = get_user_by_username($username);
		if (!$user) {
			throw new InvalidParameterException('registration:usernamenotvalid');
		}
	}

	$group = $topic->getContainerEntity();
	if (!$group->canWriteToContainer($user)) {
		$msg = elgg_echo('groups:notmember');
		throw new InvalidParameterException($msg);
	}

	$reply = $topic->annotate('group_topic_post', $text, $topic->access_id, $user->guid);
	if ($reply) {
		add_to_river('river/annotation/group_topic_post/reply', 'reply', $user->guid, $topic->guid, "", 0, $reply_id);	
	} else {
		$msg = elgg_echo('grouppost:failure');
		throw new InvalidParameterException($msg);
	}
	
	$return['success'] = true;
	return $return;
} 
				
expose_function('group.forum.save_reply',
				"group_forum_save_reply",
				array(
						'postid' => array ('type' => 'string'),
						'text' => array ('type' => 'string'),
						'username' => array ('type' => 'string', 'required'=>false),
					),
				"Post a reply to a group",
				'POST',
				true,
				true);
				
/**
 * Web service delete a reply
 *
 * @param string $username username
 * @param string $id       Annotation ID of reply
 *
 * @return bool
 */
function group_forum_delete_reply( $id, $username) {
	$reply = elgg_get_annotation_from_id($id);

	if (!$reply || $reply->name != 'group_topic_post') {
		$msg = elgg_echo('discussion:reply:error:notdeleted');
		throw new InvalidParameterException($msg);
	}
	
	if(!$username){
		$user = get_loggedin_user();
	} else {
		$user = get_user_by_username($username);
		if (!$user) {
			throw new InvalidParameterException('registration:usernamenotvalid');
		}
	}

	if (!$reply->canEdit($user->guid)) {
		$msg = elgg_echo('discussion:error:permissions');
		throw new InvalidParameterException($msg);
	}

	$result = $reply->delete();
	if ($result) {
		$return['success'] = true;
		$return['message'] = elgg_echo('discussion:reply:deleted');
	} else {
		$msg = elgg_echo('discussion:reply:error:notdeleted');
		throw new InvalidParameterException($msg);
	}
	return $return;

} 
				
expose_function('group.forum.delete_reply',
				"group_forum_delete_reply",
				array(
						'id' => array ('type' => 'string'),
						'username' => array ('type' => 'string', 'required' =>false),
					),
				"Delete a reply from a group forum post",
				'POST',
				true,
				true);

/**
 * Web service to get activity feed for a group
 *
 * @param int $guid - the guid of the group
 * @param int $limit default 10
 * @param int $offset default 0
 *
 * @return bool
 */
function group_activity($guid, $limit = 10, $offset = 0) {
$group = get_entity($guid);			
if(!$guid){
	$msg = elgg_echo('groups:notfound');
	throw new InvalidParameterException($msg);
}

$db_prefix = elgg_get_config('dbprefix');


global $jsonexport;
	
$content = elgg_list_river(array(
	'limit' => $limit,
	'pagination' => false,
	'joins' => array("JOIN {$db_prefix}entities e1 ON e1.guid = rv.object_guid"),
	'wheres' => array("(e1.container_guid = $group->guid)"),
));

return $jsonexport['activity'];

}
expose_function('group.activity',
				"group_activity",
				array(
						'guid' => array ('type' => 'int'),
						'limit' => array ('type' => 'int', 'required' => false),
					  	'offset' => array ('type' => 'int', 'required' => false),
					),
				"Get the activity feed for a group",
				'GET',
				false,
				false);
				