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
 * Elgg view - order detailed view
 * 
 * @package Elgg SocialCommerce
 * @author Cubet Technologies
 * @copyright Cubet Technologies 2009-2010
 * @link http://elgghub.com
 */ 
global $CONFIG;

$order_item = elgg_extract('order_item', $vars, FALSE);
$product = elgg_extract('product', $vars, FALSE);
$order = elgg_extract('order', $vars, FALSE);
if($order_item){
	$owner = $order_item->getOwnerEntity();
	$owner_link = elgg_view('output/url', array(
		'href' => $owner->getUrl(),
		'text' => $owner->name,
	));
	$author_text = elgg_echo('byline', array($owner_link));
	$date = elgg_view_friendly_time($order_item->time_created);
	
	$subtitle = "$author_text $date";
	
	$sub_total = ($order_item->quantity * ($order_item->price-$order_item->coupon_discount))+$order_item->tax_price+$order_item->relative_product_price_total+$order_item->shipping_price;
	$status = elgg_view("{$CONFIG->pluginname}/product_status",array('entity'=>$order_item,'action'=>'edit'));
	
	$icon = elgg_view_entity_icon($owner, 'tiny');
	
	if($order->b_first_name && $order->b_last_name){
		$billing_details = elgg_view("{$CONFIG->pluginname}/order_display_address",array('entity'=>$order,'type'=>'b'));
		$billing_details = <<<EOF
			<div class="order_details">
				<h3>Billing Details</h3>
				{$billing_details}
			</div>			
EOF;
	}
	
	if($order->shipping_method && $product->product_type_id == 1){
		if($order->s_first_name && $order->s_last_name){
			$shipping_details = elgg_view("{$CONFIG->pluginname}/order_display_address",array('entity'=>$order,'type'=>'s'));
			$shipping_details = <<<EOF
				<div class="order_details">
					<h3>Shipping Details</h3>
					{$shipping_details}
				</div>			
EOF;
		}
		$shipping_method = "<tr><td align='left' colspan='2'><B>Shipping Method:</B> ".$order->shipping_method."</td></tr>";
	}
	
	if($order_item->final_payment_fee_per_item>0){
		$final_payment_fee_per_item = $order_item->final_payment_fee_per_item;			
	}else{
		$related_product_price = 0;
		$coupon_discount = 0;
		$tax_price = 0;
		$payment_fee_percentage = 1;
		$item_shipping_price = 0;
		$final_payment_fee_per_item =0;
		$item_shipping_price=$order_item->shipping_price;
		$tax_price = $order_item->tax_price;
		$related_product_price = $order_item->relative_product_price_total;
		$coupon_discount = $order_item->coupon_discount;
		$payment_fee_percentage = $order->percentage;
					
		$final_payment_fee_per_item =(($order_item->price-$coupon_discount)*$order_item->quantity)+$related_product_price+$tax_price+$item_shipping_price;
		if($final_payment_fee_per_item > 0)
			$final_payment_fee_per_item = ($final_payment_fee_per_item * $payment_fee_percentage)/100;
	}
	
	/* display the site commission in the case of the product owner not the site admin */ 
	$final_site_commission = 0;
	if($order_item->final_site_commission>0) {
		$final_site_commission = $order_item->final_site_commission;    
	}else {// for select the older value ie, befor adding the code $order_item->product_site_commission in the module.php
		$transction_details = elgg_get_entities_from_metadata(array('types'=>'object','subtypes'=>'transaction','metadata_names'=>array('order_item_guid'),'metadata_values'=>array($order_item_id),'owner_guids'=>$order_item->product_owner_guid));
		$transction_details=$transction_details[0];
		
		if($transction_details->trans_category=='site_commission') {
			$final_site_commission = $transction_details->amount;
		}else {
			$final_site_commission = $transction_details->product_site_commission;
		}
	}
}	
?>
<div class="elgg-image-block clearfix">
	<div class="elgg-image">
		<?php echo $icon;?>
	</div>
	<div class="elgg-body" style="padding-left:5px;">
		<div><?php echo $subtitle?></div>
		<div><B><?php echo elgg_echo('quantity');?>: </B><?php echo $order_item->quantity;?></div>
		<?php if($order_item->coupon_discount){?>	
			<div><B><?php echo elgg_echo('Discount');?>: </B><?php echo get_price_with_currency($order_item->coupon_discount*$order_item->quantity);?></div>
		<?php }?>
		<?php if($order_item->relative_product_price_total){?>
			<div><B><?php echo elgg_echo('Relative Products Price');?>: </B><?php echo get_price_with_currency($order_item->relative_product_price_total);?></div>
		<?php }?>
		<?php if($order_item->tax_price>0){?>
			<div><B><?php echo elgg_echo('Tax');?>: </B><?php echo get_price_with_currency($order_item->tax_price);?></div>
		<?php }?>
		<?php if($order_item->shipping_price>0){?>
			<div><B><?php echo elgg_echo('Shipping Price');?>: </B><?php echo get_price_with_currency($order_item->shipping_price);?></div>
		<?php }?>
		<table width="100%"><tr><td align="left">
			<div><B><?php echo elgg_echo('price');?>: </B><?php echo get_price_with_currency($order_item->price);?></div>
		</td><!--  <td align="right">
			<div><B><?php // echo elgg_echo('paypal:fee:per:item');?>: </B><?php // echo get_price_with_currency($order_item->payment_fee_per_item);?></div>
		</td>--></tr>
		<tr><td align="left">
			<div><B><?php echo elgg_echo('total');?>: </B><?php echo get_price_with_currency($sub_total);?></div>
		</td>
		<?php //if(elgg_is_admin_logged_in()) {?>
			<!-- td align="right">
				<div><B><?php //echo elgg_echo('toatal:paypal:fee');?>: </B><?php //echo get_price_with_currency($final_payment_fee_per_item);?></div>
			</td-->
		<?php //}else {?>
			<td align="right">
				<div><B><?php echo elgg_echo('toatal:site:commission');?>: </B><?php echo get_price_with_currency($final_site_commission);?></div>
			</td>
		<?php // }?>
		</tr>
		<?php echo $shipping_method; ?>
		</table>
		<div>
			<div class="product_order" style="font-size:11px;">
				<?php echo $billing_details;?>
				<?php echo $shipping_details;?>
				<div class="clear"></div>						
			</div>
		</div>
		<div>
			<form action="<?php echo $CONFIG->wwwroot;?>action/<?php echo $CONFIG->pluginname;?>/change_order_status">
				<?php echo $status;?>
			</form>
		</div>
	</div>
</div>