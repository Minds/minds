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
	 * Elgg cart - success page
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	
	$page_owner = elgg_get_page_owner_entity();
	
	// Set stores title
	$title = elgg_echo('cart:success');
	elgg_push_breadcrumb($title);
	
	// Get objects
	elgg_set_context('order');
	$body = elgg_echo('cart:success:content');
	$area2 .= <<< AREA2
		<div class="search_listing_info">
			<br>{$body}<br><br>
		</div>
AREA2;

	$area2 .= elgg_view("{$CONFIG->pluginname}/cart_success");
	elgg_set_context('stores');
	
	$options = array('types'		=>	"object",
					 'subtypes'		=>	"cart",
					 'owner_guids'	=>	$user_guid);
	$cart = elgg_get_entities($options);
	
	if($cart){
		$cart = $cart[0];
		$options = array('relationship' 		=> 	'cart_item',
						 'relationship_guid' 	=>	$cart->guid);
		$cart_items = elgg_get_entities_from_relationship($options);
		if($cart_items){
			foreach ($cart_items as $cart_item){
				$cart_item->delete();
			}
		}
		$cart->delete();
	}
	
	$area2 = elgg_view("{$CONFIG->pluginname}/storesWrapper",array('body'=>$area2));
	
	// These for left side menu
	$area1 .= gettags();
		
	// Create a layout
	$body = elgg_view_layout('content', array(
		'filter' => '',
		'content' => $area2,
		'title' => $title,
		'sidebar' => $area1,
	));
	
	// Finally draw the page
	echo elgg_view_page($title, $body);