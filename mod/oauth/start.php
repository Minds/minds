<?php

  /**
   * Elgg oauth client and server
   * 
   * @author Justin Richer
   * @copyright The MITRE Corporation
   * @link http://mitre.org/
   */

function oauth_init() {
			
	// Get config
	global $CONFIG;

	// include the OAuth library
	if (!class_exists('OAuthConsumer')) {
		include($CONFIG->pluginspath . 'oauth/lib/OAuth.php');
	}

	// set up the data store
	include($CONFIG->pluginspath . 'oauth/lib/ElggOAuthDataStore.php');


	// set up our actions and hooks

	// mechanisms to register and unregister consumers
	elgg_register_action('oauth/register', $CONFIG->pluginspath . 'oauth/actions/register.php');
	elgg_register_action('oauth/unregister', $CONFIG->pluginspath . 'oauth/actions/unregister.php');
	elgg_register_action('oauth/editconsumer', $CONFIG->pluginspath . 'oauth/actions/editconsumer.php');
  
	// mechanisms to let the user authorize and revoke their tokens
	elgg_register_action('oauth/authorize', $CONFIG->pluginspath . 'oauth/actions/authorize.php');
	elgg_register_action('oauth/revoke', $CONFIG->pluginspath . 'oauth/actions/revoke.php');

	// mechanisms to allow consumers of remote sites to request tokens
	elgg_register_action('oauth/gettoken', $CONFIG->pluginspath . 'oauth/actions/gettoken.php');

	// page handler
	elgg_register_page_handler('oauth', 'oauth_page_handler');

	// plugins hooks (for permissions on OAuth token and consumer objects)
	elgg_register_plugin_hook_handler('permissions_check', 'object',  'oauth_permissions_check');

	// cron to clean up old nonces and tokens
	elgg_register_plugin_hook_handler('cron', 'hourly', 'oauth_cron_cleanup');

	// add our menu pieces
	/*elgg_register_menu_item('site', array('name' => elgg_echo('oauth:menu'),
	    'text' => elgg_echo('oauth:menu'),
	    'href' => $CONFIG->wwwroot . 'oauth/authorize'));*/

	// hook for the PAM permissions system
	register_pam_handler('oauth_pam_handler', 'sufficient', 'user');
	register_pam_handler('oauth_pam_handler', 'sufficient', 'api');

	// API Test function
	expose_function('oauth.echo',
			'oauth_echo',
			array('string' => array('type' => 'string')),
			'A testing method for OAuth authentication',
			'GET',
			true,
			true);


	// run our set up and upgrade functions
	run_function_once('oauth_run_once');
	run_function_once('oauth_upgrade_201004');
			
}

// page setup
function oauth_pagesetup() {
	global $CONFIG;
	// add our page menus as needed
	/*elgg_register_menu_item('page', array('name' => elgg_echo('oauth:authorized'),
	    'text' => elgg_echo('oauth:authorized'),
	    'context' => 'oauth',
	    'href' => $CONFIG->wwwroot . 'oauth/authorize'));*/

	elgg_register_menu_item('page', array(
	    'name' => 'oauthregister',
	    'text' => elgg_echo('admin:oauthregister'),
	    'href' => 'admin/oauthregister',
	    'context' => 'admin',
	    'section' => 'configure'));

	/*
	elgg_register_menu_item('page', array('name' => elgg_echo('oauth:registered'),
	    'text' => elgg_echo('oauth:registered'),
	    'context' => 'admin',
	    'href' => $CONFIG->wwwroot . 'oauth/register'));
	*/
	if(elgg_get_context() == "settings" && elgg_get_logged_in_user_guid()){
		elgg_register_menu_item('page', array('name' => elgg_echo('oauth:menu'),
	    'text' => elgg_echo('oauth:menu'),
	    'href' => $CONFIG->wwwroot . 'oauth/authorize'));
	}
}

// API test function
function oauth_echo($string) {
	$user = get_loggedin_user();
	return $string . ': ' . $user->username;
}

// register our token and consumer objects
function oauth_run_once() {
	// Register a class
	add_subtype('object', 'oauthtoken');	  
	add_subtype('object', 'oauthconsumer');
}

