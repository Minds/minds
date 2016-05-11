<?php
/**
 * Elgg users
 * Functions to manage multiple or single users in an Elgg install
 *
 * @package Elgg.Core
 * @subpackage DataModel.User
 */

/// Map a username to a cached GUID
global $USERNAME_TO_GUID_MAP_CACHE;
$USERNAME_TO_GUID_MAP_CACHE = array();

/// Map a user code to a cached GUID
global $CODE_TO_GUID_MAP_CACHE;
$CODE_TO_GUID_MAP_CACHE = array();

/**
 * Return the user specific details of a user by a row.
 *
 * @param int $guid The ElggUser guid
 *
 * @return mixed
 * @access private
 * @deprecated - using standard entitiy to row now
 */
function get_user_entity_as_row($guid) {
	global $CONFIG;

	//$guid = (int)$guid;
	//return get_data_row("SELECT * from {$CONFIG->dbprefix}users_entity where guid=$guid");
}

/**
 * Create or update the entities table for a given user.
 * Call create_entity first.
 *
 * @param int    $guid     The user's GUID
 * @param string $name     The user's display name
 * @param string $username The username
 * @param string $password The password
 * @param string $salt     A salt for the password
 * @param string $email    The user's email address
 * @param string $language The user's default language
 * @param string $code     A code
 *
 * @return bool
 * @access private
 */
function create_user_entity(array $options = array()) {
	global $CONFIG;


	$defaults = array(	'type' => 'user',

				'guid' => 0,

				'name' => '',
				'username' => '',
				'password' => '',
				'salt' => '',
				'email' => '',
				'language' => '',
				'code' => '',

				'time_created' => time()
			);

	$options = array_merge($defaults, $options);

	$options = array_filter($options, 'strlen');//remove null values

	$options['username'] = strtolower($options['username']);

	$db = new Minds\Core\Data\Call('entities');
	$result = $db->insert($options['guid'], $options);

	return $result;

	if ($result !== false) {
		$entity = get_entity($result, 'user');
		//if (elgg_trigger_event('create', $entity->type, $entity)) {
			return $guid;
		//} else {
		//	$entity->delete();
		//}
	}

	return false;
}

/**
 * Disables all of a user's entities
 *
 * @param int $owner_guid The owner GUID
 *
 * @return bool Depending on success
 */
function disable_user_entities($owner_guid) {
	global $CONFIG;
	$owner_guid = (int) $owner_guid;
	if ($entity = get_entity($owner_guid)) {
		if (elgg_trigger_event('disable', $entity->type, $entity)) {
			if ($entity->canEdit()) {
				$query = "UPDATE {$CONFIG->dbprefix}entities
					set enabled='no' where owner_guid={$owner_guid}
					or container_guid = {$owner_guid}";

				$res = update_data($query);
				return $res;
			}
		}
	}

	return false;
}

/**
 * Ban a user
 *
 * @param int    $user_guid The user guid
 * @param string $reason    A reason
 *
 * @return bool
 */
function ban_user($user_guid, $reason = "") {
	global $CONFIG;

	$user = get_entity($user_guid, 'user');

	if (($user) && ($user->canEdit()) && ($user instanceof ElggUser)) {
		if (elgg_trigger_event('ban', 'user', $user)) {

			// Add reason
			$user->ban_reason = $reason;

			//set ban flag
			$user->banned = 'yes';

			// clear "remember me" cookie code so user cannot login in using it
			$user->code = "";

			$user->save();

			// invalidate memcache for this user
			static $newentity_cache;
			if ((!$newentity_cache) && (is_memcache_available())) {
				$newentity_cache = new ElggMemcache('new_entity_cache');
			}

			if ($newentity_cache) {
				$newentity_cache->delete($user_guid);
			}

			return true;

		}

		return FALSE;
	}

	return FALSE;
}

/**
 * Unban a user.
 *
 * @param int $user_guid Unban a user.
 *
 * @return bool
 */
