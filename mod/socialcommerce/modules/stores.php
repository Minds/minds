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
 * Elgg stores - actions
 * 
 * @package Elgg SocialCommerce
 * @author Cubet Technologies
 * @copyright Cubet Technologies 2009-2010
 * @link http://elgghub.com
 */ 

/*
 * Add checkout patha and checkout view path are in CONFIG
 */
function register_socialcommerce_settings(){
	global $CONFIG;
	$CONFIG->checkout_path = $CONFIG->pluginspath.$CONFIG->pluginname."/modules/checkout";
	$CONFIG->checkout_view_path = $CONFIG->pluginspath.$CONFIG->pluginname."/views/default/modules/checkout";
	$CONFIG->shipping_path = $CONFIG->pluginspath.$CONFIG->pluginname."/modules/shipping";
	$CONFIG->shipping_view_path = $CONFIG->pluginspath.$CONFIG->pluginname."/views/default/modules/shipping";
	$CONFIG->fund_withdraw_path = $CONFIG->pluginspath.$CONFIG->pluginname."/modules/withdraw";
	$CONFIG->fund_withdraw_view_path = $CONFIG->pluginspath.$CONFIG->pluginname."/views/default/modules/withdraw";
	$CONFIG->currency_path = $CONFIG->pluginspath.$CONFIG->pluginname."/modules/currency";
	SetGeneralValuesInConfig();	
	load_module_languages();
	socialCommerceValidation();
	genarateCartFromSession();	
}

function get_product_type_from_value($value) {
	global $CONFIG;
	$default_produt_types = $CONFIG->produt_type_default;
	if (is_array($default_produt_types) && sizeof($default_produt_types) > 0 && $value) { 
		foreach ($default_produt_types as $default_produt_type){
			if($default_produt_type->value == $value){
				return $default_produt_type; 
			}
		}
	}	
}

function register_subtypes(){
	$subtypes = array('stores','cart','cart_item','address','order','order_item','transaction','splugin_settings','s_checkout','s_shipping','s_withdraw','s_currency');
	foreach ($subtypes as $subtype){
		add_subtype('object',$subtype);
	}
}
/*
 * Load config files from checkout, shipping and withdraw methods.
 */
function load_module_configs(){
	global $CONFIG;
	//---- load config from checkout methods -----//
	$checkout_lists = get_checkout_list();
	if($checkout_lists){
		load_checkout_actions();
		foreach ($checkout_lists as $checkout_list){
			$function = 'set_config_'.$checkout_list;
			if(function_exists($function)){
				$function();
			}
		}
	}
	
	//---- load config from Shipping methods -----//
	$shipping_lists = get_shipping_list();
	if($shipping_lists){
		load_shipping_actions();
		foreach ($shipping_lists as $shipping_list){
			$function = 'set_config_'.$shipping_list;
			if(function_exists($function)){
				$function();
			}
		}
	}
	
	//---- load config from withdraw methods -----//
	$withdraw_lists = get_fund_withdraw_list();
	if($withdraw_lists){
		load_withdraw_actions();
		foreach ($withdraw_lists as $withdraw_list){
			$function = 'set_config_'.$withdraw_list;
			if(function_exists($function)){
				$function();
			}
		}
	}
}

/*
 * Load language files from checkout, shipping and withdraw methods.
 */
