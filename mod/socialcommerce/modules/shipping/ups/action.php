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
	 * Elgg UPS shipping - actions
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	function set_config_ups(){
		global $CONFIG;
		$CONFIG->ups_service_types = array(
						"1DM"=>'Next Day Air Early AM',
						"1DA"=>'Next Day Air',
						"1DP"=>'Next Day Air Saver',
						"2DM"=>'2nd Day Air Early AM',
						"2DA"=>'2nd Day Air',
						"3DS"=>'3 Day Select',
						"GND"=>'Ground',
						"STD"=>'Canada Standard',
						"XPR"=>'Worldwide Express',
						"XDM"=>'Worldwide Express Plus',
						"XPD"=>'Worldwide Expedited'
					);
					
		$CONFIG->ups_packing_type = array(
						"00"=>'Customer Packaging',
						"01"=>'UPS Letter Envelope',
						"03"=>'UPS Tube',
						"21"=>'UPS Express Box',
						"24"=>'UPS Worldwide 25KG Box',
						"25"=>'UPS Worldwide 10KG Box'
					);
					
		$CONFIG->ups_address_type = array(
						"01"=>'Residential',
						"02"=>'Commercial'
					);
					
		$CONFIG->ups_shipping_rate = array(
						"Regular+Daily+Pickup"=>'Regular Daily Pickup',
						"On+Call+Air"=>'On Call Air',
						"One+Time+Pickup"=>'One Time Pickup',
						"Letter+Center"=>'Letter Center',
						"Customer+Counter"=>'Customer Counter'
					);
	}
	
	function set_shipping_settings_ups(){
		
		$guid = get_input('guid');
		
		$error_field = "";
		$service_types = get_input('service_types');
		$packing_type = trim(get_input('packing_type'));
		$shipping_rate = trim(get_input('shipping_rate'));
		$address_type = trim(get_input('address_type'));
		
		if(count($service_types) <= 0){
			$error_field .= ", ".elgg_echo("service:types");
		}
		if(empty($packing_type)){
			$error_field .= ", ".elgg_echo("packaging:type");
		}
		if(empty($shipping_rate)){
			$error_field .= ", ".elgg_echo("shipping:rate");
		}
		if(empty($address_type)){
			$error_field .= ", ".elgg_echo("address:type");
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
			$shipping_settings->shipping_method = 'ups';
			$shipping_settings->service_types = $service_types;
			$shipping_settings->packing_type = $packing_type;
			$shipping_settings->shipping_rate = $shipping_rate;
			$shipping_settings->address_type = $address_type;
			$shipping_settings->save();
			
			system_message(sprintf(elgg_echo("settings:saved"),""));
			return $settings->guid;
		}elseif (!empty($error_field)){
			$error_field = substr($error_field,2);
			register_error(sprintf(elgg_echo("settings:validation:null"),$error_field));
			return false;
		}
	}
	
	function get_quote_ups($service_types,$packaging_type,$shipping_rate,$address_type,$destination,$origin,$weight,$length='',$width='',$height=''){
		global $CONFIG;
		//Depricated function replace
		$options = array('types'			=>	"object",
						'subtypes'			=>	"splugin_settings",
						'owner_guids'		=>	$_SESSION['user']->guid,
					);
		$splugin_settings = elgg_get_entities($options);
		//$splugin_settings = get_entities('object','splugin_settings',$_SESSION['user']->guid);
		if($splugin_settings){
			$splugin_settings = $splugin_settings[0];
		}
		
		if(is_array($weight) && count($weight) > 0){
			$to_unit = $weight['to_unit'];
			$weight = $weight['weight'];
			$weight = convert_weight($weight,'lbs',$to_unit);
		}else{
			$weight = convert_weight($weight,'lbs');
		}
		
		$ups_quote = array();
		$result = "";
		$valid_quote = false;
		$action = "3";
		$ups_url = "http://www.ups.com/using/services/rave/qcostcgi.cgi?accept_UPS_license_agreement=yes&";
		$ups_fields = array("10_action=$action",
							"13_product=".$service_types,
							"48_container=".$packaging_type,
							"47_rateChart=".$shipping_rate,
							"49_residential=".$address_type,
							"22_destCountry=".$destination['to_country'],
							"20_destCity=".$destination['to_state'],
							"19_destPostal=".$destination['to_zip'],
							"14_origCountry=".'US',
							"origCity=".'Redmond',
							"15_origPostal=".'98052',
							"23_weight=".$weight
			);
		if($length != "")
			$ups_fields['25_length'] = $length;
		if($width != "")
			$ups_fields['26_width'] = $width;
		if($height != "")
			$ups_fields['27_height'] = $height;	
	
		$post_vars = implode("&",$ups_fields);
		
		if(function_exists("curl_exec")) {
			$ch = @curl_init($ups_url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vars);
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			if ($splugin_settings->http_proxy_server) {
				curl_setopt($ch, CURLOPT_PROXY, $splugin_settings->http_proxy_server);
				if ($splugin_settings->http_proxy_port) {
					curl_setopt($ch, CURLOPT_PROXYPORT, $splugin_settings->http_proxy_port);
				}
			}
			if($splugin_settings->http_varify_ssl)
				$http_varify_ssl = 1;
			else 
				$http_varify_ssl = 0;
				
			if ($http_varify_ssl == 0) {
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			}

			$result = curl_exec($ch);

			if($result != "") {
				$valid_quote = true;
			}
		}else {
			// Use fopen instead
			if($fp = @fopen($ups_url . $post_vars, "rb")) {
				$result = "";

				while(!feof($fp))
					$result .= fgets($fp, 4096);

				@fclose($fp);
				$valid_quote = true;
			}
		}
		
		if($valid_quote) {
			$result = explode("%", $result);
			if(count($result) > 5) {
				$quote_desc = "";
				// Set the description of the method
				foreach($CONFIG->ups_service_types as $v=>$k) {
					if($v == $result[1]) {
						$quote_desc = $k;
					}
				}
				$return = $quote_desc.' - '.$result[8];
			}
		}else{
			$return = "An error occured.";
		}
		return $return;
	}
	
	function price_calc_ups($products){
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
	
	function varyfy_shipping_settings_ups(){
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
