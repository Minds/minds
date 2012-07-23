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
	 * Elgg masspay withdraw - action
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	function set_withdraw_settings_masspay(){
		global $CONFIG;
		$guid = get_input('guid');
		
		$error_field = "";
		$display_name = get_input('display_name');
		$api_username = get_input('paypal_api_username');
		$api_password = get_input('paypal_api_password');
		$api_signature = get_input('paypal_api_signature');
		$environment = get_input('socialcommerce_paypal_environment');
		if(empty($display_name)){
			$error_field = ", ".elgg_echo("display:name");
		}
		if(empty($api_username)){
			$error_field .= ", ".elgg_echo("paypal:api:usernaem");
		}
		if(empty($api_password)){
			$error_field .= ", ".elgg_echo("paypal:api:password");
		}
		if(empty($api_signature)){
			$error_field .= ", ".elgg_echo("paypal:api:signature");
		}
		if(empty($error_field)){
			if($guid){
				$withdraw_settings = get_entity($guid);
			}else{
				$withdraw_settings = new ElggObject($guid);
			}
			
			$withdraw_settings->access_id = 2;
			$withdraw_settings->container_guid = $_SESSION['user']->guid;
			$withdraw_settings->subtype = 's_withdraw';
			$withdraw_settings->withdraw_method = 'masspay';
			$withdraw_settings->display_name = $display_name;
			$withdraw_settings->api_paypal_username = $api_username;
			$withdraw_settings->api_paypal_password = $api_password;
			$withdraw_settings->api_paypal_signature = $api_signature;
			$withdraw_settings->socialcommerce_paypal_environment = $environment;
			$withdraw_settings->save();
			
			system_message(sprintf(elgg_echo("settings:saved"),""));
			return $settings->guid;
		}elseif (!empty($error_field)){
			$error_field = substr($error_field,2);
			register_error(sprintf(elgg_echo("settings:validation:null"),$error_field));
			return false;
		}
	}
	
	function wsettings_forms_masspay(){
		global $CONFIG;
		$settings = elgg_view('modules/withdraw/masspay/settings_form');
		return $settings;
	}
	
	function withdraw_funt_masspay(){
		global $CONFIG;
		
		$original_amount = get_input('withdraw_amount');
		$total_amount = get_input('total_amount');
		$receiver_email = trim(get_input('paypal_email'));
		$error = 0;
		if(!$original_amount > 0){
			register_error(elgg_echo('error:amount:null'));
			$error = 1;
		}else{
			$pattern = '/^((\d+(\.\d*)?)|((\d*\.)?\d+))$/';
			if(!preg_match($pattern,$original_amount)){
				register_error(elgg_echo('error:amount:not:valid'));
				$error = 1;
			}else{
				if($original_amount > $total_amount){
					$view_total_amount = get_price_with_currency($total_amount);
					register_error(sprintf(elgg_echo('error:amount:not:allow'),$view_total_amount));
					$error = 1;
				}
			}
		}
		if($receiver_email == ""){
			register_error(elgg_echo('error:email:null'));
			$error = 1;
		}else{
			$pattern = '/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/';
			if(!preg_match($pattern,$receiver_email)){
				register_error(elgg_echo('error:email:not:valid'));
				$error = 1;
			}
		}
		//$error = 1;
		if($error == 1){
			$_SESSION['WITHDRAW']['amount'] = $original_amount;
			$_SESSION['WITHDRAW']['paypal_email'] = $receiver_email;
			return false;
		}else {
			// Set request-specific fields.
			$emailSubject =urlencode('Social Commerce: Fund Withdrawal');
			$receiverType = urlencode('EmailAddress');
			
			$validate_currency = validate_currency($CONFIG->currency_code,$original_amount,'paypal');
			$currency = urlencode($validate_currency['currency_code']);
			$amount = $validate_currency['amount'];
			
			// Add request-specific fields to the request string.
			$nvpStr="&EMAILSUBJECT=$emailSubject&RECEIVERTYPE=$receiverType&CURRENCYCODE=$currency";
			
			$receiversArray = array();
			
			$receiverData = array('receiverEmail' => $receiver_email,
								'amount' => $amount,
								'uniqueID' => rand(),
								'note' => "Payment To:".$receiver_email);
			$receiversArray[0] = $receiverData;
			
			foreach($receiversArray as $i => $receiverData) {
				$receiverEmail = urlencode($receiverData['receiverEmail']);
				$amount = urlencode($receiverData['amount']);
				$uniqueID = urlencode($receiverData['uniqueID']);
				$note = urlencode($receiverData['note']);
				$nvpStr .= "&L_EMAIL$i=$receiverEmail&L_Amt$i=$amount&L_UNIQUEID$i=$uniqueID&L_NOTE$i=$note";
			}
			
			// Execute the API operation; see the PPHttpPost function above.
			$httpParsedResponseAr = PayPalHttpPost('MassPay', $nvpStr);
			
			if("Success" == $httpParsedResponseAr["ACK"] || "SuccessWithWarning" == $httpParsedResponseAr["ACK"]) {
				$result = create_withdraw_transaction($original_amount,$receiver_email);
				//echo '@@'.$_SESSION['user']->guid;
				//Depricated function replace
				$options = array('types'			=>	"object",
								'subtypes'			=>	"wth_request",
								'owner_guids'		=>	$_SESSION['user']->guid,
								'limit'				=>	1,
							);
				$payment_processed = elgg_get_entities($options);
				//$payment_processed = get_entities('object','wth_request',$_SESSION['user']->guid,'',1);
				if($payment_processed){
					$payment_processed[0]->processed = 1;
					$payment_processed[0]->transaction = $result;
					$result = $payment_processed[0]->save();
					if($result){
						(elgg_echo("mass:pay:transaction:success"));
						return true;
					}else{
						register_error(elgg_echo("mass:pay:transaction:failed"));
						return false;
					}
				}
				return false;
			}else{
				$_SESSION['WITHDRAW']['amount'] = $original_amount;
				$_SESSION['WITHDRAW']['paypal_email'] = $receiver_email;
				for ($i=0;$i<=10;$i++){
					if(isset($httpParsedResponseAr['L_ERRORCODE'.$i])){
						$error_code = urldecode($httpParsedResponseAr['L_ERRORCODE'.$i]);
						$error_smessage = urldecode($httpParsedResponseAr['L_SHORTMESSAGE'.$i]);
						$error_message = urldecode($httpParsedResponseAr['L_LONGMESSAGE'.$i]);
						$paypal_message = <<<EOF
							<div>
								<div><B>Error Code: </B>{$error_code}</div>
								<div><B>Short Message: </B>{$error_smessage}</div>
								<div><B>Message: </B>{$error_message}</div>
							</div>
EOF;
					}else{
						break;
					}
				}
				register_error(sprintf(elgg_echo("mass:pay:transaction:failed"),$paypal_message));
				return false;
			}
		}
	}
	
	function PayPalHttpPost($methodName, $nvpStr) {
		global $CONFIG;
		//Depricated function replace
		$options = array(	'metadata_name_value_pairs'	=>	array('withdraw_method' => 'masspay'),
						'types'				=>	"object",
						'subtypes'			=>	"s_withdraw",
						'limit'				=>	1,
					);
		$withdraw_settings = elgg_get_entities_from_metadata($options);
		//$withdraw_settings = get_entities_from_metadata('withdraw_method','masspay','object','s_withdraw',0,1);
				
		if($withdraw_settings)
			$withdraw_settings = $withdraw_settings[0];
			
		if($withdraw_settings->socialcommerce_paypal_environment){
			$environment = $withdraw_settings->socialcommerce_paypal_environment;
		}else{
			$environment = "sandbox";
		}
	
		// Set up your API credentials, PayPal end point, and API version.
		$API_UserName = $withdraw_settings->api_paypal_username;
		$API_Password = $withdraw_settings->api_paypal_password;
		$API_Signature = $withdraw_settings->api_paypal_signature;
		
		$API_Endpoint = "https://api-3t.paypal.com/nvp";
		
		if("sandbox" === $environment || "beta-sandbox" === $environment) {
			$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
		}
		
		$version = urlencode('52.0');
	
		// Set the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
	
		// Turn off the server and peer verification (TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
	
		// Set the API operation, version, and API signature in the request.
		$nvpreq = "METHOD={$methodName}&VERSION={$version}&PWD={$API_Password}&USER={$API_UserName}&SIGNATURE={$API_Signature}{$nvpStr}";
		
		// Set the request as a POST FIELD for curl.
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
	
		// Get response from the server.
		$httpResponse = curl_exec($ch);
	
		if(!$httpResponse) {
			exit("$methodName failed: ".curl_error($ch).'('.curl_errno($ch).')');
		}
	
		// Extract the response details.
		$httpResponseAr = explode("&", $httpResponse);
	
		$httpParsedResponseAr = array();
		foreach ($httpResponseAr as $i => $value) {
			$tmpAr = explode("=", $value);
			if(sizeof($tmpAr) > 1) {
				$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
			}
		}
	
		if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
			exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
		}
	
		return $httpParsedResponseAr;
	}	
		
	function varyfy_withdraw_settings_masspay(){
		//Depricated function replace
		$options = array(	'metadata_name_value_pairs'	=>	array('withdraw_method' => 'masspay'),
						'types'				=>	"object",
						'subtypes'			=>	"s_withdraw",
						'limit'				=>	1,
					);
		$settings = elgg_get_entities_from_metadata($options);
		//$settings = get_entities_from_metadata('withdraw_method','masspay','object','s_withdraw',0,1);
		if($settings){
			$settings = $settings[0];
			$display_name = trim($settings->display_name);
			$api_username = trim($settings->api_paypal_username);
			$api_password = trim($settings->api_paypal_password);
			$api_signature = trim($settings->api_paypal_signature);
			$environment = trim($settings->socialcommerce_paypal_environment);
			
			if($display_name == "")
				$missing_field = elgg_echo('display:name');
			if($api_username == "")
				$missing_field .= $missing_field != "" ? ",".elgg_echo('paypal:api:usernaem') : elgg_echo('paypal:api:usernaem');
			if($api_password == "")
				$missing_field .= $missing_field != "" ? ",".elgg_echo('paypal:api:password') : elgg_echo('paypal:api:password');
			if($api_signature == "")
				$missing_field .= $missing_field != "" ? ",".elgg_echo('paypal:api:signature') : elgg_echo('paypal:api:signature');
			if($environment == "")
				$missing_field .= $missing_field != "" ? ",".elgg_echo('mode') : elgg_echo('mode');
			
			if($missing_field != ""){
				return sprintf(elgg_echo('masspay:missing:fields'),$missing_field);
			}
			return;
		}else{
			return elgg_echo('not:fill:masspay:settings');
		}
	}
?>