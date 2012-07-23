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
 * Elgg view - cart confirm list page
 * 
 * @package Elgg SocialCommerce
 * @author Cubet Technologies
 * @copyright Cubet Technologies 2009-2010
 * @link http://elgghub.com
 */ 
	 
global $CONFIG;
$checkout_confirm = $vars['checkout_confirm'];
//Depricated function replace
$options = array('types'			=>	"object",
				'subtypes'			=>	"cart",
				'owner_guids'		=>	$_SESSION['user']->getGUID(),
			);
$cart = elgg_get_entities($options);
//$cart = get_entities('object','cart',$_SESSION['user']->getGUID());

$coupon_subtype = get_subtype_id('object','coupons');
if($cart){
	$cart = $cart[0];
	//Depricated function replace
	$options = array('relationship' 		=> 	'cart_item',
					'relationship_guid' 	=>	$cart->guid);
	$cart_items = elgg_get_entities_from_relationship($options);
	//$cart_items = get_entities_from_relationship('cart_item',$cart->guid);
	if($cart_items){
		$grand_total = $total = $tax_price = 0;
		foreach ($cart_items as $cart_item){
			$tax_total = 0;
			if($product = get_entity($cart_item->product_id)){
				$title = $product->title;
				$discount_price = $price = $product->price;
				$country_code = $product->countrycode;
				$cart_item_coupon = $cart_item->coupon_code;
				if($cart_item_coupon){
					$cart_item_coupon = get_coupon_by_couponcode($cart_item_coupon);
					if($cart_item_coupon){
						$coupon_amount = $cart_item_coupon->coupon_amount;
						$coupon_type = $cart_item_coupon->coupon_type;
						if($coupon_type != 1){
							$coupon_amount = round(($price * $coupon_amount) / 100,2);
						}
						$discount_price = $price - $coupon_amount;
					}
				}
				// for store the details about version				
				if($product->product_type_id == 2){
						$order_item->version_guid = $cart_item->version_guid;
						$order_item->version_release = $cart_item->version_release;
						$order_item->version_summary = $cart_item->version_summary;
						$cart_item_version_guid = $cart_item->version_guid;
				}
				if($order_item->version_release != ""){
					$version_detail = "<br />".elgg_echo('product:mupload_version:label').":" .$order_item->version_release;								
				}
				//Depricated function replace
				$options = array('relationship' 		=> 	'cart_related_item',
													'relationship_guid' 	=>	$cart_item->guid,
													'types'					=>	"object",
													'subtypes'				=>	"cart_related_item",
													'limit'					=>	99999,
													);
				$related_products = elgg_get_entities_from_relationship($options);
				//$related_products = get_entities_from_relationship('cart_related_item',$cart_item->guid,'','object','cart_related_item','','',9999);
				$related_product_price = 0;
				$related_products_display = $related_products_price_display = '';
				if($related_products){
					foreach($related_products as $related_product){
						$details = $related_product->details;
						if(!is_array($details) && $details != ''){
							$details = array($details);
						}
						if(!empty($details)){
							foreach($details as $detail){
								$detail = get_entity($detail);
								if($detail){
									$detail_price = $detail->price;
									$detail_price_display = get_price_with_currency($detail_price);
									$related_product_price += $detail_price;
									$related_products_display .= <<<EOF
										<div class="related_details">
											<div style="float:left;">{$detail->title}</div>
											<div class="clear"></div>
										</div>
EOF;
									$related_products_price_display .= <<<EOF
										<div>
											<div>{$detail_price_display}</div>
										</div>
EOF;
								}
							}
						}
					}
				}
				$quantity = $cart_item->quantity;
				$total = $tax_total = $quantity * $discount_price;
				$grand_total += $total;
				if($related_product_price > 0){
					$grand_total += $related_product_price;
					$tax_total += $related_product_price;
				}
					
				if($CONFIG->allow_tax_method == 2) {
					$tax_price += generate_tax($tax_total, '',$country_code);
				} else {
					$tax_price += generate_tax($tax_total, '');
				}
				$display_price = get_price_with_currency($price);
				if($cart_item_coupon){
					$display_price = "<span class='display_original_price'>".get_price_with_currency($price)."</span>".get_price_with_currency($discount_price);
				}
				$display_total = get_price_with_currency($total);
				$item_details .= <<<EOF
					<tr>
						<td style="width:350px;">
							{$title}
							{$version_detail}
							{$related_products_display}
						</td>
						<td style="text-align:center;">{$quantity}</td>
						<td style="text-align:right;">
							{$display_price}
							{$related_products_price_display}
						</td>
						<td style="text-align:right;">
							{$display_total}
							{$related_products_price_display}
						</td>
					</tr>
EOF;
			}
		}
		
		
		if($tax_price > 0){
			$grand_total = $grand_total + $tax_price;
		}
		$display_tax_dollar= get_price_with_currency($tax_price);
		$grand_total += $_SESSION['CHECKOUT']['shipping_price'];
		$_SESSION['CHECKOUT']['total'] = $grand_total;
	}
}