function unban_user($user_guid) {
	global $CONFIG;

	$user = get_entity($user_guid, 'user');

	if (($user) && ($user->canEdit()) && ($user instanceof ElggUser)) {
		if (elgg_trigger_event('unban', 'user', $user)) {
			create_metadata($user_guid, 'ban_reason', '', '', 0, ACCESS_PUBLIC);

			$user->ban_reason = '';
			$user->banned = 'no';

			$user->save();

			// invalidate memcache for this user
			static $newentity_cache;
			if ((!$newentity_cache) && (is_memcache_available())) {
				$newentity_cache = new ElggMemcache('new_entity_cache');
			}

			if ($newentity_cache) {
				$newentity_cache->delete($user_guid);
			}
			return true;
		}

		return FALSE;
	}

	return FALSE;
}

/**
 * Makes user $guid an admin.
 *
 * @param int $user_guid User guid
 *
 * @return bool
 */
function make_user_admin($user_guid) {
	global $CONFIG;

	$user = get_entity($user_guid, 'user');

	if (($user) && ($user instanceof ElggUser) && ($user->canEdit())) {
		if (elgg_trigger_event('make_admin', 'user', $user)) {

			// invalidate memcache for this user
			static $newentity_cache;
			if ((!$newentity_cache) && (is_memcache_available())) {
				$newentity_cache = new ElggMemcache('new_entity_cache');
			}

			if ($newentity_cache) {
				$newentity_cache->delete($user_guid);
			}

			$user->admin = 'yes';
			$user->save();

			invalidate_cache_for_entity($user_guid);
			return true;
		}

		return FALSE;
	}

	return FALSE;
}

/**
 * Removes user $guid's admin flag.
 *
 * @param int $user_guid User GUID
 *
 * @return bool
 */
function remove_user_admin($user_guid) {
	global $CONFIG;

	$user = get_entity($user_guid, 'user');

	if (($user) && ($user instanceof ElggUser) && ($user->canEdit())) {
		if (elgg_trigger_event('remove_admin', 'user', $user)) {

			// invalidate memcache for this user
			static $newentity_cache;
			if ((!$newentity_cache) && (is_memcache_available())) {
				$newentity_cache = new ElggMemcache('new_entity_cache');
			}

			if ($newentity_cache) {
				$newentity_cache->delete($user_guid);
			}

			$user->admin = 'no';
			$user->save();
			invalidate_cache_for_entity($user_guid);
			return true;
		}

		return FALSE;
	}

	return FALSE;
}

/**
 * Get the sites this user is part of
 *
 * @param int $user_guid The user's GUID
 * @param int $limit     Number of results to return
 * @param int $offset    Any indexing offset
 *
 * @return ElggSite[]|false On success, an array of ElggSites
 */
function get_user_sites($user_guid, $limit = 10, $offset = 0) {
	//deprecated since cassandra rewrite
	return;
}

/**
 * Adds a user to another user's friend/subscription list.
 *
 * @param int $user_guid   The GUID of the friending user
 * @param int $friend_guid The GUID of the user to friend
 *
 * @return bool Depending on success
 */
function user_add_friend($user_guid, $friend_guid) {
	$user_guid = $user_guid;
	$friend_guid = $friend_guid;
	if ($user_guid == $friend_guid) {
		return false;
	}
	if (!$friend = get_entity($friend_guid, 'user')) {
		return false;
	}
	if (!$user = get_entity($user_guid, 'user')) {
		return false;
	}
	if ((!($user instanceof ElggUser)) || (!($friend instanceof ElggUser))) {
		return false;
	}

	//add this this users list of subscriptions
	$friends = new Minds\Core\Data\Call('friends');
	$friends->insert($user_guid, array($friend_guid => time()));
	//add user to friends list of subscriptions
	$friendsof = new Minds\Core\Data\Call('friendsof');
	$friendsof->insert($friend_guid, array($user_guid => time()));

	//hack - update session!
	if(elgg_is_logged_in()){
		unset($_SESSION['friends']);
       	//$_SESSION['friends'] = elgg_get_logged_in_user_entity()->getFriends(null, 200, 0, 'guids');
	}
	return true;
}

