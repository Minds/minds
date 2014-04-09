<?php

/**
 * Use Mozilla Persona to log into your Elgg site.
 * See persona.org for more information.
 * 
 * By Ben Werdmuller <ben@benwerd.com> <http://benwerd.com/>
 * 
 * @package Persona
 */

// Call the initialization function

    elgg_register_event_handler('init', 'system', 'persona_init');
    elgg_register_library('persona', dirname(__FILE__) . '/lib/persona.php');

// Initialization functions

    function persona_init() {

	// If the administrator has enabled login via Persona, extend the login form with it

	    if (persona_is_enabled()) {
		//elgg_extend_view('login/extend', 'persona/login');
		elgg_extend_view('page/elements/foot', 'persona/metatags');
	    }

	// Registering page handlers for authentication APIs and 
	    
	    elgg_register_page_handler('persona', 'persona_auth_pagehandler');
	    
	// We need the login callback to be available evenin a walled garden
		
	    elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'persona_public_pages');
	    
	// Let's register some actions ..
	    
	    $actions = dirname(__FILE__) . '/actions/persona';				    // Base actions URL
	    elgg_register_action('persona/register', "$actions/register.php", 'public');    // Action for post-login registration, if required

	// Finally, if we've just logged in with Persona but we don't have an account ...
	// (This is a terrible, terrible fudge for now)
	    
	    if (empty($_SESSION['last_forward_from']) && !empty($_SESSION['msg']) && elgg_is_logged_in() && in_array(elgg_echo('persona:welcome'),$_SESSION['msg']['success'])) {
		if (!substr_count(current_page_url(),'persona'))
			forward('persona/register');
	    }
	    
    }

/**
 * Serves the authentication back-end page
 * 
 * @param type $page
 * @return type 
 */
    
    function persona_auth_pagehandler($page) {
	header('X-No-Client-Cache:0');
	switch($page[0]) {
	    
	    case 'assert':	    
				    if (elgg_is_logged_in()) exit;
				    $assertion = get_input('assertion');
				    if(!empty($assertion)) {
					$url = 'https://verifier.login.persona.org/verify';
					$c = curl_init($url);
					$data = 'assertion='.$_POST['assertion'].'&audience=' . elgg_get_site_url();

					curl_setopt_array($c, array(
					    CURLOPT_RETURNTRANSFER  => true,
					    CURLOPT_POST            => true,
					    CURLOPT_POSTFIELDS      => $data,
					    CURLOPT_SSL_VERIFYPEER  => true,
					    CURLOPT_SSL_VERIFYHOST  => 2
					));

					$result = curl_exec($c);
					curl_close($c);

					$response = json_decode($result);
					
					// If Persona login worked out A-OK -
					if ($response->status == 'okay') {
					    $email = $response->email;
					    
					    // If the user already exists -
					    if ($user = get_user_by_email($email)) {
						if(is_array($user)) $user = array_pop($user);	// Why on earth would $user be an array here? But it is.
						login($user);
						if ($user->persona_status == 'prereg') {
						    system_message(elgg_echo('persona:welcome'));
						} else {
						    system_message(elgg_echo('loginok'));
						}
					    // Otherwise -
					    } else {
						$email = $response->email;
						$password = sha1();
						$username = md5($user->email);	// This is temporary
						$name = 'Persona user';		// This is temporary
						$user = new ElggUser();
						$user->username = $username;
						$user->email = $email;
						$user->name = $name;
						$user->access_id = ACCESS_PUBLIC;
						$user->salt = generate_random_cleartext_password(); // Note salt generated before password!
						$user->password = generate_user_password($user, generate_random_cleartext_password());
						$user->owner_guid = 0; // Users aren't owned by anyone, even if they are admin created.
						$user->container_guid = 0; // Users aren't contained by anyone, even if they are admin created.
						$user->language = get_current_language();
						$user->persona_status = 'prereg';
						try {
						    $user->save();
						} catch (Exception $e) {
						    error_log($e->getMessage());
						}
						try {
						    error_log(var_export($user,true));
						    if (!empty($user->guid)) {
							login($user);
							system_message(elgg_echo('persona:welcome'));
						    }
						} catch (Exception $e) {
						    error_log($e->getMessage());
						}
					    }
					} else {
					    header('HTTP',true,'400');	// Drop a bad request header
					}
					
				    }
				    exit;
				    break;
	    case 'logout':	    logout();
				    exit;
				    break;
	    case 'register':
				    gatekeeper();
				    $user = elgg_get_logged_in_user_entity();
				    if ($user->persona_status != 'prereg') forward();   // We only want Persona users who are pre-registered

				    $body = elgg_view_layout('one_column', array('content' => elgg_view_form('persona/register',array('user' => $user))));
				    
				    //page_draw($title, $body)
				    echo elgg_view_page(elgg_echo('persona:details'), $body);
				    break;
	    default:		    return false;
				    break;
	    
	}
	
    }

/**
 * Register as public pages for walled garden.
 *
 * @param string $hook
 * @param string $type
 * @param array  $return_value
 * @param array  $params
 */

    function persona_public_pages($hook, $type, $return_value, $params) {
	    $return_value[] = 'persona_auth/assert';
	    return $return_value;
    }

/**
 * Are Persona logins currently enabled?
 * 
 * @return boolean
 */

    function persona_is_enabled() {
	if ($enable = elgg_get_plugin_setting('enable_sign_on', 'persona')) {
	    return true;
	}
	return false;
    }
