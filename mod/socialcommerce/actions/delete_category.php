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
	 * Elgg category - delete action
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
		global $CONFIG;
		$guid = (int) get_input('guid');
		if ($category = get_entity($guid)) {

			if ($category->canEdit()) {
				$parent_id = $category->parent_category_id;
				$container = get_entity($category->container_guid);
				$category_guid = get_input('category_selected');
				if (!$category->delete()) {
					register_error(elgg_echo("category:deletefailed"));
				} else {
					//Reassign the product to another category.
					if($category_guid>0){
						$options = array('metadata_name_value_pairs'	=>	array('category' => $category->guid,'status'=>1),
							'types'							=>	'object',
							'subtypes'						=>	'stores',						
							);
						$stores = elgg_get_entities_from_metadata($options);
						foreach($stores as $store){
							$store->category = $category_guid;
							$store->save();
						}
						system_message(elgg_echo("category:save:success"));
					}else{
						system_message(elgg_echo("category:deleted"));
					}
					// Get the subcategory of the category
					$options = array('metadata_name_value_pairs' => array('parent_category_id' => $category->guid),
									'types'		=>	'object',
									'subtypes'	=>	'category',
									'limit'		=>	99999,
									);
		
					$category_lists = elgg_get_entities_from_metadata($options);
			        if($category_lists){
			        	$parent_category = 0;
			        	if($parent_id > 0){
			        		$parent_category = $parent_id;
			        	}
			        	foreach ($category_lists as $category_list){	        			        		       		
							$category_list->parent_category_id = $parent_category;
							$category_list->save();
			        	}
			        }
				}
			} else {
				$container = $_SESSION['user'];
				register_error(elgg_echo("category:deletefailed"));
			}
		} else {
			register_error(elgg_echo("category:deletefailed"));
		}
		forward("{$CONFIG->pluginname}/category/");
?>