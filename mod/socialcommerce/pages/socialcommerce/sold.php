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
	 * Elgg view - sold products
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 

	global $CONFIG;
	
	$page_owner = elgg_get_page_owner_entity();
	
	// Set stores title
	$title = elgg_echo('stores:sold:products');
	
	elgg_push_breadcrumb($page_owner->name, $page_owner->getURL());
	elgg_push_breadcrumb($title);
	
	// Check membership privileges
	$permission = membership_privileges_check('sell');
	if($permission == 1) {
		$limit = 10;
		$offset = get_input('offset', 0);
		
		$sold_products = get_sold_products(elgg_get_logged_in_user_guid(), $limit, $offset);
		$count = get_data("SELECT FOUND_ROWS( ) AS count");
		$count = $count[0]->count;
		if($sold_products){
			$baseurl = $CONFIG->wwwroot.$CONFIG->pluginname."/sold";
			$nav = elgg_view('navigation/pagination',array(
							 'baseurl' 	=> $baseurl,
							 'offset' 	=> $offset,
							 'count' 	=> $count,
							 'limit' 	=> $limit
						));
			$area2 = "<ul class='elgg-list elgg-list-entity'>";
			foreach ($sold_products as $sold_product){
				$sold_product = get_entity($sold_product->value);
				$area2 .= "<li class='elgg-item'>".elgg_view("{$CONFIG->pluginname}/sold_products",array('entity'=>$sold_product))."</li>";
			}
			$area2 .= "</ul>";
			$area2 = $nav.$area2.$nav;
		} else {
		$area2 = elgg_echo('no:data');
		}
	} else {
		$area2 .= "<div class='contentWrapper'>".elgg_echo('update:sell')."</div>";
	} 
	
	$area2 .= elgg_view("$CONFIG->pluginname}/extendSoldView");
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