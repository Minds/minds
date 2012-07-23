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
	 * Elgg wishlist - add action
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 

	global $CONFIG;
	if (!elgg_is_logged_in()) {
		system_message(elgg_echo("add:wishlist:not:login"));
		$_SESSION['last_forward_from'] = current_page_url();
		forward(REFERER);
	}else {
		$product_guid = (int) get_input('pgid');
		if($product_guid > 0){
			$product = get_entity($product_guid);
			$product_type_details = get_product_type_from_value($product->product_type_id);
			if($product_type_details->addto_cart != 1){
				$reditrect = $product->getURL();
				forward($reditrect);
			}
			if($product->status != 1 || $_SESSION['user']->guid == $product->owner_guid){
				forward(REFERER);
			}
		}else{
			forward(REFERER);
		}
	}
	
	// Get variables
	if($product && $product_guid && $product_guid > 0){
		if(check_entity_relationship($_SESSION['user']->guid,'wishlist',$product_guid)){
			system_message(elgg_echo("wishlist:already:added"));
		}else{
			$result = add_entity_relationship($_SESSION['user']->guid,'wishlist',$product_guid);
			if($result){
				if(in_array('wishlist_add',$CONFIG->river_settings))
					add_to_river('river/object/wishlist/create','wishlistadd',$_SESSION['user']->guid,$product_guid);
				system_message(elgg_echo("wishlist:added"));
			}else {
				register_error(elgg_echo("wishlist:added:failed"));
			}
		}
	}
	
	forward(REFERER);
?>