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
	 * Elgg default shipping - actions
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	function set_config_fedex(){
		global $CONFIG;
		$CONFIG->fedex_service_types = array(
						'PRIORITYOVERNIGHT'=>'Priority Overnight',
						'STANDARDOVERNIGHT'=>'Standard Overnight',
						'FIRSTOVERNIGHT'=>'First Overnight',
						'FEDEX2DAY'=>'FedEx 2 Day',
						'FEDEXEXPRESSSAVER'=>'FedEx Express Saver',
						'INTERNATIONALPRIORITY'=>'International Priority',
						'INTERNATIONALECONOMY'=>'International Economy',
						'INTERNATIONALFIRST'=>'International First',
						'FEDEX1DAYFREIGHT'=>'FedEx 1 Day Freight',
						'FEDEX2DAYFREIGHT'=>'FedEx 2 Day Freight',
						'FEDEX3DAYFREIGHT'=>'FedEx 3 Day Freight',
						'FEDEXGROUND'=>'FedEx Ground',
						'GROUNDHOMEDELIVERY'=>'Ground Home Delivery',
						'INTERNATIONALPRIORITY FREIGHT'=>'International Priority Freight',
						'INTERNATIONALECONOMY FREIGHT'=>'International Economy Freight',
						'EUROPEFIRSTINTERNATIONALPRIORITY'=>'Europe First International Priority'
					);
					
		$CONFIG->fedex_delivery_types = array(
						'FDXE'=>'FedEx Express',
						'FDXG'=>'FedEx Ground'
					);
					
		$CONFIG->fedex_drop_off_type = array(
						'REGULARPICKUP'=>'Regular Pickup',
						'REQUESTCOURIER'=>'Request Courier',
						'DROPBOX'=>'Drop Box',
						'BUSINESSSERVICE CENTER'=>'Business Service Center',
						'STATION'=>'Station'
					);
					
		$CONFIG->fedex_packaging_type = array(
						'FEDEXENVELOPE'=>'FedEx Envelope',
						'FEDEXPAK'=>'FedEx Pak',
						'FEDEXBOX'=>'FedEx Box',
						'FEDEXTUBE'=>'FedEx Tube',
						'FEDEX10KGBOX'=>'FedEx 10kg Box',
						'FEDEX25KGBOX'=>'FedEx 25kg Box',
						'YOURPACKAGING'=>'Your Packaging'
					);
					
		$CONFIG->fedex_rate_type = array(
						'list'=>'List Rate',
						'account'=>'Account Rate'
					);
	}
	
	function set_shipping_settings_fedex(){
		
		$guid = get_input('guid');
		
		$error_field = "";
		$account_no = trim(get_input('account_no'));
		$meter_no = trim(get_input('meter_no'));
		$service_types = get_input('service_types');
		$delivery_types = get_input('delivery_types');
		$drop_off_type = trim(get_input('drop_off_type'));
		$packaging_type = trim(get_input('packaging_type'));
		$rate_type = trim(get_input('rate_type'));
		
		if(empty($account_no)){
			$error_field = ", ".elgg_echo("account:no");
		}
		if(empty($meter_no)){
			$error_field .= ", ".elgg_echo("meter:no");
		}
		if(count($service_types) <= 0){
			$error_field .= ", ".elgg_echo("service:types");
		}
		if(count($delivery_types) <= 0){
			$error_field .= ", ".elgg_echo("delivery:types");
		}
		if(empty($drop_off_type)){
			$error_field .= ", ".elgg_echo("drop:off:type");
		}
		if(empty($packaging_type)){
			$error_field .= ", ".elgg_echo("packaging:type");
		}
		if(empty($rate_type)){
			$error_field .= ", ".elgg_echo("rate:type");
		}
		
		if(empty($error_field)){
			if($guid){
				$shipping_settings = get_entity($guid);
			}else{
				$shipping_settings = new ElggObject($guid);
			}
			
			$shipping_settings->access_id = 2;
			$shipping_settings->container_guid = $_SESSION['user']->guid;
			$shipping_settings->subtype = 's_shipping';
			$shipping_settings->shipping_method = 'fedex';
			$shipping_settings->account_no = $account_no;
			$shipping_settings->meter_no = $meter_no;
			$shipping_settings->service_types = $service_types;
			$shipping_settings->delivery_types = $delivery_types;
			$shipping_settings->drop_off_type = $drop_off_type;
			$shipping_settings->packaging_type = $packaging_type;
			$shipping_settings->rate_type = $rate_type;
			$shipping_settings->save();
			
			system_message(sprintf(elgg_echo("settings:saved"),""));
			return $settings->guid;
		}elseif (!empty($error_field)){
			$error_field = substr($error_field,2);
			register_error(sprintf(elgg_echo("settings:validation:null"),$error_field));
			return false;
		}
	}
	
	function price_calc_fedex($products){
		/*$shipping_settings = get_entities_from_metadata('shipping_method','default','object','s_shipping',0,1);
		if($shipping_settings){
			$shipping_settings = $shipping_settings[0];
			$shipping_per_item = $shipping_settings->shipping_per_item;
		}
		$shipping_price = array();
		foreach($products as $product_guid=>$product){
			if($product->type == 1)
				$shipping_price[$product_guid] = $shipping_per_item * $product->quantity;
		}
		return $shipping_price;*/
	}
	
	function varyfy_shipping_settings_fedex(){
		/*$settings = get_entities_from_metadata('shipping_method','default','object','s_shipping',0,1);
		if($settings){
			$settings = $settings[0];
			$display_name = trim($settings->display_name);
			$shipping_per_item = trim($settings->shipping_per_item);
			
			if($display_name == "")
				$missing_field = elgg_echo('display:name');
			if($shipping_per_item == "")
				$missing_field .= $missing_field != "" ? ",".elgg_echo('shipping:cost:per:item') : elgg_echo('shipping:cost:per:item');
			
			if($missing_field != ""){
				return sprintf(elgg_echo('default:missing:fields'),$missing_field);
			}
			return;
		}else{
			return elgg_echo('not:fill:default:settings');
		}*/
	}
?>
