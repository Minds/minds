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
	 * Elgg view - friend's produt
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	global $CONFIG;
	
	$owner = elgg_get_page_owner_entity();

	elgg_push_breadcrumb($owner->name, "socialcommerce/owner/$owner->username");
	elgg_push_breadcrumb(elgg_echo('friends'));
	
	elgg_register_title_button();
	
	//set stores title
	$title = elgg_echo('stores:yours:friends');
	$view = get_input('view');
	
	//elgg_set_context('search');
	$search_viewtype = get_input('search_viewtype');
	if($search_viewtype == 'gallery'){
		$limit = 20;
	}else{
		$limit = 10;
	}
	
	$user_guid = $page_owner->guid;
	if ($friends = get_user_friends($user_guid, "", 999999, 0)) {
		$friendguids = array();
		foreach ($friends as $friend) {
			$friendguids[] = $friend->getGUID();
		}
		$options = array('metadata_name_value_pairs'=> array('status' => 1),
						 'types'					=> "object",
						 'subtypes'					=> "stores",
						 'owner_guids'				=> $friendguids,
						 'limit'					=> $limit,
					 	 'full_view'				=> false,
					 	 'view_type_toggle' 		=> false
						);
		$area2 = elgg_list_entities_from_metadata($options);
	}
	
	if($view != 'rss'){
		if(empty($area2)){
			$area2 = "<div style=\"padding:10px;\">".elgg_echo('product:null')."</div>";
		}
	}
	$area2 .= elgg_view("$CONFIG->pluginname}/extendFriendsView");
	$area2 = elgg_view("{$CONFIG->pluginname}/storesWrapper",array('body'=>$area2));
	//elgg_set_context('socialcommerce');
	// These for left side menu
	$area1 .= gettags();
	
	// Create a layout
	$body = elgg_view_layout('content', array(
		'filter_context' => 'friends',
		'content' => $area2,
		'title' => $title,
		'sidebar' => $area1,
	));
	
	// Finally draw the page
	echo elgg_view_page($title, $body);
?>