// register our nonce objects and do some housecleaning
function oauth_upgrade_201004() {
	add_subtype('object', 'oauthnonce');

	elgg_set_ignore_access(true);
	// grab all the consumers and update their types to the new keys
	$consumers = elgg_get_entities(array('type' => 'object',
					     'subtype' => 'oauthconsumer',
					     'limit' => 0
					       ));
	foreach($consumers as $cons) {
		if ($cons->consumer_type == 'client') {
			$cons->consumer_type = 'inbound';
		} else if ($cons->consumer_type == 'server') {
			$cons->consumer_type = 'outbound';
		} else {
			// might as well clean up invalid ones while we're here
			$cons->delete();
		}
	}
	elgg_set_ignore_access(false);
}

// clean up old nonce and token objects
function oauth_cron_cleanup($hook, $entity_type, $returnvalue, $params) {
	elgg_set_ignore_access(true);
	$nonces = elgg_get_entities(array('type' => 'object', 
					  'subtype' => 'oauthnonce',
					  'limit' => 0
					    ));
	$deleted = 0;
	$now = time();
	foreach ($nonces as $nonce) {
		//print 'Now: ' . $now . "\n" . 'Exp: ' . $nonce->expires;
		//print "\n\n";
		if ($now > $nonce->expires) {
			$nonce->delete();
			$deleted++;
		}
	}

	$tokens = elgg_get_entities(array('type' => 'object',
					  'subtype' => 'oauthtoken',
					  'limit' => 0
					    ));

	$tokenTimeout = 60 * 60; // unclaimed request tokens are good for one hour
	$tdeleted = 0;
	foreach ($tokens as $token) {
		$consumEnt = oauth_lookup_consumer_entity($token->consumerKey);
		if (!$consumEnt) {
			// no consumer entity, delete it
			$token->delete();
			$tdeleted++;
		} else if ($token->requestToken && $token->accessToken) {
			// both flavors of token, clean the request token
			// Should this actually be a delete?
			$token->clearMetaData('requestToken');
			$tdeleted++;
		} else if ($token->requestToken && $now > $token->getTimeCreated() + $tokenTimeout) {
			$token->delete();
			$tdeleted++;
		} else if ($token->accessToken && $token->callbackUrl) {
                        $token->clearMetaData('callbackUrl');
                }
	}

	elgg_set_ignore_access(false);
	if ($deleted || $tdeleted) {
		print 'Cleaned up ' . $deleted . ' OAuth nonces and ' . $tdeleted . ' OAuth tokens';
		print "\n";
	}

}

function oauth_page_handler($page) {
	global $CONFIG;
	elgg_set_context('settings');
	switch ($page[0]) {
	case 'authorize':
	case 'gottoken':
	case 'register':
	case 'editconsumer':
	case 'accesstoken':
	case 'requesttoken':
		include($CONFIG->pluginspath . 'oauth/pages/' . $page[0] . '.php');

		return true;
	}
}

// create a consumer object with the given properties and return the resulting (saved) entity
function oauth_create_consumer($name, $desc, $key, $secret, $revA = TRUE, $outbound = TRUE, $callback = NULL) {

	global $CONFIG;

	$consumEnt = new ElggObject();
	$consumEnt->subtype = 'oauthconsumer';
	$consumEnt->name = $name;
	$consumEnt->description = $desc;
	$consumEnt->access_id = ACCESS_PUBLIC; // this feels wrong, but it's the only way to make it accessible to multiple users
	$consumEnt->save();
	

	if ($outbound) {
		$consumEnt->consumer_type = 'outbound'; // connecting out to a server
		// callback is based on the local server's gottoken page
		if (!$callback) {
			$callback = $CONFIG->wwwroot . 'oauth/gottoken';
		}
		$consumEnt->callbackUrl = $callback;
	} else {
		$consumEnt->consumer_type = 'inbound'; // connecting in from a client
		if ($callback) {
			$consumEnt->callbackUrl = $callback;
		}
	}

	// are we using revA?
	$consumEnt->revA = $revA;

	$consumEnt->key = $key;
	$consumEnt->secret = $secret;

	return $consumEnt;
}


// look up a consumer based on its key
function oauth_lookup_consumer_entity($consumer_key) {

	if (!$consumer_key) {
		return NULL;
	}

	$consumers = elgg_get_entities_from_metadata(array('metadata_names' => 'key', 'metadata_values' => $consumer_key, 
							   'type' => 'object', 'subtype' => 'oauthconsumer', 
							   'limit' => 1));

	if ($consumers && $consumers[0]) {
		$consumEnt = $consumers[0];
		return $consumEnt;
	} else {
		return NULL;
	}
}

