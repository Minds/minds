<?php

/**
 * Elgg session management
 * Functions to manage logins
 *
 * @package Elgg.Core
 * @subpackage Session
 */

/** Elgg magic session */
global $SESSION;

/**
 * Return the current logged in user, or NULL if no user is logged in.
 *
 * If no user can be found in the current session, a plugin
 * hook - 'session:get' 'user' to give plugin authors another
 * way to provide user details to the ACL system without touching the session.
 *
 * @return ElggUser
 */
function elgg_get_logged_in_user_entity() {
	global $SESSION, $USERNAME_TO_GUID_MAP_CACHE;
	
	if (isset($SESSION)) {
		//cache username
		$USERNAME_TO_GUID_MAP_CACHE[$SESSION['username']] = $SESSION['guid'];
		return $SESSION['user'];
	}

	return NULL;
}

/**
 * Return the current logged in user by id.
 *
 * @see elgg_get_logged_in_user_entity()
 * @return int
 */
function elgg_get_logged_in_user_guid() {
	$user = elgg_get_logged_in_user_entity();
	if ($user) {
		return $user->guid;
	}

	return 0;
}

/**
 * Returns whether or not the user is currently logged in
 *
 * @return bool
 */
function elgg_is_logged_in() {
	$user = elgg_get_logged_in_user_entity();

	if ((isset($user)) && ($user instanceof ElggUser) && $user->guid) {
		return true;
	}

	return false;
}

/**
 * Returns whether or not the user is currently logged in and that they are an admin user.
 *
 * @return bool
 */
function elgg_is_admin_logged_in() {
	$user = elgg_get_logged_in_user_entity();

	if ((elgg_is_logged_in()) && $user->isAdmin()) {
		return TRUE;
	}

	return FALSE;
}

/**
 * Check if the given user has full access.
 *
 * @todo: Will always return full access if the user is an admin.
 *
 * @param int $user_guid The user to check
 *
 * @return bool
 * @since 1.7.1
 */
function elgg_is_admin_user($user_guid) {
	global $CONFIG;
	// cannot use magic metadata here because of recursion

	// must support the old way of getting admin from metadata
	// in order to run the upgrade to move it into the users table.
	$version = (int) datalist_get('version');

	if ($version < 2010040201) {
		
		$user = get_entity($user_guid, 'user');	
		if($user->isAdmin()){
			return true;
		}
	}
	return FALSE;
}

/**
 * Perform user authentication with a given username and password.
 *
 * @warning This returns an error message on failure. Use the identical operator to check
 * for access: if (true === elgg_authenticate()) { ... }.
 *
 *
 * @see login
 *
 * @param string $username The username
 * @param string $password The password
 *
 * @return true|string True or an error message on failure
 * @access private
 */
function elgg_authenticate($username, $password) {
	$pam = new ElggPAM('user');
	$credentials = array('username' => $username, 'password' => $password);
	$result = $pam->authenticate($credentials);
	if (!$result) {
		return $pam->getFailureMessage();
	}
	return true;
}

/**
 * Hook into the PAM system which accepts a username and password and attempts to authenticate
 * it against a known user.
 *
 * @param array $credentials Associated array of credentials passed to
 *                           Elgg's PAM system. This function expects
 *                           'username' and 'password' (cleartext).
 *
 * @return bool
 * @throws LoginException
 * @access private
 */
function pam_auth_userpass(array $credentials = array()) {

	if (!isset($credentials['username']) || !isset($credentials['password'])) {
		return false;
	}

	$user = get_user_by_username($credentials['username']);
	if (!$user) {
		throw new LoginException(elgg_echo('LoginException:UsernameFailure'));
	}

	if (check_rate_limit_exceeded($user->guid)) {
		throw new LoginException(elgg_echo('LoginException:AccountLocked'));
	}

	if ($user->password !== generate_user_password($user, $credentials['password'])) {
		log_login_failure($user->guid);
		throw new LoginException(elgg_echo('LoginException:PasswordFailure'));
	}

	return true;
}

/**
 * Log a failed login for $user_guid
 *
 * @param int $user_guid User GUID
 *
 * @return bool
 */
function log_login_failure($user_guid) {
	$user_guid = (int)$user_guid;
	$user = get_entity($user_guid);

	if (($user_guid) && ($user) && ($user instanceof ElggUser)) {
		$fails = (int)$user->getPrivateSetting("login_failures");
		$fails++;

		$user->setPrivateSetting("login_failures", $fails);
		$user->setPrivateSetting("login_failure_$fails", time());
		return true;
	}

	return false;
}