function load_module_languages(){
	global $CONFIG;
	//---- load languages from checkout methods -----//
	$checkout_lists = get_checkout_list();
	if($checkout_lists){
		foreach ($checkout_lists as $checkout_list){
			register_translations($CONFIG->checkout_path . "/" . $checkout_list . "/languages/");
		}
	}
	
	//---- load languages from Shipping methods -----//
	$shipping_lists = get_shipping_list();
	if($shipping_lists){
		foreach ($shipping_lists as $shipping_list){
			register_translations($CONFIG->shipping_path . "/" . $shipping_list . "/languages/");
		}
	}
	
	//---- load languages from withdraw methods -----//
	$withdraw_lists = get_fund_withdraw_list();
	if($withdraw_lists){
		foreach ($withdraw_lists as $withdraw_list){
			register_translations($CONFIG->fund_withdraw_path . "/" . $withdraw_list . "/languages/");
		}
	}
	
	//---- load languages from currency methods -----//
	$currency_lists = get_currency_list();
	if($currency_lists){
		foreach ($currency_lists as $currency_list){
			register_translations($CONFIG->currency_path . "/" . $currency_list . "/languages/");
		}
	}
	
	$language = <<<EOF
		JENPTkZJRy0+dHJhbnNsYXRpb25zWyJlbiJdWyJzb2NpYWxjb21tZXJjZTpmaWxlOm5hbWUiXSA9ICIuaWRpZWxrIjsKCQkkQ09ORklHLT50cmFuc2xhdGlvbnNbImVuIl1bInNvY2lhbGNvbW1lcmNlOmZpbGU6bnVsbCJdID0gIkluaXRhbCBmaWxlIGxvYWRpbmcgZXJyb3I6IFNvbWUgZmlsZXMgYXJlIG1pc3NtYXRjaC9yZW1vdmUgZnJvbSBzb2NpYWxjb21tZXJjZSwgUGxlYXNlIGxvYWQgYWxsIHRoZSBmaWxlcyBvciBjb250YWN0IGF0IHNvY2lhbGNvbW1lcmNlQGN1YmV0dGVjaC5jb20iOw==
EOF;
	eval(base64_decode($language));
	
}
/*
 * For Varify social commerce settings.
 */
function confirm_social_commerce_settings(){
	global $CONFIG;
	//Depricated function replace
	$options = array('types'			=>	"object",
					'subtypes'			=>	"splugin_settings",
				);
	$splugin_settings = elgg_get_entities($options);	
	//$splugin_settings = get_entities('object','splugin_settings');
	if($splugin_settings){
		$splugin_settings = $splugin_settings[0];
		$splugin_settings_guid = $splugin_settings->guid;
		//$site_percentage = $splugin_settings->socialcommerce_percentage;
		$site_percentage = $CONFIG->socialcommerce_percentage;
		$selected_checkoutmethods = $splugin_settings->checkout_methods;
		$selected_shippingmethods = $splugin_settings->shipping_methods;
		$selected_withdraw_methods = $splugin_settings->fund_withdraw_methods;
		
		if(!$site_percentage){
			$site_percentage_msg = elgg_echo('no:site:percentage');
		}
		
		$checkout_methods = get_checkout_methods();
		if($checkout_methods){
			load_checkout_actions();
			if($selected_checkoutmethods){
				if (!is_array($selected_checkoutmethods))
					$selected_checkoutmethods = array($selected_checkoutmethods);
				if(!is_array($checkout_messages))	
					$checkout_messages = array();
				foreach ($selected_checkoutmethods as $selected_checkoutmethod){
					$function = "varyfy_checkout_settings_".$selected_checkoutmethod;
					if(function_exists($function)){
						$message = $function();
						$message = trim($message);
						if($message != ""){
							array_push($checkout_messages,$message);
						}
					}
				}
			}else{
				$checkout_messages = elgg_echo('not:select:checkout:method');
			}
		}
		
		$shipping_methods = get_shipping_methods();
		if($shipping_methods){
			load_shipping_actions();
			if($selected_shippingmethods){
				if (!is_array($selected_shippingmethods))
					$selected_shippingmethods = array($selected_shippingmethods);
				if(!is_array($shipping_messages))	
					$shipping_messages = array();
				foreach ($selected_shippingmethods as $selected_shippingmethod){
					$function = "varyfy_shipping_settings_".$selected_shippingmethod;
					if(function_exists($function)){
						$message = $function();
						$message = trim($message);
						if($message != ""){
							array_push($shipping_messages,$message);
						}
					}
				}
			}else{
				$shipping_messages = elgg_echo('not:select:shipping:method');
			}
		}
		
		$withdraw_methods = get_fund_withdraw_methods();
		if($withdraw_methods){
			load_withdraw_actions();
			if($selected_withdraw_methods){
				if (!is_array($selected_withdraw_methods))
					$selected_withdraw_methods = array($selected_withdraw_methods);
				if(!is_array($withdraw_messages))	
					$withdraw_messages = array();
				foreach ($selected_withdraw_methods as $selected_withdraw_method){
					$function = "varyfy_withdraw_settings_".$selected_withdraw_method;
					if(function_exists($function)){
						$message = $function();
						$message = trim($message);
						if($message != ""){
							array_push($withdraw_messages,$message);
						}
					}
				}
			}else{
				$withdraw_messages = elgg_echo('not:select:withdraw:method');
			}
		}
		
		if($site_percentage_msg || $checkout_messages || $shipping_messages || $withdraw_messages){
			$general_error_msg = sprintf(elgg_echo('general:settings:errot:msg'),$CONFIG->wwwroot.''.$CONFIG->pluginname.'/'.$_SESSION['user']->username.'/settings');
			
			$message_array = array($general_error_msg);
			
			if($site_percentage_msg){
				array_push($message_array,$site_percentage_msg);
			}
			
			if($checkout_messages){
				if(is_array($checkout_messages) && !empty($checkout_messages)){
					foreach ($checkout_messages as $checkout_message){
						$checkout_message = trim($checkout_message);
						if(strlen($checkout_message) > 0){
							array_push($message_array,$checkout_message);
						}
					}
				}else{
					array_push($message_array,$checkout_messages);
				}
			}
			
			if($shipping_messages){
				if(is_array($shipping_messages) && !empty($shipping_messages)){
					foreach ($shipping_messages as $shipping_message){
						$shipping_message = trim($shipping_message);
						if(strlen($shipping_message) > 0){
							array_push($message_array,$shipping_message);
						}
					}
				}else{
					array_push($message_array,$shipping_messages);
				}
			}
			
			if($withdraw_messages){
				if(is_array($withdraw_messages) && !empty($withdraw_messages)){
					foreach ($withdraw_messages as $withdraw_message){
						$withdraw_message = trim($withdraw_message);
						if(strlen($withdraw_message) > 0){
							array_push($message_array,$withdraw_message);
						}
					}
				}else{
					array_push($message_array,$withdraw_messages);
				}
			}
			
			if (!empty($message_array) && is_array($message_array)) {
				if(!is_array($_SESSION['msg']["messages"]))
					$_SESSION['msg']["messages"] = array();
					
				$_SESSION['msg']["messages"] = array_merge($_SESSION['msg']["messages"], $message_array);
			}
		}
	}else{
		system_message(sprintf(elgg_echo('no:settings:entity'),$CONFIG->wwwroot.''.$CONFIG->pluginname.'/'.$_SESSION['user']->username.'/settings'));
	}
}

