<?php
	/**
	 * Elgg Social Commerce browser
	 * 
	 * @package Elgg Social Commerce sold price details 
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009
	 * @link http://cubettech.com/
	 */

	global $CONFIG;
	
	$guid = trim(get_input("Guid"));
	$transaction = get_entity ($guid);
	//echo $transaction->order_item_guid;
	$coupon_discount = get_price_with_currency($transaction->coupon_discount_amount);
	$shipping_price= get_price_with_currency($transaction->product_total_shipping_price);
	$site_commission=get_price_with_currency($transaction->product_site_commission);
	$item_quantity=$transaction->product_quantity;
	$tax=get_price_with_currency($transaction->product_total_tax);
	$final_amount = get_price_with_currency($transaction->amount_user);
	if($item_quantity==0 || $item_quantity=="")
		$item_quantity = 1;
	$product_amount=get_price_with_currency(($transaction->total_amount)/$item_quantity);
	$product_total_amount=get_price_with_currency($transaction->total_amount);
	
	//***Product Details**///
	$products_display ="";
	if($transaction->order_item_guid){
			$oreder_item = get_entity($transaction->order_item_guid);
			if($oreder_item && $oreder_item->product_id){
				$product = get_entity($oreder_item->product_id);
				$product_owner = get_user($product->owner_guid);
				if($product){
					$product_url = $CONFIG->wwwroot."{$CONFIG->pluginname}/read/".$product->guid."/".$product->title;
					$p_title = trim($product->title);
					if(strlen($p_title) > 20){
						$p_title = substr($p_title,0,20)."...";
					}
					$item_title = "<a href=\"{$product_url}\">{$p_title}</a>";
					$products_display .= <<<PRO
							<tr>
								<td>{$item_title}</td>
								<td class="center">{$item_quantity}</td>
								<td class="right">{$product_amount}</td>
								<td class="right">{$product_total_amount}</td>
							</tr>
PRO;
				}
			}
		}
		
	
	//****Display the related Product****/
	$options = array('relationship' 		=> 	'order_related_item',
					'relationship_guid' 	=>	$transaction->order_item_guid,
					'types'					=>	'object',
					'subtypes'				=>	'order_related_item',
					'limit'					=>	99999);
	$related_products = elgg_get_entities_from_relationship($options);
	$related_product_price = 0;
			$related_products_display = $related_products_price_display = '';
			if($related_products){
				foreach($related_products as $related_product){
					$options = array('relationship' 		=> 	'order_related_details',
									'relationship_guid' 	=>	$related_product->guid,
									'types'					=>	'object',
									'subtypes'				=>	'order_related_details',
									'limit'					=>	99999);
					$details = elgg_get_entities_from_relationship($options);
					if($details){
						foreach($details as $detail){
							$detail_price = $detail->price;
							$detail_price_display = get_price_with_currency($detail_price);
							$related_product_price += $detail_price;
							$related_products_display .= <<<EOF
								<tr class="related_details">
									<td class="relatedpadding">{$detail->title}</td>
									<td class="center">1</td>
									<td class="right">{$detail_price_display}</td>
									<td class="right">{$detail_price_display}</td>
								</tr>
EOF;
						}
					}
				}
			}
	/*   Coupon Detail*/
	$coupon_discount_display ="";
	$label_Discount = elgg_echo('trans:Discount');
	if($transaction->coupon_discount_amount>0)
	{
		$coupon_discount_display = <<<COUPON
		<tr>
			<td>{$label_Discount}</td>
			<td class="center">1</td>
			<td></td>
			<td class="right">{$coupon_discount}</td>
		</tr>
COUPON;
	}
	/*Tax Dtails*/
	$tax_display ="";
	$label_Tax = elgg_echo('trans:Tax');
	if($transaction->product_total_tax>0)
	{
		$tax_display =<<<TAX
		<tr>
			<td>{$label_Tax}</td>
			<td class="center">1</td>
			<td></td>
			<td class="right">$tax</td>
		</tr>
TAX;
	}
	/* Shipping */
	$shipping_display ="";
	$label_shipping = elgg_echo('trans:Shipping');
	if($transaction->product_total_shipping_price>0)
	{
		$shipping_display =<<<SHIPPING
		<tr>
			<td>{$label_shipping}</td>
			<td></td>
			<td></td>
			<td class="right">$shipping_price</td>
		</tr>
SHIPPING;
	}
	/* Site Commission */
	
	if($transaction->product_site_commission>0)
	{
		$commission_display ="";
		$label_site_commission= elgg_echo('trans:SiteCommission');
		$commission_display=<<<COMMISSION
		<tr>
			<td>{$label_site_commission}</td>
			<td></td>
			<td></td>
			<td class="right">$site_commission</td>
		</tr>
COMMISSION;
	}
	/*Final Product Amount*/
	$final_amount_display = "";
	$lable_final_amount= elgg_echo('trans:FinalAmount');
	if($transaction->amount_user>0)
	{
		$final_amount_display=<<<FINALAMOUNT
		<tr class="total">
			<td colspan="3"><b>{$lable_final_amount}</b></td>
			<td class="right">$final_amount</td>
		</tr>
FINALAMOUNT;
	}
	$label_items=elgg_echo('trans:Items');
	$label_qty=elgg_echo('trans:Qty');
	$label_item_price=elgg_echo('trans:ItemPrice');
	$label_item_total=elgg_echo('trans:ItemTotal');
	$details_listindg= <<< PRICEDTLS
	<table style="border-collapse:collapse;">
	<tr>
		<td style="text-align:right; border:none;"><a href="javascript:divhide()"><img src="{$CONFIG->wwwroot}mod/{$CONFIG->pluginname}/images/close.gif"></a></td>
	</tr>
	<tr>
		<td style="border:none;">
			<table class="tans_details" style="border-collapse:collapse;"  border="1" bordercolor="#990033">
			<tr>
				<td class="center"><b>{$label_items}</b></td>
				<td class="center"><b>{$label_qty}</b></td>
				<td class="center"><b>{$label_item_price}</b></td>
				<td class="center"><b>{$label_item_total}</b></td>
			</tr>
			{$products_display}
			{$related_products_display}
			{$coupon_discount_display}
			{$tax_display}
			{$shipping_display}
			{$commission_display}
			{$final_amount_display}
		</table>
		</td>
	</tr>
	</table>
PRICEDTLS;
	
	echo $details_listindg;		
?>		
<td style="padding-left:5px;"></td>
<?php exit(); ?>