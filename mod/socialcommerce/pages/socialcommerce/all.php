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
	 * Elgg product - view all
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */
	 
	global $CONFIG;
	
	elgg_pop_breadcrumb();
	elgg_push_breadcrumb(elgg_echo('socialcommerce'));
	
	elgg_register_title_button();
	
	//set stores title
	$title = elgg_echo('stores:all:products');
		
	//elgg_set_context('search');
	
	$search_viewtype = get_input('search_viewtype');
	if($search_viewtype == 'gallery'){
		$limit = 20;
	}else{
		$limit = 10;
	}
	
	$options = array('types'			=> "object",
					 'subtypes'			=> "stores",
					 'limit'			=> $limit,
					 'full_view'		=> false,
					 'view_type_toggle' => false);
	
	$view = get_input('view');
	if (elgg_is_admin_logged_in()) {
		$filter = get_input("filter", "active");
		switch($filter){
			case "active":
				$options['metadata_name_value_pairs'] =	array('status' => 1);
				$area2 = elgg_list_entities_from_metadata($options);
			break;
			case "deleted":
				$options['metadata_name_value_pairs'] =	array('status' => 0);
				$area2 = elgg_list_entities_from_metadata($options);
			break;
		}
		if(empty($area2)){
			$area2 = "<div style=\"padding:10px;\">".elgg_echo('product:null')."</div>";
		}
		
		if(elgg_get_viewtype() != 'mobile') {
			if($view != 'rss'){
				$area2 = elgg_view("{$CONFIG->pluginname}/product_tab_view",array('base_view' => $area2, "filter" => $filter));
			}
		}
	}else{
		$options['metadata_name_value_pairs'] =	array('status' => 1);
		$area2 = elgg_list_entities_from_metadata($options);
		
		if(empty($area2)){
			$area2 = "<div style=\"padding:10px;\">".elgg_echo('product:null')."</div>";
		}
	}
	// For category listing in mobile view
	if(!isset($vars) && empty($vars)){
		$vars = array();
	}
	$area2 .= elgg_view("{$CONFIG->pluginname}/extendAllProducts",$vars);
	if($view != 'rss'){
		$area2 = elgg_view("{$CONFIG->pluginname}/storesWrapper",array('body'=>$area2));
	}
	
	// These for left side menu
	$area1 .= gettags();
	
	// Create a layout
	$body = elgg_view_layout('content', array(
		'filter_context' => 'all',
		'content' => $area2,
		'title' => $title,
		'sidebar' => $area1,
	));
	
	// Finally draw the page
	echo elgg_view_page($title, $body);
?>