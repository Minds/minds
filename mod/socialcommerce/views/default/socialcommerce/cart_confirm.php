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
 * Elgg view - cart confirm page
 * 
 * @package Elgg SocialCommerce
 * @author Cubet Technologies
 * @copyright Cubet Technologies 2009-2010
 * @link http://elgghub.com
 */ 
	 
global $CONFIG;

$options = array('types'		=>	"object",
				'subtypes'		=>	"cart",
				'owner_guids'	=>	$_SESSION['user']->getGUID(),
			);
$cart = elgg_get_entities($options);
if($cart){
	$cart = $cart[0];
	$options = array('relationship'		=>	'cart_item',
					 'relationship_guid'=>	$cart->guid,);
	$cart_items = elgg_get_entities_from_relationship($options);
	if($cart_items){
		$display_cart_items = "<ul class=\"elgg-list\">";
		foreach ($cart_items as $cart_item){
			if($product = get_entity($cart_item->product_id)){
				$owner = $product->getOwnerEntity();
	
				$product_guid = $product->getGUID();
				$ts = time();
				
				$owner_link = elgg_view('output/url', array(
					'href' => $CONFIG->pluginname."/owner/$owner->username",
					'text' => $owner->name,
				));
				$author_text = elgg_echo('byline', array($owner_link));
				
				$date = elgg_view_friendly_time($product->time_created);
				
				$subtitle = "$author_text $date";
				$tags = elgg_view('output/tags', array('tags' => $product->tags));
				
				$mime = $product->mimetype;
				$category = $product->category;
				if($category > 0){
					$category = get_entity($category);
				}
				
				$product_type_out =  elgg_view('output/product_type',array('value' => $product->product_type_id));
				$category_out =  elgg_view('output/category',array('value' => $category->title));
				$excerpt = <<<EOF
					<table style="margin-top:3px;width:100%;">
						<tr>
							<td style="width:300px;">
								<div class="object_tag_string">{$tags_out}</div>
							</td>
							<td>
								<div style="float:left;">
									{$product_type_out}
								</div>
								<div style="float:left;">
									{$category_out}
								</div>
							</td>
						</tr>
					</table>
EOF;
				$excerpt .= elgg_cart_quantity($cart_item);
				$excerpt .= elgg_view('output/confirmlink',array(
									'href' => $vars['url'] . "action/{$CONFIG->pluginname}/remove_cart?cart_guid=" . $cart_item->getGUID(),
									'text' => elgg_echo("remove"),
									'confirm' => elgg_echo("cart:delete:confirm"),
								)); 
				$image = elgg_view("{$CONFIG->pluginname}/image", array(
											'entity' => $product,
											'size' => 'small',
											'display'=>'full'
										  )
									);
				if($product->mimetype && $product->product_type_id == 2){							
					$icon = "<div style=\"padding-top:10px;\"><a href=\"{$product->getURL()}\">" . elgg_view("{$CONFIG->pluginname}/icon", array("mimetype" => $mime, 'thumbnail' => $product->thumbnail, 'stores_guid' => $product->guid, 'size' => 'small')) . "</a></div>";
				}
				$params = array(
					'entity' => $product,
					'subtitle' => $subtitle,
					'tags' => $tags,
					'content' => $excerpt
				);
				
				$info = elgg_view('object/elements/summary', $params);
				$display_cart_items .= "<li id=\"item-{$product->getType()}-{$product->guid}\" class=\"elgg-item\">".elgg_view_image_block($image.$icon, $info).'</li>';
			}
		}
		$display_cart_items .= '</ul>';
		$update_cart = elgg_view("{$CONFIG->pluginname}/forms/updatecart");
		
	}
	$confirm_cart = elgg_view("{$CONFIG->pluginname}/forms/confirm_cart");
	$options = array('types'			=>	"object",
					'subtypes'			=>	"address",
					'owner_guids'		=>	elgg_get_page_owner_guid(),
					'limit'				=>  10);
	$confirm_address = elgg_list_entities($options);
}
// Set Payment 
if(elgg_get_plugin_setting('socialcommerce_paypal_environment', $CONFIG->pluginname)){
	$environment = elgg_get_plugin_setting('socialcommerce_paypal_environment', $CONFIG->pluginname);
}else{
	$environment = "sandbox";
}
if($environment == "sandbox"){
	$business = elgg_get_plugin_setting('socialcommerce_paypal_email', $CONFIG->pluginname);
	$paypalurl = "https://www.sandbox.paypal.com/cgi-bin/webscr";
	
}else if ($environment == "paypal") {
	$business = elgg_get_plugin_setting('socialcommerce_paypal_email', $CONFIG->pluginname);
	$paypalurl = "https://www.paypal.com/cgi-bin/webscr";	
}
$cencelurl = $CONFIG->wwwroot."{$CONFIG->pluginname}/cancel/";
$returnurl = $CONFIG->wwwroot."{$CONFIG->pluginname}/cart_success/";
$ipnurl = $CONFIG->wwwroot."action/{$CONFIG->pluginname}/makepayment?page_owner=".elgg_get_page_owner_guid();

echo $cart_body = <<<EOF
	<form name="frm_cart" method="post" action="{$paypalurl}">
		{$display_cart_items}
		{$update_cart}
		<input type="hidden" name="cmd" value="_xclick">
		<input type="hidden" name="item_name" value="stores_purchase">
		<input type="hidden" name="quantity" value="1">
		<input type="hidden" name="currency_code" value="USD">
		<input type="hidden" name="paymentaction" value="sale">
		<input type="hidden" name="custom" value="stores_payment">
		<input type="hidden" name="rm" value="2">
		<input type="hidden" name="no_note" value="0">
		<input type="hidden" name="business" value="$business">
		<input type="hidden" name="cancel_return" value="{$cencelurl}">
		<input type="hidden" name="return" value="{$returnurl}">
		<input type="hidden" name="notify_url" value="{$ipnurl}">
		{$confirm_address}
		{$confirm_cart}
	</form>
EOF;
?>