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
	 
	function set_checkout_settings_cod(){
		
		return true;
	}
	
	function checkout_payment_settings_cod(){	
	
		makepayment_cod();	
	}
	
	function makepayment_cod(){
		gatekeeper();
		global $CONFIG;
//		/print_r($_SESSION['CHECKOUT']);exit;
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
							  'total'=>$payment_gross);

*/		create_order($page_owner,$CheckoutMethod,$transactions,$BillingDetails,$ShippingDetails,$ShippingMethods);	
		//return true;
		$ts = time();
		$redirect = $CONFIG->wwwroot."action/{$CONFIG->pluginname}/manage_socialcommerce?manage_action=cart_success&__elgg_token=".generate_action_token($ts)."&__elgg_ts={$ts}&view=".elgg_get_viewtype();
		forward($redirect);
		exit;
	}
	
	function varyfy_checkout_settings_cod(){

	}
	
?>