/**
 * Removes a user from another user's friends list.
 *
 * @param int $user_guid   The GUID of the friending user
 * @param int $friend_guid The GUID of the user on the friends list
 *
 * @return bool Depending on success
 */
function user_remove_friend($user_guid, $friend_guid) {
	$user_guid = $user_guid;
	$friend_guid = $friend_guid;

	// perform cleanup for access lists.
	/*$collections = get_user_access_collections($user_guid);
	if ($collections) {
		foreach ($collections as $collection) {
			remove_user_from_access_collection($friend_guid, $collection->id);
		}
	}*/

	$friends = new Minds\Core\Data\Call('friends');
	$friends->removeAttributes($user_guid, array($friend_guid));
	$friendsof = new Minds\Core\Data\Call('friendsof');
	$friendsof->removeAttributes($friend_guid, array($user_guid));

	//hack - update session!
	unset($_SESSION['friends']);
	//$SESSION['friends'] = elgg_get_logged_in_user_entity()->getFriends(null, 200, 0, 'guids');
	return true;

}

/**
 * Determines whether or not a user is another user's friend.
 *
 * @param int $user_guid   The GUID of the user
 * @param int $friend_guid The GUID of the friend
 *
 * @return bool
 */
function user_is_friend($user_guid, $friend_guid) {
	$friends = get_user_friends($user_guid, '', $limit = 10000, '', 'guids');
	if(is_array($friends) && isset($friends[$friend_guid])){
		return true;
	}
	return false;
}

/**
 * Obtains a given user's friends
 *
 * @param int    $user_guid The user's GUID
 * @param string $subtype   The subtype of users, if any
 * @param int    $limit     Number of results to return (default 10)
 * @param int    $offset    Indexing offset, if any
 *
 * @return ElggUser[]|false Either an array of ElggUsers or false, depending on success
 */
function get_user_friends($user_guid, $subtype = ELGG_ENTITIES_ANY_VALUE, $limit = 10,
$offset = "", $output = 'entities') {
	static $cache;
	if(!$cache){
		$cache = new ElggStaticVariableCache('friends');
	}

	if(!$row = $cache->load($user_guid)){
		$db = new Minds\Core\Data\Call('friends');
		$row = $db->getRow($user_guid, array('limit' => $limit, 'offset' => $offset));
		$cache->save($user_guid,$row);
	}

	if($output == 'entities'){
	//	$db = new Minds\Core\Data\Call('user');
	//	$guids = $db->getRows($row);
		$row = elgg_get_entities(array('type'=>'user', 'guids'=>array_keys($row)));
	}
	return $row;
}

/**
 * Obtains the people who have made a given user a friend
 *
 * @param int    $user_guid The user's GUID
 * @param string $subtype   The subtype of users, if any
 * @param int    $limit     Number of results to return (default 10)
 * @param int    $offset    Indexing offset, if any
 *
 * @return ElggUser[]|false Either an array of ElggUsers or false, depending on success
 */
function get_user_friends_of($user_guid, $subtype = ELGG_ENTITIES_ANY_VALUE, $limit = 10,
$offset = "", $output = 'entities') {

	if(!$user_guid)
		return false;

	static $cache;
	if(!$cache){
		$cache = new ElggStaticVariableCache('friendsof');
	}

    if(!$row = $cache->load($user_guid)){
		$db = new Minds\Core\Data\Call('friendsof');
		$row = $db->getRow($user_guid, array( 'limit' => $limit, 'offset' => $offset));
		$cache->save($user_guid, $row);
	}

	if($row && $output == 'entities'){
		$row = elgg_get_entities(array('type'=>'user', 'guids'=>array_keys($row)));
	}
	return $row;
}

