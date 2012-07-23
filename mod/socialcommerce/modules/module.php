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
 * Elgg module - actions
 * 
 * @package Elgg SocialCommerce
 * @author Cubet Technologies
 * @copyright Cubet Technologies 2009-2010
 * @link http://elgghub.com
 */

// Load system configuration
	global $CONFIG; 
	
// Set Config Values	
	$CONFIG->default_price_sign = "$";
	$CONFIG->default_currency_name = 'US Dollar';
	$CONFIG->pluginname = "socialcommerce";
		
// Load socialcommerce main functions
require_once(dirname(__FILE__)."/stores.php");
	 
/*
 * Add general social commerce values in CONFIG
 */
function SetGeneralValuesInConfig(){
	global $CONFIG, $sVersion, $sVersionUpdate;
	set_default_currency_to_global();
	//Depricated function replace
	$options = array('types'			=>	"object",
					'subtypes'			=>	"splugin_settings",
				);
	$splugin_settings = elgg_get_entities($options);
	//$splugin_settings = get_entities('object','splugin_settings');
	if($splugin_settings){
		$settings = $splugin_settings[0];
		$CONFIG->allow_add_cart = $settings->allow_add_cart;
		
		if($settings->min_withdraw_amount > 0){
			$CONFIG->min_withdraw_amount = $settings->min_withdraw_amount;
		}else{
			$CONFIG->min_withdraw_amount = 10;
		}
		
		if($settings->socialcommerce_percentage > 0){
			$CONFIG->socialcommerce_percentage = $settings->socialcommerce_percentage;
		}else {
			$CONFIG->socialcommerce_percentage = 10;
		}
		
		/* Used Flat Rate addition to store percentage*/
		if($settings->allow_socialcommerce_store_percetage > 0) {
			$CONFIG->allow_socialcommerce_store_percetage = 1;
		}else {
			$CONFIG->allow_socialcommerce_store_percetage = 0;
		}
		if($settings->allow_socialcommerce_flat_amount>0) {
			$CONFIG->allow_socialcommerce_flat_amount = 1;
		}else {
			$CONFIG->allow_socialcommerce_flat_amount = 0;
		}
		$CONFIG->socialcommerce_flat_amount = $settings->socialcommerce_flat_amount;
		
		/*if($CONFIG->allow_socialcommerce_flat_amount !=1) {
			$CONFIG->allow_socialcommerce_store_percetage = 1;
		}*/

		if(isset($settings->withdraw_option)){
	  	 	$CONFIG->withdraw_option = $settings->withdraw_option;
	  	 	if($settings->holding_days > 0)
	  	 		$CONFIG->holding_days = $settings->holding_days;
	  	 	else
	  	 		$CONFIG->holding_days = 0;
		}else{
	  	 	$CONFIG->withdraw_option = 'instant';
	  	 	$CONFIG->holding_days = 0;
		}  
		if(isset($settings->allow_add_coupon_code)){
			$CONFIG->allow_add_coupon_code = $settings->allow_add_coupon_code;
		}else{
			$CONFIG->allow_add_coupon_code = 0;
		}
		
		if($settings->default_view){
			$CONFIG->default_view = $settings->default_view;
		}else{
			$CONFIG->default_view = 'list';
		}
		$river_settings = $settings->river_settings;
		if(!is_array($river_settings))
			$river_settings = array($river_settings);
		$CONFIG->river_settings = $river_settings;
		
		$hide_system_message =  $settings->hide_system_message;
		if($hide_system_message){
			$CONFIG->hide_system_message = $settings->hide_system_message;
		}else{
			$CONFIG->hide_system_message = 0;
		}
		
		$send_mail_on_outofstock =  $settings->send_mail_on_outofstock;
		if($send_mail_on_outofstock){
			$CONFIG->send_mail_on_outofstock = $send_mail_on_outofstock;
		}else{
			$CONFIG->send_mail_on_outofstock = 0;
		}
		
	    $allow_shipping_method =  $settings->allow_shipping_method;
		if($allow_shipping_method == 1 ){
			$CONFIG->allow_shipping_method = 1;
		}else{
			$CONFIG->allow_shipping_method = 2;
		}
		
	    $allow_tax_method =  $settings->allow_tax_method;
		if($allow_tax_method == 1 ){
			$CONFIG->allow_tax_method = 1;
		}elseif($allow_tax_method == 2){
			$CONFIG->allow_tax_method = 2;
		}else{
			$CONFIG->allow_tax_method = 3;
		}
		if(!empty($settings->socialcommerce_key)){
			$CONFIG->product_key = $settings->socialcommerce_key;
		}else{
			$CONFIG->product_key = "";
		}
		$allow_add_related_product = $settings->allow_add_related_product;
		if($allow_add_related_product == 1 ){
			$CONFIG->allow_add_related_product = 1;
		}else{
			$CONFIG->allow_add_related_product = 0;
		}
		$share_this = trim($settings->share_this);
		if($share_this != '' ){
			$CONFIG->share_this = $settings->share_this;
		}else{
			$CONFIG->share_this = 'd5b880d9-942b-41cd-8fb1-e3a70cdf8735';
		}
		
		$https_allow = $settings->https_allow;
		if($https_allow){
			$CONFIG->checkout_base_url = trim($settings->https_url_text);
		}else{
			$CONFIG->checkout_base_url = $CONFIG->wwwroot;
		}
		$allow_single_click_to_cart = trim($settings->allow_single_click_to_cart);
		if($allow_single_click_to_cart == 1 ){
			$CONFIG->allow_single_click_to_cart = 1;
		}else{
			$CONFIG->allow_single_click_to_cart = 0;
		}
		
		$allow_multiple_version_digit_product = trim($settings->allow_multiple_version_digit_product);
		if($allow_multiple_version_digit_product == 1 ){
			$CONFIG->allow_multiple_version_digit_product = 1;
		}else{
			$CONFIG->allow_multiple_version_digit_product = 0;
		}
		
		$allow_down_latest_product = trim($settings->allow_down_latest_product);
		if($allow_down_latest_product == 1 ){
			$CONFIG->allow_down_latest_product = 1;
			$CONFIG->allow_down_latest_product = trim($settings->latest_pro_down_days);
		}else{
			$CONFIG->allow_down_latest_product = 0;
			$CONFIG->allow_down_latest_product = trim($settings->latest_pro_down_days);
		}
		
		$ftp_upload_allow = trim($settings->ftp_upload_allow);
		if($ftp_upload_allow){
			$CONFIG->ftp_upload_allow = $ftp_upload_allow; 
			$CONFIG->ftp_host_url = $settings->ftp_host_url;
			$CONFIG->ftp_port = $settings->ftp_port;
			$CONFIG->ftp_user = html_entity_decode($settings->ftp_user);
			$CONFIG->ftp_password = html_entity_decode($settings->ftp_password);
			$CONFIG->ftp_upload_dir = substr($settings->ftp_upload_dir, -1)=='/' ? $settings->ftp_upload_dir : $settings->ftp_upload_dir.'/';
			$CONFIG->ftp_http_path = substr($settings->ftp_http_path, -1)=='/' ? $settings->ftp_http_path : $settings->ftp_http_path.'/';
			$CONFIG->ftp_base_path = substr($settings->ftp_base_path, -1)=='/' ? $settings->ftp_base_path : $settings->ftp_base_path.'/';			
	
		}else{
			$CONFIG->ftp_upload_allow = "";
			$CONFIG->ftp_host_url = "";
			$CONFIG->ftp_port = "";
			$CONFIG->ftp_user = "";
			$CONFIG->ftp_password = "";	
			$CONFIG->ftp_upload_dir = "";
			$CONFIG->ftp_http_path = "";
			$CONFIG->ftp_base_path = "";		
		}
		
		// Download the latest version
		$download_newversion_allow = $settings->download_newversion_allow;
		if($download_newversion_allow){			
			$CONFIG->download_newversion_allow = $download_newversion_allow;
			$CONFIG->download_newversion_days = $settings->download_newversion_days;
		}else{
			$CONFIG->download_newversion_allow = "";
			$CONFIG->download_newversion_days = "";
		}  
		
	}else{
		$CONFIG->allow_add_cart = 0;
		$CONFIG->min_withdraw_amount = 10;
		$CONFIG->socialcommerce_percentage = 10;
		$CONFIG->allow_socialcommerce_store_percetage = 1;
		$CONFIG->allow_socialcommerce_flat_amount = 0;
		$CONFIG->withdraw_option = 'instant';
		$CONFIG->holding_days = 0;
		$CONFIG->allow_add_coupon_code = 0;
		$CONFIG->default_view = 'list';
		$CONFIG->river_settings = array();
		$CONFIG->hide_system_message = 0;
		$CONFIG->product_key = "";
		$CONFIG->send_mail_on_outofstock = 0;
		$CONFIG->allow_shipping_method = 2;
		$CONFIG->allow_tax_method = 1;
		$CONFIG->allow_add_related_product = 0;
		$CONFIG->checkout_base_url = $CONFIG->wwwroot;
		$CONFIG->allow_single_click_to_cart = 0;
		$CONFIG->allow_multiple_version_digit_product = 0;
		$CONFIG->ftp_upload_allow = "";
		$CONFIG->download_newversion_allow = "";
		$CONFIG->download_newversion_days = "";
	}
	$CONFIG->powered_by = true;
	if($CONFIG->powered_by) {
		$CONFIG->powered_by_image='http://socialcommerce.in/socialcommerce.png';
		$CONFIG->powered_by_link_url = 'http://socialcommerce.in';
	}
	if($CONFIG->loggedin){
		//Depricated function replace
		$options = array('types'			=>	"object",
						'subtypes'			=>	"cart",
						'owner_guids'		=>	$_SESSION['user']->guid,
					);
		$carts = elgg_get_entities($options);
		//$carts = get_entities('object','cart',$_SESSION['user']->guid);
		if($carts){
			$items = 0;
			$cart = $carts[0];
			//Depricated function replace
			$options = array('relationship' 		=> 	'cart_item',
							'relationship_guid' 	=>	$cart->guid,
							);
			$cart_items = elgg_get_entities_from_relationship($options);
			//$cart_items = get_entities_from_relationship('cart_item',$cart->guid);
			if($cart_items){
				foreach ($cart_items as $cart_item){
					$items += $cart_item->quantity;
				}
			}
			if($items)
				$CONFIG->cart_item_count = $items;
		}
	}elseif ($CONFIG->allow_add_cart == 1 && isset($_SESSION['GUST_CART'])){
		$cart_items = $_SESSION['GUST_CART'];
		$items = 0;
		if($cart_items){
			foreach ($cart_items as $cart_item){
				$items += $cart_item['quantity'];
			}
		}
		if($items)
			$CONFIG->cart_item_count = $items;
	}
	//Depricated function replace
	$options = array('relationship' 		=> 	'wishlist',
					'relationship_guid' 	=>	$_SESSION['user']->guid,
					'count'					=>	TRUE,
					);
	$wishlist_count = elgg_get_entities_from_relationship($options);
	//$wishlist_count = get_entities_from_relationship('wishlist',$_SESSION['user']->guid,false,'','','','','','',true);
	if($wishlist_count){
		$CONFIG->wishlist_item_count = $wishlist_count;
	}
	//Depricated function replace
	$options = array(	'metadata_name_value_pairs'	=>	array('set_default' => 1),
					'types'				=>	"object",
					'subtypes'			=>	"s_currency",
					'limit'				=>	1,
				);
	$default_currency = elgg_get_entities_from_metadata($options);
	//$default_currency = get_entities_from_metadata('set_default',1,'object','s_currency',0,1);
	if($default_currency){
		$default_currency = $default_currency[0];
		$currency_code = $default_currency->currency_code;
		$CONFIG->currency_code = $currency_code;
	}else{
		$CONFIG->currency_code = "USD";
	}
	
	$CONFIG->default_weight_unit = 'LBS';
	load_module_configs();
	
	$CONFIG->UpgradeSocialcommerce = false;
	$sCommerceUpgrade = elgg_get_plugin_setting('upgradeVersion','socialcommerce');
	if($sCommerceUpgrade != $sVersion && $sVersionUpdate[$sVersion] === true){
		$sCount = elgg_get_entities(array('types'=>'object', 'subtypes'=>'stores', 'count'=>true));
		if($sCount > 0){
			$CONFIG->UpgradeSocialcommerce = true;			
		}
	}
}

