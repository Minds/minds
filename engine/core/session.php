<?php
/**
 * Minds session manager
 */
namespace minds\core;

use minds\core;

class session extends base{

	private $session_name = 'minds';

	public function __construct($force = NULL){
			
		$handler = new core\data\sessions();
	        session_set_save_handler(
			array($handler, "open"),
        		array($handler, "close"),
            		array($handler, "read"),
            		array($handler, "write"),
            		array($handler, "destroy"),
           		array($handler, "gc")
		);
		
		ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30); // Persistent cookies - 30 days
		session_name('minds');
		session_start();
		session_cache_limiter('public');
		session_regenerate_id(true);
	
		//@todo move these to new page model
		\elgg_register_action('login', '', 'public');
	    \elgg_register_action('logout');
	        
		// Register a default PAM handler
		//@todo create an OOP pam handler
	    \register_pam_handler('pam_auth_userpass');
	
		//there are occassions where we may want a session for loggedout users. 
		if($force)
			$_SESSION['force'] = true;
	
		// is there a remember me cookie
		//@todo remove this - now we have long lasting sessions
		if (isset($_COOKIE['mindsperm']) && !isset($_SESSION['user'])) { 
			// we have a cookie, so try to log the user in
			$cookie = md5($_COOKIE['mindsperm']);
			if ($user = get_user_by_cookie($cookie)) {
				login($user);
			}
		}
	
		// Generate a simple token (private from potentially public session id)
		if (!isset($_SESSION['__elgg_session'])) {
			$_SESSION['__elgg_session'] = md5(microtime() . rand());
		}
		
		if(isset($_SESSION['user'])){
			setcookie('loggedin', 1, time() + 3600, '/'); 
			cache_entity($_SESSION['user']);
		} else { 
			setcookie('loggedin', 0, time() + 3600, '/');
		}
		
		header('X-Powered-By: Minds', true);
		
		register_shutdown_function(array($this, 'shutdown'));
	}

	/**
	 * Shutdown function to remove the session
	 */
	public function shutdown(){
		if ( isset( $_COOKIE[session_name()] ) && !isset($_SESSION['user']) && !isset($_SESSION['force'])){
			//clear session from disk
			$params = session_get_cookie_params();
		/*	setcookie(session_name(), '', time()-60*60*24*30*12,
		        $params["path"], $params["domain"],
		        $params["secure"], $params["httponly"]
		    );*/
			$_SESSION = array();
			unset($_COOKIE[session_name()]);
			session_destroy();
		}
	}
	
	/**
	 * Check if the user is logged in
	 */
	static public function isLoggedin(){
		$user = self::getLoggedinUser();

		if ((isset($user)) && ($user instanceof \ElggUser) && $user->guid) {
			return true;
		}

		return false;
	}
	
	/**
	 * Get the logged in user entity
	 */
	static public function getLoggedinUser(){
		global $USERNAME_TO_GUID_MAP_CACHE;

		/**
		 * The OAuth plugin, for example, might use this. 
		 */
		if($user = \elgg_trigger_plugin_hook('logged_in_user', 'user')){
			return $user;
		}
		
		if (isset($_SESSION['user'])) {
			//cache username
			$USERNAME_TO_GUID_MAP_CACHE[$_SESSION['username']] = $_SESSION['guid'];
			return $_SESSION['user'];
		}
	
		return NULL;
	}
	
}
