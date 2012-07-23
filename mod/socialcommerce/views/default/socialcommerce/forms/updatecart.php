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
	 * Elgg form - update cart
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	
	$change_quantity = elgg_echo('cart:update:text');
	$update_cart = elgg_echo('cart:update');
	$cart_payment = elgg_echo('cart:confirm:payment');
	$total = calculate_cart_total();
	$display_price = get_price_with_currency($total);
	$subtotal_text = elgg_echo('cart:subtotal');
	if(elgg_get_context() == "confirm") {
		$form_body .= <<< BOTTOM
			<div class="search_listing">
				<span class="address_listing_info_head"><B><h3>$cart_payment</h3></B></span>
			</div>
			<div class="search_listing_info">
				<span class="cart_subtotal">
					<span><B>{$subtotal_text} : {$display_price}</span></B>
				</span>
			</div>
			<input type="hidden" name="amount" value="{$display_price}">  
BOTTOM;
	}elseif (elgg_get_context() == "order"){
		$form_body .= <<< BOTTOM
			<div class="search_listing">
				<span class="address_listing_info_head"><B><h3>$cart_payment</h3></B></span>
			</div>
			<div class="search_listing_info">
				<span class="cart_subtotal">
					<span><B>{$subtotal_text} : {$display_price}</span></B>
				</span>
			</div>
BOTTOM;
	}else{
		$form_body .= <<< BOTTOM
			<div class="search_listing">
				<span class="update_cart_quantity">
					<span class="qtext">{$change_quantity}</span>
					<span class="stores_update_cart"><a href="javascript:void(0);" onclick="javascript:document.frm_cart.submit();">{$update_cart}</a></span>
				</span>
				<span class="cart_subtotal">
					<span><B>{$subtotal_text} : {$display_price}</span></B>
				</span>
			</div>
BOTTOM;
	}
$form_body .= elgg_view('input/securitytoken');
echo $form_body;
?>