$cencelurl = $CONFIG->wwwroot."{$CONFIG->pluginname}/cancel/";
//$returnurl = $CONFIG->wwwroot."action/stores/add_order?page_owner=".elgg_get_page_owner_guid();
$returnurl = $CONFIG->wwwroot."{$CONFIG->pluginname}/cart_success/";
$ipnurl = $CONFIG->wwwroot."action/{$CONFIG->pluginname}/makepayment?page_owner=".elgg_get_page_owner_guid();
$redirectlurl = $CONFIG->checkout_base_url."{$CONFIG->pluginname}/checkout_process";
if($checkout_confirm)
	$disabled = true;
else 
	$disabled = false;

	

if($_SESSION['CHECKOUT']['allow_shipping'] == 1){
	$display_shipping_price = get_price_with_currency($_SESSION['CHECKOUT']['shipping_price']);
	$checkout_shipping_text = elgg_echo('checkout:shipping');
	$shipping_details = <<<EOF
		<tr>
			<td class="order_total" colspan="4">
				<div style="width:100px;float:right;">{$display_shipping_price}</div>
				<div style="padding-right:30px;">{$checkout_shipping_text}: </div> 
			</td>
		</tr>
		
			
EOF;
}
	$checkout_tax = elgg_echo('checkout:tax');
	
	if($CONFIG->allow_tax_method == 1){
		$tax_line = '';
	}else{ //if($CONFIG->allow_tax_method == 2 and 3) {
		$tax_line = <<<CTAX
		    <tr>
				<td class="order_total" colspan="4">
					<div style="width:100px;float:right;">{$display_tax_dollar}</div>
					<div style="padding-right:30px;">{$checkout_tax}: </div> 
				</td>
			</tr>
CTAX;
	}

$display_grand_total = get_price_with_currency($grand_total);


if($coupon_subtype > 0){
	$coupon_header = elgg_echo('checkout:coupon:header');
	$coupon_description = elgg_echo('checkout:coupon:description');
	$coupon_apply = elgg_echo('checkout:coupon:apply');
	$security_tokens = elgg_view('input/securitytoken');
	$btn = elgg_view('input/submit', array('name' => 'apply_code', 'value' => elgg_echo('checkout:coupon:apply'), 'onclick'=>"return apply_couponcode();", 'id'=>"apply_code"));
	$coupon_applay = <<<EOF
		<div style="" class="right coupon_back">
			<h4>{$coupon_header}</h4>
			<p>{$coupon_description}</p>
			<p>
				<strong>Code:</strong>
				<input type="text" style="width: 140px;" id="couponcode" name="couponcode"/>
				{$btn}
				{$security_tokens}
			</p>
		</div>
		<div style="clear:both;"></div>
EOF;
}
$cart_item_text = elgg_echo('checkout:cart:item');
$qty_text = elgg_echo('checkout:qty');
$item_price_text = elgg_echo('checkout:item:price');
$cart_item_total_text = elgg_echo('checkout:item:total');
$cart_total_cost = elgg_echo('checkout:total:cost');
$btn = elgg_view('input/submit', array('name' => 'order_confirm', 'value' => elgg_echo('checkout:confirm:btn'), 'disabled'=>$disabled));
echo $cart_body = <<<EOF
	<div id="coupon_apply_result"></div>
	<form name="frm_cart" method="post" action="{$redirectlurl}">
		<div id="checkout_confirm_list">
			<table class="checkout_table">
				<tr>
					<th><B>{$cart_item_text}</B></th>
					<th style="text-align:center;"><B>{$qty_text}</B></th>
					<th style="text-align:right;"><B>{$item_price_text}</B></th>
					<th style="text-align:right;"><B>{$cart_item_total_text}</B></th>
				</tr>
				{$item_details}
				{$tax_line}
				{$shipping_details}
				<tr>
					<td class="order_total" colspan="4">
						<div style="width:100px;float:right;">{$display_grand_total}</div>
						<div style="padding-right:30px;">{$cart_total_cost}: </div> 
					</td>
				</tr>
			</table>
		</div>
		{$coupon_applay}
		{$btn}
		<input type="hidden" name="checkout_order" value="4">
	</form>
EOF;
?>