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
	 * Elgg view - order products
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	$order = $vars['entity'];
	if ($order) {
		$options = array('relationship' 		=> 	'order_item',
						'relationship_guid' 	=>	$order->guid);
		$order_items = elgg_get_entities_from_relationship($options);
		if($order_items){
			$tax_price = 0;
			foreach ($order_items as $order_item){
				$tax_price += $order_item->tax_price;
				$product = get_entity($order_item->product_id);
				$order_item_guid = $order_item->getGUID();
				$title = $order_item->title;
				$discount_price = $price = $order_item->price;
				$order_item_coupon = $order_item->coupon_code;
				if($order_item_coupon){
					$coupon_amount = $order_item->coupon_discount;
					$discount_price = $order_item->price - $coupon_amount;
				}
				$options = array('relationship' 		=> 	'order_related_item',
								 'relationship_guid' 	=>	$order_item->guid,
								 'types'				=>	"object",
								 'subtypes'				=>	"order_related_item",
								 'limit'				=>	99999
								);
				$related_products = elgg_get_entities_from_relationship($options);
				$related_product_price = 0;
				$related_products_display = $related_products_price_display = '';
				if($related_products){
					foreach($related_products as $related_product){
						$options = array('relationship' 		=> 	'order_related_details',
										 'relationship_guid' 	=>	$related_product->guid,
										 'types'				=>	"object",
										 'subtypes'				=>	"order_related_details",
										 'limit'				=>	99999,
										);
						$details = elgg_get_entities_from_relationship($options);
						if($details){
							foreach($details as $detail){
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
				$quantity = $order_item->quantity;
				$shipping = $order_item->shipping_price;
				$total = $quantity * $discount_price;
				$grand_total += $total;
				if($related_product_price > 0){
					$grand_total += $related_product_price;
				}
				$shipping_total += $shipping;
				elgg_set_context('order');
				$status = elgg_view("{$CONFIG->pluginname}/product_status",array('entity'=>$order_item,'status'=>$order_item->status,'action'=>'view'));
				$display_price = get_price_with_currency($price);
				if($order_item_coupon){
					$display_price = "<span class='display_original_price'>".get_price_with_currency($price)."</span>".get_price_with_currency($discount_price);
				}
				$display_total = get_price_with_currency($total);				
				$version = get_entity($order_item->version_guid);
						
				if($version){
					$mimetype = $version->mimetype;
				}else{
					$mimetype = $product->mimetype;
					if($mimetype==""){						
						$version = get_latest_version($product->guid);
						if($version){							
							$mimetype = $version->mimetype;
						}
					}					
				} 
				$version_detail = "";
				if($mimetype && $product->product_type_id == 2){					
					$download_action_url = $CONFIG->wwwroot."action/".$CONFIG->pluginname."/download?product_guid=".$order_item->guid;						
					$download_action_url = elgg_add_action_tokens_to_url($download_action_url);
					$latest_version_dowload  = elgg_view('socialcommerce/extended_order',array('order'=>$order,'order_item'=>$order_item));					
					if($version){						
						$icon = "<div title='Download' class='order_icon_class'><a href=\"{$download_action_url}\">" . elgg_view("{$CONFIG->pluginname}/icon", array("mimetype" => $version->mimetype, 'thumbnail' => $version->thumbnail, 'stores_guid' => $version->guid, 'size' => 'small')) . "</a></div>";
					}else{					
						$icon = "<div title='Download' class='order_icon_class'><a href=\"{$download_action_url}\">" . elgg_view("{$CONFIG->pluginname}/icon", array("mimetype" => $product->mimetype, 'thumbnail' => $product->thumbnail, 'stores_guid' => $product->guid, 'size' => 'small')) . "</a></div>";
					}
					if($order_item->version_release != ""){
						$version_detail = elgg_echo('product:mupload_version:label').":" .$order_item->version_release;
					}
				}else{
					$icon = "";
				}
				$item_details .= <<<EOF
				
				<tr>
					<td style="width: 350px; padding-bottom:0px;" valign="top">
						<div style="float:left;margin-bottom:5px;">{$title}</div>{$icon}<br clear="all"/>
						{$version_detail}
						{$latest_version_dowload}
						{$extended_order_details}
					</td>
					<td style="text-align:left; padding-bottom:0px;" valign="top">
					{$status}
					</td>
					<td style="text-align:center; padding-bottom:0px;" valign="top">{$quantity}</td>
					<td style="text-align:right; padding-bottom:0px;" valign="top">{$display_price}</td>
					<td style="text-align:right; padding-bottom:0px;" valign="top">{$display_total}</td>
				</tr>
				<tr>
					<td style="width: 350px;">							
							{$related_products_display}
					</td>
					<td style="text-align:left;"></td>
					<td style="text-align:center;"></td>
					<td style="text-align:right;">
							{$related_products_price_display}
					</td>
					<td style="text-align:right;">							
							{$related_products_price_display}
					</td>
				</tr>
EOF;
			}
		   
		 	if($tax_price){
				$display_tax_dollar = get_price_with_currency($tax_price);
		    }
			if($shipping_total > 0){
				$display_shipping_total = get_price_with_currency($shipping_total);
				$checkout_shipping_text = elgg_echo('checkout:shipping');
				$shipping_price = <<<EOF
					<tr>
						<td class="order_total" colspan="5">
							<div style="width:100px;float:right;">{$display_shipping_total}</div>
							<div style="padding-right:30px;">{$checkout_shipping_text}: </div> 
						</td>
					</tr>
EOF;
			}
				$checkout_tax = elgg_echo('checkout:tax');
				$tax_line = <<<TAX
					<tr>
						<td class="order_total" colspan="5">
							<div style="width:100px;float:right;">{$display_tax_dollar}</div>
							<div style="padding-right:30px;">$checkout_tax: </div> 
						</td>
					</tr>

TAX;
			if(!$tax_price)
			{
				$tax_line = '';
			}
			$grand_total += $shipping_total;
			
			$billing_details = elgg_view("{$CONFIG->pluginname}/order_display_address",array('entity'=>$order,'type'=>'b'));
			$order_billing_address_head = elgg_echo('order:billing:address:head');
			if($order->s_first_name && $order->s_last_name){
				$shipping_details = elgg_view("{$CONFIG->pluginname}/order_display_address",array('entity'=>$order,'type'=>'s'));
				$order_shipping_address_head = elgg_echo('order:shipping:address:head');
				$shipping_details = <<<EOF
					<div class="order_details">
						<h3>{$order_shipping_address_head}</h3>
						{$shipping_details}
					</div>			
EOF;
			}
			$order_datre = '<b>'.elgg_echo('order:date').':</b> '.date("dS M Y h:i a", $order->time_created);
			if($order->s_first_name && $order->s_last_name){
				$order_recipient = '<b>'.elgg_echo('order:recipient').":</b> ".$order->s_first_name." ".$order->s_last_name;
			}else{
				$order_recipient = '<b>'.elgg_echo('order:recipient').":</b> ".$order->b_first_name." ".$order->b_last_name;
			}
			if($order->shipping_method){
				$order_shipping_method = "<div><B>".elgg_echo('order:shipping:method').": </B>".$order->shipping_method."</div>";
			}
			if($tax_price){
				$grand_total = $grand_total + $tax_price;
			}
		    $order_total = '<b>'.elgg_echo('order:total').":</b> ".get_price_with_currency($grand_total);
			$order_item_follows = sprintf(elgg_echo('order:item:follows'),$order->guid);
			$action = $CONFIG->wwwroot.''.$CONFIG->pluginname.'/order/';
			$display_grand_total = get_price_with_currency($grand_total);
		
			$cart_item_text = elgg_echo('checkout:cart:item');
			$qty_text = elgg_echo('checkout:qty');
			$item_price_text = elgg_echo('checkout:item:price');
			$cart_item_total_text = elgg_echo('checkout:item:total');
			$cart_total_cost = elgg_echo('checkout:total:cost');
			$order_status = elgg_echo('order:status');
			$order_body = <<<EOF
				<div class="ordered_items">
					<div style="margin-bottom:10px;line-height:20px;">
						<div>{$order_datre}</div>
						<div>{$order_recipient}</div>
						<div>{$order_total}</div>
						{$order_shipping_method}
					</div>
					<div>
						<div class="order_details">
							<h3>{$order_billing_address_head}</h3>
							{$billing_details}
						</div>
						{$shipping_details}
						<div class="clear"></div>						
					</div>
					<div clas="order" style="line-height:30px;">
						<B>{$order_item_follows}</B>
					</div>
					<div>
						<table class="checkout_table">
							<tr>
								<th><B>{$cart_item_text}</B></th>
								<th style="text-align:left;"><B>{$order_status}</B></th>
								<th style="text-align:center;"><B>{$qty_text}</B></th>
								<th style="text-align:right;"><B>{$item_price_text}</B></th>
								<th style="text-align:right;"><B>{$cart_item_total_text}</B></th>
							</tr>
							{$item_details}
							{$tax_line}
							{$shipping_price}
							<tr>
								<td class="order_total" colspan="5">
									<div style="width:100px;float:right;">{$display_grand_total}</div>
									<div style="padding-right:30px;">{$cart_total_cost}: </div> 
								</td>
							</tr>
						</table>
					</div>
				</div>		
EOF;
			echo $order_body;
		}
	}
?>