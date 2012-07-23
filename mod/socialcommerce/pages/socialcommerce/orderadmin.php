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
	 * Elgg order admin - view page
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $autofeed;
		
	$product_guid = get_input('guid');
	$product = get_entity($product_guid);
	if (elgg_instanceof($product, 'object', 'stores')) {
		$owner = $product->getOwnerEntity();
		$product_guids = $product->guid;
	}else{
		$product_guids = array();
		$owner = elgg_get_page_owner_entity();
		$options = array('types'		=>	'object',
						 'subtypes'		=> 	'stores',
						 'limit'		=>	9999,
						 'limit'		=>	0,
						 'owner_guids'	=> $owner->guid);
		$products = elgg_get_entities($options);
		if($products){
			foreach ($products as $pro){
				$product_guids[] = $pro->guid;
			}
		}
	}
	
	if($owner->guid != elgg_get_logged_in_user_guid() && !elgg_is_admin_logged_in()){
		register_error(elgg_echo('stores:not:allow'));
		forward(REFERRER);
	}
	elgg_push_breadcrumb($owner->name, $owner->getURL());
	if (elgg_instanceof($product, 'object', 'stores')) {
		elgg_push_breadcrumb($product->title, $product->getURL());
	}
	// Set stores title
	$title = elgg_echo('stores:order:admin');
	elgg_push_breadcrumb($title);

	if(!empty($product_guids)){
		$limit = 10;
		$offset = get_input('offset');
		if(!$offset)
			$offset = 0;
		
		$options = array('metadata_name_value_pairs' => array('product_id'=>$product_guids),
						 'types'			=>	"object",
						 'subtypes'			=>	"order_item",
						 'limit'			=>  $limit,
		   				 'offset'			=> 	$offset,
		   				 'count'			=>	true);
		$count = elgg_get_entities_from_metadata($options);
		if($count > 0){
			unset($options['count']);
			$order_items = elgg_get_entities_from_metadata($options);
			$area2 =  elgg_view("{$CONFIG->pluginname}/order_view",array('order_items'=>$order_items, 'count'=>$count, 'limit'=>$limit, 'offset'=>$offset));
		}
	} else {
		$area2 = elgg_echo('order:null');
	}
	
	if(empty($area2)){
		$area2 = elgg_echo('order:null');
	}
	if($view != 'rss'){
		
	}else{
		$title="";
	}
	
	$area2 .= elgg_view("$CONFIG->pluginname}/extendOrderView");
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