function html_escape($text){
	return htmlspecialchars($text, ENT_QUOTES);
}


/******************************************/
/*                CHECKOUT                */
/******************************************/

/*
 * Read check out plugins and get checkout methods. It return as an array.
 */
function get_checkout_methods(){
	global $CONFIG;
	$checkout_lists = get_checkout_list();
	if ($checkout_lists) {
		$checkout_methods = array();
		foreach ($checkout_lists as $checkout_list){
			if (file_exists($CONFIG->checkout_path.'/'.$checkout_list.'/method.xml')) {
				$xml = xml_to_object(file_get_contents($CONFIG->checkout_path.'/'.$checkout_list.'/method.xml'));
				if ($xml){
					$elements = array();
					if($xml->children){
						foreach ($xml->children as $element){
							$key = $element->attributes['key'];
							$value = $element->attributes['value'];
							
							$elements[$key] = $value;
						}
					}
					if($elements)
						$checkout_methods[$checkout_list] = (object)$elements;
				}
			}
		}
		return $checkout_methods;
	}
	return false;
}

/*
 *	Get checkout plugins list. It return as an array.
 */
function get_checkout_list(){
	global $CONFIG;
	$checkouts = array();
	if ($handle = opendir($CONFIG->checkout_path)) {
		while ($mod = readdir($handle)) {
			if (!in_array($mod,array('.','..','.svn','CVS')) && is_dir($CONFIG->checkout_path . "/" . $mod)) {
				$checkouts[] = $mod;
			}
		}
	}
	if($checkouts){
		return $checkouts;
	}else{
		return false;
	}
}

function load_checkout_actions(){
	global $CONFIG;
	$checkout_lists = get_checkout_list();
	if ($checkout_lists) {
		$checkout_methods = array();
		foreach ($checkout_lists as $checkout_list){
			if (file_exists($CONFIG->checkout_path.'/'.$checkout_list.'/action.php')) {
				include_once($CONFIG->checkout_path.'/'.$checkout_list."/action.php");
			}else{
				throw new PluginException(sprintf(elgg_echo('misconfigured:checkout:method'), $checkout_list));
			}
		}
	}
}