/**
 * Obtains a list of objects owned by a user's friends
 *
 * @param int    $user_guid The GUID of the user to get the friends of
 * @param string $subtype   Optionally, the subtype of objects
 * @param int    $limit     The number of results to return (default 10)
 * @param int    $offset    Indexing offset, if any
 * @param int    $timelower The earliest time the entity can have been created. Default: all
 * @param int    $timeupper The latest time the entity can have been created. Default: all
 *
 * @return ElggObject[]|false An array of ElggObjects or false, depending on success
 */
function get_user_friends_objects($user_guid, $subtype = ELGG_ENTITIES_ANY_VALUE, $limit = 10,
$offset = 0, $timelower = 0, $timeupper = 0) {

	if ($friends = get_user_friends($user_guid, null, 999999, 0)) {
		$friendguids = array();
		foreach ($friends as $friend) {
			$friendguids[] = $friend->getGUID();
		}
		return elgg_get_entities(array(
			'type' => 'object',
			'subtype' => $subtype,
			'owner_guids' => $friendguids,
			'limit' => $limit,
			'offset' => $offset,
			'container_guids' => $friendguids,
			'created_time_lower' => $timelower,
			'created_time_upper' => $timeupper
		));
	}
	return FALSE;
}

/**
 * Counts the number of objects owned by a user's friends
 *
 * @param int    $user_guid The GUID of the user to get the friends of
 * @param string $subtype   Optionally, the subtype of objects
 * @param int    $timelower The earliest time the entity can have been created. Default: all
 * @param int    $timeupper The latest time the entity can have been created. Default: all
 *
 * @return int The number of objects
 */
function count_user_friends_objects($user_guid, $subtype = ELGG_ENTITIES_ANY_VALUE,
$timelower = 0, $timeupper = 0) {

	if ($friends = get_user_friends($user_guid, ELGG_ENTITIES_ANY_VALUE, 999999, 0)) {
		$friendguids = array();
		foreach ($friends as $friend) {
			$friendguids[] = $friend->getGUID();
		}
		return elgg_get_entities(array(
			'type' => 'object',
			'subtype' => $subtype,
			'owner_guids' => $friendguids,
			'count' => TRUE,
			'container_guids' => $friendguids,
			'created_time_lower' => $timelower,
			'created_time_upper' => $timeupper
		));
	}
	return 0;
}

/**
 * Displays a list of a user's friends' objects of a particular subtype, with navigation.
 *
 * @see elgg_view_entity_list
 *
 * @param int    $user_guid      The GUID of the user
 * @param string $subtype        The object subtype
 * @param int    $limit          The number of entities to display on a page
 * @param bool   $full_view      Whether or not to display the full view (default: true)
 * @param bool   $listtypetoggle Whether or not to allow you to flip to gallery mode (default: true)
 * @param bool   $pagination     Whether to display pagination (default: true)
 * @param int    $timelower      The earliest time the entity can have been created. Default: all
 * @param int    $timeupper      The latest time the entity can have been created. Default: all
 *
 * @return string
 */
function list_user_friends_objects($user_guid, $subtype = ELGG_ENTITIES_ANY_VALUE, $limit = 10, $full_view = true,
$listtypetoggle = true, $pagination = true, $timelower = 0, $timeupper = 0) {

	$offset = (int)get_input('offset');
	$limit = (int)$limit;
	$count = (int)count_user_friends_objects($user_guid, $subtype, $timelower, $timeupper);

	$entities = get_user_friends_objects($user_guid, $subtype, $limit, $offset,
		$timelower, $timeupper);

	return elgg_view_entity_list($entities, array(
		'count' => $count,
		'offset' => $offset,
		'limit' => $limit,
		'full_view' => $full_view,
		'archive_view' => elgg_get_context() == 'archive' ? true : false,
		'list_type_toggle' => $listtypetoggle,
		'pagination' => $pagination,
	));
}

/**
 * Get a user object from a GUID.
 *
 * This function returns an ElggUser from a given GUID.
 *
 * @param int $guid The GUID
 *
 * @return ElggUser|false
 */
