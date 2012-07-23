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
	 * Elgg coupon - edit action
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	gatekeeper();
	global $CONFIG;
	
	// Get variables
	$coupon_code = get_input('coupon_code');
	$coupon_name = get_input('coupon_name');
	$coupon_amount = get_input('coupon_amount');
	$coupon_type = get_input('coupon_type');
	$exp_date = get_input('exp_date');
	$coupon_min_purchase = get_input('coupon_min_purchase');
	$coupon_maxuses = get_input('coupon_maxuses');
	$coupon_products = get_input('coupon_products');
	if(!is_array($coupon_products))
		$coupon_products = array($coupon_products);
	$guid = (int) get_input('coupon_guid');
	
	$result = false;
	//Validation
	if(empty($coupon_code)){
		$error_field = elgg_echo("coupon:code");
	}
	if(empty($coupon_name)){
		$error_field .= $error_field ? ','.elgg_echo("coupon:name") : elgg_echo("coupon:name");
	}
	if(empty($coupon_amount)){
		$error_field .= $error_field ? ','.elgg_echo("coupon:discount") : elgg_echo("coupon:discount");
	}
	
	if(!empty($error_field)){
		$vars['coupon']['coupon_code'] = $coupon_code;
		$vars['coupon']['coupon_name'] = $coupon_name;
		$vars['coupon']['coupon_amount'] = $coupon_amount;
		$vars['coupon']['coupon_type'] = $coupon_type;
		$vars['coupon']['exp_date'] = $exp_date;
		$vars['coupon']['coupon_min_purchase'] =$coupon_min_purchase;
		$vars['coupon']['coupon_maxuses'] = $coupon_maxuses;
			
		register_error(sprintf(elgg_echo("coupon:validation:null"),$error_field));
		$redirect = $CONFIG->wwwroot . "{$CONFIG->pluginname}/coupon/";
	}else{
		$coupon =  new ElggObject($guid);
		$coupon->subtype = 'coupons';
		$coupon->access_id = 2;
		
		$coupon->coupon_code = $coupon_code;
		$coupon->coupon_name = $coupon_name;
		$coupon->coupon_amount = $coupon_amount;
		$coupon->coupon_type = $coupon_type;
		if($exp_date){
			$exp_date = strtotime($exp_date);
			$coupon->exp_date = $exp_date;
		}
		$coupon->coupon_min_purchase = $coupon_min_purchase;
		if($coupon_maxuses == 0 || $coupon_maxuses == ''){
			$coupon_maxuses = 'Unlimited';
		}
		$coupon->coupon_maxuses = $coupon_maxuses;
		$coupon->coupon_products = $coupon_products;
		$result = $coupon->save();
		
		if ($result){
			if($coupon_products){
				remove_entity_relationships($result, 'coupon_product');
				foreach($coupon_products as $coupon_product){
					add_entity_relationship($result,'coupon_product',$coupon_product);
				}
			}
			system_message(elgg_echo("coupon:saved"));
			unset($_SESSION['coupon']);
		}else{
			register_error(elgg_echo("coupon:addfailed"));
		}
		
		$container_user = get_entity($container_guid);
		$redirect = $CONFIG->wwwroot . "{$CONFIG->pluginname}/coupon/";
	}
	forward($redirect);
?>