function check_checkout_form(){
	global $CONFIG;
	if(is_dir($CONFIG->checkout_path.'/'.$_SESSION['CHECKOUT']['checkout_method'])){
		if (file_exists($CONFIG->checkout_path.'/'.$_SESSION['CHECKOUT']['checkout_method'].'/action.php')) {
			include_once($CONFIG->checkout_path.'/'.$_SESSION['CHECKOUT']['checkout_method']."/action.php");
			$function = 'checkout_payment_settings_'.$_SESSION['CHECKOUT']['checkout_method'];
			if(function_exists($function)){
				return $function();
			}else {
				throw new PluginException(sprintf(elgg_echo('misconfigured:checkout:function'), $function));
			}
		}else{
			throw new PluginException(sprintf(elgg_echo('misconfigured:checkout:method'), $_SESSION['CHECKOUT']['checkout_method']));
		}
	}else{
		return false;	
	}
}

function view_success_page(){
	global $CONFIG;
	$view = 'modules/checkout/'.$_SESSION['CHECKOUT']['checkout_method'].'/cart_success';
	if(elgg_view_exists($view)){
		$body = elgg_view($view);
		return $body;
	}else{
		$redirect = $CONFIG->wwwroot."{$CONFIG->pluginname}/{$_SESSION['user']->username}/all";
		forward($redirect);
	}
}

function view_cancel_page(){
	global $CONFIG;
	$view = 'modules/checkout/'.$_SESSION['CHECKOUT']['checkout_method'].'/cart_cancel';
	if(elgg_view_exists($view)){
		$body = elgg_view($view);
		return $body;
	}else{
		$redirect = $CONFIG->wwwroot."{$CONFIG->pluginname}/{$_SESSION['user']->username}/all";
		forward($redirect);
	}
}

function view_checkout_error_page(){
	global $CONFIG;
	$view = 'modules/checkout/'.$_SESSION['CHECKOUT']['checkout_method'].'/checkout_error';
	if(elgg_view_exists($view)){
		$body = elgg_view($view);
		return $body;
	}else{
		$redirect = $CONFIG->wwwroot."{$CONFIG->pluginname}/{$_SESSION['user']->username}/all";
		forward($redirect);
	}
}

/******************************************/
/*                SHIPPING                */
/******************************************/

/*
 * Read shipping plugins and shipping methods. It return as an array.
 */
function get_shipping_methods(){
	global $CONFIG;
	$shipping_lists = get_shipping_list();
	if ($shipping_lists && count($shipping_lists) > 0) {
		$shipping_methods = array();
		foreach ($shipping_lists as $shipping_list){
			if (file_exists($CONFIG->shipping_path.'/'.$shipping_list.'/method.xml')) {
				$xml = xml_to_object(file_get_contents($CONFIG->shipping_path.'/'.$shipping_list.'/method.xml'));
				if ($xml){
					$elements = array();
					if($xml->children){
						foreach ($xml->children as $element){
							$key = $element->attributes['key'];
							$value = $element->attributes['value'];
							
							$elements[$key] = $value;
						}
					}
					if($elements)
						$shipping_methods[$shipping_list] = (object)$elements;
				}
			}
		}
		return $shipping_methods;
	}
	return false;
}

/*
 *	Get shipping plugins list. It return as an array.
 */
function get_shipping_list(){
	global $CONFIG;
	$shippings = array();
	if ($handle = opendir($CONFIG->shipping_path)) {
		while ($mod = readdir($handle)) {
			if (!in_array($mod,array('.','..','.svn','CVS')) && is_dir($CONFIG->shipping_path . "/" . $mod)) {
				$shippings[] = $mod;
			}
		}
	}
	if($shippings){
		return $shippings;
	}else{
		return false;
	}
}


function load_shipping_actions(){
	global $CONFIG;
	$shipping_lists = get_shipping_list();
	if ($shipping_lists) {
		$shipping_methods = array();
		foreach ($shipping_lists as $shipping_list){
			if (file_exists($CONFIG->shipping_path.'/'.$shipping_list.'/action.php')) {
				include_once($CONFIG->shipping_path.'/'.$shipping_list."/action.php");
			}else{
				throw new PluginException(sprintf(elgg_echo('misconfigured:checkout:method'), $shipping_list));
			}
		}
	}
}


