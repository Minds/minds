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
	 * Elgg product - manage related products
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 

	global $CONFIG;
	// Check membership privileges
	$permission = membership_privileges_check('sell');
	if($permission == 1) {
		$manage_action = get_input('manage_action','');
		switch($manage_action){
			case "add":
				$product_guid = trim(get_input('product_guid'));
				$title = trim(get_input('title'));
				$product_type_id = trim(get_input('product_type_id'));
				$description = trim(get_input('description'));
				$listing = trim(get_input('listing'));
				
				$number_of_details = trim(get_input('number_of_details'));
				
				if(!$product_guid > 0){
					register_error(elgg_echo('related:produt:error:product:guid'));
					forward($CONFIG->wwwroot . $CONFIG->pluginname.'/related/add/'.$product_guid);
				}
				if(empty($title)){
					$register_error = elgg_echo('related:produt:title');
				}
				if($product_type_id <= 0){
					$register_error = elgg_echo('related:product:type');
				}
				if(empty($listing)){
					$register_error = elgg_echo('related:produt:listing');
				}
				
				if(!empty($register_error)){
					$_SESSION['related_product']['title'] = $title;
					$_SESSION['related_product']['product_type_id'] = $product_type_id;
					$_SESSION['related_product']['description'] = $description;
					$_SESSION['related_product']['listing'] = $listing;
					
					register_error(sprintf(elgg_echo('related:produt:mandatory:fields:null'),$register_error));
					forward($CONFIG->wwwroot . $CONFIG->pluginname.'/related/add/'.$product_guid);
					exit;
				}else{
					 $related_product = new ElggObject();
					 $related_product->subtype = 'related_product';
			         $related_product->owner_guid = $_SESSION['user']->guid;
			         $related_product->container_guid = $_SESSION['user']->guid;
			         $related_product->access_id = ACCESS_PUBLIC;
			         
			         $related_product->product = $product_guid;
			         $related_product->title = $title;
			         $related_product->product_type_id = $product_type_id;
			         $related_product->description = $description;
			         $related_product->listing = $listing;
			         $related_product->status = 1;
			         $related_product_guid = $related_product->save();
			         if($related_product_guid){
			         	 $number_of_details = get_input('number_of_details');
				         for ($i = 0; $i < $number_of_details; $i++) {
					         $details = new ElggObject();
					         $details->subtype = 'related_product_details';
					         $details->owner_guid =$_SESSION['user']->guid;
				        	 $details->container_guid = $_SESSION['user']->guid;
					         $details->access_id = ACCESS_PUBLIC;
					         $details->related_product = $related_product_guid;
					         $details->title = get_input('details_'.$i.'_title','');
					         $details->price = get_input('details_'.$i.'_price','');
					         $details->status = 1;
					         if ($details->title) {
					           	 $details->save();
					         }
					     }
					     unset($_SESSION['related_product']);
					     system_message(elgg_echo('related:produt:add:success'));
	        			 $redirect_url = $CONFIG->wwwroot . $CONFIG->pluginname.'/related/'.$product_guid;
			         }else{
			         	 register_error(elgg_echo('related:produt:add:faild'));
			         	 $redirect_url = $CONFIG->wwwroot . $CONFIG->pluginname.'/related/add/'.$product_guid;
			         }
				}
			break;
			case "edit":
				$product_guid = trim(get_input('product_guid'));
				$related_product_guid = trim(get_input('related_product_guid'));
				$title = trim(get_input('title'));
				$product_type_id = trim(get_input('product_type_id'));
				$description = trim(get_input('description'));
				$listing = trim(get_input('listing'));
				
				$number_of_details = trim(get_input('number_of_details'));
				
				if(!$product_guid > 0 || !$related_product_guid > 0){
					register_error(elgg_echo('related:produt:error:product:guid'));
					 $redirect_url = $CONFIG->wwwroot . $CONFIG->pluginname.'/related/edit/'.$related_product_guid.'/'.$product_guid;
				}
				if(empty($title)){
					$register_error = elgg_echo('related:produt:title');
				}
				if($product_type_id <= 0){
					$register_error = elgg_echo('related:product:type');
				}
				if(empty($listing)){
					$register_error = elgg_echo('related:produt:listing');
				}
				
				if(!empty($register_error)){
					$_SESSION['related_product']['title'] = $title;
					$_SESSION['related_product']['product_type_id'] = $product_type_id;
					$_SESSION['related_product']['description'] = $description;
					$_SESSION['related_product']['listing'] = $listing;
					
					register_error(sprintf(elgg_echo('related:produt:mandatory:fields:null'),$register_error));
					forward($CONFIG->wwwroot . $CONFIG->pluginname.'/related/edit/'.$related_product_guid.'/'.$product_guid);
					exit;
				}else{
					 $related_product = get_entity($related_product_guid);
			         $related_product->title = $title;
			         $related_product->product_type_id = $product_type_id;
			         $related_product->description = $description;
			         $related_product->listing = $listing;
			         if($related_product->save()){
			         	 $number_of_details = get_input('number_of_details');
				         for ($i = 0; $i < $number_of_details; $i++) {
					         $details = new ElggObject();
					         $details->subtype = 'related_product_details';
					         $details->owner_guid =$_SESSION['user']->guid;;
				        	 $details->container_guid = $_SESSION['user']->guid;;
					         $details->access_id = ACCESS_PUBLIC;
					         $details->related_product = $related_product->guid;
					         $details->title = get_input('details_'.$i.'_title','');
					         $details->price = get_input('details_'.$i.'_price','');
					         $details->status = 1;
					         if ($details->title) {
					           	 $details->save();
					         }
					     }
					     unset($_SESSION['related_product']);
					     system_message(elgg_echo('related:produt:edit:success'));
	        			 $redirect_url = $CONFIG->wwwroot . $CONFIG->pluginname.'/related/'.$product_guid;
			         }else{
			         	 register_error(elgg_echo('related:produt:edit:faild'));
			         	 $redirect_url = $CONFIG->wwwroot . $CONFIG->pluginname.'/related/edit/'.$related_product_guid.'/'.$product_guid;
			         }
				}
			break;
			case "delete":
				$related_product_guid = trim(get_input('rpid'));
				if($related_product_guid && elgg_is_logged_in()){
					$related_product = get_entity($related_product_guid);
					if($related_product){
						$product_guid = $related_product->product;
						if($related_product->canEdit()){
							$options = array(	'metadata_name_value_pairs'	=>	array('related_product' => $related_product->guid),
												'types'				=>	"object",
												'subtypes'			=>	"related_product_details",
												'limit'				=>	99999,
											);
							$details = elgg_get_entities_from_metadata($options);
							if($details){
								foreach($details as $detail)
									$detail->delete();
							}
							if($related_product->delete()){
								 system_message(elgg_echo('related:produt:delete:success'));
								 $redirect_url = $CONFIG->wwwroot . $CONFIG->pluginname.'/related/'.$product_guid;
							}else{
								register_error(elgg_echo('related:produt:delete:faild'));
								$redirect_url = $CONFIG->wwwroot . $CONFIG->pluginname.'/related/'.$product_guid;
							}
						}
					}
				}
			break;
			case "edit_details":
				$detail_guid = get_input('guid');
				$user_guid = get_input('u_id');
				if($detail_guid > 0 && $user_guid > 0){
					$detail = get_entity($detail_guid);
					elgg_set_context('related_products');
					$title = trim(get_input('details_title'));
					$price = trim(get_input('details_price'));
					if($title != ''){
						$detail->title = $title;
						$detail->price = $price;
						echo $detail->save();
					}else{
						echo elgg_echo ('related:product:detail:title:null');
					}
				}else{
					echo elgg_echo ('related:product:details:edit:failed');
				}
				exit;
			break;
			case "reload_detail":
				$detail_guid = get_input('guid');
				if($detail_guid > 0){
					$detail = get_entity($detail_guid);
					$detaile_template = <<<EOF
						<div %s>
							<input class="details_title_input" type="text" name="details_%s_title" value="%s" %s />
							<input class="details_price_input" type="text" name="details_%s_price" value="%s" %s />
							%s
							<div class="details_label_div"></div>
						</div>
EOF;
					$edit_delete = <<<EOF
						<div class="edit_delete_details">
							<a class="edit" rel="scbox" href="{$CONFIG->wwwroot}{$CONFIG->pluginname}/related/detail/{$detail_guid}"> </a>
							<a class="delete" href="javascript:void(0);" onClick="delete_related_product_detail({$detail->guid})"> </a>
						</div>
						<script>
							jQuery(document).ready(function($) {
								$("#detail_{$detail->guid}").find(('a[rel*=scbox]')).scbox();
							}); 
						</script>
EOF;
		       	 echo $details_bit = sprintf($detaile_template,'id="detail_'.$detail->guid.'"',$detail->guid,$detail->title,'disabled="yes"',$detail->guid,$detail->price,'disabled="yes"',$edit_delete);
				}else{
					echo "Fail";
				}
				exit;
			break;
			case "delete_detail":
				$detail_guid = get_input('guid');
				$user_guid = get_input('u_id');
				if($detail_guid > 0){
					$detail = get_entity($detail_guid);
					if($detail){
						elgg_set_context('related_products');
						if($detail->owner_guid == $user_guid){
							if($detail->delete()){
								echo 1;
							}else{
								echo elgg_echo('related:product:details:delete:failed');
							}
						}else{
							echo elgg_echo('related:product:details:delete:failed');
						}
					}else{
						echo elgg_echo('related:product:details:delete:failed');
					}
				}else{
					echo elgg_echo('related:product:details:delete:failed');	
				}
				exit;
			break;
			case "delete_detail_from_cart":
				$related_product = get_input('related_product');
				$selected_detail = get_input('detail');
				$selected_detail_entity = get_entity($selected_detail);
				$product = get_input('product');
				$u_id = get_input('u_id');
				$charge = 0;
				if($u_id == 'GUST'){
					if(isset($_SESSION['GUST_CART'][$product]['related_products'][$related_product])){
						$selected_related_product = $_SESSION['GUST_CART'][$product]['related_products'][$related_product];
						if(count($selected_related_product) == 1){
							unset($_SESSION['GUST_CART'][$product]['related_products'][$related_product]);
							echo $selected_detail_entity->price;
						}else if(count($selected_related_product) > 1){
							foreach($selected_related_product as $key=>$detail){
								if($selected_detail == $detail)
									unset($_SESSION['GUST_CART'][$product]['related_products'][$related_product][$key]);
									echo $selected_detail_entity->price;
							}
						}
					}
				}else{
					if($related_product && $selected_detail){
						elgg_set_context('related_products');
						$related_product = get_entity($related_product);
						$details = $related_product->details;
						if(!is_array($details)){
							if($details == $selected_detail){
								$related_product->delete();
								echo $selected_detail_entity->price;
							}else{
								echo elgg_echo('related_product:delete:problem');	
							}
						}else{
							foreach($details as $key=>$detail){
								if($detail == $selected_detail){
									unset($details[$key]);
									$charge = $selected_detail_entity->price;
								}
							}
							if($charge){
								$related_product->details = $details;
								$related_product->save();
								echo $charge;
							}else{
								echo elgg_echo('related_product:delete:problem');
							}
						}
					}else{
						echo elgg_echo('related_product:delete:problem');
					}
				}
				exit;
			break;
		}
		if($redirect_url != ''){
			forward($redirect_url);
		}
	} else {
		system_message(elgg_echo("update:sell"));
		forward(REFERRER);
	}
?>