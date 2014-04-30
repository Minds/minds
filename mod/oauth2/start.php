<?php

/**
 * Elgg OAuth2 server implementation
 *
 * Uses the oauth2-server-php library by Brent Shaffer
 * https://github.com/bshaffer/oauth2-server-php
 * 
 * @author Billy Gunn (billy@arckinteractive.com)
 * @copyright Minds.com 2013
 * @link http://minds.com
 */

elgg_register_event_handler('init','system','oauth2_init');

function oauth2_init() {

    $base = elgg_get_plugins_path() . 'oauth2';

    // Register our OAuth2 storage implementation
    elgg_register_class('ElggOAuth2DataStore', "$base/lib/ElggOAuth2DataStore.php");

    // Register our oauth2 library
    elgg_register_library('oauth2', "$base/lib/oauth2.php");

    // page handler
    elgg_register_page_handler('oauth2', 'oauth2_page_handler');
    elgg_register_page_handler('developers', 'oauth2_page_handler');

//    $item = new ElggMenuItem('developers', elgg_echo('oauth2:developers'), 'developers');
  //  elgg_register_menu_item('site', $item);

    // Register actions
    elgg_register_action('oauth2/register', $base . '/actions/register.php');
    elgg_register_action('oauth2/unregister', $base . '/actions/unregister.php');
    elgg_register_action('oauth2/delete', $base . '/actions/delete.php');
    elgg_register_action('oauth2/authorize', $base . '/actions/authorize.php');

    // Hook into pam
    register_pam_handler('oauth2_pam_handler', 'sufficient', 'user');
    register_pam_handler('oauth2_pam_handler', 'sufficient', 'api');

    // register javascript
    $js = elgg_get_simplecache_url('js', 'oauth2/oauth2');
    elgg_register_simplecache_view('js/oauth2/oauth2');
    elgg_register_js('oauth2', $js, 'footer');

    // Register a cron to cleanup expired tokens
    elgg_register_plugin_hook_handler('cron', 'hourly', 'oauth2_expire_tokens');

    // Admin menu to manage applications
    elgg_register_admin_menu_item('administer', 'oauth2', 'administer_utilities');
			
	//register subtypes
	oauth2_subtypes();
	
	//check for Single Sign On
	oauth2_SSO();
}

function oauth2_page_handler($page) {

    // Load our library methods
    elgg_load_library('oauth2');

    // Load the javascript
    elgg_load_js('oauth2');

    $base = elgg_get_plugins_path() . 'oauth2';

    $pages = $base . '/pages/oauth2';

    switch ($page[0]) {
    	
		case 'token': 
			require $pages . "/token.php";
            break;

        case 'authorize':
            require $pages . "/authorize.php";
            break;

        case 'grant':
            oauth2_grant();
            break;

        case 'get_user':
            oauth2_get_user_by_access_token();
            break;

        case 'regenerate':
            echo oauth2_generate_client_secret();
            break;

        case 'add':
        case 'edit':
        case 'register':
            require $pages . "/register.php";
            break;

        case 'applications':
        default:
            require $pages . "/applications.php";
            break;

    }

    return true;
}

/**
 * Auto login if a cookie is found
 */
function oauth2_SSO(){
	if(isset($_COOKIE['mindsSSO']) && !elgg_is_logged_in()){
		
		$data = explode(':', base64_decode($_COOKIE['mindsSSO']));
	 
	 	// Load our oauth2 library
  		elgg_load_library('oauth2');
		$storage = new ElggOAuth2DataStore();
		$ia = elgg_set_ignore_access();
		$token = $storage->getAccessToken($data[1]);
		elgg_set_ignore_access($ia);
		if(!$token['user_id']){
			return false;
		}
	    $user = get_entity($token['user_id']);
		if(!$user)
			return false;
			
		login($user);
	}
}
/**
 * PAM: Confirm that the call includes an access token
 *
 * @return bool
 */
function oauth2_pam_handler($credentials = NULL) {

    // Load our oauth2 library
    elgg_load_library('oauth2');

    // Get our custom storage object
    $storage = new ElggOAuth2DataStore();

    // Create a server instance
    $server = new OAuth2_Server($storage);

    $ia = elgg_get_ignore_access();
    elgg_set_ignore_access(true);

    // Validate the request
    if (!$server->verifyAccessRequest(OAuth2_Request::createFromGlobals())) { 
       // error_log('oauth2_pam_handler() - ' . $server->getResponse());
        elgg_set_ignore_access($ia);
        return false;
    }

    // Get the token data
    $token = $storage->getAccessToken(get_input('access_token'));

    // get the user associated with this token
    $user = get_entity($token['user_id'], 'user');

    elgg_set_ignore_access($access);

    // couldn't get the user
    if (!$user || !($user instanceof ElggUser)) {
        error_log('oauth2_pam_handler() - Failed to retrieve user');
        return false;
    }

    // try logging in the user object here
    if (!login($user)) { 
        error_log('oauth2_pam_handler() - Failed to login user');
        return false;
    }

    // save the fact that we've validated this request already
    
    // tell the PAM system that it worked
    return true;
}

//@todo update to casandra way
function oauth2_expire_tokens() {

    $access = elgg_get_ignore_access();
    elgg_set_ignore_access(true);

    $options = array(
        'type' => 'object',
        'subtype' => 'oauth2_access_token',
        'limit'   => 9999
    );

    $entities = elgg_get_entities($options);

    if (!empty($entities)) {
        foreach ($entities as $e) {
        	if($entity->time_create < time() + 3600){
            	$e->delete();
			}
        }
    }

    elgg_set_ignore_access($access);
}

/*
 * Run once method to register subtypes
 *
 */
function oauth2_subtypes() {
	add_subtype('object', 'oauth2_client');
	add_subtype('object', 'oauth2_access_token');
	add_subtype('object', 'oauth2_refresh_token');
	add_subtype('object', 'oauth2_auth_code');
}

  

