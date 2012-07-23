<?php
/*****************************************************************************\
	+-----------------------------------------------------------------------------+
	| Elgg Socialcommerce Plugin                                                  |
	| Copyright (c) 2009-20010 Cubet Technologies <socialcommerce@cubettech.com>  |
	| All rights reserved.                                                        |
	+-----------------------------------------------------------------------------+
	| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" |
	| FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE  |
	| AT THE FOLLOWING URL: http://socialcommerce.elgg.in/license.html            |
	|                                                                             |
	| THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE |
	| THIS  SOFTWARE   PROGRAM  AND   ASSOCIATED   DOCUMENTATION    THAT  CUBET   |
	| TECHNOLOGIES (hereinafter referred as "THE AUTHOR") IS FURNISHING OR MAKING |
	| AVAILABLE TO YOU WITH  THIS  AGREEMENT  (COLLECTIVELY,  THE  "SOFTWARE").   |
	| PLEASE   REVIEW   THE  TERMS  AND   CONDITIONS  OF  THIS  LICENSE AGREEMENT |
	| CAREFULLY   BEFORE   INSTALLING   OR  USING  THE  SOFTWARE.  BY INSTALLING, |
	| COPYING   OR   OTHERWISE   USING   THE   SOFTWARE,  YOU  AND  YOUR  COMPANY |
	| (COLLECTIVELY,  "YOU")  ARE  ACCEPTING  AND AGREEING  TO  THE TERMS OF THIS |
	| LICENSE   AGREEMENT.   IF  YOU    ARE  NOT  WILLING   TO  BE  BOUND BY THIS |
	| AGREEMENT, DO  NOT INSTALL OR USE THE SOFTWARE.  VARIOUS   COPYRIGHTS   AND |
	| OTHER   INTELLECTUAL   PROPERTY   RIGHTS    PROTECT   THE   SOFTWARE.  THIS |
	| AGREEMENT IS A LICENSE AGREEMENT THAT GIVES  YOU  LIMITED  RIGHTS   TO  USE |
	| THE  SOFTWARE   AND  NOT  AN  AGREEMENT  FOR SALE OR FOR  TRANSFER OF TITLE.|
	| THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY GRANTED BY THIS AGREEMENT.      |
	|                                                                             |
	+-----------------------------------------------------------------------------+
	\*****************************************************************************/
	
	/**
	 * Elgg form - confirm cart lists
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 

	global $CONFIG;
	
	// Get variables
	$password = generate_random_password();
	$password2 = $password;
	$email = get_input('email');
	$name = $username;
	$checkout_selection = get_input('checkout_selection','normal');
	$friend_guid = '';
	$invitecode = '';
	unset($_SESSION['address']);
	if($checkout_selection == 'normal'){
			
		$firstname = trim(get_input('first_name'));
		$lastname = trim(get_input('last_name'));
		$address_line_1 = trim(get_input('add_line1'));
		$address_line_2 = trim(get_input('add_line2'));
		$city = trim(get_input('city'));
		$country = trim(get_input('currency_country'));
		$state = trim(get_input('state'));
		$pincode = trim(get_input('pincode'));
		$mobileno = trim(get_input('mobile'));
		$phoneno = trim(get_input('phone'));
		$email = get_input('address_email');
		
		$_SESSION['address']['first_name'] = $firstname;
		$_SESSION['address']['last_name'] = $lastname;
		$_SESSION['address']['email'] = $email;
		$_SESSION['address']['address'] = $address_1;
		$_SESSION['address']['address_line_1'] = $address_line_1;
		$_SESSION['address']['address_line_2'] = $address_line_2;
		$_SESSION['address']['city'] = $city;
		$_SESSION['address']['state'] = $state;
		$_SESSION['address']['country'] = $country;
		$_SESSION['address']['pincode'] = $pincode;
		$_SESSION['address']['mobileno'] = $mobileno;
		$_SESSION['address']['phoneno'] = $phoneno;	
		$validation_error = "" ;
		$forward_url = $_SESSION['last_forward_from'];
		if($forward_url == ""){
			$forward_url = $_SERVER['HTTP_REFERER'];
		}
		$comma="";
		if($firstname == ""){			
			$validation_error .= $comma.elgg_echo('first:name');
			$comma=", ";
		}
		if($lastname == ""){			
			$validation_error .= $comma.elgg_echo('last:name');
			$comma=", ";
		}
		if($email == ""){			
			$validation_error .= $comma.elgg_echo('email');
			$comma=", ";
		}
		if($address_line_1 == ""){			
			$validation_error .= $comma.elgg_echo('address:line:1');
			$comma=", ";
		}
		if($city == ""){			
			$validation_error .= $comma.elgg_echo('city');
			$comma=", ";
		}
		if($state == ""){
			$validation_error .= $comma.elgg_echo('state');
			$comma=", ";
		}
		if($country == ""){
			$validation_error .= $comma.elgg_echo('country');
			$comma=", ";
		}
		if($pincode == ""){
			$validation_error .= $comma.elgg_echo('pincode');
			$comma=", ";
		}
		if($validation_error!=""){
			register_error(sprintf(elgg_echo("socialcommerce:account_checkout:valid:error"),$validation_error));
			forward($forward_url);
		}	
	}

	$qs = explode('?',$_SERVER['HTTP_REFERER']);
	$qs = $qs[0];
	$qs .= "?u=" . urlencode($username) . "&e=" . urlencode($email) . "&n=" . urlencode($name) . "&friend_guid=" . $friend_guid;
	$user_details = create_useraccout_deatils($email);
	if($user_details === false || count($user_details)<1){	
		forward($qs);
		exit;
	}
	$username = $user_details['username'];
	$name = $user_details['name'];
	
	if (!$CONFIG->disable_registration) {
	// For now, just try and register the user
		try {
			$guid = register_user($username, $password, $name, $email, false, $friend_guid, $invitecode);
			if (((trim($password) != "") && (strcmp($password, $password2) == 0)) && ($guid)) {
				$new_user = get_entity($guid);
				$container_guid = $guid;
				set_user_validation_status($guid, true, 'email');				
				system_message(sprintf(elgg_echo("registerok"),$CONFIG->sitename));
				
				if($checkout_selection == 'normal'){
					$address = new ElggObject();
					$address->subtype="address";
					$address->owner_guid = $guid;
					$address->title = $firstname . " " . $lastname;
					$address->first_name = $firstname;
					$address->last_name = $lastname;
					$address->address_line_1 = $address_line_1;
					$address->address_line_2 = $address_line_2;
					$address->city = $city;
					$address->state = $state;
					$address->country = $country;
					$address->pincode = $pincode;
					$address->mobileno = $mobileno;
					
					if(!empty($phoneno))
						$address->phoneno = $phoneno;
					$address->access_id = 2;
					
					if ($container_guid){
						$address->container_guid = $container_guid;
					}
					$context = elgg_get_context();
					elgg_set_context('account_address');
					$result = $address->save();
					elgg_set_context($context);					
					if(!empty($first_name)){
						$new_user->name = $firstname;
						$new_user->save();
					}
					// Remove the blog post cache
					unset($_SESSION['address']);	
				}
				
				/* Automatic Login for a user after create an account*****************************/
				$persistent = get_input("persistent", false);
				// If all is present and correct, try to log in
				$result = false;
				if (!empty($username) && !empty($password)) {
					if ($user = authenticate($username,$password)) {
						$result = login($user, $persistent);
					}
				}
				$_SESSION['last_forward_from'] = $CONFIG->wwwrooot."socialcommerce/checkout_process";
				// Set the system_message as appropriate
				if ($result) {
					system_message(elgg_echo('loginok'));
					notify_user($new_user->guid, $CONFIG->site->guid, elgg_echo('socialcommerce:account_checkout:useradd:subject'), sprintf(elgg_echo('socialcommerce:account_checkout:useradd:body'), $name, $CONFIG->site->name, $CONFIG->site->url, $username, $password));
					if (isset($_SESSION['last_forward_from']) && $_SESSION['last_forward_from']) {
						$forward_url = $_SESSION['last_forward_from'];
						unset($_SESSION['last_forward_from']);
						forward($forward_url);
					} else {
						if (get_input('returntoreferer')) {
							forward($_SERVER['HTTP_REFERER']);
						} else {
							forward("dashboard/");
						}
					}
				} else {
					$error_msg = elgg_echo('loginerror');
					// figure out why the login failed
					if (!empty($username) && !empty($password)) {
						// See if it exists and is disabled
						$access_status = access_get_show_hidden_status();
						access_show_hidden_entities(true);
						if (($user = get_user_by_username($username)) && !$user->validated) {
							// give plugins a chance to respond
							if (!trigger_plugin_hook('unvalidated_login_attempt','user',array('entity'=>$user))) {
								// if plugins have not registered an action, the default action is to
								// trigger the validation event again and assume that the validation
								// event will display an appropriate message
								trigger_elgg_event('validate', 'user', $user);
							}
						} else {
							register_error(elgg_echo('loginerror'));
						}
						access_show_hidden_entities($access_status);
					} else {
						register_error(elgg_echo('loginerror'));
					}
				}
				// Forward on success, assume everything else is an error...
				forward($qs);
				/* Automatic Login for a user after create an account*****************************/
			} else {
				register_error(elgg_echo("registerbad"));
			}
		} catch (RegistrationException $r) {
			register_error($r->getMessage());
		}
	} else {
		register_error(elgg_echo('registerdisabled'));
	}

	forward($qs);
?>