// create a consumer object from an entity object
function oauth_consumer_from_entity($consumEnt) {
	return new OAuthConsumer($consumEnt->key, $consumEnt->secret, $consumEnt->callbackUrl);
}

// look up a nonce object based on its consumer, token, and nonce value
function oauth_lookup_nonce_entity($consumer, $token, $nonce) {
	if (!$consumer || !$nonce) {
		return NULL;
	}

	$pairs = array();
	$pairs[] = array('name' => 'consumerKey', 
			 'value' => $consumer->key);
	$pairs[] = array('name' => 'nonce',
			 'value' => $nonce);
	if ($token) {
		$pairs[] = array('name' => 'tokenKey',
				 'value' => $token->key);
	}

	$nonces = elgg_get_entities_from_metadata(array('metadata_name_value_pairs' => $pairs,
							'type' => 'object',
							'subtype' => 'oauthnonce'));

	if ($nonces && $nonces[0]) {
		return $nonces[0];
	} else {
		return NULL;
	}
}

function oauth_save_nonce($consumerKey, $nonce, $tokenKey = NULL, $timeout = 500) {

	if (!$consumerKey || !$nonce) {
		return NULL;
	}

	//print 'Saving nonce: ' . $consumerKey . '|' . $tokenKey . '|' . $nonce . "<br />\n";

	$expires = time() + $timeout; // when does this nonce expire?

	$noncEnt = new ElggObject();
	$noncEnt->subtype = 'oauthnonce';
	$noncEnt->access_id = ACCESS_PUBLIC; // needs to be readable (to check across users) but there should be a better way...
	$noncEnt->save();

	$noncEnt->consumerKey = $consumerKey;
	if ($tokenKey) {
		$noncEnt->tokenKey = $tokenKey;
	}
	$noncEnt->nonce = $nonce;
	$noncEnt->expires = $expires;
	
}

// looks up a token for a given owner and consumer
// (most useful for local consumers)
function oauth_get_token($owner, $consumer) {
  
	$tokens = elgg_get_entities_from_metadata(array('metadata_names' => 'consumerKey', 'metadata_values' => $consumer->key, 
							'types' => 'object', 'subtypes' => 'oauthtoken', 
							'owner_guids' => $owner->getGUID(), 'limit' => 1));

	if ($tokens && $tokens[0]) {
		$token = $tokens[0];
		return $tokens[0];
	} else {
		return NULL;
	}
}

// look up the entity for the given token key and consumer
function oauth_lookup_token_entity($tokenKey, $token_type, $consumer) {/*{{{*/

	if (!$tokenKey) {
		return NULL;
	}

	if ($token_type == 'access') {
		$tokens = elgg_get_entities_from_metadata(array('metadata_names' => 'accessToken', 'metadata_values' => $tokenKey, 
								'types' => 'object', 'subtypes' => 'oauthtoken', 'limit' => 1));
	} else if ($token_type == 'request') {
		$tokens = elgg_get_entities_from_metadata(array('metadata_names' => 'requestToken', 'metadata_values' => $tokenKey, 
								'types' => 'object', 'subtypes' => 'oauthtoken', 'limit' => 1));
	} else {
		$tokens = NULL;
	}
	if ($tokens && $tokens[0]) {
		$tokEnt = $tokens[0];
		if ($consumer) {
			// double-check against the consumer if given
			if ($tokEnt->consumerKey == $consumer->key) {
				return $tokEnt;
			} else {
				return NULL;
			}
		} else {
			// no consumer to check against, so just return the token
			return $tokEnt;
		}
	} else {
		return NULL;
	}
}
  
// creates a token object from an entity object
function oauth_token_from_entity($tokEnt) {
	if ($tokEnt->requestToken) {
		return new OAuthToken($tokEnt->requestToken, $tokEnt->secret);
	} else if ($tokEnt->accessToken) {
		return new OAuthToken($tokEnt->accessToken, $tokEnt->secret);
	} else {
		return NULL;
	}
}


