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
	 * Elgg cart - remove action
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
		global $CONFIG;
		if(elgg_is_logged_in()){
			$guid = (int) get_input('cart_guid');
			if ($cart_item = get_entity($guid)) {
				if ($cart_item->canEdit()) {
					$container = get_entity($cart_item->container_guid);
					$options = array('relationship' 		=> 	'cart_related_item',
									'relationship_guid' 	=>	$cart_item->guid,
									'types'					=>	'object',
									'subtypes'				=>	'cart_related_item',
									'limit'					=>	99999,
									);
					$related_products = elgg_get_entities_from_relationship($options);
					if ($cart_item->delete()) {
						if($related_products){
							foreach($related_products as $related_product){
								$related_product->delete();
							}
						}
						system_message(sprintf(elgg_echo("cart:deleted"),$cart_item->title));
					} else {
						register_error(elgg_echo("cart:deletefailed"));
					}
				} else {
					$container = $_SESSION['user'];
					register_error(elgg_echo("cart:deletefailed"));
				}
			} else {
				register_error(elgg_echo("cart:deletefailed"));
			}
			$username = $_SESSION['user']->username;
		}elseif ($CONFIG->allow_add_cart == 1 && isset($_SESSION['GUST_CART']) && !empty($_SESSION['GUST_CART'])){
			$guid = (int) get_input('session_key');
			if($guid){
				$cart_item = $_SESSION['GUST_CART'][$guid];
				unset($_SESSION['GUST_CART'][$guid]);
				system_message(sprintf(elgg_echo("cart:deleted"),$cart_item->title));
			}
			$username = 'gust';
		}
		forward("{$CONFIG->pluginname}/cart/");
?>