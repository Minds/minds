<?php
    /**
    * Elgg Membership plugin
    * Elgg register action
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    global $CONFIG;
    elgg_make_sticky_form('register');
    action_gatekeeper();
	
    if(isset($_SESSION['coupon_code'])){
        unset($_SESSION['coupon_code']);
    }
    
    $plugin_settings = $CONFIG->plugin_settings;
    $allow_payment = $plugin_settings->allow_regpayment;
    $auth_apiloginid = $plugin_settings->authorizenet_apiloginid;
    $auth_transkey = $plugin_settings->authorizenet_transactionkey;

    // Get variables
    $username = get_input('username');
    $usertype = get_input('usertype');
    $password = get_input('password');
    $password2 = get_input('password2');
    $email = get_input('email');
    $name = get_input('name');
    $friend_guid = (int) get_input('friend_guid',0);
    $invitecode = get_input('invitecode');
    $admin = get_input('admin');
    $email_settings = $CONFIG->plugin_settings->paypal_email;
    $amount_settings = $CONFIG->plugin_settings->payment_type;
    $show_checkout = get_input('payment_method');
    // for free user type on 29-12-2011
    if($allow_payment == '1'){
	    if($usertype != "Free") {// Not a free user
	        if($show_checkout =="authorizenet")
	        {
	            if(($usertype != "Free")&&($auth_apiloginid == '' || $auth_transkey == '')) {
	                system_message(sprintf(elgg_echo("payment:settings:error")));
	                forward($_SERVER['HTTP_REFERER']);
	            }
	        }else{
	            if(($usertype != "Free")&&((!$email_settings) || (!$amount_settings))) {
	                system_message(sprintf(elgg_echo("payment:settings:error")));
	                forward($_SERVER['HTTP_REFERER']);
	            }
	        }
	    }
    }
    if (is_array($admin)) { 
        $admin = $admin[0];
    }
    

    if (elgg_get_config('allow_registration')) {
    // For now, just try and register the user
        try {
            if (trim($password) == "" || trim($password2) == "") {
			throw new RegistrationException(elgg_echo('RegistrationException:EmptyPassword'));
            }

            if (strcmp($password, $password2) != 0) {
                    throw new RegistrationException(elgg_echo('RegistrationException:PasswordMismatch'));
            }

            $guid = register_user($username, $password, $name, $email, false, $friend_guid, $invitecode);

            if ($guid) {
//                $new_user = get_entity($guid);
//                if($allow_payment == '1'){
//                	$new_user->user_type = 'Free';
//                }else{
//                	$new_user->user_type = $usertype;
//                }
                $new_user = get_entity($guid);
                $new_user->user_type = 'Free';
                $new_user->save();
                
                if($allow_payment == '1'){
	                if($usertype == "Free"){
	                    global $registering_admin;
	                    elgg_push_context('uservalidationbyemail_new_user');
	                    $hidden_entities = access_get_show_hidden_status();
	                    access_show_hidden_entities(TRUE);
	                    $new_user->disable('uservalidationbyemail_new_user', FALSE);
	                    elgg_set_user_validation_status($guid, FALSE);
	                    if (!$registering_admin) {
	                        uservalidationbyemail_request_validation($guid);
	                    }
	                    access_show_hidden_entities($hidden_entities);
	                    elgg_pop_context();
	                    system_message(elgg_echo("registerok", array(elgg_get_site_entity()->name)));
	                    forward();
	                    
	                } else {
	                    if($show_checkout =="authorizenet"){
	                        $_SESSION['register']['username'] = $username;
	                        $_SESSION['register']['password'] = $password;
	                        $_SESSION['register']['email'] = $email;
	                        $_SESSION['register']['name'] = $name;
	                        $_SESSION['register']['friend_guid'] = $friend_guid;
	                        $_SESSION['register']['invitecode'] = $invitecode;
	                        forward("$CONFIG->wwwroot"."membership/authorizenet/$guid/$usertype");
	                    }else { //if paypal
	                        forward("$CONFIG->wwwroot"."membership/payment/$guid/$usertype");
	                    }
	                }
               }else{
                    global $registering_admin;
                    elgg_push_context('uservalidationbyemail_new_user');
                    $hidden_entities = access_get_show_hidden_status();
                    access_show_hidden_entities(TRUE);
                    $new_user->disable('uservalidationbyemail_new_user', FALSE);
                    elgg_set_user_validation_status($guid, FALSE);
                    if (!$registering_admin) {
                        uservalidationbyemail_request_validation($guid);
                    }
                    access_show_hidden_entities($hidden_entities);
                    elgg_pop_context();
                    system_message(elgg_echo("registerok", array(elgg_get_site_entity()->name)));
                    forward();
               }
            } else {
                register_error(elgg_echo("registerbad"));
            }
        } catch (RegistrationException $r) {
            register_error($r->getMessage());
        }
    } else {
        register_error(elgg_echo('registerdisabled'));
    }

    forward(REFERER);