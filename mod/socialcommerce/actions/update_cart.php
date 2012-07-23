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
	 * Elgg cart - update action
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	 // Load Elgg engine
	 require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
	 global $CONFIG;
	 // Check membership privileges
	$permission = membership_privileges_check('buy');
	if($permission == 1) {
		 $quanties = get_input('cartquantity');
		 $username = $_SESSION['user']->username;
		 foreach ($quanties as $cart_item_guid=>$quantity){
		 	$guid = (int)$cart_item_guid;
		 	if(elgg_is_logged_in()){
		 		if ($cart_item = get_entity($guid)) {
		 			if ($cart_item->canEdit()) {
		 				$product = get_entity($cart_item->product_id);
		 			}
		 		}
		 		
		 	}elseif ($CONFIG->allow_add_cart == 1 && isset($_SESSION['GUST_CART']) && !empty($_SESSION['GUST_CART'])){
		 		$cart_item = $_SESSION['GUST_CART'][$guid];
		 		if(!empty($cart_item)){
		 			if(is_array($cart_item)){
						$cart_item = (object) array('product_id'=>$cart_item['product_id'],
													'quantity' => $cart_item['quantity'],
													'amount' => $cart_item['amount'],
													'time_created' => $cart_item['time_created'],
													'guid' => $cart_item['product_id']);
					}
					$product = get_entity($cart_item->product_id);
		 		}
		 		$username = 'gust';
		 	}
		 	if (is_numeric($quantity)) { 
	 			if($quantity > 0){
	 				if($product->quantity >= $quantity){
		 				if($cart_item->quantity != $quantity){
		 					if(elgg_is_logged_in()){
					 			$cart_item->quantity = $quantity;
					 			$result = $cart_item->save();
		 					}elseif ($CONFIG->allow_add_cart == 1 && isset($_SESSION['GUST_CART']) && !empty($_SESSION['GUST_CART'])){
		 						$cart_item = $_SESSION['GUST_CART'][$guid]['quantity'] = $quantity;
		 					}
		 				}
		 			}else{
		 				register_error(sprintf(elgg_echo("cart:limit:quantity:failed"),$product->title,$product->quantity));
		 			}
	 			}else{
	 				if(empty($quantity)) $quantity = "null";
	 				register_error(sprintf(elgg_echo("cart:less:quantity:failed"),$product->title,$quantity));
	 			}
			}else {
				register_error(sprintf(elgg_echo("cart:pregmatch:quantity:failed"),$product->title));
			}
		 }
		 forward("{$CONFIG->pluginname}/cart/");
	} else {
		system_message(elgg_echo("update:buy"));
		forward($CONFIG->wwwroot."{$CONFIG->pluginname}/all");
	}
?>