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
	 
	function set_shipping_settings_default(){
		
		$guid = get_input('guid');
		
		$error_field = "";
		$display_name = get_input('display_name');
		$shipping_per_item = get_input('shipping_per_item');
		if(empty($display_name)){
			$error_field = ", ".elgg_echo("display:name");
		}
		if(empty($shipping_per_item)){
			$error_field .= ", ".elgg_echo("shipping:cost:per:item");
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
			$shipping_settings->shipping_method = 'default';
			$shipping_settings->display_name = $display_name;
			$shipping_settings->shipping_per_item = $shipping_per_item;
			$shipping_settings->save();
			
			system_message(sprintf(elgg_echo("settings:saved"),""));
			return $settings->guid;
		}elseif (!empty($error_field)){
			$error_field = substr($error_field,2);
			register_error(sprintf(elgg_echo("settings:validation:null"),$error_field));
			return false;
		}
	}
	
	function price_calc_default($products){
		//Depricated function replace
		$options = array(	'metadata_name_value_pairs'	=>	array('shipping_method' => 'default'),
						'types'				=>	"object",
						'subtypes'			=>	"s_shipping",
						'limit'				=>	1,
					);
		$shipping_settings = elgg_get_entities_from_metadata($options);
		//$shipping_settings = get_entities_from_metadata('shipping_method','default','object','s_shipping',0,1);
		if($shipping_settings){
			$shipping_settings = $shipping_settings[0];
			$default_shipping_per_item = $shipping_settings->shipping_per_item;			
		}
		$shipping_price = array();
		foreach($products as $product_guid=>$product){
			$product_entity = get_entity($product_guid);
			$shipping_per_item = $default_shipping_per_item;			
			if($product_entity->p_fixed > 0){
				$shipping_per_item = $product_entity->p_fixed;
			}
			if($product->type == 1)
				$shipping_price[$product_guid] = $shipping_per_item * $product->quantity;
		}
		return $shipping_price;
	}
	
	function varyfy_shipping_settings_default(){
		//Depricated function replace
		$options = array(	'metadata_name_value_pairs'	=>	array('shipping_method' => 'default'),
						'types'				=>	"object",
						'subtypes'			=>	"s_shipping",
						'limit'				=>	1,
					);
		$settings = elgg_get_entities_from_metadata($options);
		//$settings = get_entities_from_metadata('shipping_method','default','object','s_shipping',0,1);
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
		}
	}
?>