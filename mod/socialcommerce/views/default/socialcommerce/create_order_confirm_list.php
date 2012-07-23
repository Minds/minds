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
	$customer = $vars['customer'];
	$products = $vars['products'];

	$hidden_values = "";
	$options = array('types'	=>	"object",
					'subtypes'	=>	"splugin_settings",
					'limit'		=>	99999);
	$settings = elgg_get_entities($options);
	$settings = $settings[0];
	if($settings){
		$ShippingMethod = $settings->shipping_methods;
	}
	foreach($products as $product){
		/* Shipping Price Calculation */
		if($ShippingMethod){
			$productss[$product->guid] = (object)array('quantity'=>1,'price'=>$product->price,'type'=>$product->product_type_id);
		}
		if($ShippingMethod && count($productss) > 0){
			$function = "price_calc_".$ShippingMethod;
			if(function_exists($function)){
				$s_prince = $function($productss);
			}
		}		
	}	
	if(is_array($products)){
		$grand_total = $tax_price_total = $shipping_total = 0;
		foreach($products as $product){
			$version_id = "";
			$version_detail = "";
			$version_internal_id = "";
			if($product->product_type_id == 2){
				$version_id = get_input($product->guid.'version_guid');
				$version = get_entity($version_id[0]);		
				$version_detail = elgg_echo('product:mupload_version:label').":" .$version->version_release;
				$version_internal_id = $product->guid.'version_guid';
			}
			$country_code = $product->countrycode;		
			$quantity = 1;//$product->quantity;
			$price = $product->price;
			$total = $tax_price = 0;
			
			$total = $tax_total = $quantity * $price;
			$grand_total += $total;
			
			/* Tax Calculation */
			if($CONFIG->allow_tax_method == 2) {
				$tax_price += generate_tax($tax_total, '',$country_code);
			} else {
				$tax_price += generate_tax($tax_total, '');
			}
			$display_price = get_price_with_currency($price);
			$display_total = get_price_with_currency($total);
			if($tax_price > 0){
				$grand_total = $grand_total + $tax_price;
			}
			$tax_price_total += $tax_price;
			
			$hidden_values .= "<input type='hidden' name='product[]' value='{$product->guid}'>";
			$item_details .= <<<EOF
				<tr>
					<td style="width:350px;">{$product->title}<br />{$version_detail}</td>
					<td style="text-align:center;">{$quantity}</td>
					<td style="text-align:right;">{$display_price}</td>
					<td style="text-align:right;">{$display_total}</td>
					<input type="hidden" name="{$version_internal_id}" value="{$version_id[0]}" /> 
				</tr>
EOF;
		
				if($s_prince[$product->guid]){
					$order_item->shipping_price = $s_prince[$product->guid];
					$item_shipping_price = $s_prince[$product->guid];
					$shipping_total += $s_prince[$product->guid];
				}	
		}
	}
	$grand_total += $shipping_total;
	$checkout_tax = elgg_echo('checkout:tax');
	$display_tax_dollar= get_price_with_currency($tax_price_total);	
	if($CONFIG->allow_tax_method == 1){
		$tax_line = '';
	}else { //if($CONFIG->allow_tax_method == 2 and 3) {
		$tax_line = <<<CTAX
		    <tr>
				<td class="order_total" colspan="4">
					<div style="width:100px;float:right;">{$display_tax_dollar}</div>
					<div style="padding-right:30px;">{$checkout_tax}: </div> 
				</td>
			</tr>
CTAX;
	}
			
	$display_shipping_price = get_price_with_currency($shipping_total);
	$checkout_shipping_text = elgg_echo('checkout:shipping');
	$shipping_details = <<<EOF
			<tr>
				<td class="order_total" colspan="4">
					<div style="width:100px;float:right;">{$display_shipping_price}</div>
					<div style="padding-right:30px;">{$checkout_shipping_text}: </div> 
				</td>
			</tr>	
EOF;

	$display_grand_total = get_price_with_currency($grand_total);
	$redirectlurl = $CONFIG->wwwroot."action/{$CONFIG->pluginname}/create_order";
	$hidden_values .= elgg_view('input/securitytoken');
	$btn = elgg_view('input/submit', array('name' => 'order_confirm', 'value' => elgg_echo('order:confirm')));
	echo $cart_body = <<<EOF
		<form name="frm_cart" method="post" action="{$redirectlurl}">
			<div id="checkout_confirm_list" style="margin-bottom:20px;">
				<table class="checkout_table">
					<tr>
						<th><B>Cart Items</B></th>
						<th style="text-align:center;"><B>Qty</B></th>
						<th style="text-align:right;"><B>Item Price</B></th>
						<th style="text-align:right;"><B>Item Total</B></th>
					</tr>
					{$item_details}
					{$tax_line}
					{$shipping_details}
					<tr>
						<td class="order_total" colspan="4">
							<div style="width:100px;float:right;">{$display_grand_total}</div>
							<div style="padding-right:30px;">Total Cost: </div> 
						</td>
					</tr>
				</table>
			</div>
			{$btn}
			{$hidden_values}
			<input type="hidden" name="customer" value="{$customer->guid}">
		</form>
EOF;
?>