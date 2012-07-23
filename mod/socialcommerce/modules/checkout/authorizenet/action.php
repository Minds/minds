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
	 * Elgg authorize.net checkout - actions
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	function set_checkout_settings_authorizenet(){
		
		$guid = get_input('guid');
		
		$error_field = "";
		$display_name = get_input('display_name');
		$authorizenet_apiloginid = get_input('socialcommerce_authorizenet_apiloginid');
                $authorizenet_transactionkey = get_input('socialcommerce_authorizenet_transactionkey');
		$authorizenet_envi = get_input('socialcommerce_authorizenet_environment');

		if(empty($display_name)){
			$error_field = ", ".elgg_echo("display:name");
		}
		if(empty($authorizenet_apiloginid)){
			$error_field .= ", ".elgg_echo("api:login:id");
		}
                if(empty($authorizenet_transactionkey)){
			$error_field .= ", ".elgg_echo("transaction:key");
		}
		if(empty($authorizenet_envi)){
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
			$checkout_settings->checkout_method = 'authorizenet';
			$checkout_settings->display_name = $display_name;
			$checkout_settings->socialcommerce_authorizenet_apiloginid = $authorizenet_apiloginid;
            $checkout_settings->socialcommerce_authorizenet_transactionkey = $authorizenet_transactionkey;
			$checkout_settings->socialcommerce_authorizenet_environment = $authorizenet_envi;
			$checkout_settings->save();
			
			system_message(sprintf(elgg_echo("settings:saved"),$checkout_methods[$method]->label));
			return $settings->guid;
		}elseif (!empty($error_field)){
			$error_field = substr($error_field,2);
			register_error(sprintf(elgg_echo("settings:validation:null"),$error_field));
			return false;
		}
	}
	
	function checkout_payment_settings_authorizenet(){
		global $CONFIG;
                $ts = time();
                
		$method = $_SESSION['CHECKOUT']['checkout_method'];
	
		$options = array('metadata_name_value_pairs'	=>	array('checkout_method' => $method),
						'types'				=>	"object",
						'subtypes'			=>	"s_checkout",
						'limit'				=>	1);
		$settings = elgg_get_entities_from_metadata($options);
		if($settings){
			$settings = $settings[0];	
		}
		
		$total = $_SESSION['CHECKOUT']['total'];
		$validate_currency = validate_currency($CONFIG->currency_code,$total,'authorizenet');
		//print_r($validate_currency);
		$loginid = $settings->socialcommerce_authorizenet_apiloginid;
        $transactionkey = $settings->socialcommerce_authorizenet_transactionkey;
		$authorizenet_environment = $settings->socialcommerce_authorizenet_environment;


        $authorizenet_url = $CONFIG->wwwroot."action/{$CONFIG->pluginname}/manage_socialcommerce?page_owner=".page_owner()."&manage_action=makepayment&payment_method=authorizenet&__elgg_token=".generate_action_token($ts)."&__elgg_ts={$ts}";
                
		/* 
		 *	Enter any extra datas from clinit side
		 *	if 1 we allow to enter datas
		 * 	Otherwise it automatically redirect to the given url
		 */
		$not_compleated = 1;
		
		/* 
		 *	This is the view to display that extra fields in client side
		 */
		//$field_view = "paypal_entries";
        $field_view = "authorizenet_details";

		return redirect_to_form($authorizenet_url, $hiddenFields, $not_compleated, $field_view);
	}
	
	function makepayment_authorizenet(){
		global $CONFIG;
                
		///////////////////////////////////////////////////////////////////////////////////////////////////////////

                //print_r($_SESSION);
                $cartitems = ($_SESSION['CHECKOUT']['product']);
                //print_r($cartitems);
                foreach($cartitems as $key=>$cartitem)
                {
                    $selectprod = (get_entity($key));
                    $selectedproducts .= $selectprod->title.",";
                }
                $selectedproducts = substr($selectedproducts, 0, strlen($selectedproducts)-1);


                $method = $_SESSION['CHECKOUT']['checkout_method'];
                
                $options = array('metadata_name_value_pairs'	=>	array('checkout_method' => 'authorizenet'),
                                 'types'	=>	"object",
                                 'subtypes'	=>	"s_checkout",
                                 'limit'	=>	1);
                $settings = elgg_get_entities_from_metadata($options);
                if($settings){
                        $settings = $settings[0];
                }

                $total = $_SESSION['CHECKOUT']['total'];
                //$validate_currency = validate_currency($CONFIG->currency_code,$total,'authorizenet');
                $authorizenet_apiloginid = $settings->socialcommerce_authorizenet_apiloginid;
                $authorizenet_transactionkey = $settings->socialcommerce_authorizenet_transactionkey;
                $authorizenet_environment = $settings->socialcommerce_authorizenet_environment;

                if($authorizenet_environment == "authorizenet")
                {
                    $account_type = "";
                }
                else
                {
                    $account_type = "true";
                }
                
                ///////////////////////////////////////////////////Authorize.net API Connection/////////////////////////////////

                require("AuthnetAIM_class.php");


                //$user_id = 1;
                $email   = $_SESSION['user']['email'];
                $product = $selectedproducts;
                $business_firstname = $_POST["bill_first_name"];
                $business_lastname  = $_POST["bill_last_name"];
                $business_address   = $_POST["billing_address1"];
                $business_city      = $_POST["billing_city"];
                $business_state     = $_POST["billing_state"];
                $business_zipcode   = $_POST["billing_zip"];
                /*$business_telephone = '800-555-1234';
                $shipping_firstname = 'John';
                $shipping_lastname  = 'Smith';
                $shipping_address   = '100 Business Rd';
                $shipping_city      = 'Big City';
                $shipping_state     = 'NY';
                $shipping_zipcode   = '10101';*/

                $creditcard = $_POST["credit_card_number"];
                $expiration = $_POST["expiration_month"].'-'.$_POST["expiration_year"];
                $total      = $total;
                //$total      = 0.01;
                $cvv        = $_POST["security_code"];
                //$invoice    = substr(time(), 0, 6);
                //$tax        = 0.00;

                $payment = new AuthnetAIM($authorizenet_apiloginid, $authorizenet_transactionkey, $account_type);
                $payment->setTransaction($creditcard, $expiration, $total, $cvv);
                //$payment->setParameter("x_duplicate_window", 180);
                //$payment->setParameter("x_cust_id", $user_id);
                $payment->setParameter("x_customer_ip", $_SERVER['REMOTE_ADDR']);
                $payment->setParameter("x_email", $email);
                $payment->setParameter("x_email_customer", FALSE);
                $payment->setParameter("x_first_name", $business_firstname);
                $payment->setParameter("x_last_name", $business_lastname);
                $payment->setParameter("x_address", $business_address);
                $payment->setParameter("x_city", $business_city);
                $payment->setParameter("x_state", $business_state);
                $payment->setParameter("x_zip", $business_zipcode);
                //$payment->setParameter("x_phone", $business_telephone);
                /*$payment->setParameter("x_ship_to_first_name", $shipping_firstname);
                $payment->setParameter("x_ship_to_last_name", $shipping_lastname);
                $payment->setParameter("x_ship_to_address", $shipping_address);
                $payment->setParameter("x_ship_to_city", $shipping_city);
                $payment->setParameter("x_ship_to_state", $shipping_state);
                $payment->setParameter("x_ship_to_zip", $shipping_zipcode);*/
                $payment->setParameter("x_description", $product);
                $payment->process();

                if ($payment->isApproved())
                {
                    //print_r($payment);

                    // Get info from Authnet to store in the database
                    $approval_code  = $payment->getAuthCode();
                    $avs_result     = $payment->getAVSResponse();
                    $cvv_result     = $payment->getCVVResponse();
                    $transaction_id = $payment->getTransactionID();
                    $ResponseText 	= $payment->getResponseText();

                    $_SESSION['approval_code'] =$approval_code;
                    $_SESSION['transaction_id'] = $transaction_id;
                    $_SESSION['responese_text'] = $ResponseText;
                    
                    $check_out= $_SESSION['CHECKOUT'];

                    $CheckoutMethod = $_SESSION['CHECKOUT']['checkout_method'];
                    $BillingDetails = $check_out['billing_address']->guid;
                    $ShippingDetails = $check_out['shipping_address']->guid;
                    $ShippingMethods = $check_out['shipping_method'];
                    if(!$ShippingMethods)
                            $ShippingMethods = 0;
                    $transactions = array();
    /*		$transactions = array('status'=>'Pending',
                                                              'txn_id'=>$txn_id,
                                                              'email'=>$receiver_email,
                                                              'fee'=>$payment_fee,
                                                              'total'=>$payment_gross,
                                                              'params'=>array());

    */              create_order($page_owner,$CheckoutMethod,$transactions,$BillingDetails,$ShippingDetails,$ShippingMethods);
                    //return true;
                    $ts = time();
                    $redirect = $CONFIG->wwwroot."action/{$CONFIG->pluginname}/manage_socialcommerce?manage_action=cart_success&__elgg_token=".generate_action_token($ts)."&__elgg_ts={$ts}&view=".elgg_get_viewtype();
                    forward($redirect);
                    exit;

                    // Do stuff with this. Most likely store it in a database.
                    // Direct the user to a receipt or something similiar.
                }
                else if ($payment->isDeclined())
                {
                    // Get reason for the decline from the bank. This always says,
                    // "This credit card has been declined". Not very useful.
                    $reason = $payment->getResponseText();

                    $_SESSION['reason'] = $reason;
                    
                    $ts = time();
                    $redirect = $CONFIG->wwwroot."action/{$CONFIG->pluginname}/manage_socialcommerce?manage_action=cart_cancel&__elgg_token=".generate_action_token($ts)."&__elgg_ts={$ts}&view=".elgg_get_viewtype();
                    forward($redirect);
                    exit;

                    // Politely tell the customer their card was declined
                    // and to try a different form of payment.
                }
                else if ($payment->isError())
                {
                    // Get the error number so we can reference the Authnet
                    // documentation and get an error description.
                    $error_number  = $payment->getResponseSubcode();
                    $error_message = $payment->getResponseText();

                    // OR

                    // Capture a detailed error message. No need to refer to the manual
                    // with this one as it tells you everything the manual does.
                    $full_error_message =  $payment->getResponseMessage();

                    $_SESSION['full_error_message'] = $full_error_message;

                    $ts = time();
                    $redirect = $CONFIG->wwwroot."action/{$CONFIG->pluginname}/manage_socialcommerce?manage_action=cart_cancel&__elgg_token=".generate_action_token($ts)."&__elgg_ts={$ts}&view=".elgg_get_viewtype();
                    forward($redirect);
                    exit;

                }
                /////////////////////////////////////////////////////////////////////////////////////////////////
                
	}
	
	function varyfy_checkout_settings_authorizenet(){
		$options = array('metadata_name_value_pairs'	=>	array('checkout_method' => 'authorizenet'),
						'types'				=>	"object",
						'subtypes'			=>	"s_checkout",
						'limit'				=>	1);
		$settings = elgg_get_entities_from_metadata($options);
		if($settings){
			$settings = $settings[0];
			$display_name = trim($settings->display_name);
			$loginid = trim($settings->socialcommerce_authorizenet_apiloginid);
			$transactionkey = trim($settings->socialcommerce_authorizenet_transactionkey);
            //$authorizenet_environment = trim($settings->socialcommerce_authorizenet_environment);
			
			if($display_name == "")
				$missing_field = elgg_echo('display:name');
			if($loginid == "")
				$missing_field .= $missing_field != "" ? ",".elgg_echo('api:login:id') : elgg_echo('api:login:id');
			if($transactionkey == "")
				$missing_field .= $missing_field != "" ? ",".elgg_echo('transaction:key') : elgg_echo('transaction:key');
			if($missing_field != ""){
				return sprintf(elgg_echo('missing:fields'),$missing_field);
			}
			return;
		}else{
			return elgg_echo('not:fill:Authorize.net:settings');
		}
	}
	
?>
