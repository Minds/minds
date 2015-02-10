<?php

/**
 * Elgg session management
 * Functions to manage logins
 *
 * @package Elgg.Core
 * @subpackage Session
 */

/** 
 * Elgg magic session 
 * 
 * There is nothing magic about this. @deprecated
 */
global $SESSION;

/**
 * @deprecated
 */
function _elgg_session_boot($force = false) {
	return new Minds\Core\session($force);
}

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
	return Minds\Core\session::getLoggedinUser();
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
	return Minds\Core\session::isLoggedin();
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
		
		$user = new ElggUser($user_guid);	
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

	$algo = 'sha256';
	if(strlen($user->password) == 32)
		$algo = 'md5';

	if ($user->password !== generate_user_password($user, $credentials['password'], $algo)) {
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
				$user->removePrivateSetting("login_failure_" . $n);
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
	
	if(!elgg_trigger_event('login', 'user', $user)){
        return false;
	}
	
    $_SESSION['user'] = $user;
	$_SESSION['guid'] = $user->getGUID();
	$_SESSION['id'] = $_SESSION['guid'];
	$_SESSION['username'] = $user->username;
	$_SESSION['name'] = $user->name;
	//$SESSION['friends'] = $user->getFriends(null, 200, 0, 'guids');
	//$SESSION['friendsof'] = $user->getFriendsOf(null, 200, 0, 'guids');

	// if remember me checked, set cookie with token and store token on user
	if (($persistent)) {
		$code = (md5($user->name . $user->username . time() . rand()));
		$expires = (time() + (86400 * 30));
		$user->{'cookie:'.md5($code)} = $expires; //cookie_id => expires
		setcookie("mindsperm", $code, $expires, "/");
		//add to the user index cf
		$db = new Minds\Core\Data\Call('user_index_to_guid');
		$db->insert('cookie:'. md5($code), array($user->getGUID() => $expires));
	}
	
	if (!$user->save() || !elgg_trigger_event('login', 'user', $user)) {
		unset($_SESSION['username']);
		unset($_SESSION['name']);
		unset($_SESSION['guid']);
		unset($_SESSION['id']);
		unset($_SESSION['user']);
		setcookie("mindsperm", "", (time() - (86400 * 30)), "/");
		throw new LoginException(elgg_echo('LoginException:Unknown'));
	}

	// Users privilegeohas been elevated, so change the session id (prevents session fixation)
	session_regenerate_id(true);

	// Update statistics
	set_last_login($_SESSION['guid']);
	reset_login_failure_count($user->guid); // Reset any previous failed login attempts

	 setcookie('loggedin', 1, time() + 3600, '/');
	 
	if(!elgg_trigger_event('loggedin', 'user', $user)){
		return false;
	}

	setcookie('sidebarOpen', 'true', time() + 60, '/');

	return true;
}

/**
 * Log the current user out
 *
 * @return bool
 */
function logout() {
	global $CONFIG;

	/** 
	 * Cookie cleanup
	 */
	$user = elgg_get_logged_in_user_entity();
	foreach($user as $k=>$v){
		if(strpos($k, 'cookie:') !== FALSE){
			$user->removePrivateSetting($k);
		}
	}
	
	if (isset($_SESSION['user'])) {
		if (!elgg_trigger_event('logout', 'user', $_SESSION['user'])) {
			return false;
		}
		$_SESSION['user']->code = "";
	//	$_SESSION['user']->save();
	}

	unset($_SESSION['username']);
	unset($_SESSION['name']);
	unset($_SESSION['code']);
	unset($_SESSION['guid']);
	unset($_SESSION['id']);
	unset($_SESSION['user']);

	setcookie("mindsperm", "", (time() - (86400 * 30)), "/");
	setcookie("mindsSSO", "", (time() - (86400 * 30)), "/");

	session_destroy();
	session_regenerate_id(true);
	setcookie(session_name(), '', (time() - (86400 * 30)), "/");
	
	elgg_trigger_event('loggedout', 'user', $user);
		
	return TRUE;
}

/**
 * Used at the top of a page to mark it as logged in users only.
 *
 * @return void
 */
function gatekeeper() {
	if (!elgg_is_logged_in()) {
		$_SESSION['last_forward_from'] = current_page_url();
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
		$_SESSION['last_forward_from'] = current_page_url();
		register_error(elgg_echo('adminrequired'));
		forward('', 'admin');
	}
}