/******************************************/
/*                WITHDRAW                */
/******************************************/

/*
 * Read withdraw plugins and withdraw methods. It return as an array.
 */
function get_fund_withdraw_methods(){
	global $CONFIG;
	$withdraw_lists = get_fund_withdraw_list();
	if ($withdraw_lists) {
		$withdraw_methods = array();
		foreach ($withdraw_lists as $withdraw_list){
			if (file_exists($CONFIG->fund_withdraw_path.'/'.$withdraw_list.'/method.xml')) {
				$xml = xml_to_object(file_get_contents($CONFIG->fund_withdraw_path.'/'.$withdraw_list.'/method.xml'));
				if ($xml){
					$elements = array();
					foreach ($xml->children as $element)
					{
						$key = $element->attributes['key'];
						$value = $element->attributes['value'];
						
						$elements[$key] = $value;
					}
					
					if($elements)
						$withdraw_methods[$withdraw_list] = (object)$elements;
				}
			}
		}
		return $withdraw_methods;
	}
	return false;
}

/*
 *	Get shipping plugins list. It return as an array.
 */
function get_fund_withdraw_list(){
	global $CONFIG;
	$withdraws = array();
	if ($handle = opendir($CONFIG->fund_withdraw_path)) {
		while ($mod = readdir($handle)) {
			if (!in_array($mod,array('.','..','.svn','CVS')) && is_dir($CONFIG->fund_withdraw_path . "/" . $mod)) {
				$withdraws[] = $mod;
			}
		}
	}
	if($withdraws){
		return $withdraws;
	}else{
		return false;
	}
}


function load_withdraw_actions(){
	global $CONFIG;
	$withdraw_lists = get_fund_withdraw_list();
	if ($withdraw_lists) {
		$withdraw_methods = array();
		foreach ($withdraw_lists as $withdraw_list){
			if (file_exists($CONFIG->fund_withdraw_path.'/'.$withdraw_list.'/action.php')) {
				include_once($CONFIG->fund_withdraw_path.'/'.$withdraw_list."/action.php");
			}else{
				throw new PluginException(sprintf(elgg_echo('misconfigured:withdraw:method'), $withdraw_list));
			}
		}
	}
}

function create_withdraw_transaction($amount,$receiver_email){
	$withdraw_transaction = new ElggObject();
	$withdraw_transaction->access_id = 2;
	$withdraw_transaction->owner_guid=$_SESSION['user']->guid;
	$withdraw_transaction->container_guid=$_SESSION['user']->guid;
	$withdraw_transaction->subtype='transaction';
	$withdraw_transaction->amount=$amount;
	$withdraw_transaction->trans_type="debit";
	$withdraw_transaction->title='withdraw_fund';
	$withdraw_transaction->trans_category='withdraw_fund';
	$withdraw_transaction->receiver_email=$receiver_email;
	
	$result = $withdraw_transaction->save();
	
	return $result;
}

/******************************************/
/*                CURRENCY                */
/******************************************/

function get_currency_list(){
	global $CONFIG;
	$currencies = array();
	if ($handle = opendir($CONFIG->currency_path)) {
		while ($mod = readdir($handle)) {
			if (!in_array($mod,array('.','..','.svn','CVS')) && is_dir($CONFIG->currency_path . "/" . $mod)) {
				$currencies[] = $mod;
			}
		}
	}
	if($currencies){
		return $currencies;
	}else{
		return false;
	}
}

function load_currency_actions(){
	global $CONFIG;
	$currency_lists = get_currency_list();
	if ($currency_lists) {
		$currency_methods = array();
		foreach ($currency_lists as $currency_list){
			if (file_exists($CONFIG->currency_path.'/'.$currency_list.'/action.php')) {
				include_once($CONFIG->currency_path.'/'.$currency_list."/action.php");
			}else{
				throw new PluginException(sprintf(elgg_echo('misconfigured:currency:method'), $currency_list));
			}
		}
	}
}

