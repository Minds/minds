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
	 * Elgg order admin - detailed view page
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $autofeed;
		
	$order_item_guid = get_input('guid');
	$order_item = get_entity($order_item_guid);
	if (!elgg_instanceof($order_item, 'object', 'order_item')) {
		register_error(elgg_echo('order:not:allow'));
		forward(REFERRER);
	}
	
	$owner = $order_item->getOwnerEntity();
	$product = get_entity($order_item->product_id);
	
	if($product->owner_guid != elgg_get_logged_in_user_guid() && !elgg_is_admin_logged_in()){
		register_error(elgg_echo('stores:not:allow'));
		forward(REFERRER);
	}
	
	elgg_push_breadcrumb($owner->name, $owner->getURL());
	if (elgg_instanceof($product, 'object', 'stores')) {
		elgg_push_breadcrumb($product->title, $product->getURL());
	}
	
	elgg_push_breadcrumb(elgg_echo('stores:order:admin'), $CONFIG->wwwroot.$CONFIG->pluginname.'/orderadmin/'.$product->guid);
	
	$options = array('relationship'		=> 	'order_item',
					 'relationship_guid'=>	$order_item->guid,
					 'inverse_relationship' => true,
					 'types'			=>	"object",
					 'subtypes'			=>	"order",
					 'limit'			=>	1);
	$order = elgg_get_entities_from_relationship($options);
	if($order){
		$order = $order[0];
	}

	// Set stores title
	$title = sprintf(elgg_echo('order:item:heading'),$order->guid, $order_item->title);
	elgg_push_breadcrumb($title);
	
	$area2 =  elgg_view("{$CONFIG->pluginname}/order_view_detail",array('order_item'=>$order_item, 'product'=>$product, 'order'=>$order));
	
	if(empty($area2)){
		$area2 = elgg_echo('order:null');
	}
	if($view != 'rss'){
		
	}else{
		$title="";
	}
	
	$area2 .= elgg_view("$CONFIG->pluginname}/extendOrderAdminView");
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