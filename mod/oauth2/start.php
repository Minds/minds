<?php

/**
 * Elgg OAuth2 server implementation
 * 
 * @author Billy Gunn
 * @copyright Minds.com 2013
 * @link http://minds.com
 */

elgg_register_event_handler('init','system','oauth2_init');

function oauth2_init() {

    $base = elgg_get_plugins_path() . 'en_connections';

    // Register our oauth2 helper functions
    elgg_register_library('oauth2', elgg_get_plugins_path() . 'oauth2/lib/oauth2.php');
			
	// Add the required subtype
	//run_function_once('oauth_run_once');
    oauth_run_once();

    //elgg_load_library('oauth2');
    //print oauth2_generate_client_id(); exit;
}

function oauth2_page_handler($page) {

}

// register the oauth2token entity subtype
function oauth2_run_once() {
	add_subtype('object', 'oauth2_client');
	add_subtype('object', 'oauth2_access_token');
	add_subtype('object', 'oauth2_refresh_token');
	add_subtype('object', 'oauth2_auth_code');
}

  

