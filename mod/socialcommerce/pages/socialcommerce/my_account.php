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
	 * Elgg my account - view
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	
	$page_owner = elgg_get_page_owner_entity();
	if($page_owner->guid != elgg_get_logged_in_user_guid()){
		register_error(elgg_echo('stores:user:not:match'));
		forward();
	}
	
	elgg_push_breadcrumb($page_owner->name, $page_owner->getUrl());
	elgg_push_breadcrumb(elgg_echo('stores:account'));
		
	// Set stores title
	$title = elgg_echo('stores:my:account');
	
	// Get objects
	$filter = get_input("filter", "address");
	$limit = 10;
	$offset = get_input('offset', 0);
			
	$position = strstr($CONFIG->checkout_base_url,'https://');
	if($position === false)
	{
		$baseurl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}else{
		$baseurl = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}
	switch($filter){
		case "address":
			$area2 = elgg_view("{$CONFIG->pluginname}/myaccount_address");
		break;
		case "transactions":
			/*$cound = get_entities_from_metadata('trans_category','sold_product','object','transaction',$_SESSION['user']->guid,$limit,$offset,'','',true);
			$transactions = get_entities_from_metadata('trans_category','sold_product','object','transaction',$_SESSION['user']->guid,$limit,$offset);*/
			$transactions = get_purchased_orders('trans_category','sold_product,withdraw_fund','object','transaction','','','','','',$limit,$offset,'',$_SESSION['user']->guid);
			$count = get_data("SELECT FOUND_ROWS( ) AS count");
			$count = $count[0]->count;
			$nav = elgg_view('navigation/pagination',array(
												'baseurl' => $baseurl,
												'offset' => $offset,
												'count' => $count,
												'limit' => $limit
											));
			$area2 = elgg_view("{$CONFIG->pluginname}/my_account",array('entity'=>$transactions,'filter'=>$filter,'nav'=>$nav));
		break;
		case "withdraw":
			$transactions = get_purchased_orders('trans_category','sold_product,withdraw_fund','object','transaction','','','','','',999999,$offset,'',$_SESSION['user']->guid);
			$area2 = elgg_view("{$CONFIG->pluginname}/withdraw",array('entity'=>$transactions,'filter'=>$filter));
		break;
		case "fee":
			//Depricated function replace
			$options = array(	'metadata_name_value_pairs'	=>	array('trans_category' => 'site_commission'),
							'types'				=>	"object",
							'subtypes'			=>	"transaction",
							'owner_guids'		=>	$_SESSION['user']->guid,						
							'limit'				=>	$limit,
							'offset'			=>	$offset,
							'count'				=>	TRUE,
							
						);
			$count = elgg_get_entities_from_metadata($options);
			//$count = get_entities_from_metadata('trans_category','site_commission','object','transaction',$_SESSION['user']->guid,$limit,$offset,'','',true);
			//Depricated function replace
			$options = array(	'metadata_name_value_pairs'	=>	array('trans_category' => 'site_commission'),
							'types'				=>	"object",
							'subtypes'			=>	"transaction",
							'owner_guids'		=>	$_SESSION['user']->guid,						
							'limit'				=>	$limit,
							'offset'			=>	$offset,
							
						);
			$transactions = elgg_get_entities_from_metadata($options);
			//$transactions = get_entities_from_metadata('trans_category','site_commission','object','transaction',$_SESSION['user']->guid,$limit,$offset);
			$nav = elgg_view('navigation/pagination',array(
												'baseurl' => $baseurl,
												'offset' => $offset,
												'count' => $count,
												'limit' => $limit
											));
			$area2 = elgg_view("{$CONFIG->pluginname}/my_account",array('entity'=>$transactions,'filter'=>$filter,'nav'=>$nav));
		break;
		case "request":
			if(elgg_is_admin_logged_in()){
				//Depricated function replace
				$options = array('types'			=>	"object",
								'subtypes'			=>	"wth_request",
								'limit' 			=>	$limit,
								'offset'			=>	$offset,	
								'count'				=>	TRUE,
							);
				$count = elgg_get_entities($options);
				//$count = get_entities('object','wth_request','','',$limit,$offset,true);
				//Depricated function replace
				$options = array('types'			=>	"object",
								'subtypes'			=>	"wth_request",
								'limit' 			=>	$limit,
								'offset'			=>	$offset,
							);
				$wth_request = elgg_get_entities($options);
				//$wth_request = get_entities('object','wth_request','','',$limit,$offset);
				$nav = elgg_view('navigation/pagination',array(
													'baseurl' => $baseurl,
													'offset' => $offset,
													'count' => $count,
													'limit' => $limit
												));
				$area2 = elgg_view("{$CONFIG->pluginname}/withdrawal_request",array('entity'=>$wth_request,'filter'=>$filter,'nav'=>$nav));
			}else{
				//Depricated function replace
				$options = array('types'			=>	"object",
								'subtypes'			=>	"wth_request",
								'owner_guids'		=>	$_SESSION['user']->guid,
								'limit' 			=>	$limit,
								'offset'			=>	$offset,
								'count'				=>	TRUE,
							);
				$count = elgg_get_entities($options);
				//$count = get_entities('object','wth_request',$_SESSION['user']->guid,'',$limit,$offset,true);
				//Depricated function replace
				$options = array('types'			=>	"object",
								'subtypes'			=>	"wth_request",
								'owner_guids'		=>	$_SESSION['user']->guid,
								'limit' 			=>	$limit,
								'offset'			=>	$offset,
							);
				$wth_requeststatus = elgg_get_entities($options);
				//$wth_requeststatus = get_entities('object','wth_request',$_SESSION['user']->guid,'',$limit,$offset);
				$nav = elgg_view('navigation/pagination',array(
													'baseurl' => $baseurl,
													'offset' => $offset,
													'count' => $count,
													'limit' => $limit
												));
				$area2 = elgg_view("{$CONFIG->pluginname}/withdrawal_status",array('entity'=>$wth_requeststatus,'filter'=>$filter,'nav'=>$nav));
			}
		break;
	}
		
	$area2 = elgg_view("{$CONFIG->pluginname}/my_account_tab_view",array('base_view' => $area2, "filter" => $filter));
	$area2 = elgg_view("{$CONFIG->pluginname}/storesWrapper",array('body'=>$area2));
	// This is used to inser the loader div in to footer page
	$area2 .= <<<EOF
	<script language="javascript" type="text/javascript">
 	 	$(document).ready(function() {
			$(".elgg-layout").append('<div id="load_action"></div><div id="load_action_div"><img src="{$CONFIG->wwwroot}mod/{$CONFIG->pluginname}/images/loadingAnimation.gif"><div style="color:#FFFFFF;font-weight:bold;font-size:14px;margin:10px;">Processing...</div></div><div id="div_product_sold_price_details" class="sold_product_price_list"/>');
		});
	</script>			
EOF;
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