function get_user($guid) {
	// Fixes "Exception thrown without stack frame" when db_select fails
	if (!empty($guid)) {
		$result = get_entity($guid, 'user');
	}

	if ((!empty($result)) && (!($result instanceof ElggUser))) {
		return false;
	}

	if (!empty($result)) {
		return $result;
	}

	return false;
}

/**
 * GET INDEX TO GUID
 */
function get_user_index_to_guid($index){
	try{
		$db = new Minds\Core\Data\Call('user_index_to_guid');
		$row = $db->getRow($index);
		if(!$row || !is_array($row)){
			return false;
		}
		foreach($row as $k=>$v){
			return $k;
		}
	}catch(Exception $e){
		return false;
	}
}

/**
 * Get user by username
 *
 * @param string $username The user's username
 *
 * @return ElggUser|false Depending on success
 */
function get_user_by_username($username) {
	global $CONFIG, $USERNAME_TO_GUID_MAP_CACHE, $DB;

	$username = strtolower($username);

	if(!$username){
		return false;
	}

	$guid = isset($USERNAME_TO_GUID_MAP_CACHE[$username]) ? $USERNAME_TO_GUID_MAP_CACHE[$username] : null;

	if(!$guid){
		$guid = get_user_index_to_guid($username);
	}

	$entity = get_entity($guid);
	if ($entity) {
		$USERNAME_TO_GUID_MAP_CACHE[$username] = $entity->guid;
	} else {
		$entity = false;
	}

	return $entity;
}

/**
 * Get user by session code
 *
 * @param string $code The session code
 *
 * @return ElggUser|false Depending on success
 */
function get_user_by_code($code) {
	global $CONFIG, $CODE_TO_GUID_MAP_CACHE;

	$index = new Minds\Core\Data\Call('user_index_to_guid');
	$guid = $index->getRow('code:'.$code);

    $entity = get_entity($guid, 'user');

	if ($entity) {
		$CODE_TO_GUID_MAP_CACHE[$code] = $entity->guid;
	}

	return $entity;
}

function get_user_by_cookie($cookie){

	try{
		$db = new Minds\Core\Data\Call('user_index_to_guid');
		$results = $db->getRow("cookie:$cookie");
	} catch(Exception $e){
		return false;
	}

	if(!$results || !is_array($results)){
		return false;
	}

	$user_guid = array_keys($results);
	$expires = $results[$user_guid[0]];

	if($expires >= time()){
		$user = new ElggUser($user_guid[0]);
		//check if the cookie has been tampered with, and it matches the users
		if($user->{"cookie:$cookie"} == $expires){
			return $user;
		}
	}

	return false;
}

/**
 * Get an array of users from an email address
 *
 * @param string $email Email address.
 *
 * @return array
 */
function get_user_by_email($email) {
	global $CONFIG;

        $guids = get_user_index_to_guid($email);

	if(is_array($guids)){
		foreach($guids as $guid){
      			$entities[] = get_entity($guid);
		}
	} else {
		$entities[] = get_entity($guids);
	}

	if($entities[0] == null){
		return false;
	}

	return $entities;

}

/**
 * A function that returns a maximum of $limit users who have done something within the last
 * $seconds seconds or the total count of active users.
 *
 * @param int  $seconds Number of seconds (default 600 = 10min)
 * @param int  $limit   Limit, default 10.
 * @param int  $offset  Offset, default 0.
 * @param bool $count   Count, default false.
 *
 * @return mixed
 */
function find_active_users($seconds = 600, $limit = 10, $offset = 0, $count = false) {
	$seconds = (int)$seconds;
	$limit = (int)$limit;
	$offset = (int)$offset;
	$params = array('seconds' => $seconds, 'limit' => $limit, 'offset' => $offset, 'count' => $count);
	$data = elgg_trigger_plugin_hook('find_active_users', 'system', $params, NULL);
	if (!$data) {
		global $CONFIG;

		$time = time() - $seconds;

		$data = elgg_get_entities(array(
			'type' => 'user',
			'limit' => $limit,
			'offset' => $offset,
			'count' => $count,
			'joins' => array("join {$CONFIG->dbprefix}users_entity u on e.guid = u.guid"),
			'wheres' => array("u.last_action >= {$time}"),
			'order_by' => "u.last_action desc"
		));
	}
	return $data;
}

