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
	 * Elgg category - edit action
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 

	global $CONFIG;
	
	// Get variables
	$title = trim(get_input("categorytitle"));
	$product_type_id = trim(get_input("product_type_id"));
	$desc = get_input("categorybody");
	
	$guid = (int) get_input('category_guid');
	
	$parent_category = get_input('category_selected', 0);
	if (!$category = get_entity($guid)) {
		register_error(elgg_echo("category:addfailed"));
		forward($CONFIG->wwwroot . "{$CONFIG->pluginname}/category/");
		exit;
	}
	
	$result = false;
	//Validation
	if(empty($title)){
		$error_field = elgg_echo("title");
	}
	if(empty($product_type_id) || $product_type_id <=0){
		$error_field = elgg_echo("product:type");
	}
	$container_guid = $category->container_guid;
	$container = get_entity($container_guid);
	$error_field .= trigger_plugin_hook('validate_category','object',array(
														'entity' => $category),
														FALSE);
	if(!empty($error_field)){
		$_SESSION['category']['categorytitle'] = $title;
		$_SESSION['category']['categorybody'] = $desc;
		$_SESSION['category']['product_type_id'] = $product_type_id;
		$_SESSION['category']['category_selected'] = $parent_category;		
		
		register_error(sprintf(elgg_echo("product:validation:null"),$error_field));
		$container_user = get_entity($container_guid);
		$redirect = $CONFIG->wwwroot . $CONFIG->pluginname."/category/edit/".$guid;
	}else{
		if ($category->canEdit()) {
			$all_child_ids = getall_child($guid);			
			if(in_array($parent_category,$all_child_ids)){
				$parent_id = $category->parent_category_id;				
				$new_parent = get_entity($parent_category);
				if($new_parent){
					$new_parent->parent_category_id = $parent_id;
					$new_parent->save();
				}
			}			
			$category->access_id = 2;
			$category->title = $title;
			$category->product_type_id = $product_type_id;
			$category->description = $desc;
			$category->parent_category_id = $parent_category;
			$result = $category->save();
		}
		if ($result){
			trigger_elgg_event('socialcommerce_category_edit',$category->type,$category);
			system_message(elgg_echo("category:saved"));
			unset($_SESSION['category']);
		}else{
			register_error(elgg_echo("category:addfailed"));
		}
		
		$container_user = get_entity($container_guid);
		$redirect = $CONFIG->wwwroot . "{$CONFIG->pluginname}/category/";
	}
	forward($redirect);
	

?>