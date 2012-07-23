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
	 * Elgg product - edit
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 

	global $CONFIG;
	
	$customer = get_input('customer');
	$product = get_input('product');
	$products = array();
	
	if(empty($customer) || empty($product)){
		forward($CONFIG->wwwroot . "{$CONFIG->pluginname}/create_order");
	}
	
	if(is_numeric($customer) && $customer > 0){
		$customer = get_user($customer);
	}else{
		$customer = get_user_by_username($customer);
	}
	
	if(is_array($product)){
		foreach($product as $pro){
			if(is_numeric($pro) && $pro > 0){
				$pro = get_entity($pro);
				array_push($products,$pro);
			}
		}
	}else{
		if(is_numeric($product) && $product > 0){
			$products = get_entity($product);
			array_push($products,$pro);
		}
	}
	
	if(!$customer || count($products) == 0){
		register_error(elgg_echo('create:order:error:value'));
		forward($CONFIG->wwwroot . "{$CONFIG->pluginname}/create_order");
	}
	
	elgg_push_breadcrumb(elgg_echo('socialcommerce:settings'), $CONFIG->wwwroot.$CONFIG->pluginname."/settings");
	elgg_push_breadcrumb(elgg_echo('stores:order:create'), $CONFIG->wwwroot.$CONFIG->pluginname."/create_order");
	// Render the product upload page
	$title = elgg_echo('stores:order:confirmation');
	elgg_push_breadcrumb($title);
	
    // These for left side menu
	$customer_display = elgg_view_entity($customer, array('full_view'=>false));
	$area2 = <<<EOF
		<div style="padding:10px;">
			{$customer_display}
		</div>
EOF;
	$area2 .= elgg_view("{$CONFIG->pluginname}/create_order_confirm_list",array('customer'=>$customer,'products'=>$products));
	
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