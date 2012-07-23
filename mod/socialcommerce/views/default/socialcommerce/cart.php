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
 * Elgg view - caet page
 * 
 * @package Elgg SocialCommerce
 * @author Cubet Technologies
 * @copyright Cubet Technologies 2009-2010
 * @link http://elgghub.com
 */ 

global $CONFIG;
if(get_input('not_allow') && get_input('not_allow') == 1){
	register_error(elgg_echo('not:allow:error'));
}

if(elgg_is_logged_in()){
	$options = array('types'			=>	"object",
					 'subtypes'			=>	"cart",
					 'owner_guids'		=>	elgg_get_logged_in_user_guid()
				);
	$cart = elgg_get_entities($options);
	
	if(!empty($cart) && is_array($cart) && is_object($cart[0])){
		$cart = $cart[0];
		
		$options = array('relationship' 		=> 	'cart_item',
						 'relationship_guid' 	=>	$cart->guid,
						 'types'				=>	"object",
					 	 'subtypes'				=>	"cart_item"
						);
		$cart_items = elgg_get_entities_from_relationship($options);
	}else{
		$display_cart_items = elgg_echo('cart:null');
	}
}elseif ($CONFIG->allow_add_cart == 1 && isset($_SESSION['GUST_CART']) && !empty($_SESSION['GUST_CART'])){
	$cart_items = $_SESSION['GUST_CART'];
}
	
if($cart_items){
	$display_cart_items = "<ul class=\"elgg-list\">";
	foreach ($cart_items as $cart_item){
		$info = '';
		if(is_array($cart_item)){
			$cart_item = (object) array('product_id'=>$cart_item['product_id'],
										'quantity' => $cart_item['quantity'],
										'amount' => $cart_item['amount'],
										'time_created' => $cart_item['time_created'],
										'guid' => $cart_item['product_id'],
										'version_guid' => $cart_item['version_guid'],
										'version_release'=> $cart_item['version_release'],
										'version_summary'=> $cart_item['version_summary']
										);
		}
		
		$date = elgg_view_friendly_time($cart_item->time_created);
		$subtitle = "<div class=\"elgg-subtext\">$date</div>";
		if($product = get_entity($cart_item->product_id)){
			$mime = $product->mimetype;
			if($product->product_type_id == 2){ 
				$version = get_entity($cart_item->version_guid);
			}	
			if(elgg_is_logged_in()){
				$owner = $cart_item->getOwnerEntity();
				$parameters = "cart_guid=".$cart_item->getGUID();
			}elseif ($CONFIG->allow_add_cart == 1 && isset($_SESSION['GUST_CART']) && !empty($_SESSION['GUST_CART'])){
				$parameters = "session_key=".$cart_item->product_id;
			}
			
			if($version->version_release != ""){
				$version_label = elgg_echo('product:mupload_version:label').":";
				$info .= "<p>{$version_label}{$version->version_release}";
				$info .= "</p>";
			}	
			$tags_out =  elgg_view('output/tags',array('value' => $product->tags));
			$product_type_out =  elgg_view('output/product_type',array('value' => $product->product_type_id));
			$category_out =  elgg_view('output/category',array('value' => $product->category));
			$info .= <<<EOF
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
			$info .= elgg_cart_quantity($cart_item);
			$info .= "<div class=\"stores_remove\">";
			
			$info .= elgg_view('output/confirmlink',array(
								'href' => $vars['url'] . "action/{$CONFIG->pluginname}/remove_cart?" . $parameters,
								'text' => elgg_echo("remove"),
								'confirm' => elgg_echo("cart:delete:confirm"),
							)); 
			$info .= "</div>";
			if(($product->product_type_id == 1 && $product->quantity < $cart_item->quantity) || $product->status == 0){
				$info .= "<div style='color:red;padding-top:10px;'>".elgg_echo('not:available')."</div>";
				$not_allow = 1;
			}
			if($product->product_type_id == 2 && $not_allow!=1){
				if($cart_item->version_guid>0){
					$version_flag = false;
					if(check_entity_relationship($product->guid,'version_release',$cart_item->version_guid)){
						$version_flag = true;
					}
					if($version_flag === false){
						$info .= "<div style='color:red;padding-top:10px;'>".elgg_echo('not:available:version')."</div>";
						$not_allow = 1;
					}
				}
			}

			$image = elgg_view("{$CONFIG->pluginname}/image", array(
										'entity' => $product,
										'size' => 'small',
										'display'=>'full'
									  )
								);
			
			if($version && $version->mimetype && $product->product_type_id == 2){	
				$mime = $version->mimetype;
				$icon = "<div style=\"padding-top:10px;\"><a href=\"{$product->getURL()}\">" . elgg_view("{$CONFIG->pluginname}/icon", array("mimetype" => $mime, 'thumbnail' => $version->thumbnail, 'stores_guid' => $version->guid, 'size' => 'small')) . "</a></div>";
			}else if($product->mimetype && $product->product_type_id == 2){							
				$icon = "<div style=\"padding-top:10px;\"><a href=\"{$product->getURL()}\">" . elgg_view("{$CONFIG->pluginname}/icon", array("mimetype" => $mime, 'thumbnail' => $product->thumbnail, 'stores_guid' => $product->guid, 'size' => 'small')) . "</a></div>";
			}else{
				$icon = "";	
			}
			$params = array(
				'entity' => $product,
				'subtitle' => $subtitle,
				'tags' => false,
				'content' => $info
			);
			$info = elgg_view('object/elements/summary', $params);
			$display_cart_items .=  "<li id=\"item-{$product->getType()}-{$product->guid}\" class=\"elgg-item\">".elgg_view_image_block($image.$icon, $info).'</li>';
		}
	}
	$display_cart_items .= '</ul>';
	$update_cart = elgg_view("{$CONFIG->pluginname}/forms/updatecart");
	$confirm_cart_list = elgg_view("{$CONFIG->pluginname}/forms/confirm_cart_list",array('not_allow'=>$not_allow));
}else{
	$display_cart_items = elgg_echo('cart:null');
}

if($not_allow == 1){
	$hidden = "<input type=\"hidden\" name=\"not_allow\" value=\"1\">";
	$action = "#";
}else{
	$action = $CONFIG->wwwroot."action/{$CONFIG->pluginname}/update_cart";
}
$hidden .= elgg_view('input/securitytoken');
echo $cart_body = <<<EOF
	<form name="frm_cart" method="post" action="{$action}">
		{$display_cart_items}
		{$update_cart}
		{$hidden}
	</form>
	{$confirm_cart_list}
EOF;
?>