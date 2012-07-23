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
	 * Elgg cart - individual view
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 

	global $CONFIG;
	
	$cart = $vars['entity'];
	
	$cart_guid = $cart->getGUID();
	$title = $cart->title;
	$desc = $cart->description;
	$quantity = $cart->quantity;
	$product_guid = $cart->product_id;
	if($product = get_entity($product_guid)){
		$product_url = $product->getURL();
		$title = $product->title;
		$mime = $product->mimetype;
	}
	$owner = $vars['entity']->getOwnerEntity();
	$friendlytime = elgg_view_friendly_time($vars['entity']->time_created);
	
	if (elgg_get_context() == "search") {
		$info = "<p> <a href=\"{$product_url}\">{$title}</a></p>";
		$info .= "<p class=\"owner_timestamp\">{$friendlytime}";
		$info .= "</p>";
		$info .= elgg_cart_quantity($cart);
		//$info .= "<a href=".$vars['url']."action/stores/remove_cart?cart_guid=".$cart->getGUID().">".elgg_echo('remove')."</a>&nbsp"; 
		$info .= "<div class=\"stores_remove\">".elgg_view('output/confirmlink',array(
							'href' => $vars['url'] . "action/{$CONFIG->pluginname}/remove_cart?cart_guid=" . $cart->getGUID(),
							'text' => elgg_echo("remove"),
							'confirm' => elgg_echo("cart:delete:confirm")
						))."</div>"; 
		$icon = elgg_view("{$CONFIG->pluginname}/image", array(
												'entity' => $product,
												'size' => 'small',
											  )
										);
		
		echo elgg_view_image_block($icon, $info);
	}elseif (elgg_get_context() == "confirm") {
		$info = "<p> <a href=\"{$product_url}\">{$title}</a></p>";
		$info .= "<p class=\"owner_timestamp\">{$friendlytime}";
		$info .= "</p>";
		$info .= elgg_cart_quantity($cart);
		
		$icon = elgg_view("{$CONFIG->pluginname}/image", array(
												'entity' => $product,
												'size' => 'small',
											  )
										);
		echo elgg_view_image_block($icon, $info);
	}elseif (elgg_get_context() == "order") {
		$info = "<p> <a href=\"{$product_url}\">{$title}</a></p>";
		$info .= "<p class=\"owner_timestamp\">{$friendlytime}";
		$info .= "</p>";
		$info .= elgg_cart_quantity($cart);
		
		$icon = elgg_view("{$CONFIG->pluginname}/image", array(
												'entity' => $product,
												'size' => 'small',
											  )
										);
		
		echo elgg_view_image_block($icon, $info);
	}
?>