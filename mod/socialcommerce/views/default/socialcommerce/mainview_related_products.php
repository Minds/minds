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
	 * Elgg view - tags menu
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	$product = $vars['entity'];
	$related_products = $vars['related_products'];
	$cart_related_details = array();
	if(elgg_is_logged_in()){
		//Depricated function replace
		$options = array(	'metadata_name_value_pairs'	=>	array('product_id' => $product->guid),
						'types'				=>	"object",
						'subtypes'			=>	"cart_item",
						'owner_guids'		=>	$_SESSION['user']->guid,
					);
		$cart_item = elgg_get_entities_from_metadata($options);
		//$cart_item = get_entities_from_metadata('product_id',$product->guid,'object','cart_item',$_SESSION['user']->guid);
		if($cart_item){
			//Depricated function replace
			$options = array('relationship' 		=> 	'cart_related_item',
												'relationship_guid' 	=>	$cart_item->guid,
												'types'					=>	"object",
												'subtypes'				=>	"cart_related_item",
												'limit'					=>	99999,
												);
			$cart_related_products = elgg_get_entities_from_relationship($options);
			//$cart_related_products = get_entities_from_relationship('cart_related_item',$cart_item->guid,'','object','cart_related_item','','',9999);
			if($cart_related_products){
				foreach($cart_related_products as $cart_related_product){
					$cart_related_details['detail_'.$cart_related_product->related_product] = $cart_related_product->details;
				}
			}
		}
	}else{
		$cart_related_products = $_SESSION['GUST_CART'][$product->guid]['related_products'];
		if($cart_related_products){
			foreach($cart_related_products as $cart_related_product=>$cart_details){
				$cart_related_details['detail_'.$cart_related_product] = $cart_details;
			}
		}
	}
	$selected_related_products = get_input('related_product',array(),false);
	if(count($selected_related_products) <= 0)
		$selected_related_products = $cart_related_details;
	
	if($product && $related_products){
		$context = elgg_get_context();
		elgg_set_context('mainview');
		foreach($related_products as $related_product){
			$selected_related_product = $selected_related_products['detail_'.$related_product->guid];
			//echo elgg_view_entity($related_product);
			echo elgg_view("{$CONFIG->pluginname}/related_product_mainview",array('entity'=>$related_product,'selected_related_product'=>$selected_related_product));
		}
		elgg_set_context($context);
	}
?>