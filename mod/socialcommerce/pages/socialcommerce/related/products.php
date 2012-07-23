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
	 * Elgg social commerce - related product page
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	
	$product_guid = get_input('guid');
	$product = get_entity($product_guid);
	if (!elgg_instanceof($product, 'object', 'stores') || ($CONFIG->allow_add_related_product != 1 && !elgg_is_admin_logged_in())) {
		register_error(elgg_echo('stores:unknown'));
		forward(REFERRER);
	}
	
	$owner = $product->getOwnerEntity();
	elgg_push_breadcrumb($owner->name, $owner->getURL());
	elgg_push_breadcrumb($product->title, $product->getURL());
	
	$title = elgg_echo('related:products:services');
	elgg_push_breadcrumb($title);
		
	// Check membership privileges
	$permission = membership_privileges_check('sell');
	if($permission == 1) {
		if(isset($_SESSION['related_product'])){
			unset($_SESSION['related_product']);
		}
		
		if($product->canEdit()){
			elgg_register_menu_item('page',array('name'=>elgg_echo('socialcommerce:related:products'), 'text' => elgg_echo('socialcommerce:related:products'), 'href' => $CONFIG->wwwroot . ''.$CONFIG->pluginname.'/related/'.$product_guid));
			elgg_register_menu_item('page',array('name'=>elgg_echo('add:related:products'), 'text' => elgg_echo('add:related:products'), 'href' => $CONFIG->wwwroot . ''.$CONFIG->pluginname.'/related/add/'.$product_guid));
		}else{
			foreard($product->getUrl());
		}
		$base_product = elgg_view_entity($product);
			
		$area2 = <<<EOF
			<div class="contentWrapper related_products_list">
				{$base_product}
			</div>
EOF;
		
		$options = array('metadata_name_value_pairs'	=>	array('product' => $product->guid),
						 'types'			=>	"object",
						 'subtypes'			=>	"related_product",
						 'owner_guids'		=>	$_SESSION['user']->guid,
						 'limit'			=> 10,
						 'full_view' 		=> FALSE,
					);
		$related_products = elgg_list_entities_from_metadata($options);
		$area2 .= elgg_view("{$CONFIG->pluginname}/storesWrapper",array('body'=>$related_products));
	} else {
		$area2 = "<div class='contentWrapper'>".elgg_echo('update:sell')."</div>";
	}
	
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