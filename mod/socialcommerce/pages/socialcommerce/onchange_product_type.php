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
	 * Elgg product - type change
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	
	$product_type_id = get_input('pt');
	$action = get_input('at');
	$product_id = get_input('id');
	if($product_id){
		$product = get_entity($product_id);
	}
	
	if($action == 'get_category'){
		/*$category_lists = get_entities_from_metadata("product_type_id",$product_type_id,"object","category",0,99999);
		$options_values = array();
		if($category_lists){
			foreach ($category_lists as $category_list){
				$options_values[$category_list->guid] = $category_list->title;
			}	
		}
		$category_label = elgg_echo('category');
		if(!empty($category_lists)){
			$category = elgg_view('input/dropdown', array('name' => 'storescategory', 
													  'value' => "$category", 
													  'options_values' => $options_values));
		}else{
			$category = elgg_echo('no:category');	
		}*/
		
		$category_label = elgg_echo('category');
		$category = get_categories(0,0,$product_type_id,0);
		if($category == ""){
			$category = elgg_echo('no:category');
		}
		$body = <<<EOF
			<p>
				<label><span style="color:red">*</span>$category_label</label><br />
				<div name = "category_listing" id='category_listing' class = "category_listing">					
				<ul id="cat_tree" class="treeview-red">
				{$category}	
				</ul>									
				</div>				
			</p>
EOF;
		echo $body;
	}elseif ($action == 'get_fields'){
		$cstom_fields = elgg_view("custom_field/view",array('entity'=>$product,'entity_type'=>$product_type_id));
		$fields = '';
		$product_fields = $CONFIG->product_fields[$product_type_id];
		if (is_array($product_fields) && sizeof($product_fields) > 0){
			foreach ($product_fields as $shortname => $valtype){
				$value = $product->$shortname;
				if(elgg_echo('product:'.$shortname) == elgg_echo('product:price') || elgg_echo('product:'.$shortname) == elgg_echo('product:quantity') || elgg_echo('product:'.$shortname) == elgg_echo('stores:base:stocek') || elgg_echo('product:'.$shortname) == elgg_echo('stores:file')){
					$mandatory = '<span style="color:red">*</span>';
				}else{
					$mandatory = '';
				}
				$fields .= '<p><label>'.$mandatory .elgg_echo('product:'.$shortname).'</label><br />';
				if($product->mimetype != "" && $shortname == 'upload' && $valtype['field'] == 'file'){
					$fields .= elgg_view("{$CONFIG->pluginname}/icon", array("mimetype" => $product->mimetype, 'thumbnail' => $product->thumbnail, 'file_guid' => $product->guid));
				}else{
					$fields .= elgg_view("input/{$valtype['field']}",array(
															'name' => $shortname,
															'value' => $value,
															));
				}
				$fields .= '</p>';
	
			}
		}
		echo $fields.$cstom_fields;
	}
	
	exit;
?>