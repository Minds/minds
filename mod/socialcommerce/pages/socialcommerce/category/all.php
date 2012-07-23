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
	 * Elgg category - view
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	
	global $CONFIG;

	elgg_push_breadcrumb(elgg_echo('category'));
	
	$page_owner = elgg_get_page_owner_entity();
	
	$title = elgg_echo('category');
	
	if(!elgg_is_logged_in()){
		forward("{$CONFIG->pluginname}/all");
	}
		
	// Get objects
	//elgg_set_context('search');
	$d_area = list_categories(0,2);
	$p_area = list_categories(0,1);
	if($p_area != ""){
		$physical_cate_text = elgg_echo('stores:physical');
		$physical_area = <<<EOF
			<div class="left category_list">
				<div style="margin:10px 0;"><b>{$physical_cate_text}</b></div>
				<ul id="phy_tree" class="treeview-red">
				{$p_area}
				</ul>
			</div>
			<script type="text/javascript">
			$(document).ready(function(){
				// third example
				$("#phy_tree").treeview({
					animated: "fast",
					collapsed: true,
					control: "#treecontrol"
				});
			});
			</script>
EOF;
	}
	if($d_area != ""){
		$digital_cate_text = elgg_echo('stores:digital');
		$digital_area = <<<EOF
			<div class="right category_list">
				<div style="margin:10px 0;"><b>{$digital_cate_text}</b></div>
				<ul id="digi_tree" class="treeview-red">
				{$d_area}
				</ul>
			</div>
			<script type="text/javascript">
				$(document).ready(function(){
					// third example
					$("#digi_tree").treeview({
						animated: "fast",
						collapsed: true,
						control: "#treecontrol"
					});
				});
			</script>
EOF;
	}
	$clear_div = "<div class='clear'></div>";
	//$area2 .= list_entities("object","category",elgg_get_page_owner_guid(),10);
	if(!isset($vars) && empty($vars)){
		$vars = array();
	}
	$area = elgg_view("$CONFIG->pluginname}/extendCategoryView",$vars);
	$area2 = elgg_view("{$CONFIG->pluginname}/storesWrapper",array('body'=>$physical_area.$digital_area.$area.$clear_div));
		
	// These for left side menu
	$area1 .= gettags();
		
	$body = elgg_view_layout('content', array(
		'filter' => '',
		'content' => $area2,
		'title' => $title,
		'sidebar' => $area1,
	));
	
	// Finally draw the page
	echo elgg_view_page($title, $body);