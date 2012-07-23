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
	 * Elgg social commerce - manage settings
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 

	global $CONFIG;
	//admin_gatekeeper();
	load_checkout_actions();
	load_shipping_actions();
	load_withdraw_actions();
	load_currency_actions();
	
	$site = get_entity($CONFIG->site_guid);
	$manage_action = get_input('manage_action');
	$version_upgrade = get_input('version_upgrade');
	//Error Flag
	$error_validation = "";
	$holding_error =0;
	$http_url_error=0;
	//Error flag
	$version_flag = true; 
	switch ($manage_action){
		case 'versionUpdate':			
			if($version_upgrade == 1){
				if(upgrade_digitailProduct_Version()){
				}else{
					$version_flag = false;
				}
				if(upgrade_categories()){
				}else{
					$version_flag = false;
				}				
				if($version_flag === true){
					system_message(elgg_echo('socialcommerce:settings:version:upgarde:success'));
				}else{
					register_error(elgg_echo('socialcommerce:settings:version:upgarde:fail'));
				}
			}
			$redirect = $CONFIG->wwwroot.$CONFIG->pluginname.'/settings';
			break;
		case 'register_product':
			$socialcommerce_key = trim(get_input('socialcommerce_key'));			
			$guid = get_input('guid');
			if($guid > 0){
				$settings = get_entity($guid);
			}else{
				$settings = new ElggObject();
				$settings->subtype = 'splugin_settings';
				$settings->access_id = 2;
				$settings->owner_guid = $_SESSION['user']->guid;
				$settings->container_guid = $_SESSION['user']->guid;
			}
			$settings->socialcommerce_key = $socialcommerce_key;
				$settings->save();
			unset($_SESSION['msg']);
			if(!empty($socialcommerce_key)){
				$settings->socialcommerce_key = $socialcommerce_key;
				$settings->save();
				$acivation_key_status = get_activationKey($settings);	
				if(is_array($acivation_key_status)){
					if($acivation_key_status['verification'] == 'success'){
						$settings->activation_key = $acivation_key_status['activation_key'];
						$settings->activation_number = $acivation_key_status['activation_number'];
						$day = date('Y-m-d');
						datalist_set('check_date',$day);
						system_messages($acivation_key_status['message']);
					}else if ($acivation_key_status['verification'] == 'fail'){
						register_error("sorry\n".$acivation_key_status['code']." ".$acivation_key_status['message']);
					}
					$settings->code = $acivation_key_status['code'];
					$settings->verification = $acivation_key_status['verification'];
					//echo $settings->activation_key."##".$settings->activation_number."##".$acivation_key_status['verification'];
				}
			}
			$redirect = $CONFIG->wwwroot.$CONFIG->pluginname.'/settings';
			break;
		case 'settings':
			$checkoutmethods = get_input('checkout_method');
			if(!$checkoutmethods)
				$checkoutmethods = array();
			
			$shippingmethods = get_input('shipping_method');
			$allow_shipping_method = get_input('allow_shipping_method');
			$allow_tax_method = get_input('allow_tax_method');

			if(!$shippingmethods)
				$shippingmethods = array();
				
			$fund_withdraw_methods = get_input('fund_withdraw_method');
			if(!$fund_withdraw_methods)
				$fund_withdraw_methods = array();
				
			$percentage = get_input('socialcommerce_percentage');
			$allow_store_percetage=get_input('allow_store_percentage');
			
			$store_flat_amount = get_input('socialcommerce_flat_amount');
			$allow_store_flat_amount =  get_input('allow_store_flat_amount');
			
			$min_withdraw_amount = get_input('min_withdraw_amount');
			$river_settings = get_input('river_settings');
			
			$hide_system_message = get_input('hide_system_message');
			$send_mail_on_outofstock = get_input('send_mail_on_outofstock');
			
			$http_proxy_server = trim(get_input('http_proxy_server'));
			$http_proxy_port = get_input('http_proxy_port');
			$http_varify_ssl = get_input('http_varify_ssl');
			$allow_add_product = get_input('allow_add_product');
			$allow_add_cart = get_input('allow_add_cart');
			$withdraw_option = get_input('withdraw_option');
			
			$default_view = get_input('default_view');
			
			$https_allow = get_input('https_allow');
			if($https_allow){
				$https_url_text = get_input('https_url_text');
				if($https_url_text==""){
					$http_url_error=1;
					$error_validation .='https url should not be blank';
					$Comma =',';
				}
			}
			else{
				$https_allow =0;
				$https_url_text = "";
			}
			/*
			 * download new version
			 */			
			$download_newversion_allow = get_input('download_newversion_allow');
			if($download_newversion_allow>0){
				$download_newversion_allow = true;
				$download_newversion_days = get_input('download_newversion_days');
				if($download_newversion_days == ""){
					$http_url_error=1;
					$error_validation .=elgg_echo('settings:download:newversion:validation:error');
					$Comma =',';
				}
			}else{
				$download_newversion_allow = false;
				$download_newversion_days = "";
			}
			
			
			
			$allow_single_click_to_cart = get_input('allow_single_click_to_cart');
			
			$allow_multiple_version_digit_product = get_input('allow_mult_ver_digital_product');
			
			$share_this = get_input('share_this','',false);
			
			if($withdraw_option == 'escrow' || $withdraw_option == 'moderation_escrow')
			{	
				$holding_days = get_input('holding_days');
				if($holding_days<1){
					$holding_error=1;
					$error_validation .=$Comma.'holding days should be a number';
				}
			}	
			else
				$holding_days = '';
				
			$allow_add_coupon_code = get_input('allow_add_coupon_code');
			$allow_add_related_product = get_input('allow_add_related_product');
			
			//$socialcommerce_key = trim(get_input('socialcommerce_key'));
			
			
			//Ftp upload details save
			$ftp_upload_allow = trim(get_input('ftp_upload_allow'));			
			if($ftp_upload_allow>0){
				$ftp_upload_allow = true;				
				$ftp_host_url = trim(get_input('ftp_host_url'));
				$ftp_port = trim(get_input('ftp_port'));
				$ftp_user = trim(get_input('ftp_user'));
				$ftp_password = trim(get_input('ftp_password'));
				$ftp_upload_dir = trim(get_input('ftp_upload_dir'));
				$ftp_http_path = trim(get_input('ftp_http_path'));
				$ftp_base_path = trim(get_input('ftp_base_path'));
			}else{
				$ftp_upload_allow = "";
				$ftp_host_url = "";
				$ftp_port = "";
				$ftp_user = "";
				$ftp_password = "";
				$ftp_upload_dir = "";
				$ftp_http_path = "";
				$ftp_base_path = "";
			}
					
			$min_withdraw_amount = get_input('min_withdraw_amount');
			$river_settings = get_input('river_settings');
			
			$hide_system_message = get_input('hide_system_message');
			$send_mail_on_outofstock = get_input('send_mail_on_outofstock');
			
			$settings->https_allow = $https_allow;
			$settings->https_url_text = $https_url_text;
			$settings->allow_single_click_to_cart = $allow_single_click_to_cart;
			
			
			$guid = get_input('guid');
			if($guid){
				$settings = get_entity($guid);
			}else{
				$settings = new ElggObject($guid);
				$settings->subtype = 'splugin_settings';
				$settings->access_id = 2;
				$settings->container_guid = $_SESSION['user']->guid;
			}
			
			$settings->socialcommerce_percentage = $percentage;
			$settings->socialcommerce_flat_amount= $store_flat_amount;
			$settings->min_withdraw_amount = $min_withdraw_amount;
			$settings->checkout_methods = $checkoutmethods;
			$settings->shipping_methods = $shippingmethods;
			$settings->fund_withdraw_methods = $fund_withdraw_methods;
			$settings->withdraw_option = $withdraw_option;
			$settings->holding_days = $holding_days;
			$settings->default_view = $default_view;
			// For Add The https URL 
			$settings->https_allow = $https_allow;
			$settings->https_url_text = $https_url_text;
			$settings->allow_single_click_to_cart = $allow_single_click_to_cart;
			$settings->allow_multiple_version_digit_product = $allow_multiple_version_digit_product;
			// ftp for upload products
			$settings->ftp_upload_allow = $ftp_upload_allow;
			$settings->ftp_host_url = $ftp_host_url;
			$settings->ftp_port = $ftp_port;
			$settings->ftp_user = $ftp_user;
			$settings->ftp_password = $ftp_password;
			$settings->ftp_upload_dir = $ftp_upload_dir;
			$settings->ftp_http_path = $ftp_http_path;
			$settings->ftp_base_path = $ftp_base_path;
			$settings->share_this = $share_this;
			//Down load new version for digital product	
			$settings->download_newversion_allow = $download_newversion_allow;
			$settings->download_newversion_days = $download_newversion_days;
			
			if($allow_store_percetage==1) {
				$settings->allow_socialcommerce_store_percetage = $allow_store_percetage;
			}else {
				$settings->allow_socialcommerce_store_percetage = "";
			}
			if($allow_store_flat_amount==1) {
				$settings->allow_socialcommerce_flat_amount = $allow_store_flat_amount;
			}else {
				$settings->allow_socialcommerce_flat_amount ="";
			}
			
			
			
			if(!empty($river_settings)){
				$settings->river_settings = $river_settings;
			}else{
				$settings->river_settings = array();
			}
			if(!empty($hide_system_message)){
				$settings->hide_system_message = $hide_system_message;
			}else{
				$settings->hide_system_message = '';
			}
			if(!empty($send_mail_on_outofstock)){
				$settings->send_mail_on_outofstock = $send_mail_on_outofstock;
			}else{
				$settings->send_mail_on_outofstock = '';
			}
			if(!empty($http_proxy_server)){
				$settings->http_proxy_server = $http_proxy_server;
			}else{
				$settings->http_proxy_server = "";
			}
			if(!empty($http_proxy_port)){
				$settings->http_proxy_port = $http_proxy_port;
			}else{
				$settings->http_proxy_port = "";
			}
			if($http_varify_ssl == 1){
				$settings->http_varify_ssl = $http_varify_ssl;
			}else{
				$settings->http_varify_ssl = "";
			}
			if($allow_add_product == 1){
				$settings->allow_add_product = $allow_add_product;
			}else{
				$settings->allow_add_product = "";
			}
			if($allow_add_cart == 1){
				$settings->allow_add_cart = $allow_add_cart;
			}else{
				$settings->allow_add_cart = "";
			}
			if($allow_add_coupon_code == 1){
				$settings->allow_add_coupon_code = $allow_add_coupon_code;
			}else{
				$settings->allow_add_coupon_code = "";
			}
			if($allow_add_related_product == 1){
				$settings->allow_add_related_product = $allow_add_related_product;
			}else{
				$settings->allow_add_related_product = "";
			}
	        if($allow_shipping_method == 1){
				$settings->allow_shipping_method = 1;
			}else{
				$settings->allow_shipping_method = 2;
			}
	        if($allow_tax_method == 1){
				$settings->allow_tax_method = 1;
			}elseif($allow_tax_method == 2){
				$settings->allow_tax_method = 2;
			}else{
				$settings->allow_tax_method = 3;
			}
			/*unset($_SESSION['msg']);
			if(!empty($socialcommerce_key)){
				$settings->socialcommerce_key = $socialcommerce_key;
				$acivation_key_status = get_activationKey($settings);	
				if(is_array($acivation_key_status)){
					if($acivation_key_status['verification'] == 'success'){
						$settings->activation_key = $acivation_key_status['activation_key'];
						$settings->activation_number = $acivation_key_status['activation_number'];
						system_messages($acivation_key_status['message']);
					}else if ($acivation_key_status['verification'] == 'fail'){
						register_error("sorry\n".$acivation_key_status['code']." ".$acivation_key_status['message']);
					}
					$settings->code = $acivation_key_status['code'];
					$settings->verification = $acivation_key_status['verification'];
					//echo $settings->activation_key."##".$settings->activation_number."##".$acivation_key_status['verification'];
				}
			}*/
			if($holding_error!=0 || $http_url_error!=0)
			{
				
				register_error("sorry\n".$error_validation);
			}
			else
			{
				if($settings->save()){
					
					if(!empty($settings->socialcommerce_key)){
						$domain_name = "";
					}
					trigger_elgg_event('socialcommerce_settings',$settings->type,$settings);
				}
			}
			$redirect = $CONFIG->wwwroot.$CONFIG->pluginname.'/settings';
			break;	
		case 'membership':
			if(elgg_is_active_plugin('cubet_membership')) {
				$membership_buy_methods = get_input('membership_buy_method');
				$membership_sell_methods = get_input('membership_sell_method');
				if(!$membership_buy_methods)
					$membership_buy_methods = array();
				if(!$membership_sell_methods)
					$membership_sell_methods = array();
				$guid = get_input('guid');
				if($guid){
					$settings = get_entity($guid);
				}else{
					$settings = new ElggObject($guid);
					$settings->subtype = 'splugin_membership_settings';
					$settings->access_id = 2;
					$settings->container_guid = $_SESSION['user']->guid;
				}
				$settings->membership_buy_methods = $membership_buy_methods;
				$settings->membership_sell_methods = $membership_sell_methods;
				if($settings->save()) {
					// Get all members to update product access
					//Depricated function replace
					$options = array('types'			=>	"user",
									'limit'				=>	99999,	
								);
					$members = elgg_get_entities($options);
					//$members = get_entities("user", 0, 0,0,99999);
					foreach ($members as $member) {
						if(!$member->admin) {
							update_product_access($member->guid);
						}
					}
									
					system_message(elgg_echo("membership:ok"));
					
				} else {
					register_error(elgg_echo("membership:error"));
				}
				$redirect = $CONFIG->wwwroot.$CONFIG->pluginname.'/settings/membership';
			} else {
				$redirect = $CONFIG->wwwroot.$CONFIG->pluginname.'/settings';
			}
			break;
		case 'checkout':
			$order = get_input('order');
			$method = get_input('method');
			$function = 'set_checkout_settings_'.$method;
			if(function_exists($function)){
				$function();
			}
			$redirect = $CONFIG->wwwroot.$CONFIG->pluginname.'/settings/checkout?order='.$order;
			break;	
		case 'shipping':
			$order = get_input('order');
			$method = get_input('method');
			$function = 'set_shipping_settings_'.$method;
			if(function_exists($function)){
				$function();
			}
			$redirect = $CONFIG->wwwroot.$CONFIG->pluginname.'/settings/shipping?order='.$order;
			break;
		case 'makepayment':
			$method = get_input('payment_method');
			$function = 'makepayment_'.$method;
			
			if(function_exists($function)){
				$success = $function();
			}
			
			break;
		case 'cart_success':
			$body = view_success_page();
			$title = elgg_view_title(elgg_echo('cart:success'));
			$head = elgg_echo ('cart:success');
			$display = true;
			elgg_set_context('socialcommerce');
			break;
		case 'cart_cancel':
			$body = view_cancel_page();
			$title = elgg_view_title(elgg_echo('cart:cancel'));
			$head = elgg_echo ('cart:cancel');
			$display = true;
			elgg_set_context('socialcommerce');
			break;
		case 'checkout_error':
			$body = view_checkout_error_page();
			$title = elgg_view_title(elgg_echo('checkout:error'));
			$head = elgg_echo ('checkout:error');
			$display = true;
			elgg_set_context('socialcommerce');
			break;
		case 'withdraw':
			$order = get_input('order');
			$method = get_input('method');
			$function = 'set_withdraw_settings_'.$method;
			if(function_exists($function)){
				$function();
			}
			$redirect = $CONFIG->wwwroot.$CONFIG->pluginname.'/settings/withdraw?order='.$order;
			break;
		case 'show_wsettings':
			$method = get_input('method');
			$function = 'wsettings_forms_'.$method;
			if(function_exists($function)){
				$wsettings_form = $function();
			}else{
				$wsettings_form = "Settings does not exist";
			}
			echo $wsettings_form;
			break;
		case 'withdraw_action':
			$method = get_input('selected_method');
			if($method) {
				$function = 'withdraw_funt_'.$method;
				if(function_exists($function)){
					$result = $function();
					if(!$result && isset($_SESSION['WITHDRAW'])){
						$redirect = $CONFIG->wwwroot.$CONFIG->pluginname.'/my_account/withdraw?method='.$method;
					}else {
						$redirect = $CONFIG->wwwroot.$CONFIG->pluginname.'/my_account/withdraw';
					}
				}else{
					register_error(sprintf(elgg_echo('misconfigured:withdraw:function'),$function));
					$redirect = $CONFIG->wwwroot.$CONFIG->pluginname.'/my_account/withdraw?method='.$method;
				}
			} else {
				register_error(elgg_echo('select:withdraw:method'));
				$redirect = $CONFIG->wwwroot.$CONFIG->pluginname.'/my_account/withdraw?method='.$method;
			}
			break;	
		case 'add_currency':
			$context = elgg_get_context();
			elgg_set_context('add_settings');
			
			$guid = get_input('guid');
			$user_guid = get_input('u_id');
			$currency_name = trim(get_input('c_name'));
			$currency_country = trim(get_input('c_country'));
			$currency_code = trim(get_input('c_code'));
			$exchange_rate = trim(get_input('e_rate'));
			$currency_token = trim(get_input('c_token'));
			$token_location = trim(get_input('t_location'));
			$decimal_token = trim(get_input('d_token'));
			$set_default = trim(get_input('set_def'));
			
			if($currency_name == ""){
				$err_field = elgg_echo('currency:name');
			}
			
			if($currency_country == ""){
				$err_field .= ($err_field != "") ? ','.elgg_echo('currency:country') : elgg_echo('currency:country');
			}
			
			if($currency_code == ""){
				$err_field .= ($err_field != "") ? ','.elgg_echo('currency:code') : elgg_echo('currency:code');
			}
			
			if($exchange_rate == ""){
				$err_field .= ($err_field != "") ? ','.elgg_echo('exchange:rate') : elgg_echo('exchange:rate');
			}else{
				$pattern = '/^((\d+(\.\d*)?)|((\d*\.)?\d+))$/';
				if(!preg_match($pattern,$exchange_rate)){	
					$err_field .= ($err_field != "") ? ','.elgg_echo('exchange:rate') : elgg_echo('exchange:rate');
				}
			}
			
			if($currency_token == ""){
				$err_field .= ($err_field != "") ? ','.elgg_echo('currency:token') : elgg_echo('currency:token');
			}
			
			if($token_location == ""){
				$err_field .= ($err_field != "") ? ','.elgg_echo('token:location') : elgg_echo('token:location');
			}
			
			$pattern = '/^\d+$/';
			if($decimal_token == "" || !preg_match($pattern,$decimal_token)){
				$err_field .= ($err_field != "") ? ','.elgg_echo('decimal:token') : elgg_echo('decimal:token');
			}
			
			if($err_field == ""){
				if($guid > 0){
					$currency = get_entity($guid);
				}else{
					$currency = new ElggObject();
				}
				$currency->access_id = 2;
				$currency->owner_guid = $user_guid;
				$currency->container_guid = $user_guid;
				$currency->subtype = 's_currency';
				$currency->currency_name = $currency_name;
				$currency->currency_country = $currency_country;
				$currency->currency_code = $currency_code;
				$currency->exchange_rate = $exchange_rate;
				$currency->currency_token = $currency_token;
				$currency->token_location = $token_location;
				$currency->decimal_token = $decimal_token;
				$currency->set_default = $set_default;
				$result = $currency->save();
				echo $result;
			}else{
				echo sprintf(elgg_echo('error:currency:settings'),$err_field);
			}
			elgg_set_context($context);
			exit;
			break;	
		case 'delete_currency':
			$context = elgg_get_context();
			elgg_set_context('add_settings');
			$user_guid = get_input('u_id');
			elgg_set_page_owner_guid($user_guid);
			$currency_guid = get_input('c_id');
			$currency = get_entity($currency_guid);
			$subtype = get_subtype_id('object', 's_currency');
			if($currency && $currency->subtype == $subtype){
				$delete = $currency->delete();
				if($delete){
					echo 1;
				}else{
					echo elgg_echo('currency:deletefailed');
				}
			}else{
				echo elgg_echo('currency:deletefailed');
			}
			elgg_set_context($context);
			break;
		case 'set_default_currency':
			$context = elgg_get_context();
			elgg_set_context('add_settings');
			$user_guid = get_input('u_id');
			elgg_set_page_owner_guid($user_guid);
			$currency_guid = get_input('c_id');
			$default_currency = get_entity($currency_guid);
			$from_code = $default_currency->currency_code;
			//Depricated function replace
			$options = array('types'			=>	"object",
							'subtypes'			=>	"s_currency",
							'limit'				=>	99999,	
						);
			$currencies = elgg_get_entities($options);
			//$currencies = get_entities('object','s_currency',0,'',99999);
			if($currencies){
				foreach ($currencies as $currency){
					if($currency_guid == $currency->guid){
						$currency->set_default = 1;
						$set_default = 1;
						$currency->exchange_rate = 1;
					}else{
						$currency->set_default = 0;
						$to_code = $currency->currency_code;
						if(function_exists('get_exchange_rate')){
							if(!defined('ISC_SAFEMODE')) {
								define('ISC_SAFEMODE', @ini_get('safemode'));
							}
							$e_rate = get_exchange_rate($from_code, $to_code);	
							$currency->exchange_rate = $e_rate;
						}
					}
					$currency->save();
				}
				if($set_default){
					echo 1;	
				}else{
					echo elgg_echo('currency:set_default:failed');
				}
			}else {
				echo elgg_echo('currency:set_default:failed');
			}
			elgg_set_context($context);
			break;
		case 'get_exchange_rate':
			$to_code = get_input('c_code');
			//Depricated function replace
			$options = array(	'metadata_name_value_pairs'	=>	array('set_default' => 1),
								'types'				=>	"object",
								'subtypes'			=>	"s_currency",
								'limit'				=>	1,
							);
			$default_currency = elgg_get_entities_from_metadata($options);
			//$default_currency = get_entities_from_metadata('set_default',1,'object','s_currency',0,1);
			if($default_currency){
				$default_currency = $default_currency[0];
				$from_code = $default_currency->currency_code;
				if($from_code && $to_code){
					if(function_exists('get_exchange_rate')){
						if(!defined('ISC_SAFEMODE')) {
							define('ISC_SAFEMODE', @ini_get('safemode'));
						}
						echo get_exchange_rate($from_code, $to_code);	
					}else {
						
					}
				}
			}else{
				
			}
			break;
		case 'set_checkout_session':
			$url = get_input('url');
			$_SESSION['last_forward_from'] = $url;
			break;
		case 'withdraw_request':
			$total_useramt = get_input('total_useramt');
			$total = get_price_with_currency($total_useramt);
			$requestwithdrawal = new ElggObject();
			$requestwithdrawal->access_id = 2;
			$requestwithdrawal->owner_guid = $_SESSION['user']->guid;
			$requestwithdrawal->container_guid = $_SESSION['user']->guid;
			$requestwithdrawal->subtype = 'wth_request';
			$requestwithdrawal->description = get_input('request_desc');
			$requestwithdrawal->approval = 0;
			$requestwithdrawal->processed = 0;
			$result = $requestwithdrawal->save();
			if($result){
				$from = $_SESSION['user'];
				$to = get_site_admin();
				$subject = sprintf(elgg_echo('request:mail:subject'),$site->name);
				$message = sprintf(elgg_echo('request:mail'),$to->name,$total,$_SESSION['user']->name);
				stores_send_mail($from,$to,$subject,$message,$headers = null);
				
				system_message(elgg_echo("request:saved"));
				$redirect = $CONFIG->wwwroot.$CONFIG->pluginname.'/my_account/withdraw';
			}else{
				register_error(elgg_echo("request:sendfailed"));
				$redirect = $CONFIG->wwwroot.$CONFIG->pluginname.'/my_account/withdraw';
			}
			break;
		case 'wthdwl_request_approval':
			$wth_requset_id=get_input('wth_requset_id');
			$wthdwl_requset = get_entity($wth_requset_id);
			if($wthdwl_requset){
				$wthdwl_requset->approval = 1;
				$result = $wthdwl_requset->save();
				if($result){
					$from = $_SESSION['user'];
					$to = get_entity($wthdwl_requset->owner_guid);
					$subject = sprintf(elgg_echo('approval:mail:subject'),$site->name);
					$message = sprintf(elgg_echo('approval:mail'),$to->name,$site->name,$site->name);
					stores_send_mail($from,$to,$subject,$message,$headers = null);
					
					echo $result;
				}else{
					echo elgg_echo("with:approval:failed");
				}
			}
			exit;
			break;
		case 'wthdwl_request_denied':
			$wth_requset_id=get_input('wth_requset_id');
			$wthdwl_requset = get_entity($wth_requset_id);
			if($wthdwl_requset){
				$wthdwl_requset->approval = 2;
				$result = $wthdwl_requset->save();
				if($result){
					$from = $_SESSION['user'];
					$to = get_entity($wthdwl_requset->owner_guid);
					$subject = sprintf(elgg_echo('denied:mail:subject'),$site->name);
					$message = sprintf(elgg_echo('denied:mail'),$to->name,$site->name,$site->name);
					stores_send_mail($from,$to,$subject,$message,$headers = null);					
					
					echo $result;
				}else{
					echo elgg_echo("with:denined:failed");
				}
			}
			exit;
			break;
		case 'coupon_process':
				$couponcode = get_input('code');
				$coupon = get_coupon_by_couponcode($couponcode);
				$allow_coupon = 0;
				if($coupon){
					$curren_datetime = mktime (0,0,0,date("n"),date("j"),date("Y"));
					$exp_date = $coupon->exp_date;
					if($exp_date && $curren_datetime > $exp_date){
						echo "exp_date,".date("M d Y",$exp_date);
						exit;
					}
					$coupon_maxuses = $coupon->coupon_maxuses;
					if($coupon_maxuses != "Unlimited"){
						$coupon_uses = get_coupon_uses($coupon->guid);
						if($coupon_uses){
							$coupon_uses = count($coupon_uses);
						}else{
							$coupon_uses = 0;
						}
						if(($coupon_maxuses - $coupon_uses) == 0){
							echo "coupon_maxuses";
							exit;
						}
					}
					$coupon_amount = $coupon->coupon_min_purchase;
					if($coupon_amount > 0){
						$cart_total = calculate_cart_total();
						if($cart_total < $coupon_amount){
							echo 'coupon_amount_less,'.$coupon_amount;
							exit;
						}
					}
					//Depricated function replace
					$options = array('types'			=>	"object",
									'subtypes'			=>	"cart",
									'owner_guids'		=>	$_SESSION['user']->getGUID(),
								);
					$cart = elgg_get_entities($options);
					//$cart = get_entities('object','cart',$_SESSION['user']->getGUID());
					if($cart){
						$cart = $cart[0];
						//Depricated function replace
						$options = array('relationship' 		=> 'cart_item',
										'relationship_guid' 	=> $cart->guid,
										);
						$cart_items = elgg_get_entities_from_relationship($options);
						//$cart_items = get_entities_from_relationship('cart_item',$cart->guid);
						if($cart_items){
							foreach ($cart_items as $cart_item){
								if($product = get_entity($cart_item->product_id)){
									if(check_entity_relationship($coupon->guid, 'coupon_product', $product->guid)){
										$allow_coupon = 1;
										$cart_item->coupon_code = $couponcode;
										$cart_item->save();
									}
								}
							}
						}
					}
					if($allow_coupon){
						echo "coupon_applied";
					}else{
						echo "not_applied";
					}
				}else{
					echo "no_coupon";
				}
			break;
		case 'coupon_reload_process':
		//.........	
		/*$taxrate = get_entities('object','addtax_common');
		foreach($taxrate as $taxrates)
		{
			$taxrate_val = $taxrates->taxrate;
		}
		$tax_price = generate_tax($taxrate_val,$price,$quantity,$grand_total);*/
		//..........	
				//Depricated function replace
				$options = array('types'			=>	"object",
								'subtypes'			=>	"cart",
								'owner_guids'		=>	$_SESSION['user']->getGUID(),
							);
				$cart = elgg_get_entities($options);
				//$cart = get_entities('object','cart',$_SESSION['user']->getGUID());
				if($cart){
					$cart = $cart[0];
					//Depricated function replace
					$options = array('relationship' 		=> 'cart_item',
									'relationship_guid' 	=> $cart->guid,
									);
					$cart_items = elgg_get_entities_from_relationship($options);
					//$cart_items = get_entities_from_relationship('cart_item',$cart->guid);
					if($cart_items){
						$grand_total = $total = 0;
						//$grand_total = $grand_total+$tax_price;
						/*foreach ($cart_items as $cart_item){
							if($product = get_entity($cart_item->product_id)){
								$title = $product->title;
								$discount_price = $price = $product->price;
								$cart_item_coupon = $cart_item->coupon_code;
								if($cart_item_coupon){
									$cart_item_coupon = get_coupon_by_couponcode($cart_item_coupon);
									if($cart_item_coupon){
										$coupon_amount = $cart_item_coupon->coupon_amount;
										$coupon_type = $cart_item_coupon->coupon_type;
										if($coupon_type != 1){
											$coupon_amount = round(($price * $coupon_amount) / 100,2);
										}
										$discount_price = $price - $coupon_amount;
									}
								}
								$quantity = $cart_item->quantity;
								
								$total = $quantity * $discount_price;
								$grand_total += $total;
								$display_price = get_price_with_currency($price);
								if($cart_item_coupon){
									$display_price = "<span class='display_original_price'>".get_price_with_currency($price)."</span>".get_price_with_currency($discount_price);
								}
								$display_total = get_price_with_currency($total);
								$item_details .= <<<EOF
									<tr>
										<td style="width:350px;">{$title}</td>
										<td style="text-align:center;">{$quantity}</td>
										<td style="text-align:right;">{$display_price}</td>
										<td style="text-align:right;">{$display_total}</td>
									</tr>
EOF;
							}
						}*/
						foreach ($cart_items as $cart_item){
							if($product = get_entity($cart_item->product_id)){
								$title = $product->title;
								$discount_price = $price = $product->price;
								$country_code = $product->countrycode;
								$cart_item_coupon = $cart_item->coupon_code;
								if($cart_item_coupon){
									$cart_item_coupon = get_coupon_by_couponcode($cart_item_coupon);
									if($cart_item_coupon){
										$coupon_amount = $cart_item_coupon->coupon_amount;
										$coupon_type = $cart_item_coupon->coupon_type;
										if($coupon_type != 1){
											$coupon_amount = round(($price * $coupon_amount) / 100,2);
										}
										$discount_price = $price - $coupon_amount;
									}
								}
								//Depricated function replace
								$options = array('relationship' 		=> 	'cart_related_item',
												'relationship_guid' 	=>	$cart_item->guid,
												'types'					=>	'object',
												'subtypes'				=>	'cart_related_item',
												'limit'					=>	99999,
												);
								$related_products = elgg_get_entities_from_relationship($options);
								//$related_products = get_entities_from_relationship('cart_related_item',$cart_item->guid,'','object','cart_related_item','','',9999);
								$related_product_price = 0;
								$related_products_display = $related_products_price_display = '';
								if($related_products){
									foreach($related_products as $related_product){
										$details = $related_product->details;
										if(!is_array($details) && $details != ''){
											$details = array($details);
										}
										if(!empty($details)){
											foreach($details as $detail){
												$detail = get_entity($detail);
												if($detail){
													$detail_price = $detail->price;
													$detail_price_display = get_price_with_currency($detail_price);
													$related_product_price += $detail_price;
													$related_products_display .= <<<EOF
														<div class="related_details">
															<div style="float:left;">{$detail->title}</div>
															<div class="clear"></div>
														</div>
EOF;
													$related_products_price_display .= <<<EOF
														<div>
															<div>{$detail_price_display}</div>
														</div>
EOF;
												}
											}
										}
									}
								}
								$quantity = $cart_item->quantity;
								$total = $tax_total = $quantity * $discount_price;
								$grand_total += $total;
								if($related_product_price > 0){
									$grand_total += $related_product_price;
									$tax_total += $related_product_price;
								}
									
								if($CONFIG->allow_tax_method == 2) {
									$tax_price += generate_tax($tax_total, '',$country_code);
								} else {
									$tax_price += generate_tax($tax_total, '');
								}
								$display_price = get_price_with_currency($price);
								if($cart_item_coupon){
									$display_price = "<span class='display_original_price'>".get_price_with_currency($price)."</span>".get_price_with_currency($discount_price);
								}
								$display_total = get_price_with_currency($total);
								$item_details .= <<<EOF
									<tr>
										<td style="width:350px;">
											{$title}
											{$related_products_display}
										</td>
										<td style="text-align:center;">{$quantity}</td>
										<td style="text-align:right;">
											{$display_price}
											{$related_products_price_display}
										</td>
										<td style="text-align:right;">
											{$display_total}
											{$related_products_price_display}
										</td>
									</tr>
EOF;
							}
						}
						if($tax_price > 0){
							$grand_total = $grand_total + $tax_price;
						}
						$display_tax_dollar= get_price_with_currency($tax_price);
						$grand_total += $_SESSION['CHECKOUT']['shipping_price'];
						$_SESSION['CHECKOUT']['total'] = $grand_total;
					}
				}
				
				if($_SESSION['CHECKOUT']['allow_shipping'] == 1){
					$display_shipping_price = get_price_with_currency($_SESSION['CHECKOUT']['shipping_price']);
					$checkout_shipping_text = elgg_echo('checkout:shipping');
					$shipping_details = <<<EOF
						<tr>
							<td class="order_total" colspan="4">
								<div style="width:100px;float:right;">{$display_shipping_price}</div>
								<div style="padding-right:30px;">{$checkout_shipping_text}: </div> 
							</td>
						</tr>
EOF;
				}
				$checkout_tax = elgg_echo('checkout:tax');
				
				if($CONFIG->allow_tax_method == 1){
					$tax_line = '';
				}else{ //($CONFIG->allow_tax_method == 2 & 3) 
					$tax_line = <<<CTAX
					    <tr>
							<td class="order_total" colspan="4">
								<div style="width:100px;float:right;">{$display_tax_dollar}</div>
								<div style="padding-right:30px;">{$checkout_tax}: </div> 
							</td>
						</tr>
CTAX;
	}
				$display_grand_total = get_price_with_currency($grand_total);
				$cart_item_text = elgg_echo('checkout:cart:item');
				$qty_text = elgg_echo('checkout:qty');
				$item_price_text = elgg_echo('checkout:item:price');
				$cart_item_total_text = elgg_echo('checkout:item:total');
				$cart_total_cost = elgg_echo('checkout:total:cost');
				echo $cart_body = <<<EOF
					<table class="checkout_table">
						<tr>
							<th><B>{$cart_item_text}</B></th>
							<th style="text-align:center;"><B>{$qty_text}</B></th>
							<th style="text-align:right;"><B>{$item_price_text}</B></th>
							<th style="text-align:right;"><B>{$cart_item_total_text}</B></th>
						</tr>
						{$item_details}
						{$tax_line}
						{$shipping_details}
						<tr>
							<td class="order_total" colspan="4">
								<div style="width:100px;float:right;">{$display_grand_total}</div>
								<div style="padding-right:30px;">{$cart_total_cost}: </div> 
							</td>
						</tr>
					</table>
EOF;
			break;
	}
		if(!$display && $redirect){
			forward($redirect);
		}else if($display){
			if($view != 'rss'){
				$area2 = elgg_view("{$CONFIG->pluginname}/storesWrapper",array('body'=>$body));
			}
		
			// These for left side menu
			$area1 .= gettags();

			if($head) {
				$title = $head;
			}
			// Create a layout
			$body = elgg_view_layout('content', array(
				'filter' => '',
				'content' => $area2,
				'title' => $title,
				'sidebar' => $area1,
			));
			
			// Finally draw the page
			echo elgg_view_page($title, $body);
		}
	exit;
?>