/**
 * Resets the fail login count for $user_guid
 *
 * @param int $user_guid User GUID
 *
 * @return bool true on success (success = user has no logged failed attempts)
 */
function reset_login_failure_count($user_guid) {
	$user_guid = (int)$user_guid;
	$user = get_entity($user_guid, 'user');

	if (($user_guid) && ($user) && ($user instanceof ElggUser)) {
		$fails = (int)$user->getPrivateSetting("login_failures");

		if ($fails) {
			for ($n = 1; $n <= $fails; $n++) {
				$user->removePrivateSetting("login_failure_$n");
			}

			$user->removePrivateSetting("login_failures");

			return true;
		}

		// nothing to reset
		return true;
	}

	return false;
}

/**
 * Checks if the rate limit of failed logins has been exceeded for $user_guid.
 *
 * @param int $user_guid User GUID
 *
 * @return bool on exceeded limit.
 */
function check_rate_limit_exceeded($user_guid) {
	// 5 failures in 5 minutes causes temporary block on logins
	$limit = 5;
	$user_guid = (int)$user_guid;
	$user = get_entity($user_guid,'user');

	if (($user_guid) && ($user) && ($user instanceof ElggUser)) {
		$fails = (int)$user->getPrivateSetting("login_failures");
		if ($fails >= $limit) {
			$cnt = 0;
			$time = time();
			for ($n = $fails; $n > 0; $n--) {
				$f = $user->getPrivateSetting("login_failure_$n");
				if ($f > $time - (60 * 5)) {
					$cnt++;
				}

				if ($cnt == $limit) {
					// Limit reached
					return true;
				}
			}
		}
	}

	return false;
}

/**
 * Logs in a specified ElggUser. For standard registration, use in conjunction
 * with elgg_authenticate.
 *
 * @see elgg_authenticate
 *
 * @param ElggUser $user       A valid Elgg user object
 * @param boolean  $persistent Should this be a persistent login?
 *
 * @return true or throws exception
 * @throws LoginException
 */
function login(ElggUser $user, $persistent = false) {
	// User is banned, return false.
	if ($user->isBanned()) {
		throw new LoginException(elgg_echo('LoginException:BannedUser'));
	}

	//is the user disabled?
	if(!$user->isEnabled()){
		throw new LoginException(elgg_echo('LoginException:DisabledUser'));
	}

	_elgg_session_boot(true);
	global $SESSION;
	$SESSION['user'] = $user;
	$SESSION['guid'] = $user->getGUID();
	$SESSION['id'] = $SESSION['guid'];
	$SESSION['username'] = $user->username;
	$SESSION['name'] = $user->name;
	//$SESSION['friends'] = $user->getFriends(null, 200, 0, 'guids');
	//$SESSION['friendsof'] = $user->getFriendsOf(null, 200, 0, 'guids');
	
	// if remember me checked, set cookie with token and store token on user
	if (($persistent)) {
		$code = (md5($user->name . $user->username . time() . rand()));
		$expires = (time() + (86400 * 30));
		$user->{'cookie:'.md5($code)} = $expires; //cookie_id => expires
		setcookie("mindsperm", $code, $expires, "/");
		//add to the user index cf
		db_insert('cookie:'. md5($code), array('type'=>'user_index_to_guid', $user->getGUID() => $expires));
	}

	if (!$user->save() || !elgg_trigger_event('login', 'user', $user)) {
		unset($SESSION['username']);
		unset($SESSION['name']);
		unset($SESSION['guid']);
		unset($SESSION['id']);
		unset($SESSION['user']);
		setcookie("mindsperm", "", (time() - (86400 * 30)), "/");
		_elgg_session_boot();
		setcookie("Elgg", "", (time() - (86400 * 30)), "/");
		throw new LoginException(elgg_echo('LoginException:Unknown'));
	}

	// Users privilegeohas been elevated, so change the session id (prevents session fixation)
	session_regenerate_id();

	// Update statistics
	set_last_login($SESSION['guid']);
	reset_login_failure_count($user->guid); // Reset any previous failed login attempts

	return true;
}

/**
 * Log the current user out
 *
 * @return bool
 */
