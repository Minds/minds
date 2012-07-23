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
	 * Elgg paypal checkout - actions
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	function set_checkout_settings_paypal(){
		
		$guid = get_input('guid');
		
		$error_field = "";
		$display_name = get_input('display_name');
		$paypal_email = get_input('socialcommerce_paypal_email');
		$paypal_envi = get_input('socialcommerce_paypal_environment');
		if(empty($display_name)){
			$error_field = ", ".elgg_echo("display:name");
		}
		if(empty($paypal_email)){
			$error_field .= ", ".elgg_echo("paypal:email");
		}
		if(empty($paypal_envi)){
			$error_field .= ", ".elgg_echo("mode");
		}
		if(empty($error_field)){
			if($guid){
				$checkout_settings = get_entity($guid);
			}else{
				$checkout_settings = new ElggObject($guid);
			}
			
			$checkout_settings->access_id = 2;
			$checkout_settings->container_guid = $_SESSION['user']->guid;
			$checkout_settings->subtype = 's_checkout';
			$checkout_settings->checkout_method = 'paypal';
			$checkout_settings->display_name = $display_name;
			$checkout_settings->socialcommerce_paypal_email = $paypal_email;
			$checkout_settings->socialcommerce_paypal_environment = $paypal_envi;
			$checkout_settings->save();
			
			system_message(sprintf(elgg_echo("settings:saved"),$checkout_methods[$method]->label));
			return $settings->guid;
		}elseif (!empty($error_field)){
			$error_field = substr($error_field,2);
			register_error(sprintf(elgg_echo("settings:validation:null"),$error_field));
			return false;
		}
	}
	
	function checkout_payment_settings_paypal(){
		
		global $CONFIG;
		$method = $_SESSION['CHECKOUT']['checkout_method'];
		//Depricated function replace
		$options = array(	'metadata_name_value_pairs'	=>	array('checkout_method' => $method),
						'types'				=>	"object",
						'subtypes'			=>	"s_checkout",
						'limit'				=>	1,
					);
		$settings = elgg_get_entities_from_metadata($options);
		//$settings = get_entities_from_metadata('checkout_method',"{$method}",'object','s_checkout',0,1);
		if($settings){
			$settings = $settings[0];	
		}
		
		$total = $_SESSION['CHECKOUT']['total'];
		$validate_currency = validate_currency($CONFIG->currency_code,$total,'paypal');
		
		$email = $settings->socialcommerce_paypal_email;

		$paypal_environment = $settings->socialcommerce_paypal_environment;

		if($paypal_environment == "paypal") {
			$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
		}else {
			$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		}

		$BillingDetails = $_SESSION['CHECKOUT']['billing_address'];
		$ShippingDetails = $_SESSION['CHECKOUT']['shipping_address'];
		
		if($_SESSION['CHECKOUT']['product']){
			$products = $_SESSION['CHECKOUT']['product'];
			$product_titles = '';
			foreach($products as $pguid=>$product){
				$product = get_entity($pguid);
				$product_titles .= $product_titles ? ", ".$product->title : $product->title;
			}
		}
		/* Get the cart guid*/
		$page_owner = $_SESSION['user']->guid;
		//Depricated function replace
		$options = array('types'			=>	"object",
						'subtypes'			=>	"cart",
						'owner_guids'		=>	$page_owner,
					);
		$carts = elgg_get_entities($options);
		//$carts = get_entities('object','cart',$page_owner);
		if($carts){
			$cart_guid = $carts[0]->guid;
		}
		/*$orders = $this->GetOrders();
		$orderIds = array();
		foreach($orders as $order) {
			$orderIds[] = '#'.$order['orderid'];
		}
		$orderIdAppend = '('.implode(', ', $orderIds).')';*/
		$ts = time();
		//echo $CONFIG->wwwroot."action/{$CONFIG->pluginname}/manage_socialcommerce?manage_action=cart_success&__elgg_token=".generate_action_token($ts)."&__elgg_ts={$ts}";
		$hiddenFields = array(
			'cmd'			=> '_ext-enter',
			'redirect_cmd'	=> '_xclick',
			'business'		=> $email,
			'rm'			=> 2,

			// Order details
			'amount'		=> $validate_currency['amount'],
			'no_shipping'	=> 1,
			//'tax'			=> 0,
			'no_note'		=> 0,
			'currency_code'	=> $validate_currency['currency_code'],
			'custom'		=>"{$method},".elgg_get_page_owner_guid().','.$BillingDetails->guid.','.$ShippingDetails->guid.','.$_SESSION['CHECKOUT']['shipping_method'].','.$cart_guid,
			'item_name'		=> elgg_echo('cart:item:name').":".$product_titles,

			// Notification and return URLS
			'return'		=> $CONFIG->wwwroot."action/{$CONFIG->pluginname}/manage_socialcommerce?manage_action=cart_success&__elgg_token=".generate_action_token($ts)."&__elgg_ts={$ts}&view=".elgg_get_viewtype(),
			'cancel_return'	=> $CONFIG->wwwroot."action/{$CONFIG->pluginname}/manage_socialcommerce?manage_action=cart_cancel&__elgg_token=".generate_action_token($ts)."&__elgg_ts={$ts}&view=".elgg_get_viewtype(),
			//'notify_url'	=> $CONFIG->wwwroot."action/{$CONFIG->pluginname}/manage_socialcommerce?page_owner=".elgg_get_page_owner_guid().'&manage_action=makepayment&payment_method='.$method,

			// Customer details
			'first_name'	=> $billingDetails['firstname'],
			'last_name'		=> $billingDetails['lastname'],
			//'email'			=> $billingDetails['ordbillemail'],
			'address1'		=> $billingDetails['address_line_1'],
			'address2'		=> $billingDetails['address_line_2'],
			//'day_phone_a'	=> $phone_1,
			//'day_phone_b'	=> $phone_2,
			//'day_phone_c'	=> $phone_3,
			//'night_phone_a'	=> $phone_1,
			//'night_phone_b'	=> $phone_2,
			//'night_phone_c'	=> $phone_3,
			'country'		=> get_name_by_fields('iso3',$billingDetails['country']),
			'zip'			=> $billingDetails['pincode'],
			'city'			=> $billingDetails['city'],
			'state'			=> $billingDetails['state']
			//'address_override' => 1
		);
		/* 
		 *	Enter any extra datas from clinit side
		 *	if 1 we allow to enter datas
		 * 	Otherwise it automatically redirect to the given url
		 */
		//$not_compleated = 0;
		
		/* 
		 *	This is the view to display that extra fields in client side
		 */
		//$field_view = "paypal_entries";
		return redirect_to_form($paypal_url, $hiddenFields, $not_compleated, $field_view);
	}
	
	function makepayment_paypal(){
		global $CONFIG;
		
		$custom = get_input('custom');
		//$custom = 'stores_payment';
		//$replay .= "\n CUSTOM: \n ".$custom;
		/*if(!empty($custom)){*/
			$custome = explode(',',$custom);
			$CheckoutMethod = $custome[0];
			$page_owner = $custome[1];
			$BillingDetails = $custome[2];
			$ShippingDetails = $custome[3];
			if(!$ShippingDetails)
				$ShippingDetails = 0;
			$ShippingMethods = $custome[4];
			if(!$ShippingMethods)
				$ShippingMethods = 0;
			/*Cart Guid*/
			$cart_guid = $custome[5];
			//Depricated function replace
			$options = array(	'metadata_name_value_pairs'	=>	array('checkout_method' => 'paypal'),
							'types'				=>	"object",
							'subtypes'			=>	"s_checkout",
							'limit'				=>	1,
						);
			$settings = elgg_get_entities_from_metadata($options);
			//$settings = get_entities_from_metadata('checkout_method','paypal','object','s_checkout',0,1);
			if($settings){
				$settings = $settings[0];	
			}
			$stores_paypal_email = $settings->socialcommerce_paypal_email;
			$environment = $settings->socialcommerce_paypal_environment;
			if(!$environment){
				$environment = "sandbox";
			}
			
			$req = '';
			foreach ($_POST as $key => $value) {
				if($key != "cmd"){
					$value = urlencode(stripslashes($value));
					$req .= !empty($req) ? "&$key=$value" : "$key=$value";
				}
			}
			$req .= '&cmd=_notify-validate';
			$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
			$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
			
			if($environment == "sandbox"){
				$fp = fsockopen ('www.sandbox.paypal.com', 80, $errno, $errstr, 30);
			}elseif ($environment == "paypal"){
				$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
			}
			
			// assign posted variables to local variables
			$item_name 			= $_POST['item_name'];
			$item_number 		= $_POST['item_number'];
			$payment_status 	= $_POST['payment_status'];
			$payment_amount 	= (float)$_POST['mc_gross'];
			$payment_gross   	= (float)$_POST['payment_gross'];
			$payment_fee        = (float)$_POST['payment_fee'];
			$payment_currency 	= $_POST['mc_currency'];
			$txn_id 			= $_POST['txn_id'];
			$receiver_email 	= $_POST['receiver_email'];
			$payer_email 		= $_POST['payer_email'];
			$new_fund 			= $payment_gross - $payment_fee;
			$post_values = $_POST;
			/*$replay .= "\n IPN REPLAY: ";
			foreach ($_POST as $text)
				$replay .= "\n ". $text;*/
			
			$process_payment = false;
			if (!$fp) {
				// HTTP ERROR
			} else {
				fputs ($fp, $header . $req);
				while (!feof($fp)) {
					$res = fgets ($fp, 1024);
					if (strcmp ($res, "VERIFIED") == 0) {
						if (strtolower($receiver_email) == strtolower($stores_paypal_email)) {
							
							switch($_POST['payment_status']) {
								case "Completed":
									$ransaction_status = "Completed";
									$process_payment = true;
									break;
								case "Pending":
									$ransaction_status = "Pending";
									$process_payment = true;
									break;
							}
							 //$replay .= "\n Final: Success";
						}
					} else if (strcmp ($res, "INVALID") == 0) {
						// log for manual investigation
					}
				}
				fclose ($fp);
			}

			if($process_payment){
				if($CONFIG->currency_code != $payment_currency){
					$converted_currency = convert_currency($payment_currency,$CONFIG->currency_code,$payment_gross);
					$payment_gross = $converted_currency;
					$converted_currency = convert_currency($payment_currency,$CONFIG->currency_code,$payment_fee);
					$payment_fee = $converted_currency;
				}
				
				$transactions = array('status'=>$ransaction_status,
									  'txn_id'=>$txn_id,
									  'email'=>$receiver_email,
									  'fee'=>$payment_fee,
									  'total'=>$payment_gross);
				return create_order($page_owner,$CheckoutMethod,$transactions,$BillingDetails,$ShippingDetails,$ShippingMethods,$cart_guid);	
			}else{
				return false;
			}
		/*}*/
		return true;
	}
	
	function varyfy_checkout_settings_paypal(){
		//Depricated function replace
		$options = array(	'metadata_name_value_pairs'	=>	array('checkout_method' => 'paypal'),
						'types'				=>	"object",
						'subtypes'			=>	"s_checkout",
						'limit'				=>	1,
					);
		$settings = elgg_get_entities_from_metadata($options);
		//$settings = get_entities_from_metadata('checkout_method','paypal','object','s_checkout',0,1);
		if($settings){
			$settings = $settings[0];
			$display_name = trim($settings->display_name);
			$paypal_email = trim($settings->socialcommerce_paypal_email);
			$paypal_envi = trim($settings->socialcommerce_paypal_environment);
			
			if($display_name == "")
				$missing_field = elgg_echo('display:name');
			if($paypal_email == "")
				$missing_field .= $missing_field != "" ? ",".elgg_echo('paypal:email') : elgg_echo('paypal:email');
			if($paypal_envi == "")
				$missing_field .= $missing_field != "" ? ",".elgg_echo('mode') : elgg_echo('mode');
			if($missing_field != ""){
				return sprintf(elgg_echo('missing:fields'),$missing_field);
			}
			return;
		}else{
			return elgg_echo('not:fill:paypal:settings');
		}
	}
	
?>