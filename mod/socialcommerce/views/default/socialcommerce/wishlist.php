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
 * Elgg view - wishlist
 * 
 * @package Elgg SocialCommerce
 * @author Cubet Technologies
 * @copyright Cubet Technologies 2009-2010
 * @link http://elgghub.com
 */ 
	 
global $CONFIG;

$products = $vars['entities'];
$ts = time();							
if($products){
	foreach ($products as $product){
		$rating = "";
		$owner = $product->getOwnerEntity();
		
		$owner_link = elgg_view('output/url', array(
		'href' => "file/owner/$owner->username",
		'text' => $owner->name,
		));
		$author_text = elgg_echo('byline', array($owner_link));
		
		$date = elgg_view_friendly_time($product->time_created);
		
		$subtitle = "$author_text $date";
	
		$metadata = elgg_view_menu('entity', array(
			'entity' => $product,
			'handler' => 'socialcommerce',
			'sort_by' => 'priority',
			'class' => 'elgg-menu-hz',
		));
		
		$tags = elgg_view('output/tags', array('tags' => $product->tags));
		
		$price_text = elgg_echo('price');
		$remove_wishlist_text = elgg_echo('remove:wishlish');
		$remove_wishlist_action = $CONFIG->wwwroot."action/{$CONFIG->pluginname}/remove_wishlist?__elgg_token=".generate_action_token($ts)."&__elgg_ts={$ts}";
		$rating = elgg_view("{$CONFIG->pluginname}/view_rating",array('id'=>$product->guid,'units'=>5,'static'=>''));
		if($product->status == 1){
			$share_this = elgg_view("{$CONFIG->pluginname}/share_this",array('entity'=>$product));
			$not_available = "";
		}else{
			$not_available = "<div style='color:red;padding:5px 0;'>".elgg_echo('not:available')."</div>";
			$tell_a_friend = "";
		}
		if($product->product_type_id == 1){
			if($product->quantity > 0){
				$quantity = $product->quantity;
			}else{
				$quantity = 0;
			}
			$quantity = "<span><B>".elgg_echo('quantity').":</B> {$quantity}</span>";
		}
		$display_total = get_price_with_currency($product->price);
		$excerpt .= <<<EOF
			<div class="storesqua_stores">
				<div style="margin:5px 0;">
					<span style="width:115px;float:left;display:block;"><B>{$price_text}:</B> {$display_total}</span>
					{$quantity}
					<div class="clear"></div>
				</div>
				<table>
					<tr>
						<td>{$rating}</td>
						<td>&nbsp;</td>
						<td style="vertical-align:bottom;">
							<div class="cart_wishlist" style="padding-bottom:5px;float:left;">
								{$share_this}
							</div>
							<div class="wishlist_remove">
								<form name="frm_remove_wishlist{$product->guid}" method="post" action="{$remove_wishlist_action}">
									<a onclick="document.frm_remove_wishlist{$product->guid}.submit();" href="javascript:void(0)">{$remove_wishlist_text}</a>
									<input type="hidden" name="product_guid" value="{$product->guid}">
								</form>
							</div>
						</td>
					</tr>
				</table>
			</div>
			{$not_available}
EOF;
		$icon = elgg_view("{$CONFIG->pluginname}/image", array(
												'entity' => $product,
												'size' => 'small',
											  )
										);
										
		$params = array(
			'entity' => $product,
			'metadata' => $metadata,
			'subtitle' => $subtitle,
			'tags' => $tags,
			'content' => $excerpt
		);
		$info = elgg_view('object/elements/summary', $params);
		
		$display_cart_items .= elgg_view_image_block($icon, $info);
	}
}else {
	$display_cart_items = elgg_echo('wishlist:null');
}
echo $display_cart_items
?>