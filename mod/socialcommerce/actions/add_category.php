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
	 * Elgg category - add action
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
	$container_guid = get_input('container_guid', elgg_get_logged_in_user_guid());
	$parent_category = get_input('category_selected', 0);	
	
	//Validation
	if(empty($title)){
		$error_field = elgg_echo("title");
	}
	if(empty($product_type_id) || $product_type_id <=0){
		$error_field = elgg_echo("product:type");
	}
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
		$redirect = $CONFIG->wwwroot . "{$CONFIG->pluginname}/category/add/".$container_user->username;
	}else{
		// Extract categories from, save to default social commerce (for now)
		$category = new ElggObject();
		
		$category->access_id = 2;
		$category->subtype="category";
		$category->title = $title;
		$category->product_type_id = $product_type_id;
		$category->description = $desc;
		
		$category->parent_category_id = $parent_category;
		
		if ($container_guid){
			$category->container_guid = $container_guid;
		}
		
		$result = $category->save();
		
		if ($result){
			trigger_elgg_event('socialcommerce_category_add',$category->type,$category);
			system_message(elgg_echo("category:saved"));
			unset($_SESSION['category']);
		}else{
			register_error(elgg_echo("category:uploadfailed"));
		}
			
		$container_user = get_entity($container_guid);
		$redirect = $CONFIG->wwwroot . "{$CONFIG->pluginname}/category/";
	}
	
	forward($redirect);

?>