// get a request token from the given URL
function oauth_get_new_request_token($consumer, $url, $callback = NULL, $parameters = array()) {

	$sha = new OAuthSignatureMethod_HMAC_SHA1();

	if (!$parameters) {
		$parameters = oauth_find_parameters($url);
	}

	if ($callback) {
		// Rev A change: send a registered callback URL with the request
		$parameters['oauth_callback'] = $callback;
	}

	//print_r($parameters);
	//print($url);
	$req = OAuthRequest::from_consumer_and_token($consumer, NULL, 'GET', $url, $parameters);
	$req->sign_request($sha, $consumer, NULL);

	//print($req->to_url());

        $reqUrl = $req->to_url();

	$tokenString = url_getter_getUrl($reqUrl);
	$tokenParts = array();
	parse_str($tokenString, $tokenParts);

	$token = new OAuthToken($tokenParts['oauth_token'], $tokenParts['oauth_token_secret']);
	if ($token->key && $token->secret) {
		return $token;
	} else {
		return NULL;
	}

}

// try to automatically find the parameters from a request URL
function oauth_find_parameters($url) {
	$bits = parse_url($url);
	parse_str($bits['query'], $params);

	return $params;
}

// get an access token from the given URL and request token
function oauth_get_new_access_token($consumer, $tokEnt, $url, $verifier = NULL, $parameters = array()) {

	$reqToken = oauth_token_from_entity($tokEnt);

	if (!$parameters) {
		$parameters = oauth_find_parameters($url);
	}

	if ($verifier) {
		// Rev A change: send a registered callback URL with the request
		$parameters['oauth_verifier'] = $verifier;
	}

	$sha = new OAuthSignatureMethod_HMAC_SHA1();
  
	$req = OAuthRequest::from_consumer_and_token($consumer, $reqToken, 'GET', $url, $parameters);
	$req->sign_request($sha, $consumer, $reqToken);

	$tokenString = url_getter_getUrl($req->to_url());
	$tokenParts = array();
	parse_str($tokenString, $tokenParts);

	$token = new OAuthToken($tokenParts['oauth_token'], $tokenParts['oauth_token_secret']);

	if ($token->key && $token->secret) {
		return $token;
	} else {
		return NULL;
	}

}

// save the request token to a new entity
function oauth_save_request_token($token, $consumer, $user, $callback = NULL) {

	$tokEnt = new ElggObject();
	$tokEnt->subtype = 'oauthtoken';
	if ($user) {
		$tokEnt->owner_guid = $user->getGUID();
		$tokEnt->container_guid = $user->getGUID();
	} else {
		// user can potentially be null
		$tokEnt->owner_guid = 0;
		$tokEnt->container_guid = 0;
	}
	$tokEnt->access_id = ACCESS_PUBLIC; // needs to be readable (but this doesn't feel right...)

	$tokEnt->save();

	$tokEnt->requestToken = $token->key;
	$tokEnt->secret = $token->secret;
	$tokEnt->consumerKey = $consumer->key;
	if ($callback) {
		$tokEnt->callbackUrl = $callback;
	}
	$tokEnt->save();

	return $tokEnt;
}

// save the access token to the given entity
function oauth_save_access_token($tokEnt, $token) {

	// clear out old data
	$tokEnt->clearMetaData('requestToken');
        $tokEnt->clearMetaData('callbackUrl');
	$tokEnt->clearMetaData('secret');
  
	// reuse this token object for the access token
	$tokEnt->accessToken = $token->key;
	$tokEnt->secret = $token->secret;

	$tokEnt->save();

	return $tokEnt;

}

// get or create an OAuth server
function oauth_get_server() {
	global $OAUTH_SERVER; // cache the object
	if (!$OAUTH_SERVER) {
		$OAUTH_SERVER = new OAuthServer(new ElggOAuthDataStore());
		$OAUTH_SERVER->add_signature_method(new OAuthSignatureMethod_HMAC_SHA1());
		$OAUTH_SERVER->add_signature_method(new OAuthSignatureMethod_PLAINTEXT());
	}
	return $OAUTH_SERVER; 
}

// check permissions to let people claim unclaimed tokens
function oauth_permissions_check($hook, $entity_type, $returnvalue, $params) {

	if ($returnvalue) {
		return $returnvalue;
	}

	$ent = $params['entity'];
	$user = $params['user'];

	// anybody can edit an unclaimed token
	if ($ent->getSubtype() == 'oauthtoken' && !$ent->getOwner()) {
		return true;
	}

  
	return false;


}