/**
 * Generate and send a password request email to a given user's registered email address.
 *
 * @param int $user_guid User GUID
 *
 * @return bool
 */
function send_new_password_request($user_guid) {

	global $CONFIG;

	$user = get_entity($user_guid);
	if ($user instanceof ElggUser) {
		// generate code
		$code = generate_random_cleartext_password();
		$user->setPrivateSetting('passwd_conf_code', $code);

		// generate link
		$link = elgg_get_site_url() . "resetpassword?u=$user_guid&c=$code";

		// generate email
		$email = elgg_echo('email:resetreq:body', array($user->name, $_SERVER['REMOTE_ADDR'], $link));

		return notify_user($user->guid, elgg_get_site_entity()->guid,
			elgg_echo('email:resetreq:subject'), $email, array(), 'email');
	}

	return false;
}

/**
 * Low level function to reset a given user's password.
 *
 * This can only be called from execute_new_password_request().
 *
 * @param int    $user_guid The user.
 * @param string $password  Text (which will then be converted into a hash and stored)
 *
 * @return bool
 */
function force_user_password_reset($user_guid, $password) {
	$user = new ElggUser($user_guid);
	if ($user instanceof ElggUser) {
		$ia = elgg_set_ignore_access();

		$user->salt = generate_random_cleartext_password();
		$hash = generate_user_password($user, $password);
        $user->password = $hash;
        $user->override_password = true;
		$result = (bool)$user->save();

		elgg_set_ignore_access($ia);

		return $result;
	}

	return false;
}

/**
 * Validate and execute a password reset for a user.
 *
 * @param int    $user_guid The user id
 * @param string $conf_code Confirmation code as sent in the request email.
 *
 * @return mixed
 */
function execute_new_password_request($user_guid, $conf_code) {
	global $CONFIG;

	$user_guid = (int)$user_guid;
	$user = get_entity($user_guid,'user');

	if ($user instanceof ElggUser) {
		$saved_code = $user->getPrivateSetting('passwd_conf_code');

		if ($saved_code && $saved_code == $conf_code) {
			$password = generate_random_cleartext_password();

			if (force_user_password_reset($user_guid, $password)) {
				remove_private_setting($user_guid, 'passwd_conf_code');
				// clean the logins failures
				reset_login_failure_count($user_guid);

				$email = elgg_echo('email:resetpassword:body', array($user->name, $password));

				return notify_user($user->guid, $CONFIG->site->guid,
					elgg_echo('email:resetpassword:subject'), $email, array(), 'email');
			}
		}
	}

	return FALSE;
}

/**
 * Simple function that will generate a random clear text password
 * suitable for feeding into generate_user_password().
 *
 * @see generate_user_password
 *
 * @return string
 */
function generate_random_cleartext_password() {
	return substr(hash('sha256', microtime() . rand()), 0, 8);
}

/**
 * Generate a password for a user.
 *
 * @param ElggUser $user     The user this is being generated for.
 * @param string   $password Password in clear text
 *
 * @return string
 */
function generate_user_password(ElggUser $user, $password, $algo = 'sha256') {
	if($algo == 'md5')
			return md5($password . $user->salt);
	return hash('sha256', $password . $user->salt);
}

/**
 * Simple function which ensures that a username contains only valid characters.
 *
 * This should only permit chars that are valid on the file system as well.
 *
 * @param string $username Username
 *
 * @return bool
 * @throws RegistrationException on invalid
 */
