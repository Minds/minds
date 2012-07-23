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
 * Elgg view - product status
 * 
 * @package Elgg SocialCommerce
 * @author Cubet Technologies
 * @copyright Cubet Technologies 2009-2010
 * @link http://elgghub.com
 */ 
	 
global $CONFIG;
$order_item = $vars['entity'];
$action = $vars['action'];
$order_status = $vars['status'];
$statuses = array(0=>'Pending',1=>'Shipped',2=>'received');
$Status_text = elgg_echo("stores:status");
if(elgg_is_logged_in() && $action == "edit"){
	$offset = get_input('offset');
	if(!$offset)
		$offset = 0;
	$entity_status = $order_item->status;
	foreach ($statuses as $key=>$status){
		if($key == $entity_status){
			$select = "Selected";
		}else{
			$select = "";
		}
		$order_options .= "<option value=\"{$key}\" {$select}>{$status}</option>";
	}
	$action = $CONFIG->wwwroot."action/{$CONFIG->pluginname}/change_order_status";
	$submit_btn = elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('change:status:btn')));
	$securitytoken = elgg_view('input/securitytoken');
	$status_body = <<<EOF
		<span class="order_item_status">
			<div style="margin:10px 0;">
				<B>{$Status_text}:</B> 
				<select name="order_status" id="order_status">
					{$order_options}
				</select>
			</div>
			<div>
				{$submit_btn}
				{$securitytoken}
				<input type="hidden" name="guid" id="guid" value="{$order_item->guid}">
			</div>
		</span>
EOF;
}elseif ($action == "view"){
	if($order_status)
		$order_status = $statuses[$order_status];
	else 
		$order_status = $statuses[0];
	$status_body = <<<EOF
		<span class="order_item_status">
			<span id="status_val_{$order_item->guid}">{$order_status}</span>
		</span>
EOF;
}
echo $status_body;
?>