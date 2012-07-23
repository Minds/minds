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
	 * Elgg cart - add action
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 

	global $CONFIG;
	// Check membership privileges
	$permission = membership_privileges_check('buy');
	if($permission == 1) {
		// Get variables
		$quantity = get_input("cartquantity",1);
		$product_guid = get_input("stores_guid");
		$selected_related_products = get_input('related_product','',false);
		$all_related_products = get_input('all_related_products','',false);
	
		$container_guid = (int) get_input('container_guid', 0);
		if (!$container_guid && elgg_is_logged_in()){
			$container_guid = $_SESSION['user']->getGUID();
		}
		// Get product enthity
		$product = get_entity($product_guid);
		if($product->product_type_id == 2){
			$quantity = 1;
		}
		$product_type_details = get_product_type_from_value($product->product_type_id);
		if($product_type_details->addto_cart != 1){
			$reditrect = $product->getURL();
			forward($reditrect);
		}
		// Check the quantity of a product
		if($product->quantity > 0 || $product->product_type_id == 2){
			if(($product->quantity >= $quantity && $quantity > 0) || $product->product_type_id == 2){
				//Get the version details for a digital product 
				if($product->product_type_id == 2){
					$version_guid = get_input('version_guid');
					$version_guid = $version_guid[0];
					if($version_guid > 0){
						$version = get_entity($version_guid); 
					}else{
						$version = get_latest_version($product->guid);
					}
				}		
				
				if(elgg_is_logged_in()){
					// Get carts of a particular user for find that product is already in particular user's cart
					$options = array('types'			=>	"object",
									'subtypes'			=>	"cart",
									'owner_guids'		=>	$_SESSION['user']->getGUID(),
								);
					$carts = elgg_get_entities($options);
					if($carts){
						$cart = $carts[0];
						$cart_guid = $cart->guid;
						$cart_item = get_stores_from_relationship('cart_item',$cart_guid,'product_id',$product_guid,'object','cart_item',$_SESSION['user']->getGUID());
						if($cart_item){
							if($product->product_type_id == 1){
								$cart_item = get_entity($cart_item[0]->guid);
								if($product->quantity >= ($quantity+$cart_item->quantity)){
									$cart_item->quantity = $cart_item->quantity + $quantity;
									$result = $cart_item_guid = $cart_item->save();
									if($result){
										if($selected_related_products){
											foreach($selected_related_products as $related_product=>$details){
												$related_product = str_replace("detail_", "", $related_product);
												if(!is_array($details) && $details != ''){
													$details = array($details);
												}
												if($related_product > 0 && is_array($details)){													
													$options = array('metadata_name_value_pairs'	=>	array('related_product' => $related_product,'cart_item'=>$cart_item->guid),
																	'types'				=>	"object",
																	'subtypes'			=>	"cart_related_item",
																	'owner_guids'		=>	$_SESSION['user']->guid,
																	'limit'				=>	9999,
																);
													$cart_related_items = elgg_get_entities_from_metadata($options);
													
													if($cart_related_items){
														$cart_related_item = $cart_related_items[0];
														$add = 0;
													}else{
														$cart_related_item = new ElggObject();
														$cart_related_item->access_id = 2;
														$cart_related_item->subtype = "cart_related_item";
														$cart_related_item->related_product = $related_product;
														$cart_related_item->cart_item = $cart_item_guid;
														$cart_related_item->cart = $cart_guid;
														$add = 1;
													}
													$cart_related_item->details = $details;
													$cart_related_item_guid = $cart_related_item->save();
													if($details && $cart_related_item_guid){
														unset($all_related_products[$related_product]);
														if($add)
															add_entity_relationship($cart_item_guid,'cart_related_item',$cart_related_item_guid);
													}
												}
											}
										}
										if(count($all_related_products) > 0){
											foreach($all_related_products as $all_related_product){
												$options = array('metadata_name_value_pairs'	=>	array('related_product' => $all_related_product,'cart_item'=>$cart_item->guid),
																'types'				=>	"object",
																'subtypes'			=>	"cart_related_item",
																'owner_guids'		=>	$_SESSION['user']->guid,
																'limit'				=>	9999,
															);
												$cart_related_items = elgg_get_entities_from_metadata($options);
												
												if($cart_related_items){
													$cart_related_item = $cart_related_items[0];
													$cart_related_item->delete();
												}
											}
										}
									}
								}else{
									register_error(sprintf(elgg_echo("cart:quantity:less"),$product->title));
									$return = $CONFIG->url . "{$CONFIG->pluginname}/buy/" . $product->getGUID() . "/" . $product->title;
								}
							} else {
								register_error(elgg_echo("cart:already:added"));
								$cart_added = true;
								$return = $CONFIG->url . "{$CONFIG->pluginname}/buy/" . $product->getGUID() . "/" . $product->title;
							}
						}else{
							$cart_item = new ElggObject();
							$cart_item->access_id = 2;
							$cart_item->subtype = "cart_item";
							$cart_item->quantity = $quantity;
							$cart_item->product_id = $product_guid;
							$cart_item->amount = $product->price;
							// For select the product version for digital products	
							if($product->product_type_id == 2){								
								$cart_item->version_guid = $version->guid;
								$cart_item->version_release = $version->version_release;
								$cart_item->version_summary = $version->version_summary;
							}
							
							if ($container_guid){
								$cart_item->container_guid = $container_guid;
							}
							$cart_item_guid = $cart_item->save();
							if($cart_item_guid){
								if(in_array('cart_add',$CONFIG->river_settings))
									add_to_river('river/object/cart/create','cartadd',$_SESSION['user']->guid,$product_guid);
								$result = add_entity_relationship($cart_guid,'cart_item',$cart_item_guid);
								if($selected_related_products){
									foreach($selected_related_products as $related_product=>$details){
										$related_product = str_replace("detail_", "", $related_product);
										if(!is_array($details) && $details != ''){
											$details = array($details);
										}
										if($related_product > 0 && is_array($details)){
											$cart_related_item = new ElggObject();
											$cart_related_item->access_id = 2;
											$cart_related_item->subtype = "cart_related_item";
											$cart_related_item->related_product = $related_product;
											$cart_related_item->cart_item = $cart_item_guid;
											$cart_related_item->cart = $cart_guid;
											$cart_related_item->details = $details;
											$cart_related_item_guid = $cart_related_item->save();
											if($details && $cart_related_item_guid){
												add_entity_relationship($cart_item_guid,'cart_related_item',$cart_related_item_guid);
											}
										}
									}
								}
							}
						}
					}else{
						$cart = new ElggObject();
						$cart->access_id = 2;
						$cart->subtype = "cart";
						if ($container_guid){
							$cart->container_guid = $container_guid;
						}
						$cart_guid = $cart->save();
						if($cart_guid){
							$cart_item = new ElggObject();
							$cart_item->access_id = 2;
							$cart_item->title = $product->title;
							$cart_item->subtype = "cart_item";
							$cart_item->quantity = $quantity;
							$cart_item->product_id = $product_guid;
							$cart_item->amount = $product->price;
							// For select the product version for digital products							
							if($product->product_type_id == 2){								
								$cart_item->version_guid = $version->guid;
								$cart_item->version_release = $version->version_release;
								$cart_item->version_summary = $version->version_summary;
							}
							
							if ($container_guid){
								$cart_item->container_guid = $container_guid;
							}
							$cart_item_guid = $cart_item->save();
							if($cart_item_guid){
								if(in_array('cart_add',$CONFIG->river_settings))
									add_to_river('river/object/cart/create','cartadd',$_SESSION['user']->guid,$product_guid);
								$result = add_entity_relationship($cart_guid,'cart_item',$cart_item_guid);
								if($selected_related_products){
									foreach($selected_related_products as $related_product=>$details){
										$related_product = str_replace("detail_", "", $related_product);
										if(!is_array($details) && $details != ''){
											$details = array($details);
										}
										if($related_product > 0 && is_array($details)){
											$cart_related_item = new ElggObject();
											$cart_related_item->access_id = 2;
											$cart_related_item->subtype = "cart_related_item";
											$cart_related_item->related_product = $related_product;
											$cart_related_item->cart_item = $cart_item_guid;
											$cart_related_item->cart = $cart_guid;
											$cart_related_item->details = $details;
											$cart_related_item_guid = $cart_related_item->save();
											if($details && $cart_related_item_guid){
												add_entity_relationship($cart_item_guid,'cart_related_item',$cart_related_item_guid);
											}
										}
									}
								}
							}
						}
					}
				}elseif ($CONFIG->allow_add_cart == 1){
					if(isset($_SESSION['GUST_CART'][$product->guid])){
						if(isset($_SESSION['GUST_CART'][$product->guid]['quantity']) && $_SESSION['GUST_CART'][$product->guid]['quantity'] > 0 && $product->product_type_id == 1){
							if($product->quantity >= ($_SESSION['GUST_CART'][$product->guid]['quantity'] + $quantity)){
								$_SESSION['GUST_CART'][$product->guid]['quantity'] = $_SESSION['GUST_CART'][$product->guid]['quantity'] + $quantity;
								if($selected_related_products){
									foreach($selected_related_products as $related_product=>$details){
										$related_product = str_replace("detail_", "", $related_product);
										if(!is_array($details) && $details != ''){
											$details = array($details);
										}
										if($related_product > 0 && is_array($details)){
											$_SESSION['GUST_CART'][$product->guid]['related_products'][$related_product] = $details;
											unset($all_related_products[$related_product]);
										}
									}
								}
							}else{
								register_error(sprintf(elgg_echo("cart:quantity:less"),$product->title));
								$cart_added = true;
								$return = $CONFIG->url . "{$CONFIG->pluginname}/buy/" . $product->getGUID() . "/" . $product->title;
							}
						}else{
							$_SESSION['GUST_CART'][$product->guid]['quantity'] = $quantity;
							if($selected_related_products){
								foreach($selected_related_products as $related_product=>$details){
									$related_product = str_replace("detail_", "", $related_product);
									if(!is_array($details) && $details != ''){
										$details = array($details);
									}
									if($related_product > 0 && is_array($details)){
										$_SESSION['GUST_CART'][$product->guid]['related_products'][$related_product] = $details;
										unset($all_related_products[$related_product]);
									}
								}
							}
						}
						if(count($all_related_products) > 0){
							foreach($all_related_products as $all_related_product){
								unset($_SESSION['GUST_CART'][$product->guid]['related_products'][$all_related_product]);
							}
						}
					}else{
						$_SESSION['GUST_CART'][$product->guid]['quantity'] = $quantity;
						$_SESSION['GUST_CART'][$product->guid]['amount'] = $product->price;
						$_SESSION['GUST_CART'][$product->guid]['product_id'] = $product->guid;
						$_SESSION['GUST_CART'][$product->guid]['time_created'] = time();
						
							// For select the product version for digital products	
							if($product->product_type_id == 2){	
								$_SESSION['GUST_CART'][$product->guid]['version_guid']	= $version->guid;
								$_SESSION['GUST_CART'][$product->guid]['version_release']	= $version->version_release;
								$_SESSION['GUST_CART'][$product->guid]['version_summary'] 	= $version->version_summary;						
							}
						
						
						if($selected_related_products){
							foreach($selected_related_products as $related_product=>$details){
								$related_product = str_replace("detail_", "", $related_product);
								if(!is_array($details) && $details != ''){
									$details = array($details);
								}
								if($related_product > 0 && is_array($details)){
									$_SESSION['GUST_CART'][$product->guid]['related_products'][$related_product] = $details;
								}
							}
						}
					}
				}
				if(!$cart_added){
					if ($result){
						system_message(elgg_echo("cart:added"));
						$return = $CONFIG->url . "{$CONFIG->pluginname}/cart/";
					} else if(isset($_SESSION['GUST_CART']) && $CONFIG->allow_add_cart == 1 && !elgg_is_logged_in()){
						system_message(elgg_echo("cart:added"));
						$return = $CONFIG->url . "{$CONFIG->pluginname}/gust/cart/";
					} else {
						register_error(elgg_echo("cart:addfailed"));
						$return = $CONFIG->url . "{$CONFIG->pluginname}/buy/" . $product->getGUID() . "/" . $product->title;
					}	
				}
				$container_user = get_entity($container_guid);
			}else{
				register_error(elgg_echo("cart:addfailed:quantity"));
				$return = $CONFIG->url . "{$CONFIG->pluginname}/buy/" . $product->getGUID() . "/" . $product->title;
			}
		}else{
			register_error(elgg_echo("cart:addfailed:pquantity"));
			$return = $CONFIG->url . "{$CONFIG->pluginname}/buy/" . $product->getGUID() . "/" . $product->title;
		}
		forward($return);
	} else {
		system_message(elgg_echo("update:buy"));
		forward($_SERVER['HTTP_REFERER']);
	}
?>