function validate_username($username) {
	global $CONFIG;

	// Basic, check length
	if (!isset($CONFIG->minusername)) {
		$CONFIG->minusername = 4;
	}

	if (strlen($username) < $CONFIG->minusername) {
		$msg = elgg_echo('registration:usernametooshort', array($CONFIG->minusername));
		throw new RegistrationException($msg);
	}

	// username in the database has a limit of 128 characters
	if (strlen($username) > 128) {
		$msg = elgg_echo('registration:usernametoolong', array(128));
		throw new RegistrationException($msg);
	}

	// Blacklist non-alpha chars
	if (preg_match('/[^a-zA-Z0-9_]+/', $username)) {
	    throw new RegistrationException(elgg_echo('Invalid username! Alphanumerics only please.'));
	}

	// Blacklist for bad characters (partially nicked from mediawiki)
	$blacklist = '/[' .
		'\x{0080}-\x{009f}' . // iso-8859-1 control chars
		'\x{00a0}' .          // non-breaking space
		'\x{2000}-\x{200f}' . // various whitespace
		'\x{2028}-\x{202f}' . // breaks and control chars
		'\x{3000}' .          // ideographic space
		'\x{e000}-\x{f8ff}' . // private use
		']/u';

	if (
		preg_match($blacklist, $username)
	) {
		// @todo error message needs work
		throw new RegistrationException(elgg_echo('registration:invalidchars'));
	}

	// Belts and braces
	// @todo Tidy into main unicode
	$blacklist2 = '\'/\\"*& ?#%^(){}[]~?<>;|¬`+=';

	for ($n = 0; $n < strlen($blacklist2); $n++) {
		if (strpos($username, $blacklist2[$n]) !== false) {
			$msg = elgg_echo('registration:invalidchars', array($blacklist2[$n], $blacklist2));
			$msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
			throw new RegistrationException($msg);
		}
	}

	$result = true;
	return elgg_trigger_plugin_hook('registeruser:validate:username', 'all',
		array('username' => $username), $result);
}

/**
 * Simple validation of a password.
 *
 * @param string $password Clear text password
 *
 * @return bool
 * @throws RegistrationException on invalid
 */
function validate_password($password) {
	global $CONFIG;

	if (!isset($CONFIG->min_password_length)) {
		$CONFIG->min_password_length = 6;
	}

	if (strlen($password) < $CONFIG->min_password_length) {
		$msg = elgg_echo('registration:passwordtooshort', array($CONFIG->min_password_length));
		throw new RegistrationException($msg);
	}

	$result = true;
	return elgg_trigger_plugin_hook('registeruser:validate:password', 'all',
		array('password' => $password), $result);
}

/**
 * Simple validation of a email.
 *
 * @param string $address Email address
 *
 * @throws RegistrationException on invalid
 * @return bool
 */
function validate_email_address($address) {
	if (!is_email_address($address)) {
		throw new RegistrationException(elgg_echo('registration:notemail'));
	}

	// Got here, so lets try a hook (defaulting to ok)
	$result = true;
	return elgg_trigger_plugin_hook('registeruser:validate:email', 'all',
		array('email' => $address), $result);
}

/**
 * Registers a user, returning false if the username already exists
 *
 * @param string $username              The username of the new user
 * @param string $password              The password
 * @param string $name                  The user's display name
 * @param string $email                 Their email address
 * @param bool   $allow_multiple_emails Allow the same email address to be
 *                                      registered multiple times?
 * @param int    $friend_guid           GUID of a user to friend once fully registered
 * @param string $invitecode            An invite code from a friend
 *
 * @return int|false The new user's GUID; false on failure
 * @throws RegistrationException
 */
