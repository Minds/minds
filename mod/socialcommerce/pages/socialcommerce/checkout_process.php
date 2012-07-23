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
	 * Elgg checkout process
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	
	global $CONFIG, $checkout_order;
		
	$page_owner = elgg_get_page_owner_entity();
	if($page_owner->guid != elgg_get_logged_in_user_guid()){
		forward(REFERRER);
	}
	
	// Set stores title
	$title = elgg_echo('checkout:process');
	
	elgg_push_breadcrumb($page_owner->name, $page_owner->getURL());
	elgg_push_breadcrumb(elgg_echo('my:shopping:cart'), $CONFIG->wwwroot.$CONFIG->pluginname."/cart");
	elgg_push_breadcrumb($title);
	
	// Check membership privileges
	$permission = membership_privileges_check('buy');
	if($permission == 1) {
		$checkout_order = get_input('checkout_order');
		if($checkout_order == ""){
			$checkout_order = 0;			
			unset($_SESSION['CHECKOUT']);
			$options = array('types'			=>	"object",
							 'subtypes'			=>	"cart",
							 'owner_guids'		=>	$_SESSION['user']->getGUID(),	
						);
			$cart = elgg_get_entities($options);
			if($cart){
				$cart = $cart[0];
				//Depricated function replace
				$options = array('relationship' 		=> 	'cart_item',
								'relationship_guid' 	=>	$cart->guid);
				$cart_items = elgg_get_entities_from_relationship($options);
				//$cart_items = get_entities_from_relationship('cart_item',$cart->guid);
				if($cart_items){
					foreach ($cart_items as $cart_item){
						$product = get_entity($cart_item->product_id);
						if($_SESSION['CHECKOUT']['allow_shipping'] != 1){
							if($product->product_type_id == 2 || $CONFIG->allow_shipping_method == 2)
								$_SESSION['CHECKOUT']['allow_shipping'] = 0;
							else 
								$_SESSION['CHECKOUT']['allow_shipping'] = 1;
						}
						//if($product->product_type_id == 1){
							$_SESSION['CHECKOUT']['allow_billing'] = 1;
						//}
						$_SESSION['CHECKOUT']['product'][$cart_item->product_id] = (object)array('quantity'=>$cart_item->quantity,'price'=>$cart_item->amount,'type'=>$product->product_type_id);
					}
				}
			}
			if($_SESSION['CHECKOUT']['allow_billing'] != 1){
				$checkout_order = 1;
				if($_SESSION['CHECKOUT']['allow_shipping'] == 0){
					$checkout_order = $checkout_order + 2;
				}
			}
		}else{
			$checkout_confirm = 0;
			$checkout_order = $checkout_order + 1;	
			switch($checkout_order){
				case 1:
					$_SESSION['CHECKOUT']['confirm_billing_address'] = 1;
					if($address_guid = get_input('billing_address_guid')){
						$selected_address = get_entity($address_guid);
						$_SESSION['CHECKOUT']['billing_address'] = (object) array(
							'guid'=>$selected_address->guid,
							'firstname'=>$selected_address->first_name,
							'lastname'=>$selected_address->last_name,
							'address_line_1'=>$selected_address->address_line_1,
							'address_line_2'=>$selected_address->address_line_2,
							'city'=>$selected_address->city,
							'state'=>$selected_address->state,
							'country'=>$selected_address->country,
							'pincode'=>$selected_address->pincode,
							'mobileno'=>$selected_address->mobileno,
							'phoneno'=>$selected_address->phoneno
						);
					}
					if($_SESSION['CHECKOUT']['allow_shipping'] == 0){
						$checkout_order = $checkout_order + 2;
					}
					break;
				case 2: ;
					if($_SESSION['CHECKOUT']['allow_shipping'] == 1){
						$_SESSION['CHECKOUT']['confirm_shipping_address'] = 1;
						if($address_guid = get_input('shipping_address_guid')){
							$selected_address = get_entity($address_guid);
							$_SESSION['CHECKOUT']['shipping_address'] = (object) array(
								'guid'=>$selected_address->guid,
								'firstname'=>$selected_address->first_name,
								'lastname'=>$selected_address->last_name,
								'address_line_1'=>$selected_address->address_line_1,
								'address_line_2'=>$selected_address->address_line_2,
								'city'=>$selected_address->city,
								'state'=>$selected_address->state,
								'country'=>$selected_address->country,
								'pincode'=>$selected_address->pincode,
								'mobileno'=>$selected_address->mobileno,
								'phoneno'=>$selected_address->phoneno
							);
						}
					}
					break;	
				case 3:
					if($_SESSION['CHECKOUT']['allow_shipping'] == 1){
						$_SESSION['CHECKOUT']['confirm_shipping_method'] = 1;	
						$_SESSION['CHECKOUT']['shipping_method'] = get_input('shipping_method');
						$_SESSION['CHECKOUT']['shipping_price'] = get_input('shipping_price');
					}
					break;	
				case 4:
					$_SESSION['CHECKOUT']['confirm_checkout_method'] = 1;	
					$_SESSION['CHECKOUT']['checkout_method'] = get_input('checkout_method');
					break;
				case 5:
					$checkout_confirm = 1;
					$redirect = check_checkout_form();
					break;
			}
		}
		
		//--------- Billing Address Details ----------//
		if($_SESSION['CHECKOUT']['allow_billing'] == 1){
			$billing_details = elgg_view("{$CONFIG->pluginname}/billing_details",array('checkout_order'=>$checkout_order));
			if($_SESSION['CHECKOUT']['confirm_billing_address'] == 1){
				$billing_address_modify = "<span id='checkout_modify_0' class='checkout_modify' onclick='change_modified(0);'>".elgg_echo('checkout:modify')."</span><span style='clear:both'></span>";
			}
		}	
		//--------- Shipping Address Details ----------//
		if($_SESSION['CHECKOUT']['allow_shipping'] == 1 && $_SESSION['CHECKOUT']['confirm_billing_address'] == 1){
			$shipping_details = elgg_view("{$CONFIG->pluginname}/shipping_details",array('checkout_order'=>$checkout_order));
			if($_SESSION['CHECKOUT']['confirm_shipping_address'] == 1){
				$shipping_address_modify = "<span id='checkout_modify_0' class='checkout_modify' onclick='change_modified(1);'>".elgg_echo('checkout:modify')."</span><span style='clear:both'></span>";
			}
		
			//--------- Shipping Methods ----------//
			$shipping_methods = elgg_view("{$CONFIG->pluginname}/list_shipping_methods",array('checkout_order'=>$checkout_order));
			if($_SESSION['CHECKOUT']['confirm_shipping_method'] == 1){
				$shipping_methods_modify = "<span id='checkout_modify_0' class='checkout_modify' onclick='change_modified(2);'>".elgg_echo('checkout:modify')."</span><span style='clear:both'></span>";
			}
		}
		
		//--------- Checkout Methods Details ----------//
		$checkout_method_details = elgg_view("{$CONFIG->pluginname}/list_checkout_methods");
		if($_SESSION['CHECKOUT']['confirm_checkout_method'] == 1){
			$checkout_method_modify = "<span id='checkout_modify_1' class='checkout_modify' onclick='change_modified(3);'>".elgg_echo('checkout:modify')."</span><span style='clear:both'></span>";
		}
		//--------- Order Confirmation ----------//	
		if(isset($_SESSION['CHECKOUT']['checkout_method']) && $_SESSION['CHECKOUT']['checkout_method'] != ""){
			$checkout_plugin = $_SESSION['CHECKOUT']['checkout_method'];
			$order_confirmation_details = elgg_view("{$CONFIG->pluginname}/cart_confirm_list",array('checkout_confirm'=>$checkout_confirm));
			$checkout_checkout_confirm = elgg_echo('checkout:checkout:confirm');
			if($checkout_confirm){
				$check_out_details = <<<EOF
					<h3>
						<a>
							<span class="list1b_icon"></span>
							<B>{$checkout_checkout_confirm}</B>
						</a>
					</h3>
					<div class="ui_content">
						<div class="content">
							{$redirect}
							<div class="clear"></div>
						</div>
					</div>
EOF;
			}
		}
		if($_SESSION['CHECKOUT']['allow_billing'] == 1){
			$billing_class = "";
		}else{
			$billing_class = "class='billing_disable'";
		}
		if($_SESSION['CHECKOUT']['allow_shipping'] == 1){
			$class = "";
		}else{
			$class = "class='shipping_disable'";
		}
		
		
		$no_coupon = elgg_echo('no:coupon:in:couponcode');
		$exp_date = elgg_echo('coupon:exp_date');
		$coupon_maxuses = elgg_echo('coupon:maxuses:limit');
		$coupon_amount_less = elgg_echo('coupon:amount:less');
		$not_applied = elgg_echo('coupon:not_applied');
		$coupon_applied = elgg_echo('coupon:applied');
		$checkout_billing_details = elgg_echo('checkout:billing:details');
		$checkout_shipping_details = elgg_echo('checkout:shipping:details');
		$checkout_shipping_method = elgg_echo('checkout:shipping:method');
		$checkout_checkout_method = elgg_echo('checkout:checkout:method');
		$checkout_order_confirm = elgg_echo('checkout:order:confirm');
		
		$area2 = <<<EOF
			<div class="checkout_process">
				<script type="text/javascript" src="{$CONFIG->wwwroot}mod/{$CONFIG->pluginname}/js/chili-1.7.pack.js"></script>
				<script type="text/javascript" src="{$CONFIG->wwwroot}mod/{$CONFIG->pluginname}/js/jquery.accordion.js"></script>
				<script type="text/javascript">
					$(document).ready(function(){
						jQuery('#list1b').accordion({
							autoheight: false,
							header: 'h3',
							event: '',
							active: {$checkout_order}
						});
					});
					function change_modified(order){
						jQuery('#list1b').accordion("activate",order);
					}
					function toggle_address_type(address,type){
						if(type == 'select') {
							$('.select_'+address+'_address').show();
							$('.add_'+address+'_address').hide();
						}else {
							$('.add_'+address+'_address').show();
							$('.select_'+address+'_address').hide();
						}
					}
					
					function validate_billing_details(){
						var billing_address_type = $("input[name='billing_address_type']:checked").val();
						var billing_address = $("input[name='billing_address_guid']:checked").val();
						if(billing_address_type == "existing"){
							if($.trim(billing_address) == ""){
								alert("Please select one Address");
								return false;
							}
						}else if(billing_address_type == "add"){
							alert("Please Add Address");
							return false;
						}
						return true;
					}
					
					function validate_shipping_details(){
						var shipping_address_type = $("input[name='shipping_address_type']:checked").val();
						var shipping_address = $("input[name='shipping_address_guid']:checked").val();
						if(shipping_address_type == "existing"){
							if($.trim(shipping_address) == ""){
								alert("Please select one Address");
								return false;
							}
						}else if(shipping_address_type == "add"){
							alert("Please Add Address");
							return false;
						}
						return true;
					}
					
					function apply_couponcode(){
						var couponcode = $("#couponcode").val();
						if($.trim(couponcode) == ''){
							$("#coupon_apply_result").html("Please enter the Coupon Code");
							$("#couponcode").focus();
							$("#coupon_apply_result").css({"color":"#9F1313"});
							$("#coupon_apply_result").show();
						}else{
							var elgg_token = $('[name=__elgg_token]');
							var elgg_ts = $('[name=__elgg_ts]');
							$.post("{$CONFIG->wwwroot}action/{$CONFIG->pluginname}/manage_socialcommerce", { 
									code: couponcode,
									manage_action: "coupon_process",
									__elgg_token: elgg_token.val(),
									__elgg_ts: elgg_ts.val()
								},
								function(data){
									data = data.split(",");
									switch(data[0]){
										case 'no_coupon':
												$("#coupon_apply_result").html("{$no_coupon}");
												$("#coupon_apply_result").css({"color":"#9F1313"});
											break;
										case 'exp_date':
												$("#coupon_apply_result").html("{$exp_date}"+data[1]);
												$("#coupon_apply_result").css({"color":"#9F1313"});
											break;
										case 'not_applied':
												$("#coupon_apply_result").html("{$not_applied}");
												$("#coupon_apply_result").css({"color":"#9F1313"});
											break;
										case 'coupon_maxuses':
												$("#coupon_apply_result").html("{$coupon_maxuses}");
												$("#coupon_apply_result").css({"color":"#9F1313"});
											break;
										case 'coupon_amount_less':
												$("#coupon_apply_result").html("{$coupon_amount_less} "+data[1]);
												$("#coupon_apply_result").css({"color":"#9F1313"});
											break;
										case 'coupon_applied':
												$.post("{$CONFIG->wwwroot}action/{$CONFIG->pluginname}/manage_socialcommerce", {
														manage_action: "coupon_reload_process",
														__elgg_token: elgg_token.val(),
														__elgg_ts: elgg_ts.val()
													},
													function(data1){
														if(data1){
															$("#checkout_confirm_list").html(data1);
															$("#coupon_apply_result").html("{$coupon_applied}");
															$("#coupon_apply_result").css({"color":"#099F10"});
															$("#couponcode").val('');
														}
												});
											break;
										default:
												$("#coupon_apply_result").html("Unknown Error");
												$("#coupon_apply_result").css({"color":"#9F1313"});
											break;
									}
									$("#coupon_apply_result").show();
								}
							);
						}
						return false;
					}
				</script>
				<div class="basic" id="list1b">
					<h3 {$billing_class}>
						<a>
							<span class="list1b_icon"></span>
							<B {$billing_class}>{$checkout_billing_details}</B>
							{$billing_address_modify}
						</a>
					</h3>
					<div class="ui_content">
						<div class="content">
							<div id="billing_address">
								{$billing_details}
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
						</div>
					</div>
					
					<h3 {$class}>
						<a>
							<span class="list1b_icon"></span>
							<B {$class}>{$checkout_shipping_details}</B>
							{$shipping_address_modify}
						</a>
					</h3>
					<div class="ui_content">
						<div class="content">
							<div id="shipping_address">
								{$shipping_details}
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
						</div>
					</div>
					
					<h3 {$class}>
						<a>
							<span class="list1b_icon"></span>
							<B {$class}>{$checkout_shipping_method}</B>
							{$shipping_methods_modify}
						</a>
					</h3>
					<div class="ui_content">
						<div class="content">
							<div id="shipping_address">
								{$shipping_methods}
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
						</div>
					</div>
					
					<h3>
						<a>
							<span class="list1b_icon"></span>
							<B>{$checkout_checkout_method}</B>
							{$checkout_method_modify}
						</a>
					</h3>
					<div class="ui_content">
						<div class="content">
							<div id="checkout_methods">
								{$checkout_method_details}
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
						</div>
					</div>
					
					<h3>
						<a>
							<span class="list1b_icon"></span>
							<B>{$checkout_order_confirm}</B>
						</a>
					</h3>
					<div class="ui_content">
						<div class="content">
							<div id="checkout_methods">
								{$order_confirmation_details}
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
						</div>
					</div>
					{$check_out_details}
				</div>
			</div>
EOF;

		} else {
			$area2 .= "<div class='contentWrapper'>".elgg_echo('update:buy')."</div>";
		}
		$area2 = elgg_view("{$CONFIG->pluginname}/storesWrapper",array('body'=>$area2));
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