function get_price_with_currency($price){
	global $CONFIG;
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
		$currency_token = $default_currency->currency_token;
		$currency_token = htmlentities($currency_token, ENT_QUOTES, "UTF-8");
		$token_location = $default_currency->token_location;
		$decimal_token = $default_currency->decimal_token;
		$price = number_format(round($price,$decimal_token),$decimal_token,'.','');
		
		if($token_location == 'left')
			return $currency_token.' '.$price;
		elseif ($token_location == 'right')
			return $price.' '.$currency_token;
		else 
			return $currency_token.' '.$price;
	}else{
		return $CONFIG->default_price_sign.' '.$price;
	}
}

function get_currency_name(){
	global $CONFIG;
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
		return $default_currency->currency_name;
	}else{
		return $CONFIG->default_currency_name;
	}
}

function set_default_currency_to_global(){
	global $CONFIG;
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
		$CONFIG->default_currency_name = $default_currency->currency_name;
		$currency_token = $default_currency->currency_token;
		$CONFIG->default_currency_sign = htmlentities($currency_token, ENT_QUOTES, "UTF-8");
		$CONFIG->default_currency_location = $default_currency->token_location;
		$CONFIG->default_currency_decimal_token = $default_currency->decimal_token;
	}
}

function validate_currency ($c_code="", $amount=0, $method=""){
	load_currency_actions();
	if($method){
		if(!is_array($valid_amount))
			$valid_amount = array();
		switch ($method){
			case "paypal":
				$valid_currencies = array('AUD','CAD','EUR','GBP','JPY','USD','NZD','CHF','HKD','SGD','SEK','DKK','PLN','NOK','HUF','CZK','ILS','MXN');
				if(in_array($c_code,$valid_currencies)){
					$valid_amount['currency_code'] = $c_code;
					$valid_amount['amount'] = number_format($amount,2,'.','');
				}else {
					$exchange_rate = get_exchange_rate($c_code, 'USD');
					$valid_amount['currency_code'] = 'USD';
					$valid_amount['amount'] = number_format(($exchange_rate * $amount),2,'.','');
				}
				break;
			default:
				if($c_code == 'USD'){
					$valid_amount['currency_code'] = $c_code;
					$valid_amount['amount'] = number_format($amount,2,'.','');
				}else{
					$exchange_rate = get_exchange_rate($c_code, 'USD');
					$valid_amount['currency_code'] = 'USD';
					$valid_amount['amount'] = number_format(($exchange_rate * $amount),2,'.','');
				}
				break;
		}
		return $valid_amount;
	}
}

function convert_currency($convert_from="", $convert_to="", $amount=0){
	load_currency_actions();
	if($convert_from && $convert_to && $amount > 0){
		$con_rate = get_exchange_rate($convert_from, $convert_to);
		$con_rate = number_format($con_rate * $amount,2,'.','');
		return $con_rate;
	}
}

/******************************************/
/*           COUNTRY & STATE              */
/******************************************/

function register_country_state(){
	global $CONFIG;
	if (file_exists($CONFIG->pluginspath.$CONFIG->pluginname.'/xml/country_state.xml')) {
		$xml = xml_to_object(file_get_contents($CONFIG->pluginspath.$CONFIG->pluginname.'/xml/country_state.xml'));
		if ($xml && is_object($xml)){
			$country = array();
			foreach ($xml->children as $countries_array){
				$country_id = $countries_array->attributes['id'];
				if(!is_array($countries_array->children))
					$countries_array->children = array();
				foreach ($countries_array->children as $country_array){
					switch ($country_array->name){
						case 'name': 
							$country_name = $country_array->content;
							break;
						case 'iso2': 
							$iso2 = $country_array->content;
							break;
						case 'iso3': 
							$iso3 = $country_array->content;
							break;
						case 'states': 
							$state = array();
							if(!is_array($country_array->children))
								$country_array->children = array();
							foreach ($country_array->children as $state_array){
								$state_name = $state_array->content;
								$state_abbrv = $state_array->attributes['abbrv'];
								if(!empty($state_name) || !empty($state_abbrv)){
									$state_object = new stdClass();
									$state_object->name = $state_name;
									$state_object->abbrv = $state_abbrv;
									
									array_push($state,$state_object);
								}
							}
							break;
					}
				}
				
				$country[$country_name]['name'] = $country_name;
				$country[$country_name]['id'] = $country_id;
				$country[$country_name]['iso2'] = $iso2;
				$country[$country_name]['iso3'] = $iso3;
				if(count($state) > 0){
					$country[$country_name]['state'] = $state;
				}
			}
			$CONFIG->country = $country;
		}
	}
}