function register_user($username, $password, $name, $email,
$allow_multiple_emails = false, $friend_guid = 0, $invitecode = '') {

	// no need to trim password.
	$username = trim($username);
	$name = trim(strip_tags($name));
	$email = trim($email);

	// A little sanity checking
	if (empty($username)
	|| empty($password)
	|| empty($name)
	|| empty($email)) {
		return false;
	}

	// Make sure a user with conflicting details hasn't registered and been disabled
	$access_status = access_get_show_hidden_status();
	access_show_hidden_entities(true);

	if (!validate_email_address($email)) {
		throw new RegistrationException(elgg_echo('registration:emailnotvalid'));
	}

	if (!validate_password($password)) {
		throw new RegistrationException(elgg_echo('registration:passwordnotvalid'));
	}

	if (!validate_username($username)) {
		throw new RegistrationException(elgg_echo('registration:usernamenotvalid'));
	}

	if ($user = get_user_by_username($username)) {
		throw new RegistrationException(elgg_echo('registration:userexists'));
	}

	if ((!$allow_multiple_emails) && (get_user_by_email($email))) {
		throw new RegistrationException(elgg_echo('registration:dupeemail'));
	}

	access_show_hidden_entities($access_status);

	// Create user
	$user = new Minds\Entities\User();
	$user->username = $username;
	$user->setEmail($email);
	$user->name = $name;
	$user->access_id = ACCESS_PUBLIC;
	$user->salt = generate_random_cleartext_password(); // Note salt generated before password!
	$user->password = generate_user_password($user, $password);
	$user->owner_guid = 0; // Users aren't owned by anyone, even if they are admin created.
	$user->container_guid = 0; // Users aren't contained by anyone, even if they are admin created.
	$user->language = get_current_language();
	$guid = $user->save();

	$user->enable();
	/*// If $friend_guid has been set, make mutual friends
	if ($friend_guid) {
		if ($friend_user = get_user($friend_guid)) {
			if ($invitecode == generate_invite_code($friend_user->username)) {
				$user->addFriend($friend_guid);
				$friend_user->addFriend($user->guid);

				// @todo Should this be in addFriend?
				add_to_river('river/relationship/friend/create', 'friend', $user->getGUID(), $friend_guid);
				add_to_river('river/relationship/friend/create', 'friend', $friend_guid, $user->getGUID());
			}
		}
	}*/

	// Turn on email notifications by default
	//set_user_notification_setting($user->getGUID(), 'email', true);

	return $user;
}

/**
 * Generates a unique invite code for a user
 *
 * @param string $username The username of the user sending the invitation
 *
 * @return string Invite code
 */
function generate_invite_code($username) {
	$secret = datalist_get('__site_secret__');
	return hash('sha256', $username . $secret);
}

/**
 * Set the validation status for a user.
 *
 * @param int    $user_guid The user's GUID
 * @param bool   $status    Validated (true) or unvalidated (false)
 * @param string $method    Optional method to say how a user was validated
 * @return bool
 * @since 1.8.0
 */
function elgg_set_user_validation_status($user_guid, $status, $method = '') {
	$user = get_entity($user_guid, 'user');
	if($user){
		$user->validated = $status;
		$user->validated_method = $method;
		$user->save();
		return true;
	}
	return false;
}

/**
 * Gets the validation status of a user.
 *
 * @param int $user_guid The user's GUID
 * @return bool|null Null means status was not set for this user.
 * @since 1.8.0
 */
function elgg_get_user_validation_status($user_guid) {
	$user = get_entity($user_guid, 'user');
	if($user && $user->validated == 1){
		return true;
	}
	return false;
}

/**
 * Sets the last action time of the given user to right now.
 *
 * @param int $user_guid The user GUID
 *
 * @return void
 */
function set_last_action($user_guid) {
	$user_guid = (int) $user_guid;
	global $CONFIG;
	$time = time();

	$query = "UPDATE {$CONFIG->dbprefix}users_entity
		set prev_last_action = last_action,
		last_action = {$time} where guid = {$user_guid}";

	//execute_delayed_write_query($query);
}

/**
 * Sets the last logon time of the given user to right now.
 *
 * @param int $user_guid The user GUID
 *
 * @return void
 */
function set_last_login($user_guid) {
	$user_guid = (int) $user_guid;
	global $CONFIG;
	$time = time();

	$user = new ElggUser($user_guid);
	$user->last_login = $time;
	$user->ip = $_SERVER['REMOTE_ADDR'];
	$user->save();
	//execute_delayed_write_query($query);
}
