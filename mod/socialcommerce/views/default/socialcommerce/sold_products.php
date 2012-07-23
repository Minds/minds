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
 * Elgg view - sold product
 * 
 * @package Elgg SocialCommerce
 * @author Cubet Technologies
 * @copyright Cubet Technologies 2009-2010
 * @link http://elgghub.com
 */ 

$product = $vars['entity'];
$owner = $product->getOwnerEntity();

$owner_link = elgg_view('output/url', array(
	'href' => $CONFIG->pluginname."/owner/$owner->username",
	'text' => $owner->name,
));
$author_text = elgg_echo('byline', array($owner_link));

$date = elgg_view_friendly_time($product->time_created);

$categories = elgg_view('output/categories', $vars);
	
$comments_count = $product->countComments();
//only display if there are commments
if ($comments_count != 0) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $product->getURL() . '#file-comments',
		'text' => $text,
	));
} else {
	$comments_link = '';
}

$subtitle = "$author_text $date $categories $comments_link";
$tags =  elgg_view('output/tags',array('value' => $product->tags));
$desc = $product->description;
$price = $product->price;
$search_viewtype = get_input('search_viewtype');

$quantity = $product->quantity;

$friendlytime = elgg_view_friendly_time($product->time_created);
$price_text = elgg_echo('price');

$mime = $product->mimetype;
if($product){
	if($product->product_type_id == 1){
		if($product->quantity > 0){
			$quantity = $product->quantity;
		}else{
			$quantity = 0;
		}
		$quantity = "<span><B>".elgg_echo('quantity').":</B> {$quantity}</span>";
	}
	
	$category_out =  elgg_view('output/category',array('value' => $product->category));
	$product_type_out =  elgg_view('output/product_type',array('value' => $product->product_type_id));
	$display_price = get_price_with_currency($product->price);
	$excerpt .= <<<EOF
		<table style="margin-top:3px;width:100%;">
			<tr>
				<td>
					<div style="margin:5px 0;">
						<span style="width:115px;float:left;display:block;"><B>{$price_text}:</B> {$display_price}</span>
						{$quantity}
					</div>
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
	$rating = elgg_view("{$CONFIG->pluginname}/view_rating",array('id'=>$product->guid,'units'=>5,'static'=>''));
	$cart_url = addcartURL($product);
	$cart_text = elgg_echo('add:to:cart');
	$wishlist_text = elgg_echo('add:wishlist');
	if($product->status == 1){
		//$tell_a_friend = elgg_view("{$CONFIG->pluginname}/tell_a_friend",array('entity'=>$product,'text'=>"not_display"));
		$share_this = elgg_view("{$CONFIG->pluginname}/share_this",array('entity'=>$product));
		if($product->owner_guid != $_SESSION['user']->guid){
			$entity_hidden = elgg_view('input/securitytoken');
			$cart_wishlist = <<<EOF
				<div class="cart_wishlist">
					<a title="{$cart_text}" class="cart" href="{$cart_url}">&nbsp;</a>
				</div>
				<div class="cart_wishlist">
					<form name="frm_wishlist_{$product->guid}" method="POST" action="{$CONFIG->wwwroot}action/{$CONFIG->pluginname}/add_wishlist">
						<a title="{$wishlist_text}" class="wishlist" onclick=" document.frm_wishlist_{$product->guid}.submit();" href="javascript:void(0);">&nbsp;</a>
						<INPUT type="hidden" name="product_guid" value="{$product->guid}">
					</form>
				</div>
EOF;
		}
		$not_available = "";
	}else{
		$not_available = "<div style='color:red;padding-top:10px;'>".elgg_echo('not:available')."</div>";	
	}
	$excerpt .= <<<EOF
		<div class="storesqua_stores">
			<table>
				<tr>
					<td width="230">{$rating}</td>
					<td style="vertical-align:bottom;">
						<div class="cart_wishlist" style="padding-bottom:5px;">
							<div style="clear:both;"></div>
							<div class="cart_wishlist">
								{$share_this}
							</div>
							{$cart_wishlist}
							<div style="clear:both;"></div>	
						<div>
					</td>
				</tr>
			</table>
			{$not_available}
		</div>	
		
EOF;

	$image =  elgg_view("{$CONFIG->pluginname}/image", array(
									'entity' => $product,
									'size' => 'small',
								  )
								);
								
	//for get the latest version for a digital product
	$version = get_latest_version($product->guid);
	if($version){
		if($version->mimetype && $product->product_type_id == 2){							
			$mime = $version->mimetype;
			$icon = "<div style=\"padding-top:10px;\"><a href=\"{$product->getURL()}\">" . elgg_view("{$CONFIG->pluginname}/icon", array("mimetype" => $mime, 'thumbnail' => $version->thumbnail, 'stores_guid' => $version->guid, 'size' => 'small')) . "</a></div>";
		}
	}else{							
		if($product->mimetype && $product->product_type_id == 2){							
			$icon = "<div style=\"padding-top:10px;\"><a href=\"{$product->getURL()}\">" . elgg_view("{$CONFIG->pluginname}/icon", array("mimetype" => $mime, 'thumbnail' => $product->thumbnail, 'stores_guid' => $product_guid, 'size' => 'small')) . "</a></div>";
		}
	}
	
	$params = array(
		'entity' => $product,
		'metadata' => '',
		'subtitle' => $subtitle,
		'tags' => $tags,
		'content' => $excerpt
	);
	$info = elgg_view('object/elements/summary', $params);
	
	echo elgg_view_image_block($image.$icon, $info);
}
?>