function logout() {
	global $CONFIG;

	global $SESSION;
	if (isset($SESSION['user'])) {
		if (!elgg_trigger_event('logout', 'user', $SESSION['user'])) {
			return false;
		}
		$SESSION['user']->code = "";
		$SESSION['user']->save();
	}

	unset($SESSION['username']);
	unset($SESSION['name']);
	unset($SESSION['code']);
	unset($SESSION['guid']);
	unset($SESSION['id']);
	unset($SESSION['user']);

	setcookie("mindsperm", "", (time() - (86400 * 30)), "/");

	// pass along any messages
	$old_msg = $SESSION['msg'];

	session_destroy();
	setcookie("Minds", '', (time() - (86400 * 30)), "/");

	// starting a default session to store any post-logout messages.
	_elgg_session_boot(NULL, NULL, NULL);
	$SESSION['msg'] = $old_msg;

	return TRUE;
}

/**
 * Initialises the system session and potentially logs the user in
 *
 * This function looks for:
 *
 * 1. $_SESSION['id'] - if not present, we're logged out, and this is set to 0
 * 2. The cookie 'mindsperm' - if present, checks it for an authentication
 * token, validates it, and potentially logs the user in
 *
 * @uses $_SESSION
 *
 * @return bool
 * @access private
 */
function _elgg_session_boot($force = false) {
	global $new_db, $CONFIG, $DB, $SESSION;

	$new_db = serialize($DB);	

	
	if (isset($_COOKIE['Minds']) || isset($_COOKIE['mindsperm']) || $force) {

		$handler = new ElggSessionHandler();
		session_set_save_handler(
                        array($handler, 'open'),
                        array($handler, 'close'),
                        array($handler, 'read'),
                        array($handler, 'write'),
                        array($handler, 'destroy'),
                        array($handler, 'gc')
                );	
	
		// the following prevents unexpected effects when using objects as save handlers
		register_shutdown_function('session_write_close');

		session_name('Minds');
		session_start();	
		
		$storage = new ElggSessionStorage($handler);
	
		// Initialise the magic session
		$SESSION = new ElggSession($storage);
	
		if (!$force && !elgg_is_logged_in() && !isset($_COOKIE['mindsperm'])) {
			setcookie("Minds", "", (time() - (86400 * 30)), "/");
		} else {
			// Generate a simple token (private from potentially public session id)
			if (!isset($SESSION['__elgg_session'])) {
				$SESSION['__elgg_session'] = md5(microtime() . rand());
			}
			
			// is there a remember me cookie
			if (isset($_COOKIE['mindsperm'])) { 
				// we have a cookie, so try to log the user in
				$cookie = md5($_COOKIE['mindsperm']);
				if ($user = get_user_by_cookie($cookie)) {
					// we have a user, log him in
					$SESSION['user'] = $user;
					$SESSION['id'] = $user->getGUID();
					$SESSION['guid'] = $SESSION['id'];
				}
			}

			// test whether we have a user session
			if (empty($SESSION['guid'])) {
		
				// clear session variables before checking cookie
				unset($SESSION['user']);
				unset($SESSION['id']);
				unset($SESSION['guid']);
				unset($SESSION['code']);
		
			} else {
				// we have a session and we have already checked the fingerprint
				// reload the user object from database in case it has changed during the session
				
				//WE DON'T WANT TO RELOAD THE USER OBJECT EACH PAGE LOAD! (@mark)
			/*	if ($user = get_user($SESSION['guid'],'user')) {
					$SESSION['user'] = $user;
					$SESSION['id'] = $user->getGUID();
					$SESSION['guid'] = $SESSION['id'];
				} else {
					// user must have been deleted with a session active
					unset($SESSION['user']);
					unset($SESSION['id']);
					unset($SESSION['guid']);
					unset($SESSION['code']);
					setcookie("Elgg", "", (time() - (86400 * 30)), "/");
				}*/
			}
		
			if (isset($SESSION['guid'])) {
				set_last_action($SESSION['guid']);
			}
	
			if(isset($SESSION['user'])){
				//make sure user entity is cached
				cache_entity($SESSION['user']);	
			}
	
			// Finally we ensure that a user who has been banned with an open session is kicked.
			if ((isset($SESSION['user'])) && ($SESSION['user']->isBanned())) {
				session_destroy();
				setcookie("Minds", "", (time() - (86400 * 30)), "/");
				return false;
			}
		}
	} 
	
	if (!($SESSION instanceof ElggSession)) {
		// Initialise the magic session
		$SESSION = new ElggSessionCookie(ElggSessionCookie::filterCookiePrefixes($_COOKIE));
	}

	elgg_register_action('login', '', 'public');
	elgg_register_action('logout');
	
	// Register a default PAM handler
	register_pam_handler('pam_auth_userpass');
	
	return true;
}