// generate a verifier code
function oauth_generate_verifier($length = 8) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
	$string = '';    
	
	for ($p = 0; $p < $length; $p++) {
		$string .= $characters[mt_rand(0, strlen($characters))];
	}
	
	return $string;
}

// hook into the PAM system but use OAuth credentials
function oauth_pam_handler($credentials = NULL) {
	global $CONFIG;
	try {

		$server = oauth_get_server();

		if ($server->this_request_validated) {
			return true;
		}
		
		// check to see if the request is a valid OAuth request
		//print_r(oauth_get_params());
		$req = OAuthRequest::from_request(null, null, oauth_get_params());
		//print $req->get_signature_base_string();
		$ct = $server->verify_request($req); // returns a pair of consumer/token
		$consumer = $ct[0];
		$token = $ct[1];

		$nonce = $req->get_parameter('oauth_nonce');
		
		// save our nonce for later checking
		oauth_save_nonce($consumer->key, $nonce, $token->key);

	} catch (OAuthException $e) {
		// there was an OAuth exception
		//print 'OAuth Exception: ';
		//print_r($e);
		//die();
		return false;
	}

	// look up a valid access token
	$tokEnt = oauth_lookup_token_entity($token->key, 'access', $consumer);

	if (!$tokEnt) {
		// no token found, bail
		//print 'No Token';
		return false;
	}

	// get the user associated with this token
	$user = $tokEnt->getOwnerEntity();

	// couldn't get the user
	if (!$user) {
		//print 'No user';
		return false;
	}

	// not an actual user
	if (!($user instanceof ElggUser)) {
		//print 'Not a real user';
		return false;
	}

	// try logging in the user object here
	if (!login($user)) {
		// couldn't log in
		//print 'Could not log in';
		return false;
	}

	// if we've made it this far, then we've managed to log the 
	// user in with a valid OAuth credential set

	// save the fact that we've validated this request already
	$server->this_request_validated = true;

	// tell the PAM system that it worked
	return true;

}

// gatekeeper function that forces a check against oauth authentication
function oauth_gatekeeper() {
	oauth_pam_handler();
	gatekeeper();
}

// get the oauth parameters using the elgg get_input library and filters
function oauth_get_params() {
	global $CONFIG;

	// Find request headers
	$request_headers = OAuthUtil::get_headers();
	
	// start with an empty array
	$parameters = array();

	/***
	 *** This next part is a hack. This ignores the QUERY_STRING because it
	 *** gets messed up by the apache mod_rewrite rules for page views, and
	 *** you end up with 'handler' and 'request' variables on the parameters
	 *** stack. This in turn messes up OAuth's signature base string
	 *** generation algorithm, causing things to fail. I have a feeling
	 *** that this is going to bite me back some day, but I'm not sure 
	 *** how or where, especially if this pam module gets called from
	 *** somewhere other than the API chain in a way that makes any sense.
	 ***/

        // parse query parameters

	$querystr = '';

	if ($_SERVER['REQUEST_URI']) {
		$qparts = explode('?', $_SERVER['REQUEST_URI'], 2); // split on the question mark to get the real query parameters before Apache mangles them
		if (count($qparts) == 2) {
			$querystr = $qparts[1];
		}
	}
         
        $parameters = OAuthUtil::parse_parameters($querystr);

	/***
	 ***
	 ***/

	// It's a POST request of the proper content-type, so parse POST
	// parameters and add those overriding any duplicates from GET
	if (@strstr($request_headers["Content-Type"],
		    "application/x-www-form-urlencoded")
		) {
		$post_data = OAuthUtil::parse_parameters(
			file_get_contents(OAuthRequest::$POST_INPUT)
			);
		$parameters = array_merge($parameters, $post_data);
	}
	
	// We have a Authorization-header with OAuth data. Parse the header
	// and add those overriding any duplicates from GET or POST
	if (@substr($request_headers['Authorization'], 0, 6) == "OAuth ") {
		$header_parameters = OAuthUtil::split_header(
			$request_headers['Authorization']
			);
		$parameters = array_merge($parameters, $header_parameters);
	}

	return $parameters;
}

elgg_register_event_handler('init','system','oauth_init');
elgg_register_event_handler('pagesetup','system','oauth_pagesetup');