function genarateCartFromSession(){
	global $CONFIG;
	if ($CONFIG->loggedin && isset($_SESSION['GUST_CART']) && !empty($_SESSION['GUST_CART'])) {
		$session_cart_items = $_SESSION['GUST_CART'];
		$ownered_products = array();
		$less_quantity_products = array();
		$options = array('types'			=>	"object",
						'subtypes'			=>	"cart",
						'owner_guids'		=>	$_SESSION['user']->getGUID(),
					);
		$carts = elgg_get_entities($options);
		
		if($carts && $session_cart_items){
			$cart = $carts[0];
			$cart_guid = $cart->guid;
			foreach ($session_cart_items as $session_cart_item){
				$product = get_entity($session_cart_item['product_id']);
				if($_SESSION['user']->guid == $product->owner_guid){
					$ownered_products[] = array('guid'=>$product->guid,'title'=>$product->title,'quantity'=>$session_cart_item['quantity']);
				}else{
					$cart_item = get_stores_from_relationship('cart_item',$cart_guid,'product_id',$product->guid,'object','cart_item',$_SESSION['user']->getGUID());
					if($cart_item){
						if($product->product_type_id == 1){
							$cart_item = get_entity($cart_item[0]->guid);
							if($product->quantity >= ($session_cart_item['quantity'] + $cart_item->quantity)){
								$quantity = $cart_item->quantity + $session_cart_item['quantity'];
							}else{
								$quantity = $product->quantity;
								$less_quantity_products[] = array('guid'=>$product->guid,'title'=>$product->title,'quantity'=>$product->quantity);
							}
							$cart_item->quantity = $quantity;
							$result = $cart_item->save();
							if($result){
								$selected_related_products = $session_cart_item['related_products'];
								if($selected_related_products){
									foreach($selected_related_products as $related_product=>$details){
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
												$cart_related_item->cart_item = $cart_item->guid;
												$cart_related_item->cart = $cart_guid;
												$add = 1;
											}
											$cart_related_item->details = $details;
											$cart_related_item_guid = $cart_related_item->save();
											if($details && $cart_related_item_guid){
												if($add)
													add_entity_relationship($cart_item_guid,'cart_related_item',$cart_related_item_guid);
											}
										}
									}
								}
							}
						}
					}else{
						if($session_cart_item['quantity'] > $product->quantity && $product->product_type_id == 1){
							$quantity = $product->quantity;
							$less_quantity_products[] = array('guid'=>$product->guid,'title'=>$product->title,'quantity'=>$product->quantity);
						}else {
							$quantity = $session_cart_item['quantity'];
						}
						
						$cart_item = new ElggObject();
						$cart_item->access_id = 2;
						$cart_item->subtype = "cart_item";
						$cart_item->title = $product->title;
						$cart_item->quantity = $quantity;
						$cart_item->product_id = $product->guid;
						$cart_item->amount = $product->price;
						
						//Get the version details for a digital product 
						if($product->product_type_id == 2){
							$version_guid = $session_cart_item['version_guid'];
							if($version_guid > 0){
								$version = get_entity($version_guid); 
							}else{
								$version = get_latest_version($product->guid);
							}
						}		
						// For select the product version for digital products							
						if($product->product_type_id == 2){								
							$cart_item->version_guid = $version->guid;
							$cart_item->version_release = $version->version_release;
							$cart_item->version_summary = $version->version_summary;
						}
						$cart_item->container_guid = $_SESSION['user']->getGUID();
						$cart_item_guid = $cart_item->save();
						
						if($cart_item_guid){
							$result = add_entity_relationship($cart_guid,'cart_item',$cart_item_guid);
							if(in_array('cart_add',$CONFIG->river_settings))
								add_to_river('river/object/cart/create','cartadd',$_SESSION['user']->guid,$product->guid);
							$selected_related_products = $session_cart_item['related_products'];
							if($selected_related_products){
								foreach($selected_related_products as $related_product=>$details){
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
			}
		}else {
			$cart = new ElggObject();
			$cart->access_id = 2;
			$cart->subtype = "cart";
			$cart->container_guid = $_SESSION['user']->getGUID();
			
			$cart_guid = $cart->save();
			if($cart_guid && $session_cart_items){
				foreach ($session_cart_items as $session_cart_item){
					$product = get_entity($session_cart_item['product_id']);
					if($_SESSION['user']->guid == $product->owner_guid){
						$ownered_products[] = array('guid'=>$product->guid,'title'=>$product->title,'quantity'=>$session_cart_item['quantity']);
					}else{
						if($session_cart_item['quantity'] > $product->quantity && $product->product_type_id != 2){
							$quantity = $product->quantity;
							$less_quantity_products[] = array('guid'=>$product->guid,'title'=>$product->title,'quantity'=>$product->quantity);
						}else {
							$quantity = $session_cart_item['quantity'];
						}
						$cart_item = new ElggObject();
						$cart_item->access_id = 2;
						$cart_item->subtype = "cart_item";
						$cart_item->title = $product->title;
						$cart_item->quantity = $quantity;
						$cart_item->product_id = $product->guid;
						$cart_item->amount = $product->price;
						//Get the version details for a digital product 
						if($product->product_type_id == 2){
							$version_guid = $session_cart_item['version_guid'];
							if($version_guid > 0){
								$version = get_entity($version_guid); 
							}else{
								$version = get_latest_version($product->guid);
							}
						}
						// For select the product version for digital products							
						if($product->product_type_id == 2){								
							$cart_item->version_guid = $version->guid;
							$cart_item->version_release = $version->version_release;
							$cart_item->version_summary = $version->version_summary;
						}
						
						$cart_item->container_guid = $_SESSION['user']->getGUID();
						$cart_item_guid = $cart_item->save();
						
						if($cart_item_guid){
							$result = add_entity_relationship($cart_guid,'cart_item',$cart_item_guid);
							if(in_array('cart_add',$CONFIG->river_settings))
								add_to_river('river/object/cart/create','cartadd',$_SESSION['user']->guid,$product->guid);
							$selected_related_products = $session_cart_item['related_products'];
							if($selected_related_products){
								foreach($selected_related_products as $related_product=>$details){
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
			}
		}
		$cart_messages = "";
		if(count($ownered_products) > 0){
			$cart_message = '';
			foreach ($ownered_products as $ownered_product){
				$cart_message .= sprintf(elgg_echo('cart:ownered:product'),$ownered_product['title'],$ownered_product['quantity']);
			}
			if($cart_message != ""){
				$cart_messages = sprintf(elgg_echo('cart:ownered:products'),$cart_message);
			}
		}
		if(count($less_quantity_products) > 0){
			$cart_message = '';
			foreach ($less_quantity_products as $less_quantity_product){
				$cart_message .= sprintf(elgg_echo('cart:less:quantity:product'),$less_quantity_product['title'],$less_quantity_product['quantity']);
			}
			if($cart_message != ""){
				$cart_messages .= sprintf(elgg_echo('cart:less:quantity:products'),$cart_message);
			}
		}
		if($cart_messages != ""){
			system_message($cart_messages);
		}
		unset($_SESSION['GUST_CART']);
	}
}



/******************************************/
/*                CHECKOUT                */
/******************************************/


/**
 *	Display or redirect to payment gateway
 * 	@param string The URL to redirect to.
 * 	@param array An array of form fields to POST, if any.
 * 	@param int not_compleated for enter any extra datas from clinit side. If 1 it will allow to enter datas otherwise it automatically redirect to the given url.
 *	@param string the field_view is the view to display that extra fields in client side.
 */
function redirect_to_form($url, $fields=array(), $not_compleated = 0, $field_view){
	global $CONFIG;
	$formFields = '';
	if(is_array($fields)){
		foreach($fields as $name => $value) {
			$formFields .= "<input type=\"hidden\" name=\"".html_escape($name)."\" value=\"".html_escape($value)."\" />\n";
		}
	}
	if($not_compleated){
		$detailed_view = elgg_view('modules/checkout/'.$_SESSION['CHECKOUT']['checkout_method'].'/'.$field_view);
	}else{
		$detailed_view = elgg_echo('processing').'...';
		$auto_redirect_script = <<<EOF

			<script type="text/javascript">
				window.onload = function() {
					var window_width = $(document).width();
					var window_height = $(document).height();
					var scroll_pos = (document.all)?document.body.scrollTop:window.pageYOffset;
					scroll_pos = scroll_pos  + 300;
					$("#load_action").show();
					$("#load_action").css({'width':window_width+'px','height':window_height+'px'});
					$("#load_action_div").css("top",scroll_pos+"px");
					$("#load_action_div").css({'width':window_width+'px'});
					$("#load_action_div").show();
					/*document.payment_redirect_form.submit();*/
					$("#payment_redirect_form").submit();
				}
				function onLoadFunction(){
					document.getElementById("payment_redirect_form").submit();
				}
				setTimeout(function() {
				    onLoadFunction()
				   }, 1000);
				//setTimeout(onLoadFunction(), 500);
			</script>
EOF;
	}
	$form = <<<EOF
		<div>
			<form id="payment_redirect_form" action="{$url}" method="post">
				{$detailed_view}
				{$formFields}
			</form>
			{$auto_redirect_script}
		</div>
EOF;
	return $form;
	exit;
}

/*
 * Create an order after payment
 */
function create_order($user_guid = 0, $CheckoutMethod, $posted_values, $BillingDetails, $ShippingDetails, $ShippingMethod,$cart_guid = 0){

	global $CONFIG;
	$old = elgg_set_ignore_access(TRUE);
	$download_condition = "";
	$used_coupons = array();
	if($user_guid == 0){
		$user_guid = $_SESSION['user']->guid;	
	}
	$page_owner = $user_guid;
	if($page_owner > 0){
		elgg_set_page_owner_guid($page_owner);
	}
	elgg_set_context('add_order');
	$user = get_entity($page_owner);
	
	$container_guid = $page_owner;
	$options = array('types'			=>	"object",
					'subtypes'			=>	"splugin_settings",
					'limit'				=>	99999,
				);
	$settings = elgg_get_entities($options);
	if($settings){
		$settings = $settings[0];
	}	
	if($cart_guid == 0){
		$options = array('types'			=>	"object",
						'subtypes'			=>	"cart",
						'owner_guids'		=>	$page_owner,
					);
		$carts = elgg_get_entities($options);
		$cart = $carts[0];
	}else{
		$cart = get_entity($cart_guid);
	}
	
	if($cart){
		$payment_fee = (float)$posted_values['fee'];		
		$payment_gross = (float)$posted_values['total'];
		$payment_fee_percentage = ($payment_fee * 100)/$payment_gross;
		$new_fund = $payment_gross - $payment_fee;
		//$cart = $carts[0];
		$order = new ElggObject();
		$order->access_id = 2;
		$order->owner_guid=$page_owner;
		$order->subtype="order";
		$order->payment_fee=$payment_fee;
		$order->total=$payment_gross;
		$order->percentage=$payment_fee_percentage;
		$order->amound=$new_fund;
		$order->payer_email=$posted_values['email'];
		$order->transaction_status=$posted_values['status'];
		$order->transaction_id=$posted_values['txn_id'];
		if(isset($posted_values['params']) && is_array($posted_values['params'])){
			foreach ($posted_values['params'] as $key=>$val){
				$order->$key=$val;
			}
		}
		$order->allow_tax_method = $CONFIG->allow_tax_method;
		if($CheckoutMethod)
			$order->checkout_method=$CheckoutMethod;
		if($ShippingMethod)
			$order->shipping_method=$ShippingMethod;
		
		$BillingAddress = get_entity($BillingDetails);
		if($BillingAddress){
			$order->b_first_name=$BillingAddress->first_name;
			$order->b_last_name=$BillingAddress->last_name;
			$order->b_address_line_1=$BillingAddress->address_line_1;
			$order->b_address_line_2=$BillingAddress->address_line_2;
			$order->b_city=$BillingAddress->city;
			$order->b_state=$BillingAddress->state;
			$order->b_country=$BillingAddress->country;
			$order->b_pincode=$BillingAddress->pincode;
			if($BillingAddress->mobileno)
				$order->b_mobileno=$BillingAddress->mobileno;
			if($BillingAddress->phoneno)
				$order->b_phoneno=$BillingAddress->phoneno;
		}
		
		$ShippingAddress = get_entity($ShippingDetails);
		if($ShippingAddress){
			$order->s_first_name=$ShippingAddress->first_name;
			$order->s_last_name=$ShippingAddress->last_name;
			$order->s_address_line_1=$ShippingAddress->address_line_1;
			$order->s_address_line_2=$ShippingAddress->address_line_2;
			$order->s_city=$ShippingAddress->city;
			$order->s_state=$ShippingAddress->state;
			$order->s_country=$ShippingAddress->country;
			$order->s_pincode=$ShippingAddress->pincode;
			if($ShippingAddress->mobileno)
				$order->s_mobileno=$ShippingAddress->mobileno;
			if($ShippingAddress->phoneno)
				$order->s_phoneno=$ShippingAddress->phoneno;
		}
		
		if ($container_guid){
			$order->container_guid = $container_guid;
		}
		$order->cart_guid = $cart->guid;
		$order_id = $order->save();
		if($order_id){
			$cart_guid = $cart->guid;			
			$tot = $tax_total_price = 0;
			$related_product_total_price = 0;
			$item_details_for_store_owner = array();
			//-------- Site admin ----------------//
			$admin_user = get_site_admin();
			//-------- Site entity ----------------//
			$site = get_entity($CONFIG->site_guid);
			$options = array('relationship' 		=> 	'cart_item',
							'relationship_guid' 	=>	$cart_guid,
							);
			$cart_items = elgg_get_entities_from_relationship($options);
			if($cart_items){
				if($ShippingMethod){
					foreach ($cart_items as $cart_item){
						$product = get_entity($cart_item->product_id);
						$products[$product->guid] = (object)array('quantity'=>$cart_item->quantity,'price'=>$cart_item->amount,'type'=>$product->product_type_id);
					}
					$function = "price_calc_".$ShippingMethod;
					if(function_exists($function)){
						$s_prince = $function($products);
					}
				}
				$item_shipping_price=0;
				$related_product_price = 0;
				foreach ($cart_items as $cart_item){
					$price = 0;
					$product_id = $cart_item->product_id;
					$product = get_entity($product_id);
					$country_code = $product->countrycode;
					$item_payment_fee = ($product->price * $payment_fee_percentage)/100;
					$order_item = "";
					$order_item = new ElggObject();
					$order_item->access_id = 2;
					$order_item->owner_guid=$page_owner;
					$order_item->subtype="order_item";
					$order_item->product_id=$product_id;
					$order_item->order_guid=$order_id;
					$order_item->product_owner_guid=$product->owner_guid;
					$order_item->title = $product->title;
					$order_item->description = $product->description;
					$order_item->quantity = $cart_item->quantity;
					$order_item->price = $product->price;
					$order_item->countrycode = $country_code;
					$order_item->payment_fee_per_item = $item_payment_fee;
					$order_item->status = 0;
					$cart_item_version_guid = 0;
					// for store the details about version				
					if($product->product_type_id == 2){
						$order_item->version_guid = $cart_item->version_guid;
						$order_item->version_release = $cart_item->version_release;
						$order_item->version_summary = $cart_item->version_summary;
						$cart_item_version_guid = $cart_item->version_guid;
						if($CONFIG->download_newversion_allow){
							$order_item->download_newversion_days = $CONFIG->download_newversion_days;
						}
					}
					/**
					 * to download new version for digitasl product after purchase
					 * */
					
					$order_item->save();
					if($s_prince[$product_id]){
						$order_item->shipping_price = $s_prince[$product_id];
						$item_shipping_price = $s_prince[$product_id];
						$shipping_total += $s_prince[$product_id];
					}
					if ($container_guid){
						$order_item->container_guid = $container_guid;
					}
					$coupon_discount = 0;
					if($cart_item->coupon_code){
						$cart_item_coupon = get_coupon_by_couponcode($cart_item->coupon_code);
						if($cart_item_coupon){
							$used_coupons[$cart_item_coupon->guid] = $cart_item_coupon->coupon_code;
							$order_item->coupon_code = $cart_item_coupon->coupon_code;
							$order_item->coupon_amount = $cart_item_coupon->coupon_amount;
							$order_item->coupon_type = $cart_item_coupon->coupon_type;
							if($cart_item_coupon->coupon_type != 1){
								$coupon_discount = round(($order_item->price * $cart_item_coupon->coupon_amount) / 100,2);
							}else{
								$coupon_discount = $cart_item_coupon->coupon_amount;
							}
							$order_item->coupon_discount = $coupon_discount;
						}
					}
					if($CONFIG->allow_tax_method != 1) {
						$item_tot = $order_item->price;
						if($coupon_discount > 0)
							$item_tot = $item_tot - $coupon_discount;
						$item_tot = $item_tot * $order_item->quantity;
						if($CONFIG->allow_tax_method == 2) {
					    	$tax_price = generate_tax($item_tot,'',$country_code);
					    	$tax_price_cpy = generate_tax($item_tot,'',$country_code);
						} else {
							$tax_price = generate_tax($item_tot,'');
							$tax_price_cpy = generate_tax($item_tot,'');
						}
						
						$order_item->tax_percentage = $taxrate_val;
						$order_item->tax_price = $tax_price_cpy;
					}
					$order_item_id = $order_item->save();
					if($order_item_id){
						if(in_array('product_checkout',$CONFIG->river_settings))
							add_to_river('river/object/stores/purchase','purchase',$page_owner,$product_id);
						
						$options = array('relationship' 		=> 	'cart_related_item',
										'relationship_guid' 	=>	$cart_item->guid,
										'types'					=>	"object",
										'subtypes'				=>	"cart_related_item",
										'limit'					=>	99999,
										);
						$related_products = elgg_get_entities_from_relationship($options);	
						
						$related_product_price = 0;
						$related_products_display = $related_products_price_display = '';
						if($related_products){
							foreach($related_products as $related_product){
								$order_related_product = new ElggObject();
								$order_related_product->subtype = 'order_related_item';
						        $order_related_product->owner_guid = $page_owner;
						        $order_related_product->access_id = ACCESS_PUBLIC;
						        
						        $order_related_product->product = $product_id;
						        $order_related_product->order_item = $order_item_id;
						        $order_related_product->related_product = $related_product->related_product;
						        $order_related_product->status = 1;
								if ($container_guid){
									$order_related_product->container_guid = $container_guid;
								}
						        $order_related_product_guid = $order_related_product->save();
								$details = $related_product->details;
								if(!is_array($details) && $details != ''){
									$details = array($details);
								}
								if($order_related_product_guid){
									if(!empty($details)){
										add_entity_relationship($order_item_id,'order_related_item',$order_related_product_guid);
										foreach($details as $detail){
											$detail = get_entity($detail);
											if($detail){
												$order_details = new ElggObject();
										        $order_details->subtype = 'order_related_details';
										        $order_details->owner_guid =$page_owner;
												if ($container_guid){
													$order_details->container_guid = $container_guid;
												}
										        $order_details->access_id = ACCESS_PUBLIC;
										        $order_details->order_related_item = $order_related_product_guid;
										        $order_details->related_product = $related_product->related_product;
										        $order_details->title = $detail->title;
										        $order_details->price = $detail->price;
										        $order_details->status = 1;
										        if($order_details->save()){
										        	add_entity_relationship($order_related_product_guid,'order_related_details',$order_details->guid);
										        	$related_product_price += $detail->price;
										        	$detail_price_display = get_price_with_currency($detail->price);
										        	$src = $CONFIG->wwwroot."mod/$CONFIG->pluginname/images/dot.gif";
										        	$related_products_display .= <<<EOF
														<div style="margin-left:10px;">
															<img style="float:left;margin-right:5px;margin-top:3px;" src="{$src}">
															<div style="float:left;">{$detail->title}</div>
															<br />
															<div class="clear"></div>
														</div>
EOF;
													$related_products_price_display .= <<<EOF
														<div>
															<div>{$detail_price_display}</div>
														</div>
EOF;
												}
											}
										}
									}
									
									$related_product->delete();
								}
							}
						}
						
						if($CONFIG->allow_tax_method != 1){
							if($CONFIG->allow_tax_method == 2){
								$tax_price = generate_tax($item_tot+$related_product_price,'',$country_code);
							}else{
								$tax_price = generate_tax($item_tot+$related_product_price,'');
							}
							$order_item->tax_price = $tax_price;
							$order_item->save();
						}
						$related_product_total_price += $related_product_price;
						
						/* For calculate and display the payment fee and Total cost on each item*/
						$final_payment_fee_per_item=0;
						$order_item->relative_product_price_total = $related_product_price;
						$final_payment_fee_per_item =(($product->price-$coupon_discount)*$cart_item->quantity)+$related_product_price+$tax_price+$item_shipping_price;
						$final_amount = "";
						$final_amount = $final_payment_fee_per_item;
						if($final_payment_fee_per_item > 0)
						{
							$final_payment_fee_per_item = ($final_payment_fee_per_item * $payment_fee_percentage)/100;
							$final_payment_fee_per_item = number_format($final_payment_fee_per_item,2,'.','');
						}
						$order_item->final_payment_fee_per_item	= $final_payment_fee_per_item;					 		
						$order_item->save();
						
						//-------- Stores Percentage ---------//
						$stores_percentage = $CONFIG->socialcommerce_percentage;
						//-------- Site Commission -----------//
						if($CONFIG->allow_socialcommerce_store_percetage > 0) {
							$site_commission = number_format((((($product->price * $cart_item->quantity) + $related_product_price) * $stores_percentage)/100), 2, '.', '');
						} else {
							$site_commission = 0;
						}
						
						//----------Flat Rate ----------------//
						$store_flat_amount = $CONFIG->socialcommerce_flat_amount;
						
						//----------Select the site commission method -------------//
						$CONFIG->allow_socialcommerce_store_percetage;
						$CONFIG->allow_socialcommerce_flat_amount;
						
						$product_shipping_price =0;
						if($s_prince[$product_id]){
						$product_shipping_price = $s_prince[$product_id];
						}
						
						if($CONFIG->allow_socialcommerce_store_percetage > 0 && $CONFIG->allow_socialcommerce_flat_amount>0) {
							$site_commission_include_tax = number_format((((((($product->price-$coupon_discount)* $cart_item->quantity)+$product_shipping_price) + $related_product_price+$tax_price) * $stores_percentage)/100), 2, '.', '');
							$site_commission_include_tax = $site_commission_include_tax + $store_flat_amount;
						}else if($CONFIG->allow_socialcommerce_store_percetage > 0)	{
							$site_commission_include_tax = number_format((((((($product->price-$coupon_discount)* $cart_item->quantity)+$product_shipping_price) + $related_product_price+$tax_price) * $stores_percentage)/100), 2, '.', '');
						}else if($CONFIG->allow_socialcommerce_flat_amount>0) {
							$site_commission_include_tax = $store_flat_amount;
						} else {
							$site_commission_include_tax = 0;
						}
						/* Save the Site Commision in the oder item .For display in the sold product more details popup */
						//$order_item->final_site_commission	= $site_commission_include_tax;
						$order_item->final_site_commission	= $site_commission+$final_payment_fee_per_item;					
						$order_item->save();
						
						
						//-------- User Price ----------------//
						$user_price = (($product->price * $cart_item->quantity) + $related_product_price) - $site_commission;
						
												
						$user_price_include_tax = (((($product->price-$coupon_discount) * $cart_item->quantity) + $product_shipping_price) + $related_product_price+$tax_price) - $site_commission;
						
												
						$admin_transaction = new ElggObject();
						$admin_transaction->access_id = 2;
						$admin_transaction->owner_guid=$admin_user->guid;
						$admin_transaction->container_guid=$admin_user->guid;
						$admin_transaction->subtype='transaction';
						$admin_transaction->trans_type="credit";
						$admin_transaction->title='site_commission';
						$admin_transaction->trans_category='site_commission';
						$admin_transaction->order_guid=$order_id;
						$admin_transaction->order_item_guid=$order_item_id;
						$admin_transaction->amount=$site_commission+$final_payment_fee_per_item;
						$admin_transaction->payment_fee=$final_payment_fee_per_item;
						$admin_transaction->final_amount=$final_amount;
						$admin_transaction->save();
						
						$user_transaction = new ElggObject();
						$user_transaction->access_id = 2;
						$user_transaction->owner_guid=$product->owner_guid;
						$user_transaction->container_guid=$product->owner_guid;
						$user_transaction->subtype='transaction';
						$user_transaction->total_amount=$product->price * $cart_item->quantity;
						$user_transaction->trans_type="credit";
						$user_transaction->title='sold_product';
						$user_transaction->trans_category='sold_product';
						$user_transaction->order_guid=$order_id;
						$user_transaction->order_item_guid=$order_item_id;
						$user_transaction->amount=$user_price_include_tax-$final_payment_fee_per_item;
						$user_transaction->product_quantity=$cart_item->quantity;
						$user_transaction->product_total_tax=$tax_price;
						$user_transaction->product_total_shipping_price=$product_shipping_price;
						$user_transaction->stores_percentage=$stores_percentage;
						$user_transaction->product_site_commission=$site_commission+$final_payment_fee_per_item;
						$user_transaction->payment_fee = $final_payment_fee_per_item;
						$user_transaction->coupon_discount_amount=$coupon_discount*$cart_item->quantity;
						$user_transaction->final_amount=$final_amount;
						
						$user_transaction->save();
						

						$result = add_entity_relationship($order_id,'order_item',$order_item_id);
						if($product->product_type_id == 1){
							$product->quantity = $product->quantity - $cart_item->quantity;
							$product_update_guid = $product->save();
							if(!$product_update_guid > 0){
								$existing = get_data_row("SELECT * from {$CONFIG->dbprefix}metadata WHERE entity_guid = $product->guid and name_id=" . add_metastring('quantity') . " limit 1");
								if($existing){
									$id = $existing->id;
									$value = $product->quantity - $cart_item->quantity;
									$metadat_update = update_metadata($id, 'quantity', $value, $existing->value_type, $existing->owner_guid, $existing->access_id);	
								}
							}
						}
						if($result){
							$image = "";
							$desc = nl2br($product->description);
							$discount_price = $product->price;
							$cart_item_coupon = $cart_item->coupon_code;
							if($cart_item_coupon){
								$cart_item_coupon = get_coupon_by_couponcode($cart_item_coupon);
								if($cart_item_coupon){
									$coupon_amount = $order_item->coupon_discount;
									$discount_price = $product->price - $coupon_amount;
								}
							}
							$sub_total = $discount_price * $cart_item->quantity;
							//$original_price = $product->price * $cart_item->quantity;
							$original_price = $product->price;
							$total_price += $sub_total;
							if($tax_price > 0){
								$tax_total_price += $tax_price;
							}
							$display_price = get_price_with_currency($product->price);
							if($cart_item_coupon){
								$display_price = "<span style='margin-right:10px;text-decoration:line-through;'>".get_price_with_currency($original_price)."</span>".get_price_with_currency($discount_price);
							}
							$product_url = $product->getURL();
							$image = "<a href=\"{$product_url}\">" . elgg_view("socialcommerce/image", array('entity' => $product, 'size' => 'medium','display'=>'image')) . "</a>";
							$display_sub_total = get_price_with_currency($sub_total);
							
							$version = get_entity($cart_item_version_guid);
							
							if($version->mimetype && $product->product_type_id == 2){								
								//$icon = "<div title='Download' class='order_icon_class'><a href=\"{$CONFIG->wwwroot}action/{$CONFIG->pluginname}/download?product_guid={$order_item->guid}\">" . elgg_view("{$CONFIG->pluginname}/icon", array("mimetype" => $product->mimetype, 'thumbnail' => $product->thumbnail, 'stores_guid' => $product->guid, 'size' => 'small')) . "</a></div><div class='clear'></div>";
								if($version){
									$store_guid = $version->guid;
								}else{
									$store_guid = $product->guid;
								}
								$icon = "<div class='order_icon_class'><a href=\"{$CONFIG->wwwroot}{$CONFIG->pluginname}/download/{$order_item->guid}\">" . elgg_view("{$CONFIG->pluginname}/icon", array("mimetype" => $version->mimetype, 'thumbnail' => $version->thumbnail, 'stores_guid' => $store_guid, 'size' => 'small', 'title'=>'Download')) . "</a></div><div class='clear'></div>";
							}else{
								$icon = "";
							}
							$extended_order_details = "";
							$Order_product = get_entity($order_item->product_id);
							if($Order_product->product_key){
								$extended_order_details = elgg_view("socialcommerce_license_key/order_details_with_keys",array('order'=>$order,"order_item"=>$order_item));
							}
							if($order_item->version_release != ""){
								$version_detail = elgg_echo('product:mupload_version:label').":" .$order_item->version_release;								
							}
							$item_details .= <<<EOF
								<tr>
									<td style="width: 400px;padding-bottom:0px;" valign="top">
										<div style="float:left;">{$product->title}</div>{$icon}<br clear="all"/>
										{$version_detail}
										{$extended_order_details}
									</td>
									<td style="text-align:center;padding-bottom:0px;" valign="top">{$cart_item->quantity}</td>
									<td style="text-align:right;padding-bottom:0px;" valign="top">
										{$display_price}
									</td>
									<td style="text-align:right;padding-bottom:0px;" valign="top">
										{$display_sub_total}	
									</td>
								</tr>
								<tr>
									<td>
										{$related_products_display}
									</td>
									<td style="text-align:center;" valign="top"></td>
									<td style="text-align:right;" valign="top">
										{$related_products_price_display}
									</td>
									<td style="text-align:right;" valign="top">
										{$related_products_price_display}
									</td>
								</tr>
EOF;
							$item_details_for_store_owner[$product->owner_guid]['content'] .= <<<EOF
							<tr>
									<td style="width: 400px;padding-bottom:0px;" valign="top">
										<div style="float:left;"><a href="{$product->getUrl()}">{$product->title}</a><br clear="all"/>
										{$version_detail}
										{$extended_order_details}
									</td>
									<td style="text-align:center;padding-bottom:0px;" valign="top">{$cart_item->quantity}</td>
									<td style="text-align:right;padding-bottom:0px;" valign="top">
										{$display_price}
									</td>
									<td style="text-align:right;padding-bottom:0px;" valign="top">
										{$display_sub_total}	
									</td>
								</tr>
								<tr>
									<td>
										{$related_products_display}
									</td>
									<td style="text-align:center;" valign="top"></td>
									<td style="text-align:right;" valign="top">
										{$related_products_price_display}
									</td>
									<td style="text-align:right;" valign="top">
										{$related_products_price_display}
									</td>
								</tr>	
EOF;
						}
						$ownermail_subtotal_relativepro = $sub_total + $related_product_price;
						$item_details_for_store_owner[$product->owner_guid]['total'] += $ownermail_subtotal_relativepro;
						if($CONFIG->allow_tax_method == 2) {
						    	$item_details_for_store_owner[$product->owner_guid]['taxprice'] += generate_tax($ownermail_subtotal_relativepro,'',$country_code);
							} else {
								$item_details_for_store_owner[$product->owner_guid]['taxprice'] += generate_tax($ownermail_subtotal_relativepro,'');
							}
							
						 $item_details_for_store_owner[$product->owner_guid]['shipping_price'] += $product_shipping_price;
						 $item_details_for_store_owner[$product->owner_guid]['site_commission'] += $site_commission+$final_payment_fee_per_item;
						 //$item_details_for_store_owner[$product->owner_guid]['grand_total'] += $item_details_for_store_owner[$product->owner_guid]['total']+$item_details_for_store_owner[$product->owner_guid]['taxprice']+$item_details_for_store_owner[$product->owner_guid]['shipping_price'];	
					//echo $item_details_for_store_owner[$product->owner_guid]['grand_total']."total =".$item_details_for_store_owner[$product->owner_guid]['total']."tax = ".$item_details_for_store_owner[$product->owner_guid]['taxprice']."Shipping = ".$item_details_for_store_owner[$product->owner_guid]['shipping_price']."<br>";
					}
					$cart_item = get_entity($cart_item->guid);
					$cart_item->delete();
					
					$order_page = $CONFIG->wwwroot.''.$CONFIG->pluginname.'/order/'.$user->username;
					
					// For get the version for digital product
					if($product->product_type_id == 2){
						$version = get_entity($cart_item_version_guid);							
						if($version){
							$mimetype = $version->mimetype;
						}else{
							$mimetype = $product->mimetype;					
						}
					}
					
					if($mimetype && $product->product_type_id == 2){
						$download_condition = <<<EOF
							<div>
								<div><b>How to download</b></div>
								if you want to download please follow the below steps.
								<ul>
									<li>Please click to the product type icon on the above order details.</li>
								</ul>
								<div style="margin-left:40px;"><b>OR</b></div>
								<ul>
									<li>Please go to <a target="_blank" href="{$order_page}">My Order</a></li>
									<li>Then click on Download.</li>									
								</ul>
							</div>				
EOF;
					}
				}
				if($result){
					$user_email = $user->email;
					$site_email = $site->email;
					
					$order_date = date("dS M Y");
					$order_recipient = $order->s_first_name." ".$order->s_last_name;
					$order_recipient = trim($order_recipient);
					if($order_recipient == '')
						$order_recipient = $order->b_first_name." ".$order->b_last_name;
					$order_total = $order->s_first_name." ".$order->s_last_name;
					$billing_details = elgg_view("{$CONFIG->pluginname}/order_display_address",array('entity'=>$order,'type'=>'b'));
					$adderss_details = <<<EOF
						<div style="float:left;width:300px;">
							<h3 style="font-size:16px;">Billing Details</h3>
							{$billing_details}
						</div>		
EOF;
					if($ShippingMethod){
						$shippingg_head_label = elgg_echo('order:shipping:address:head');
						$shipping_details = elgg_view("{$CONFIG->pluginname}/order_display_address",array('entity'=>$order,'type'=>'s'));
						$adderss_details .= <<<EOF
							<div style="float:left;width:300px;">
								<h3 style="font-size:16px;">{$shippingg_head_label}</h3>
								{$shipping_details}
							</div>		
EOF;
					}
					if($shipping_total > 0){
						$display_shipping_price = get_price_with_currency($shipping_total);
						$shipping_price = <<<EOF
							<tr>
								<td style="border-top:1px solid #4690D6;" colspan="4">
									<div style="width:100px;float:right;text-align:right;"><B>{$display_shipping_price}</B></div>
									<div style="text-align:right;"><B>Shipping:</B> </div> 
								</td>
							</tr>
EOF;
					}
					$grand_total_price = $total_price + $shipping_total;
					if($related_product_total_price > 0){
						$grand_total_price += $related_product_total_price;
					}
					if($tax_total_price){
						$tax_total_price_display = get_price_with_currency($tax_total_price);
						$tax_display = <<<EOF
							<tr>
								<td style="border-top:1px solid #4690D6;" colspan="4">
									<div style="width:100px;float:right;text-align:right;"><B>{$tax_total_price_display}</B></div>
									<div style="text-align:right;"><B>Tax:</B> </div> 
								</td>
							</tr>
EOF;
						$grand_total_price += $tax_total_price;
					}
					$display_grand_total = get_price_with_currency($grand_total_price);
					$grand_total = <<<EOF
						<tr>
							<td style="border-top:1px solid #4690D6;" colspan="4">
								<div style="width:100px;float:right;text-align:right;"><B>{$display_grand_total}</B></div>
								<div style="text-align:right;"><B>Total Cost:</B> </div> 
							</td>
						</tr>
EOF;
					/*$order_page = $CONFIG->wwwroot.''.$CONFIG->pluginname.'/'.$user->username.'/order/';
					if($product->mimetype && $product->product_type_id == 2){
						$download_condition = <<<EOF
							<div>
								<div><b>How to download</b></div>
								if you want to download please follow the below steps.
								<ul>
									<li>Please click to the product type icon on the above order details.</li>
								</ul>
								<div style="margin-left:40px;"><b>OR</b></div>
								<ul>
									<li>Please go to <a target="_blank" href="{$order_page}">My Order</a></li>
									<li>Then click on View Details button.</li>
									<li>Click to the product type icon on order details.</li>
								</ul>
							</div>				
EOF;
					}else{
						$download_condition = "";
					}*/
					if(!$ShippingMethod)
						$ShippingMethod = 'None';
					$order_link = $CONFIG->wwwroot.''.$CONFIG->pluginname.'/order_products/'.$order_id;
					$view_total_price = get_price_with_currency($grand_total_price);
					$mail_body = sprintf(elgg_echo('order:mail'),
										 $user->name,
										 $order_id,
										 $order_link,
										 $order_link,
										 $order_id,
										 $order_date,
										 $order_recipient,
										 $view_total_price,
										 $ShippingMethod,
										 $adderss_details,
										 $order_id,
										 $item_details,
										 $tax_display,
										 $shipping_price,
										 $grand_total,
										 $download_condition
					);
					//echo $mail_body;
					$subject = "New {$site->name} Purchase completed";
					stores_send_mail(	 $site, 					// From entity
										 $user, 					// To entity
										 $subject,					// The subject
										 $mail_body					// Message
								);
					
					if($admin_user){
						$mail_body = sprintf(elgg_echo('order:mail:to:admin'),
										 $admin_user->name,
										 $site->name,
										 $order_link,
										 $order_id,
										 $order_date,
										 $order_recipient,
										 $view_total_price,
										 $ShippingMethod,
										 $adderss_details,
										 $order_id,
										 $item_details,
										 $tax_display,
										 $shipping_price,
										 $grand_total
						);
						//echo $mail_body; 
						$subject = "New order created on {$site->name}";
						stores_send_mail($site, 					// From entity
										 $admin_user, 				// To entity
										 $subject,					// The subject
										 $mail_body					// Message
								);
					}
					
					if(count($item_details_for_store_owner) > 0){
						//print_r($item_details_for_store_owner);
						foreach($item_details_for_store_owner as $product_owner_guid=>$item_detaile){
							$product_owner = get_entity($product_owner_guid);
							//$display_grand_total = get_price_with_currency($item_detaile['total']);
							$ownermail_tax_total_price =  $item_detaile['taxprice'];
							$ownermail_tax_display = "";
							if($ownermail_tax_total_price){
								$ownermail_tax_total_price_display = get_price_with_currency($ownermail_tax_total_price);
								$ownermail_tax_display = <<<EOF
									<tr>
										<td style="border-top:1px solid #4690D6;" colspan="4">
											<div style="width:100px;float:right;text-align:right;"><B>{$ownermail_tax_total_price_display}</B></div>
											<div style="text-align:right;"><B>Tax:</B> </div> 
										</td>
									</tr>
EOF;
								//$grand_total_price += $tax_total_price;
							}
							$ownermail_shipping_total = $item_detaile['shipping_price'];
							$ownermail_shipping_price = "";
							if($ownermail_shipping_total > 0){
							$ownermail_display_shipping_price = get_price_with_currency($ownermail_shipping_total);
							$ownermail_shipping_price = <<<EOF
								<tr>
									<td style="border-top:1px solid #4690D6;" colspan="4">
										<div style="width:100px;float:right;text-align:right;"><B>{$ownermail_display_shipping_price}</B></div>
										<div style="text-align:right;"><B>Shipping:</B> </div> 
									</td>
								</tr>
EOF;
							}
							
							$ownermail_total = $item_detaile['total']+$item_detaile['shipping_price']+$item_detaile['taxprice'];
							
							//$ownermail_total =  $item_detaile['grand_total'];
							$ownermail_display_grand_total = get_price_with_currency($ownermail_total);
							$ownermail_grand_total = <<<EOF
								<tr>
									<td style="border-top:1px solid #4690D6;" colspan="4">
										<div style="width:100px;float:right;text-align:right;"><B>{$ownermail_display_grand_total}</B></div>
										<div style="text-align:right;"><B>Total Cost:</B> </div> 
									</td>
								</tr>
EOF;
								
							//-------- Site Commission -----------//
							/*if($CONFIG->allow_socialcommerce_store_percetage > 0) {
								$site_commission = number_format((($grand_total_price * $stores_percentage)/100), 2, '.', '');
							} else {
								$site_commission = 0;
							}*/
							$ownermail_site_commission = $item_detaile['site_commission'];
							
							$total_amount_seller_display = get_price_with_currency($ownermail_total - $ownermail_site_commission);
							$total_amount_seller =  <<<EOF
							<tr>
								<td style="border-top:1px solid #4690D6;" colspan="4">
									<div style="width:100px;float:right;text-align:right;"><B>{$total_amount_seller_display}</B></div>
									<div style="text-align:right;"><B>Grand Total:</B> </div> 
								</td>
							</tr>
EOF;
							
							$ownermail_site_commission_price = get_price_with_currency($ownermail_site_commission);
							$ownermail_site_commission_display = <<<EOF
							<tr>
								<td style="border-top:1px solid #4690D6;" colspan="4">
									<div style="width:100px;float:right;text-align:right;"><B>{$ownermail_site_commission_price}</B></div>
									<div style="text-align:right;"><B>Site Commission:</B> </div> 
								</td>
							</tr>
EOF;
							$mail_body = sprintf(elgg_echo('order:mail:for:store:owner'),
										 $product_owner->name,
										 $site->name,
										 $order_id,
										 $order_date,
										 $order_recipient,
										 $adderss_details,
										 $order_id,
										 $item_detaile['content'],
										 $ownermail_tax_display,
										 $ownermail_shipping_price,
										 $ownermail_grand_total,
										 $ownermail_site_commission_display,
										 $total_amount_seller
								);
							//echo $mail_body;
							$subject = "Sold product(s) from {$site->name}";
							stores_send_mail($site, 				// From entity
										 $product_owner, 			// To entity
										 $subject,					// The subject
										 $mail_body					// Message
								);
						}
					}
				}
			}
			if(count($used_coupons) > 0 && $used_coupons){
				foreach($used_coupons as $coupon_guid=>$coupon_code){
					add_entity_relationship($coupon_guid,'coupon_uses',$order_id);
				}
			}
		}
		$cart->delete();
	}else{	
		
		$options = array(
							'metadata_names'					=>	'cart_guid',
							'metadata_values'					=>	$carts->guid,
							);
		$Orders =  elgg_get_entities_from_metadata($options);
		$payment_fee = (float)$posted_values['fee'];		
		$payment_gross = (float)$posted_values['total'];	
		if($Orders){
			$order = $Orders[0];			
			if($order->payment_fee <= 0 && $payment_fee>0){
				$payment_fee_percentage = ($payment_fee * 100)/$payment_gross;
				$new_fund = $payment_gross - $payment_fee;
	
				$order->payment_fee = $payment_fee;
				$order->percentage = $payment_fee_percentage;
				$order->amound = $new_fund;
				$order->transaction_status = $posted_values['status'];
				$order->save();
				$options = array('relationship' 		=> 	'order_item',
								'relationship_guid' 	=>	$order->guid,
								);
				$order_items = elgg_get_entities_from_relationship($options);				
				if($order_items){
					foreach ($order_items as $order_item){
						$item_payment_fee = ($order_item->price * $payment_fee_percentage)/100;
						$order_item->payment_fee_per_item = $item_payment_fee;				
						$order_item->save();
						
						$options = array(
											'metadata_names'	=>	'order_item_guid',
											'metadata_values'	=>	$order_item->guid,
											'types'				=> 'object',
											'subtypes'			=> 'transaction'		
										);
						$transactions =  elgg_get_entities_from_metadata($options);
						if($transactions){
							foreach($transactions as $transaction){								
								$final_payment_fee_per_item = $transaction->final_amount;
																
								if($final_payment_fee_per_item > 0){
									$final_payment_fee_per_item = ($final_payment_fee_per_item * $payment_fee_percentage)/100;
									$final_payment_fee_per_item = number_format($final_payment_fee_per_item,2,'.','');

								}								
								if($transaction->trans_category == "site_commission"){	
									$transaction->amount = $transaction->amount + $final_payment_fee_per_item;
									$transaction->payment_fee = $final_payment_fee_per_item;
									$transaction->save();
								}else if($transaction->trans_category == "sold_product"){
									$transaction->amount=$transaction->amount-$final_payment_fee_per_item;
									$transaction->product_site_commission=$transaction->product_site_commission+$final_payment_fee_per_item;									
									$transaction->payment_fee = $final_payment_fee_per_item;
									$transaction->save();									
								}
							}
							if($final_payment_fee_per_item>0){
								$order_item->final_site_commission = $order_item->final_site_commission+$final_payment_fee_per_item;
								$order_item->save();
							}
						}
					}
				}
			}
		}				
	}
	elgg_set_ignore_access($old);
	return $order_id;
}

function notification_for_scommerce($hook, $entity_type, $returnvalue, $params){
	global $CONFIG;
	$products = get_product_from_metadata(array('base_stock'=>'','product_type_id'=>1, 'status'=>1),'object','stores');
	if($products){
		foreach ($products as $product){
			$product = get_entity($product->guid);
			if($product){
				if($product->quantity <= $product->base_stock && $product->base_stock > 0){
					$url = $product->getURL();
					$user = get_entity($product->owner_guid);
					$site = get_entity($CONFIG->site_guid);
					if($product->quantity)
						$quantity = $product->quantity;
					else
						$quantity = 0;
						
					$mail_body = sprintf(elgg_echo('less:quantity:notification:mail'),
												   $user->name,
												   $url,
												   $product->title,
												   $url,
												   $product->title,
												   $product->base_stock,
												   $quantity,
												   $site->name
												   
					);
					$subject = sprintf(elgg_echo('less:quantity:notification:mail:subject'),$site->name);
					stores_send_mail($site, 					// From entity
									 $user, 					// To entity
									 $subject,					// The subject
									 $mail_body					// Message
								);
				}
			}
		}	
	}
}

function get_product_from_metadata($meta_array,$type=null,$subtype=null,$where_spval_con=null,$metaorder=fale,$entityorder=null,$order='ASC',$limit=null,$offset=0,$owner=0,$id_not_in=null,$relationship=null, $relationship_guid=null, $inverse_relationship = false, $access = false){
	global $CONFIG;
	
	if(!is_array($meta_array) || sizeof($meta_array) == 0) {
		return false;
	}else{
		$mindex = 1;
		foreach($meta_array as $meta_name => $meta_value) {
			$metadata_join .= " JOIN {$CONFIG->dbprefix}metadata m{$mindex} on e.guid = m{$mindex}.entity_guid "; 
			if($meta_name){
				$nameid = get_metastring_id($meta_name);
				if($nameid){
					$where .= " and m{$mindex}.name_id=".$nameid;
				}else{
					$where .= " and m{$mindex}.name_id=0";
				}
			}
			if($meta_value){
				$valueid = get_metastring_id($meta_value);
				if($valueid){
					$where .= " and m{$mindex}.value_id=".$valueid;
				}
			}
			$mindex++;
		}
	}
	
	if($type){
		$where .= " and e.type='".$type."'";
	}
	if($subtype){
		$subtypeid = get_subtype_id('object',$subtype);
		if($subtypeid){
			$where .= " and e.subtype=".$subtypeid;
		}else{
			$where .= " and e.subtype=-1";
		}
	}
	if($owner > 0)
		$where .= " and e.owner_guid=".$owner;
		
	if(is_array($id_not_in)){
		$entity_guids = get_not_in_ids($id_not_in);
		if(!empty($entity_guids)){
			$where .= " and e.guid NOT IN(".$entity_guids.") ";
		}
	}	
	if($where_spval_con){
		$where .= " ".$where_spval_con;
	}
	
	
	if($relationship){
		$joinon = "e.guid = r.guid_one";
		if (!$inverse_relationship)
			$joinon = "e.guid = r.guid_two";
			
		$join =  " JOIN {$CONFIG->dbprefix}entity_relationships r on $joinon ";
		
		if ($relationship!="")
			$where .= " and r.relationship='$relationship' ";
		if ($relationship_guid)
			$where .= ($inverse_relationship ? " and r.guid_two='$relationship_guid' " : " and r.guid_one='$relationship_guid' ");
		if ($type != "")
			$where .= " and e.type='$type' ";
		if ($subtype)
			$where .= " and e.subtype=$subtype ";
	}
	
	
	if($metaorder){
		$order = " order by  CAST( v.string AS unsigned ) ".$order;
	}elseif($entityorder){
		$order = " order by e.".$entityorder." ".$order;
	}else{
		$order = " order by e.time_created desc";
	}
	
	if($limit){
		$limit = " limit ".$offset.",".$limit;
	}else{
		$limit = "";
	}
	if($access){
		$access = get_bookraiser_access_sql_suffix();
		if($access)
			$access = " and {$access} ";
	}
	$query = "SELECT SQL_CALC_FOUND_ROWS e.guid AS guid, e.owner_guid as owner_guid, v.string as value from {$CONFIG->dbprefix}entities e {$metadata_join} JOIN {$CONFIG->dbprefix}metastrings v on m1.value_id = v.id ".$join." where (1 = 1) ".$where." ".$order." ".$limit;
	$products = get_data($query);
	
	return $products;
}

/*
 * Function for Convert a weight between the specified units.
 */
function convert_weight($weight, $to_unit, $from_unit = null){
	global $CONFIG;
	
	if(is_null($from_unit)) {
		$from_unit = $CONFIG->default_weight_unit;
	}
	$from_unit = strtolower($from_unit);
	$to_unit = strtolower($to_unit);

	$units = array(
			'pounds' => array('lb', 'pound', 'lbs', 'pounds'),
			'kg' => array('kg', 'kgs', 'kilos', 'kilograms'),
			'gram' => array('g', 'grams')
	);

	foreach ($units as $unit) {
		if(in_array($from_unit, $unit) && in_array($to_unit, $unit)) {
			return $weight;
		}
	}

	// First, let's convert back to a standardized measurement. We'll use grams.
	switch(strtolower($from_unit)) {
		case 'lbs':
		case 'pounds':
		case 'pound':
		case 'lb':
			$weight *= 453.59237;
			break;
		case 'ounces':
			$weight *= 28.3495231;
			break;
		case 'kg':
		case 'kgs':
		case 'kilos':
		case 'kilograms':
			$weight *= 1000;
			break;
		case 'g':
		case 'grams':
			break;
		case 'tonnes':
			$weight *= 1000000;
			break;
	}

	// Now we're in a standardized measurement, start converting from grams to the unit we need
	switch(strtolower($to_unit)) {
		case 'lbs':
		case 'pounds':
		case 'pound':
		case 'lb':
			$weight *= 0.00220462262;
			break;
		case 'ounces':
			$weight *= 0.0352739619;
			break;
		case 'kg':
		case 'kgs':
		case 'kilos':
		case 'kilograms':
			$weight *= 0.001;
			break;
		case 'g':
		case 'grams':
			break;
		case 'tonnes':
			$weight *= 0.000001;
			break;
	}
	return $weight;
}

function GenerateCouponCode(){
	$len = rand(8, 12);
	$retval = chr(rand(65, 90));
	for ($i = 0; $i < $len; $i++) {
		if (rand(1, 2) == 1) {
			$retval .= chr(rand(65, 90));
		} else {
			$retval .= chr(rand(48, 57));
		}
	}
	return $retval;
}
function get_coupon_by_couponcode($couponcode){
	if($couponcode){
		$options = array(	'metadata_name_value_pairs'	=>	array('coupon_code' => $couponcode),
						'types'				=>	"object",
						'subtypes'			=>	"coupons",
					);
		$coupons = elgg_get_entities_from_metadata($options);
		if($coupons){
			return $coupons[0];
		}
	}
	return false;
}

function get_coupon_uses($coupon_guid){
	global $CONFIG;
	$guid_one = (int)$coupon_guid;
	$relationship = 'coupon_uses';
		
	if ($row = get_data("SELECT * FROM {$CONFIG->dbprefix}entity_relationships WHERE guid_one=$guid_one AND relationship='$relationship' limit 0,999999", "entity_row_to_elggstar")) {
		return $row;
	}
	return false;
}

function generate_tax($price=0, $cart_item='',$country_code = ''){
	global $CONFIG;
	if($CONFIG->allow_tax_method != 1){
		if($CONFIG->allow_tax_method == 2){
			if($country_code){ 
				$options = array(	'metadata_name_value_pairs'	=>	array('tax_country' => $country_code),
								'types'				=>	"object",
								'subtypes'			=>	"addtax_country",
								'limit'				=>	1,
							);
				$taxrate = elgg_get_entities_from_metadata($options);
			}else{
				return 0;
			}	
		}else{
			$options = array('types'			=>	"object",
							'subtypes'			=>	"addtax_common",
							'limit'				=>	1,
						);
			$taxrate = elgg_get_entities($options);
		}
		if($taxrate){
			$taxrate = $taxrate[0];
			$taxrateid = $taxrate->guid;
			$taxrate_name = $taxrate->taxrate_name;
			$tax_rate = $taxrate->taxrate;
			
			if($price > 0){
				$tax_amt = ($price * $tax_rate) / 100;
			}else{
				$price = $cart_item->price;
				$quantity = $cart_item->quantity;
				$tax_amt = ($price * $tax_rate * $quantity) / 100;
			}
		    return $tax_amt;
		}
	}
	return 0;
}

function generate_vat($vat_rate = '',$sub_total = '',$country_rate = ''){
	global $CONFIG;
	if($country_rate == '')
	{
		$vat_amt = ($vat_rate * $sub_total) / 100;
		$tot_amt = $tax_amt + $sub_total ;
	}
	else
	{
		$vat_amt = ($country_rate * $sub_total) / 100;
		$tot_amt = $tax_amt + $sub_total ;
	}
    return $tot_amt;
	return false;
}

function elgg_cart_quantity($entity,$status=false,$status_val=0){
	global $CONFIG;
	if ($entity->guid > 0) {
		if($product = get_entity($entity->product_id))
			$product_price = $product->price;
		if (elgg_get_context() == "confirm") {
			$quantity_box = $entity->quantity;
			$quantity_text = elgg_echo("quantity:available");
		}elseif(elgg_get_context() == "order" || elgg_get_context() == "purchased_products"){
			$quantity_box = $entity->quantity;
			$quantity_text = elgg_echo("quantity:available");
		}else{
			if($CONFIG->loggedin){
				$quantity_box = elgg_view('input/text',array('name' => "cartquantity[{$entity->guid}]",'class'=>"input_quantity", 'value'=>$entity->quantity));
				$quantity_text = elgg_echo("quantity");				
				$options = array('relationship' 		=> 	'cart_related_item',
								'relationship_guid' 	=>	$entity->guid,
								'types'					=>	"object",
								'subtypes'				=>	"cart_related_item",
								'limit'					=>	99999,
								);
				$related_products = elgg_get_entities_from_relationship($options);
				if($related_products){
					$related_product_price = 0;
					foreach($related_products as $related_product){
						$details = $related_product->details;
						if(!is_array($details) && $details != ''){
							$details = array($details);
						}
						foreach($details as $detail){
							$detail = get_entity($detail);
							if($detail){
								$title = $detail->title;
								$detail_price = $detail->price;
								$related_product_price += $detail_price;
								$detail_price_display = get_price_with_currency($detail_price);
								$details_display .= <<<EOF
									<li class="related_detail" id="related_detail_{$detail->guid}">
										<div style="float:left;">{$title}</div>
										<div style="float:left;margin-left:10px;">({$detail_price_display})</div>
										<div class="delete_details_from_cart">
											<a class="delete" href="javascript:void(0);" onClick="delete_detail_from_cart({$related_product->guid},{$detail->guid})">&nbsp;</a>
										</div>	
										<div class="clear"></div>
									</li>
EOF;
							}
						}
					}
					$del_confirm = elgg_echo('delete:related:product:from:cart');
					$action_url = $CONFIG->wwwroot."action/{$CONFIG->pluginname}/manage/related_products";
					$hidden_values = elgg_view('input/securitytoken');
					$related_product_display = <<<EOF
						<script>
							function delete_detail_from_cart(related_product,detail){
								var del_confirm = confirm("{$del_confirm}");
								if(del_confirm){
									var elgg_token = $('[name=__elgg_token]');
									var elgg_ts = $('[name=__elgg_ts]');
									$.post("{$action_url}", {
										related_product: related_product,
										detail:detail,
										u_id: {$_SESSION['user']->guid},
										manage_action: 'delete_detail_from_cart',
										__elgg_token: elgg_token.val(),
										__elgg_ts: elgg_ts.val()
									},
									function(data){
										if(data > 0){
											$("#related_detail_"+detail).html('');
											$("#related_detail_"+detail).css({'display':'none'});
											window.location.reload();
										}else{
											alert(data);
										}
									});
								}
							}
						</script>
						<div id="cart_related_products">
							<div style="font-weight:bold;">Related Products</div>
							<ul>{$details_display}</ul>
						</div>
EOF;
				}
			}elseif ($CONFIG->allow_add_cart == 1 && isset($_SESSION['GUST_CART']) && !empty($_SESSION['GUST_CART'])){
				$quantity_box = elgg_view('input/text',array('name' => "cartquantity[{$entity->guid}]",'class'=>"input_quantity", 'value'=>$entity->quantity));
				$quantity_text = elgg_echo("quantity");
				$related_products = $_SESSION['GUST_CART'][$entity->guid]['related_products'];
				if($related_products){
					$related_product_price = 0;
					foreach($related_products as $related_product=>$details){
						$related_product = get_entity($related_product);
						if(!is_array($details) && $details != ''){
							$details = array($details);
						}
						foreach($details as $detail){
							$detail = get_entity($detail);
							if($detail){
								$title = $detail->title;
								$detail_price = $detail->price;
								$related_product_price += $detail_price;
								$detail_price_display = get_price_with_currency($detail_price);
								$details_display .= <<<EOF
									<li class="related_detail" id="related_detail_{$detail->guid}">
										<div style="float:left;">{$title}</div>
										<div style="float:left;margin-left:10px;">({$detail_price_display})</div>
										<div class="delete_details_from_cart">
											<a class="delete" href="javascript:void(0);" onClick="delete_detail_from_cart({$related_product->guid},{$detail->guid},{$entity->guid})"> </a>
										</div>	
										<div class="clear"></div>
									</li>
EOF;
							}
						}
					}
					$del_confirm = elgg_echo('delete:related:product:from:cart');
					$action_url = $CONFIG->wwwroot."action/{$CONFIG->pluginname}/manage/related_products";
					$hidden_values = elgg_view('input/securitytoken');
					$related_product_display = <<<EOF
						<script>
							function delete_detail_from_cart(related_product,detail,product){
								var del_confirm = confirm("{$del_confirm}");
								if(del_confirm){
									var elgg_token = $('[name=__elgg_token]');
									var elgg_ts = $('[name=__elgg_ts]');
									$.post("{$action_url}", {
										related_product: related_product,
										detail:detail,
										product:product,
										u_id: 'GUST',
										manage_action: 'delete_detail_from_cart',
										__elgg_token: elgg_token.val(),
										__elgg_ts: elgg_ts.val()
									},
									function(data){
										if(data > 0){
											$("#related_detail_"+detail).html('');
											$("#related_detail_"+detail).css({'display':'none'});
											window.location.reload();
										}else{
											alert(data);
										}
									});
								}
							}
						</script>
						<div id="cart_related_products">
							<div style="font-weight:bold;">Related Products</div>
							<ul>{$details_display}</ul>
						</div>
EOF;
				}
			}
		}
		
		$sub_total = $product_price * $entity->quantity;
		if($related_product_price)
			$sub_total += $related_product_price;
		$display_sub_total = get_price_with_currency($sub_total);
		$info = "<div class=\"storesqua_stores\">";
		if($entity->product_type_id == 1){
			$info .= "<b>".elgg_echo("quantity")." :</b> ". $quantity_box;
		}
		$info .= '<span class=\"space\">&nbsp;</span><B>'.elgg_echo("stores:price").' :</B>'. $display_sub_total;
		
		if($status){
			$status = elgg_view("{$CONFIG->pluginname}/product_status",array('status'=>$status_val,'action'=>$status));
		}
		$info .= "</div>";
		
		$price_text = elgg_echo("stores:total");
		$rating = elgg_view("{$CONFIG->pluginname}/view_rating",array('id'=>$entity->product_id,'units'=>5,'static'=>''));
		if($product->status == 1){
			//$tell_a_friend = elgg_view("{$CONFIG->pluginname}/tell_a_friend",array('entity'=>$entity));
			$share_this = elgg_view("{$CONFIG->pluginname}/share_this",array('entity'=>$stores));
		}
		if($product->product_type_id == 1){
			$quantity = "<div style='margin-bottom:5px;'><B>{$quantity_text}:</B> {$quantity_box}</div>";
		}else if($product->product_type_id == 2 && elgg_get_context() == 'purchased_products'){
			$download = "<div class=dproducts_download><p><a href=\"{$CONFIG->wwwroot}action/{$CONFIG->pluginname}/download?product_guid={$entity->guid}\">".elgg_echo("product:download")."</a></p></div>";
		}
		$info = <<<EOF
			<div class="storesqua_stores">
				{$related_product_display}
				<table>
					<tr>
						<td style="width:160px;">
							{$quantity}
							<div><B>{$price_text}:</B> {$display_sub_total}</div>
							<div style="margin-bottom:5px;">{$status}</div>
						</td>
						<td style="width:50px;"></td>
						<td>
							{$rating}
							<div style="padding-top:5px;">
								<div class="cart_wishlist">
									{$share_this}
								</div>
								<div style="float:left;">
									{$download}
								</div>
								<div class="clear"></div>
							</div>
						</td>
					</tr>
				</table>
			</div>		
EOF;
		
		return $info;
	}
}

function calculate_cart_total($cart_user=0,$product_user=0){
	global $CONFIG;
		
	if($CONFIG->loggedin){
		if($cart_user > 0)
			$user_guid = $cart_user;
		else
			$cart_user = $_SESSION['user']->getGUID();
			
		$options = array('types'			=>	"object",
						'subtypes'			=>	"cart",
						'owner_guids'		=>	$cart_user,
					);
		$cart = elgg_get_entities($options);	
		$cart = $cart[0];
		$options = array('relationship' 		=> 	'cart_item',
						'relationship_guid' 	=>	$cart->guid,
						);
		$cart_items = elgg_get_entities_from_relationship($options);
	}elseif ($CONFIG->allow_add_cart == 1 && isset($_SESSION['GUST_CART']) && !empty($_SESSION['GUST_CART'])){
		$cart_items = $_SESSION['GUST_CART'];
	}
	
	if($cart_items){
		$total = 0;
		foreach ($cart_items as $cart_item){
			if(is_array($cart_item)){
				$cart_item = (object) array('product_id'=>$cart_item['product_id'],
											'quantity' => $cart_item['quantity'],
											'amount' => $cart_item['amount'],
											'time_created' => $cart_item['time_created']
											);
			}
			$product_tot = 0;
			if($cart_item->owner_guid > 0 && $product_user > 0){
				if($cart_item->owner_guid == $product_user){
					$quantity = $cart_item->quantity;
					if($product = get_entity($cart_item->product_id)){
						$price = $product->price;
					}
					$product_tot = $price * $quantity;
					$total += $product_tot;
				}
			}else{
				$quantity = $cart_item->quantity;
				if($product = get_entity($cart_item->product_id)){
					$price = $product->price;
				}
				$product_tot = $price * $quantity;
				$total += $product_tot;
			}
			$related_product_price = 0;
			if($CONFIG->loggedin){
				$options = array('relationship' 		=> 	'cart_related_item',
										'relationship_guid' 	=>	$cart_item->guid,
										'types'					=>	"object",
										'subtypes'				=>	"cart_related_item",
										'limit'					=>	99999,
										);
				$related_products = elgg_get_entities_from_relationship($options);
				if($related_products){
					foreach($related_products as $related_product){
						$details = $related_product->details;
						if(!is_array($details) && $details != ''){
							$details = array($details);
						}
						foreach($details as $detail){
							$detail = get_entity($detail);
							if($detail){
								$detail_price = $detail->price;
								$related_product_price += $detail_price;
							}
						}
					}
				}
			}else{
				$related_products = $_SESSION['GUST_CART'][$cart_item->product_id]['related_products'];
				foreach($related_products as $related_product){
					if($related_product){
						foreach($related_product as $detail){
							$detail = get_entity($detail);
							if($detail){
								$detail_price = $detail->price;
								$related_product_price += $detail_price;
							}
						}
					}
				}
				
			}
			$total += $related_product_price;
		}
	}
	
	return $total;
}

// Function to select the latest version from a digital product
function get_latest_version($product_id){
  	if($product_id>0){
  		$options = array(
					'relationship' => 'version_release',
					'relationship_guid' => $product_id,
  					'limit' => 1,							
				);
		$versions = elgg_get_entities_from_relationship($options);
		$version = $versions[0];
		return $version;
  	}
  	return false;
}
  
// Function to upgade the existing version with the multiple version
function upgrade_digitailProduct_Version(){
  	global $CONFIG, $sVersion;
  	$success = false;
  	if($CONFIG->UpgradeSocialcommerce === true){
  		$join = array("
  				JOIN {$CONFIG->dbprefix}metadata n_table2 on e.guid = n_table2.entity_guid 
				JOIN {$CONFIG->dbprefix}metastrings msn2 on n_table2.name_id = msn2.id 
				JOIN {$CONFIG->dbprefix}metastrings msv2 on n_table2.value_id = msv2.id 
				JOIN {$CONFIG->dbprefix}metadata n_table3 on e.guid = n_table3.entity_guid 
				JOIN {$CONFIG->dbprefix}metastrings msn3 on n_table3.name_id = msn3.id 
				JOIN {$CONFIG->dbprefix}metastrings msv3 on n_table3.value_id = msv3.id "
		  	);
		$where = array(
		  			"msn2.string = 'product_type_id' 
					AND BINARY msv2.string = 2 
					AND msn3.string = 'filename' 
					AND BINARY msv3.string != ''"
		  		); 		
  		$options = array(
  			'types' => 'object',
  			'subtypes' => 'stores',
  			'limit'	=> 99999,
  			'joins' => $join,
  			'wheres' => $where,				
  		);
  		$entities = elgg_get_entities($options);	
  		if($entities){
	  		foreach($entities as $entity){
	  			
	  			$access_id = $entity->access_id;	
		  		$version = new ElggObject();
				$version->subtype="digital_product_versions";
				$version->owner_guid = $entity->owner_guid;
				$version->container_guid = $entity->container_guid;
				$version->access_id  = $access_id;
				
				$version->filename = $entity->filename;
				$version->mimetype = $entity->mimetype;
				$version->originalfilename = $entity->originalfilename;
				$version->simpletype = $entity->simpletype;
				$version->version_summary = elgg_echo('socialcommerce:settings:version:upgarde:versionSummary');
				$version->version_release = elgg_echo('socialcommerce:settings:version:upgarde:version');
				$version->status = 1;
				// Generate thumbnail (if image)
				if($entity->filename != ""){
					if (substr_count($entity->mimetype,'image/')){
							$version->thumbnail = $entity->thumbnail;
							$version->smallthumb = $entity->smallthumb;
							$version->largethumb = $entity->largethumb;
					}
				}
				if($version->save()){
					if(!check_entity_relationship($entity->guid,'version_release',$version->guid)){
						add_entity_relationship($entity->guid,'version_release',$version->guid);
					}
					$entity->filename = "";
					$entity->mimetype = "";
					$entity->originalfilename = "";
					$entity->simpletype = "";
					$entity->thumbnail = "";
					$entity->smallthumb = "";
					$entity->largethumb = "";
					$entity->save();
					$success = true;
				}
	  		}
  		}else{
  			$success = true;// For 1st install 
  		}
  		if($success){
	  			set_plugin_setting('upgradeVersion',$sVersion,'socialcommerce');
			return true;
  		}
  	}
  	return false;
}

/* Generate a random password*/
function generate_random_password() {
    $length = 10;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $string = '';    

    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }

    return $string;
}

function create_useraccout_deatils($email){
	global $CONFIG;
	
	// Validate email address
	if (!is_email_address($email)) {
		register_error(elgg_echo('registration:emailnotvalid'));
		return false;
	}
	
	// If we're not allowed multiple emails then see if this address has been used before
	if (get_user_by_email($email)) {
		register_error(elgg_echo('registration:dupeemail'));
		return false;
	}
	$username_arr = explode('@',$email);
	$username_frm_email = $username_arr[0];
	$name = $username_frm_email;
	$username = $username_frm_email;
	// Basic, check length
	if (!isset($CONFIG->minusername)) {
		$CONFIG->minusername = 4;
	}
	if (strlen($username_frm_email) < $CONFIG->minusername) {
		$diff = $CONFIG->minusername - strlen($username_frm_email);
		$start = $diff-1;		
		$start =  pow(10, $start);
		$end = ($start*10)-1;
		$username = $username_frm_email.rand($start,$end);
	}
	
	$blacklist = '/[' .
		'\x{0080}-\x{009f}' . # iso-8859-1 control chars
		'\x{00a0}' .          # non-breaking space
		'\x{2000}-\x{200f}' . # various whitespace
		'\x{2028}-\x{202f}' . # breaks and control chars
		'\x{3000}' .          # ideographic space
		'\x{e000}-\x{f8ff}' . # private use
		']/u';

	if (
		preg_match($blacklist, $username)
	) {
		register_error(elgg_echo('registration:invalidchars'));
		return false;
	}

	// Belts and braces TODO: Tidy into main unicode
	$blacklist2 = '/\\"\'*& ?#%^(){}[]~?<>;|¬`@-+=';
	for ($n=0; $n < strlen($blacklist2); $n++) {
		if (strpos($username, $blacklist2[$n])!==false) {
			register_error(elgg_echo('registration:invalidchars'));
			return false;
		}
	}
	$return_arr = array('name' => $name, 'username'=>$username);
	return  $return_arr;
}	
/* Get the file name*/
function get_mime_type($filename) {
        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.',$filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }
    /*
     * 
     * Category Upgarde
     * */
    function upgrade_categories(){
    global $sVersion;
    $success = true;	    	
	$options = array(
					'types'					=>	'object',
					'subtypes'				=>	'category',
					'limit'					=>	99999,
	);
	
	$categories = elgg_get_entities($options);
	if($categories){				
		foreach ($categories as $category){					
        		if($category->parent_category_id<1){		        		      			
        			$category->parent_category_id = 0;		        			
        			$category->save();
        		}
        	}			    
	}
    if($success){
  			set_plugin_setting('upgradeVersion',$sVersion,'socialcommerce');
		return true;
  	}
}
/*
 *Get all the category
 ***/
function get_categories($parent_id=0,$disabled=0,$product_type_id,$selected=0){
	global $CONFIG;					
	$where = "";
	$metavalue = "";
	$orderby = 	"oe.title ASC";
	$joins = array(" JOIN {$CONFIG->dbprefix}objects_entity oe ON e.guid = oe.guid");			
	/*if($disabled >0){				
		$where .='e.guid != "'.$disabled.'"';
	}*/
	$options = array('metadata_name_value_pairs' => array('product_type_id' => $product_type_id, 'parent_category_id' => $parent_id ),
					 'types'	=>	'object',
					 'subtypes'	=>	'category',
					 'limit'	=>	99999,
					 'wheres'	=>	$where,
					 'joins'	=>	$joins,								
					 'order_by'	=>	$orderby);
	if($parent_id>0){				
		$class = "childclass";
		
	}else{
		$class = "parentclass";
	}			
	
	$category_lists = elgg_get_entities_from_metadata($options);
	if($category_lists){
        foreach ($category_lists as $category_list){
        	$checked = "";
        	if($selected == $category_list->guid){
        		$checked = "checked";
        	}
        	$display_disabled = "";
        	if($category_list->guid == $disabled){
        		$display_disabled = "disabled";
        	}	        		       		
        	$disply_cat.='<li><span><input type = "radio" name="category_selected" '.$checked.' '.$display_disabled.' value="'.$category_list->guid.'">'.$category_list->title.'</input></span>';
        	$child = get_categories($category_list->guid,$disabled,$product_type_id,$selected);
        	if($child!=""){
        		$disply_cat .="<ul>";
        	}	
        	$disply_cat.= $child;
        	if($child){
        		$disply_cat .="</ul>";
        	}
        	$disply_cat.='</li>';
        }
	}
	return $disply_cat;
}
/**
 * 
 * 
 * List all the categories
 * */
function list_categories($parent_id=0, $product_type_id){
	global $CONFIG;					
	$where = "";
	$orderby = 	"oe.title ASC";
	$joins = array(" JOIN {$CONFIG->dbprefix}objects_entity oe ON e.guid = oe.guid");	
	$options = array('metadata_name_value_pairs' => array('product_type_id' => $product_type_id, 'parent_category_id' => $parent_id),
					 'types'	=>	'object',
					 'subtypes'	=>	'category',
					 'limit'	=>	99999,
					 'joins'	=>	$joins,								
					 'order_by'	=>	$orderby,
			 		 'full_view'=> false);
	$symbol= "";				
	$category_lists = elgg_get_entities_from_metadata($options);
	if($category_lists){
        foreach ($category_lists as $category_list){
        	$disply_cat.='<li><span>'.elgg_view_entity($category_list,array('full_view'=>$fullview)).'</span> ';
        	$child = list_categories($category_list->guid,$product_type_id);
        	if($child){
        		$disply_cat .="<ul>";
        	}	
        	$disply_cat.= $child;
        	if($child){
        		$disply_cat .="</ul>";
        	}	
        	$disply_cat.='</li>';	        		
        }
	}
	return $disply_cat;
}

function getall_child($guid,$product_type_id){
	global $all_ids;
	$options = array('metadata_name_value_pairs' => array('parent_category_id' => $guid, 'product_type_id' => $product_type_id),
					'types'		=>	'object',
					'subtypes'	=>	'category',
					'limit'		=>	99999);
	$category_lists = elgg_get_entities_from_metadata($options);
	if($category_lists){
        foreach ($category_lists as $category_list){	        		        		       		
			$all_ids[] = $category_list->guid;
			getall_child($category_list->guid,$product_type_id);
        }
	}
	return $all_ids;
}

function getall_categories($guid){
	global $all_ids;
	$options = array('metadata_name_value_pairs' => array(
														  'parent_category_id' => $guid																
														),
					'types'		=>	'object',
					'subtypes'	=>	'category',
					'limit'		=>	99999,							
					);
	$category_lists = elgg_get_entities_from_metadata($options);
  	if($category_lists){
        foreach ($category_lists as $category_list){	        		        		       		
			$all_ids[$category_list->guid] = $category_list->title;
			getall_child($category_list->guid);
        }
 	}
  	return $all_ids;
}
?>