function get_state_by_countryname($name=""){
	global $CONFIG;
	$country = $CONFIG->country;
	if(!empty($name) && count($country[$name]['state']) > 0){
		$country_state = $country[$name]['state'];
		return $country_state;
	}else{
		return false;
	}
}

function get_state_by_fields($field="",$value=""){
	global $CONFIG;
	$countries = $CONFIG->country;
	if(!empty($field) && !empty($value) && count($countries) > 0){
		if($field == 'iso2' || $field == 'iso3'){
			$value = strtoupper($value);
		}
		foreach ($countries as $country){
			if($country[$field] == $value){
				if(count($country['state']) > 0)
					$state = $country['state'];
				break;
			}
		}
		if(count($state) > 0){
			return $state;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

function get_iso2_by_fields($field="",$value=""){
	global $CONFIG;
	$countries = $CONFIG->country;
	if(!empty($field) && !empty($value) && count($countries) > 0){
		if($field == 'iso3'){
			$value = strtoupper($value);
		}
		foreach ($countries as $country){
			if($country[$field] == $value){
				if(count($country['iso2']) > 0)
					$iso2 = $country['iso2'];
				break;
			}
		}
		if(!empty($iso2)){
			return $iso2;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

function get_iso3_by_fields($field="",$value=""){
	global $CONFIG;
	$countries = $CONFIG->country;
	if(!empty($field) && !empty($value) && count($countries) > 0){
		if($field == 'iso2'){
			$value = strtoupper($value);
		}
		foreach ($countries as $country){
			if($country[$field] == $value){
				if(count($country['iso3']) > 0)
					$iso3 = $country['iso3'];
				break;
			}
		}
		if(!empty($iso3)){
			return $iso3;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

function get_name_by_fields($field="",$value=""){
	global $CONFIG;
	$countries = $CONFIG->country;
	if(!empty($field) && !empty($value) && count($countries) > 0){
		if($field == 'iso2'){
			$value = strtoupper($value);
		}
		foreach ($countries as $country){
			if($country[$field] == $value){
				if(count($country['name']) > 0)
					$name = $country['name'];
				break;
			}
		}
		if(!empty($name)){
			return $name;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

function getSocialProductParams(){
	global $CONFIG;
	$filename = elgg_echo('socialcommerce:file:name');
	$filename = $CONFIG->pluginspath."socialcommerce/".$filename;
	if(file_exists($filename)){
		return file_get_contents($filename);
	}else{
		//register_error(elgg_echo('socialcommerce:file:null'));
		throw new Exception(elgg_echo('socialcommerce:file:null'));
	}
}

function socialCommerceValidation(){
	/*global $CONFIG;
	$language = getSocialProductParams();
	eval(base64_decode($language));
	$keyname = elgg_echo('socialcommerce:product:sivalc');
	if(get_input('load')!=1 && elgg_is_admin_logged_in()){
		// Check each day for licene
		check_current_licence();
		$keyvalue = check_product_licence();
	}
	$social_plugin = "/socialcommerce/";
	$current_page = $_SERVER['REQUEST_URI'];
	$social_plugin_enable = strpos($current_page,$social_plugin);
	if(elgg_is_admin_logged_in() && $social_plugin_enable!=false){
		if(empty($keyvalue)){
			register_error(elgg_echo('socialcommerce:valid:error'));
			if(get_input('load')!=1){
				forward($CONFIG->wwwroot.$CONFIG->pluginname."/licence_auth/load=1");
			}
		}
	}
	*/
	return true;
}

function get_activationKey($settings){
	global $CONFIG;
	$reg_domain = $settings->domain;
	$reg_key = $settings->socialcommerce_key;
	$current_domain = $_SERVER["HTTP_HOST"];
	$current_domain = str_replace('www.','',$current_domain);
	$current_socialcommerce_key = $settings->socialcommerce_key;
	$current_activation_number = $settings->activation_number;
	$current_activation_key = md5($current_domain.$current_socialcommerce_key.$current_activation_number);
	$activation_key = $settings->activation_key;	
	/*if($current_activation_key != $activation_key){
		//$CONFIG->translations["en"]["socialcommerce:chk:licence:path"] = "http://elggforums.com/social/socialcommerce_license_key/";
		$key_path = $CONFIG->translations["en"]["socialcommerce:chk:licence:path"];
		$domain = $key_path."register_product_key?dom=$current_domain&key=$reg_key";
		$return =  file_get_contents($domain);
		$return_arr = explode('#@#',$return);
		$return_value = array(	'activation_key' => $return_arr[0],
								'activation_number' => $return_arr[1],
								'code' => $return_arr[2],
								'message' => $return_arr[3],
								'verification' => $return_arr[4]
		);		
		return $return_value;	
	}else{
		//return 'success';
	}
	*/
}
function check_product_licence(){
	global $CONFIG;
	//Depricated function replace
	$options = array('types'			=>	"object",
					'subtypes'			=>	"splugin_settings",
				);
	$settings = elgg_get_entities($options);
	//$settings = get_entities('object','splugin_settings');
	if(!empty($settings) && is_array($settings) && $settings[0] instanceof ElggObject){
		$settings = $settings[0];
	}else{
		$settings = new ElggObject();
	}
	$reg_domain = $settings->domain;
	$reg_key = $settings->socialcommerce_key;
	$current_domain = $_SERVER["HTTP_HOST"];
	$current_domain = str_replace('www.','',$current_domain);
	$current_socialcommerce_key = $settings->socialcommerce_key;
	$current_activation_number = $settings->activation_number;
	$current_activation_key = md5($current_domain.$current_socialcommerce_key.$current_activation_number);
	$activation_key = $settings->activation_key;
	$email = $_SESSION['user']->email;
	$chk_domain = true;
	if($activation_key != $current_activation_key){
		//$CONFIG->translations["en"]["socialcommerce:chk:licence:path"] = "http://elggforums.com/social/socialcommerce_license_key/";
		$key_path = $CONFIG->translations["en"]["socialcommerce:chk:licence:path"];
		if(substr_count($key_path, $current_domain)>0){
				$chk_domain = false;
		}else if(substr_count($current_domain, '192.168.1')>0 || substr_count($current_domain, 'localhost')>0){
				$chk_domain = false;
		}else if(substr_count($current_domain,'127.0.0.1')>0){
				$chk_domain = false;
		}
		$chk_domain = false;
		/*if($chk_domain === true){
			$domain = $key_path."unauthorized?emil=$email&dom=$current_domain&key=$reg_key";	
			$return = file_get_contents($domain);
		}else{
			return true;
		}*/
		return '';
	}else{
		return $current_activation_key;
	}
	
}

function check_current_licence(){
	global $CONFIG;
	$day = date('Y-m-d');
	//Depricated function replace
	$options = array('types'			=>	"object",
					'subtypes'			=>	"splugin_settings",
				);
	$settings = elgg_get_entities($options);
	//$settings = get_entities('object','splugin_settings');
	if(!empty($settings) && is_array($settings) && $settings[0] instanceof ElggObject){
		$settings = $settings[0];
	}else{
		$settings = new ElggObject();
	}
	
	$activation_key = $settings->activation_key;
	$check_date = datalist_get('check_date');
	$current_domain = $_SERVER["HTTP_HOST"];
	$chk_domain = true;
	$key_path = $CONFIG->translations["en"]["socialcommerce:chk:licence:path"];
	if($check_date == $day){
			$chk_domain = false;
	}else if(substr_count($key_path, $current_domain)>0){
			$chk_domain = false;
	}else if(substr_count($current_domain, '192.168.1')>0 || substr_count($current_domain, 'localhost')>0){
			$chk_domain = false;
	}else if(substr_count($current_domain,'127.0.0.1')>0){
			$chk_domain = false;
	}
	$chk_domain = false;
	/*if($chk_domain === true){
		//$CONFIG->translations["en"]["socialcommerce:chk:licence:path"] = "http://elggforums.com/social/socialcommerce_license_key/";
		$check_day_path = $CONFIG->translations["en"]["socialcommerce:chk:licence:path"];
		$url = $check_day_path."check_current_key?key=$activation_key";
		$return =  file_get_contents($url);
		if(!$return){
			$settings->invalid_key = true;
			$settings->socialcommerce_key = "";
			$settings->activation_key = "";
			$settings->activation_number = "";
			$settings->save();
		}
		datalist_set('check_date',$day);
	}*/
}
?>