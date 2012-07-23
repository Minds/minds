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
	 * Elgg cart - view
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */
	 
	global $CONFIG;
		
	if ($CONFIG->allow_add_cart != 1 && !elgg_is_logged_in()) {
		$_SESSION['last_forward_from'] = current_page_url();
		forward(REFERRER);
	}elseif (elgg_is_logged_in()){
		$page_owner = elgg_get_page_owner_entity();
		if($page_owner->guid != elgg_get_logged_in_user_guid()){
			forward(REFERRER);
		}
		elgg_push_breadcrumb($page_owner->name, $page_owner->getURL());
		$title = elgg_echo('my:shopping:cart');
	}elseif ($CONFIG->allow_add_cart == 1){
		$title = elgg_echo('gust:shopping:cart');
	}
	
	if($CONFIG->cart_item_count > 0){
		$title .= " (".$CONFIG->cart_item_count.")";
	}

	elgg_push_breadcrumb($title);
	
	// Check membership privileges
	$permission = membership_privileges_check('buy');
	
	// Get objects
	if($permission == 1) {
		$area2 = elgg_view("{$CONFIG->pluginname}/cart");
		$area2 .= elgg_view("$CONFIG->pluginname}/extendCartView");
	} else {
		$area2 .= "<div class='contentWrapper'>".elgg_echo('update:buy')."</div>";
	}
		
	if($view != 'rss'){
		$area2 = elgg_view("{$CONFIG->pluginname}/storesWrapper",array('body'=>$area2));
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