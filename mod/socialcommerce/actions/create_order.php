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
	 * Elgg address - add
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	global $CONFIG;

	$page_owner = $customer = get_input('customer');
	$products = get_input('product');
	elgg_set_page_owner_guid($customer);
	$user = get_entity($customer);
	
	elgg_set_context('add_order');
	$container_guid = $page_owner;
	//Depricated function replace
	$options = array('types'			=>	"object",
					'subtypes'			=>	"splugin_settings",					
					'limit'				=>	99999,
				);
	$settings = elgg_get_entities($options);
	//$settings = get_entities('object','splugin_settings',0,'',9999);
	$settings = $settings[0];
	if($settings){
		$ShippingMethod = $settings->shipping_methods;
	}
	
	if(is_array($products)){
		$payment_fee = 0;
		$payment_fee_percentage = 0;
		
		$order = new ElggObject();
		$order->access_id = 2;
		$order->owner_guid=$page_owner;
		$order->container_guid=$page_owner;
		$order->subtype="order";
		$order->payment_fee=$payment_fee;
		$order->percentage=$payment_fee_percentage;
		$order->transaction_status='Completed';
		$order->checkout_method="admin";
		if($ShippingMethod)
			$order->shipping_method=$ShippingMethod;
		//Depricated function replace
		$options = array('types'			=>	"object",
						'subtypes'			=>	"address",
						'owner_guids'		=>	$page_owner,	
					);
		$address = elgg_get_entities($options);
		//$address = get_entities('object',"address",$page_owner);
		if($address){
			$BillingAddress =$address[0];
			if($BillingAddress){
				$order->b_first_name=$BillingAddress->first_name;
				$order->b_last_name=$BillingAddress->last_name;
				$order->b_address_line_1=$BillingAddress->address_line_1;
				$order->b_address_line_2=$BillingAddress->address_line_2;
				$order->b_city=$BillingAddress->city;
				$order->b_state=$BillingAddress->state;
				$order->b_country=$BillingAddress->country;
				$order->b_pincode=$BillingAddress->pincode;
				if($BillingAddress->mobileno)
					$order->b_mobileno=$BillingAddress->mobileno;
				if($BillingAddress->phoneno)
					$order->b_phoneno=$BillingAddress->phoneno;
			}
		}
		if($order_id = $order->save()){
			$tax_price_total = $shipping_total = $total_price = $tax_total_price = 0;
			foreach($products as $pro){
				$product = get_entity($pro);				
				if($ShippingMethod){
							$productss[$product->guid] = (object)array('quantity'=>1,'price'=>$product->price,'type'=>$product->product_type_id);
							$function = "price_calc_".$ShippingMethod;
							if(function_exists($function)){
								$s_prince = $function($productss);
							}						
				}
			}
			foreach($products as $pro){
				$product = get_entity($pro);
				if($product){
					$item_payment_fee = 0;
					
					$payment_gross += $product->price;
					$new_fund += $payment_gross - $payment_fee;
		

					$country_code = $product->countrycode;
					
					$order_item = new ElggObject();
					$order_item->access_id = 2;
					$order_item->owner_guid=$page_owner;
					$order_item->container_guid=$page_owner;
					$order_item->subtype="order_item";
					$order_item->product_id=$product->guid;
					$order_item->product_owner_guid=$product->owner_guid;
					$order_item->title = $product->title;
					$order_item->description = $product->description;
					$order_item->quantity = 1;
					$order_item->price = $product->price;
					$order_item->countrycode = $country_code;
					$order_item->payment_fee_per_item = $item_payment_fee;
					$order_item->status = 0;
					// for store the details about version	
					$cart_item_version_guid = 0;			
					if($product->product_type_id == 2){
						$version = get_entity(get_input($product->guid.'version_guid'));
						if($version){
							;
						}else{
							$version = get_latest_version($product->guid);
						}						
						$order_item->version_guid = $version->guid;
						$order_item->version_release = $version->version_release;
						$order_item->version_summary = $version->version_summary;
						$cart_item_version_guid = $version->version_guid;
						if($CONFIG->download_newversion_allow){
							$order_item->download_newversion_days = $CONFIG->download_newversion_days;
						}
					}
					
					$order_item->save();
					
					if(isset($s_prince[$product->guid])){
						$order_item->shipping_price = $s_prince[$product->guid];
						$product_shipping_price = $s_prince[$product->guid];
						$shipping_total += $s_prince[$product->guid];
					}
					$order_item_id = $order_item->save();
					if($order_item_id){
						//-------- Site admin ----------------//
						$admin_user = get_site_admin();
						
						//-------- Stores Percentage ---------//
						if($settings){
							if($settings->allow_socialcommerce_store_percetage > 0) {
								$stores_percentage = $CONFIG->socialcommerce_percentage;
							}
							if($settings->allow_socialcommerce_flat_amount>0) {
								$store_flat_amount = $CONFIG->socialcommerce_flat_amount;
							}
						} else {
							$stores_percentage = 10;
						}
							
						if($CONFIG->allow_tax_method != 1) {
							$item_tot = $order_item->price;
							$item_tot = $item_tot * $order_item->quantity;
							if($CONFIG->allow_tax_method == 2) {
							    $tax_price_cpy = $tax_price = generate_tax($item_tot,'',$country_code);
							} else {
								$tax_price_cpy = $tax_price = generate_tax($item_tot,'');
							}
							if($tax_price > 0){
								$tax_total_price += $tax_price;
							}
							$order_item->tax_price = $tax_price_cpy;
						}
						$order_item->save();
						
						//-------- Site Commission -----------//
						if(!$cart_item->quantity){
							$cart_item->quantity = 1;
						}
						if($CONFIG->allow_socialcommerce_store_percetage > 0) {
							$site_commission = number_format(((($product->price * $cart_item->quantity) * $stores_percentage)/100), 2, '.', '');
						} else {
							$site_commission = 0;
						}
						if($CONFIG->allow_socialcommerce_store_percetage > 0 && $CONFIG->allow_socialcommerce_flat_amount>0) {
							$site_commission_include_tax = number_format(((((($product->price * $cart_item->quantity)+$product_shipping_price) +$tax_price) * $stores_percentage)/100), 2, '.', '');
							$site_commission_include_tax = $site_commission_include_tax + $store_flat_amount;
						}else if($CONFIG->allow_socialcommerce_store_percetage > 0)	{
							$site_commission_include_tax = number_format(((((($product->price * $cart_item->quantity)+$product_shipping_price) + $tax_price) * $stores_percentage)/100), 2, '.', '');
						}else if($CONFIG->allow_socialcommerce_flat_amount>0) {
							$site_commission_include_tax = $store_flat_amount;
						} else {
							$site_commission_include_tax = 0;
						}
						/* Save the Site Commision in the oder item .For display in the sold product more details popup */
						$order_item->final_site_commission	= $site_commission_include_tax;				
						$order_item->save();
						
						//-------- User Price ----------------//
						$user_price_include_tax = (($product->price * $cart_item->quantity) + $product_shipping_price + $tax_price) - $site_commission_include_tax;
						
						$admin_transaction = "";
						$admin_transaction = new ElggObject();
						$admin_transaction->access_id = 2;
						$admin_transaction->owner_guid=$admin_user->guid;
						$admin_transaction->container_guid=$admin_user->guid;
						$admin_transaction->subtype='transaction';
						$admin_transaction->amount=$site_commission_include_tax;
						$admin_transaction->trans_type="credit";
						$admin_transaction->title='site_commission';
						$admin_transaction->trans_category='site_commission';
						$admin_transaction->order_guid=$order_id;
						$admin_transaction->order_item_guid=$order_item_id;
						$admin_transaction->amount=$site_commission_include_tax;
						$admin_transaction->payment_fee=$item_payment_fee * 1;
						$admin_transaction->save();
						
						$user_transaction = "";
						$user_transaction = new ElggObject();
						$user_transaction->access_id = 2;
						$user_transaction->owner_guid=$product->owner_guid;
						$user_transaction->container_guid=$product->owner_guid;
						$user_transaction->subtype='transaction';
						$user_transaction->total_amount=$product->price * 1;
						$user_transaction->amount=$user_price_include_tax;
						$user_transaction->trans_type="credit";
						$user_transaction->title='sold_product';
						$user_transaction->trans_category='sold_product';
						$user_transaction->order_guid=$order_id;
						$user_transaction->order_item_guid=$order_item_id;
						$user_transaction->product_total_tax=$tax_price;
						$user_transaction->product_site_commission=$site_commission+$item_payment_fee;
						$user_transaction->product_total_shipping_price=$product_shipping_price;
						$user_transaction->save();
		
						$result = add_entity_relationship($order_id,'order_item',$order_item_id);
						if($product->product_type_id == 1){
							$product->quantity = $product->quantity - 1;
							$product_update_guid = $product->save();
							if(!$product_update_guid > 0){
								$existing = get_data_row("SELECT * from {$CONFIG->dbprefix}metadata WHERE entity_guid = $product->guid and name_id=" . add_metastring('quantity') . " limit 1");
								if($existing){
									$id = $existing->id;
									$value = $product->quantity - 1;
									$metadat_update = update_metadata($id, 'quantity', $value, $existing->value_type, $existing->owner_guid, $existing->access_id);	
								}
							}
						}
						if($result){
							$image = "";
							$desc = nl2br($product->description);
							$sub_total = $product->price * 1;
							$total_price += $sub_total;
							
							$product_url = $product->getURL();
							$image = "<a href=\"{$product_url}\">" . elgg_view("socialcommerce/image", array('entity' => $product, 'size' => 'medium','display'=>'image')) . "</a>";
							$display_price = get_price_with_currency($product->price);
							$display_sub_total = get_price_with_currency($sub_total);
							if($cart_item->quantity){
								$qty = $cart_item->quantity;
							}else{
								$qty = 1;
							}
							if($version){
								$mimetype = $version->mimetype;
								$thumbnail = $version->thumbnail;
								$stores_guid = $version->guid;
							}else{
								$mimetype = $product->mimetype;
								$thumbnail = $product->thumbnail;
								$stores_guid = $product->guid;
							}
							if($mimetype && $product->product_type_id == 2){
								//$icon = "<div title='Download' class='order_icon_class'><a href=\"{$CONFIG->wwwroot}action/{$CONFIG->pluginname}/download?product_guid={$order_item->guid}\">" . elgg_view("{$CONFIG->pluginname}/icon", array("mimetype" => $product->mimetype, 'thumbnail' => $product->thumbnail, 'stores_guid' => $product->guid, 'size' => 'small')) . "</a></div><div class='clear'></div>";
								$icon = "<div class='order_icon_class'><a href=\"{$CONFIG->wwwroot}{$CONFIG->pluginname}/download/{$order_item->guid}\">" . elgg_view("{$CONFIG->pluginname}/icon", array("mimetype" => $mimetype, 'thumbnail' => $thumbnail, 'stores_guid' => $stores_guid, 'size' => 'small', 'title'=>'Download', 'version_guid'=>$version->guid)) . "</a></div><div class='clear'></div>";
								
							}else{
								$icon = "";
							}
							if($order_item->version_release != ""){
								$version_detail = "<br />".elgg_echo('product:mupload_version:label').":" .$order_item->version_release;								
							}
							
							$item_details .= <<<EOF
								<tr>
									<td>
										<div style="float:left;">{$product->title}{$version_detail}</div>{$icon}
										<div style="clear:both;"></div>
									</td>
									<td style="text-align:center;">{$qty}</td>
									<td style="text-align:right;">{$display_price}</td>
									<td style="text-align:right;">{$display_sub_total}</td>
								</tr>
EOF;
							
						}
					}
				}
			}
			$order->total=$payment_gross;
			$order->amound=$new_fund;
			$order->save();
			
			$user_email = $user->email;
			$site = get_entity($CONFIG->site_guid);
			$site_email = $site->email;
			
			$order_date = date("dS M Y");
			$order_recipient = $order->b_first_name." ".$order->b_last_name;
			$order_total = $order->b_first_name." ".$order->b_last_name;
			$billing_details = elgg_view("{$CONFIG->pluginname}/order_display_address",array('entity'=>$order,'type'=>'b'));
			$adderss_details = <<<EOF
				<div style="float:left;width:200px;">
					<h3>Billing Details</h3>
					{$billing_details}
				</div>		
EOF;
			
			if($shipping_total > 0){
				$display_shipping_price = get_price_with_currency($shipping_total);
				$shipping_price = <<<EOF
					<tr>
						<td style="border-top:1px solid #4690D6;" colspan="4">
							<div style="width:100px;float:right;text-align:right;"><B>{$display_shipping_price}</B></div>
							<div style="text-align:right;"><B>Shipping:</B> </div> 
						</td>
					</tr>
EOF;
			}
			$grand_total_price = $total_price + $shipping_total;
/*			if($tax_price > 0){
				$tax_total_price += $tax_price;
			}*/
			if($tax_total_price){
						$tax_total_price_display = get_price_with_currency($tax_total_price);
						$tax_display = <<<EOF
							<tr>
								<td style="border-top:1px solid #4690D6;" colspan="4">
									<div style="width:100px;float:right;text-align:right;"><B>{$tax_total_price_display}</B></div>
									<div style="text-align:right;"><B>Tax:</B> </div> 
								</td>
							</tr>
EOF;
						$grand_total_price += $tax_total_price;
			}
			$display_grand_total = get_price_with_currency($grand_total_price);
			$grand_total = <<<EOF
				<tr>
					<td style="border-top:1px solid #4690D6;" colspan="4">
						<div style="width:100px;float:right;text-align:right;"><B>{$display_grand_total}</B></div>
						<div style="text-align:right;"><B>Total Cost:</B> </div> 
					</td>
				</tr>
EOF;
			$order_page = $CONFIG->wwwroot.''.$CONFIG->pluginname.'/order/';
				if($mimetype && $product->product_type_id == 2){
					$download_condition = <<<EOF
						<div>
							<div><b>How to download</b></div>
							if you want to download please follow the bellow steps.
							<ul>
								<li>Please click to the product type icon on the above order details.</li>
							</ul>
							<div style="margin-left:40px;"><b>OR</b></div>
							<ul>
								<li>Please go to <a target="_blank" href="{$order_page}">My Order</a></li>
								<li>Then click on View Details button.</li>
								<li>Click to the product type icon on order details.</li>
							</ul>
						</div>				
EOF;
			}else{
				$download_condition = "";
			}
			$order_link = $CONFIG->wwwroot.''.$CONFIG->pluginname.'/order_products/'.$order_id;
			$view_total_price = get_price_with_currency($total_price);
			$mail_body = sprintf(elgg_echo('order:mail'),
								 	 $user->name,
									 $order_id,
									 $order_link,
									 $order_link,
									 $order_id,
									 $order_date,
									 $order_recipient,
									 $view_total_price,
									 $ShippingMethod,
									 $adderss_details,
									 $order_id,
									 $item_details,
									 $tax_display,
									 $shipping_price,
									 $grand_total,
									 $download_condition
			);
			//echo $mail_body;
			$subject = "New {$site->name} Purchase completed";
			stores_send_mail($site, 					// From entity
							 $user, 					// To entity
							 $subject,					// The subject
							 $mail_body					// Message
						);
					
			system_message(elgg_echo("The order was successfully created"));
		}else{
			register_error(elgg_echo("Sorry! we could not create this order at this time, please try after some time"));
		}
	}
	forward($CONFIG->wwwroot . "{$CONFIG->pluginname}/create_order");
?>