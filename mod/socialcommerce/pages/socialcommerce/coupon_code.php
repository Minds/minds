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
	 * Elgg view - Coupon Code
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 

	
	global $CONFIG;
	
	// Check Membership Privileges
	$permission = membership_privileges_check('sell');
	if(!$CONFIG->allow_add_coupon_code && !elgg_is_admin_logged_in()){
		forward();
	}
	$page_owner = elgg_get_page_owner_entity();
	if ($page_owner === false || is_null($page_owner)) {
		$page_owner = $_SESSION['user'];
		elgg_set_page_owner_guid($_SESSION['guid']);
	}
		
	elgg_push_breadcrumb(elgg_echo('socialcommerce:settings'), $CONFIG->wwwroot.$CONFIG->pluginname."/settings");
	$title = elgg_echo('coupons');
	elgg_push_breadcrumb($title);
	if($permission == 1) {
		$area2 = elgg_view("{$CONFIG->pluginname}/coupons");
		$area2 .= elgg_view("$CONFIG->pluginname}/extendCouponView");
		$action_page = $CONFIG->wwwroot."{$CONFIG->pluginname}/manage_coupon";
		$delete_action = $CONFIG->wwwroot."action/{$CONFIG->pluginname}/delete_coupon";
		$area2 .= <<<EOF
			<script>
				function add_coupon(){
					$.post('{$action_page}', { manage_case: "add_coupon"},
					  function(data){
					    $("#coupcode_container").html(data);
					});
				
				}
				
				function edit_coupon(guid){
					$.post('{$action_page}', { manage_case: "add_coupon",coupon_guid:guid},
					  function(data){
					    $("#coupcode_container").html(data);
					});
				
				}
				
				function coupon_cancel(){
					$.post('{$action_page}', { manage_case: "cancel"},
					  function(data){
					    $("#coupcode_container").html(data);
					});
				}
				
				function delete_coupon(guid){
					if(confirm("Do you want to remove this coupon code?")){
						var elgg_token = $('[name=__elgg_token]');
						var elgg_ts = $('[name=__elgg_ts]');
						$.post('{$delete_action}', { 
								coupon: guid,
								__elgg_token: elgg_token.val(),
								__elgg_ts: elgg_ts.val()
							},
						  function(data){
						 	if(data == 1)
						 		coupon_cancel();
						 	else
						 		alert(data);
						});
					}
				}
			</script>
EOF;
	} else {
		$area2 = "<div class='contentWrapper'>".elgg_echo('update:sell')."</div>";
	}
	
	$area2 = elgg_view("{$CONFIG->pluginname}/storesWrapper",array('body'=>$area2));
	
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