/**
 * Used at the top of a page to mark it as logged in users only.
 *
 * @return void
 */
function gatekeeper() {
	if (!elgg_is_logged_in()) {
		global $SESSION;
		$SESSION['last_forward_from'] = current_page_url();
		register_error(elgg_echo('loggedinrequired'));
		forward('', 'login');
	}
}

/**
 * Used at the top of a page to mark it as logged in admin or siteadmin only.
 *
 * @return void
 */
function admin_gatekeeper() {
	gatekeeper();

	if (!elgg_is_admin_logged_in()) {
		global $SESSION;
		$SESSION['last_forward_from'] = current_page_url();
		register_error(elgg_echo('adminrequired'));
		forward('', 'admin');
	}
}

/**
 * Handles opening a session in the DB
 *
 * @param string $save_path    The path to save the sessions
 * @param string $session_name The name of the session
 *
 * @return true
 * @todo Document
 * @access private
 */
function _elgg_session_open($save_path, $session_name) {
	global $sess_save_path;
	$sess_save_path = $save_path;

	return true;
}

/**
 * Closes a session
 *
 * @todo implement
 * @todo document
 *
 * @return true
 * @access private
 */
function _elgg_session_close() {
	return true;
}

/**
 * Read the session data from DB failing back to file.
 *
 * @param string $id The session ID
 *
 * @return string
 * @access private
 */
function _elgg_session_read($id) {
	global $new_db;
	
	try {
		$result = db_get(array('type'=>'session', 'id'=>$id));
	var_dump($result);	
		if($result){ 
			//load serialized owner entity & add to cache
			return $result['data'];
		}
	} catch (Exception $e) {
		
		// Fall back to file store in this case, since this likely means
		// that the database hasn't been upgraded
		global $sess_save_path;
		
		$sess_file = "$sess_save_path/sess_$id";
		return (string) @file_get_contents($sess_file);
	}

	return '';
}

/**
 * Write session data to the DB falling back to file.
 *
 * @param string $id        The session ID
 * @param mixed  $sess_data Session data
 *
 * @return bool
 * @access private
 */
function _elgg_session_write($id, $sess_data) {

	global $new_db;
	//HACK (nasty one) due to object destruction
	$DB =  unserialize($new_db);
	
	/*if(isset($_COOKIE['Minds'])){	
		return; //this is to improve page times. write on each page seems excessive
	}*/
	
	try {
		$result = $DB->cfs['session']->insert($id, array('ts'=>$time,'data'=>$sess_data));
		
		if($result !== false){
			return true;
		}
	
	} catch (Exception $e) {
		// Fall back to file store in this case, since this likely means
		// that the database hasn't been upgraded
		global $sess_save_path;

		$sess_file = "$sess_save_path/sess_$id";
		if ($fp = @fopen($sess_file, "w")) {
			$return = fwrite($fp, $sess_data);
			fclose($fp);
			return $return;
		}
	}

	return false;
}

/**
 * Destroy a DB session, falling back to file.
 *
 * @param string $id Session ID
 *
 * @return bool
 * @access private
 */
function _elgg_session_destroy($id) {
	global $DB_PREFIX;
	
	try {
		return (bool)db_remove($id, 'session');
	} catch (Exception $e) {
		// Fall back to file store in this case, since this likely means that
		// the database hasn't been upgraded
		global $sess_save_path;

		$sess_file = "$sess_save_path/sess_$id";
		return @unlink($sess_file);
	}
}

/**
 * Perform garbage collection on session table / files
 *
 * @param int $maxlifetime Max age of a session
 *
 * @return bool
 * @access private
 */
function _elgg_session_gc($maxlifetime) {
	global $DB_PREFIX;
	$life = time() - $maxlifetime;
return true;
	//try {
	//	return (bool)delete_data("DELETE from {$DB_PREFIX}users_sessions where ts<'$life'");
	//} catch (DatabaseException $e) {
		// Fall back to file store in this case, since this likely means that the database
		// hasn't been upgraded
		global $sess_save_path;

		foreach (glob("$sess_save_path/sess_*") as $filename) {
			if (filemtime($filename) < $life) {
				@unlink($filename);
			}
		}
	//}

	return true;
}
