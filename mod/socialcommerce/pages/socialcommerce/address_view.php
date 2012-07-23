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
	 * Elgg address - view and reload addresses
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */
	 
	global $CONFIG;
	
	$type = get_input('type');
	if($type == "shipping")
		$checkout_order = 1;
	else 
		$checkout_order = 0;
	
	$todo = get_input('todo');
	switch ($todo){
		case 'reload_checkout_address':
			$added_address = get_input('guid');
			$page_owner = get_input('u_guid');
			if(!$page_owner)
				$page_owner = elgg_get_page_owner_guid();
			
			$options = array('types'			=>	"object",
							'subtypes'			=>	"address",
							'owner_guids'		=>	$page_owner,
						);
			$address = elgg_get_entities($options);
			if($address){
				$exist = elgg_echo($type.':address:exist');
				$exist_address = elgg_view("{$CONFIG->pluginname}/list_address",array('entity'=>$address,'display'=>'list','selected'=>$added_address,'type'=>$type));
				
				$new = elgg_echo($type.':address:new');
				$address_add = elgg_view("{$CONFIG->pluginname}/forms/checkout_edit_address",array('ajax'=>1,'type'=>$type));
				
				$submit_input = elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo($type.':address')));
				$action = $CONFIG->wwwroot."{$CONFIG->pluginname}/checkout_process";
				$address_details = <<<EOF
					<div>
						<form method="post" action="{$action}" onsubmit="return validate_{$type}_details();">
							<div style="margin-bottom:10px;">
								<input id="{$type}_address_exist" name="{$type}_address_type" checked="checked" type="radio" value="existing" onclick="toggle_address_type('{$type}','select');"/> {$exist}
								<div class="select_{$type}_address">
									{$exist_address}
								</div>
							</div>
							<div>
								<input id="{$type}_address_new" name="{$type}_address_type" type="radio" value="add" onclick="toggle_address_type('{$type}','add');"/> {$new}
								<div class="add_{$type}_address" style="display:none;">
									{$address_add}
								</div>
							</div>
							<div>
								{$submit_input}
								<input type="hidden" id="checkout_order" name="checkout_order" value="{$checkout_order}">
							</div>
						</form>
				</div>
EOF;
			}else{
				$address_details = elgg_view("{$CONFIG->pluginname}/forms/edit_address",array('ajax'=>1,'type'=>$type));
			}
			echo $address_details;
			break;
		case 'load_state':
			$country = get_input('country');
			$states = get_state_by_fields('iso3',$country);
			if(!empty($states)){
				$state_list = '<select name="state" id="'.$type.'_state" class="elgg-input-text1">';
				foreach ($states as $state){
					if($selected_state == $state->name){
						$selected = "selected";
					}else{
						$selected = "";
					}
					$state_list .= "<option value='" . $state->name . "' " . $selected . ">" . $state->name . "</option>";
				}
				$state_list .= '</select>';
			}else{
				$state_list = '<input class="elgg-input-text1" type="text" value="'.$selected_state.'" id="'.$type.'_state" name="state"/>';
			}
			echo $state_list;
			break;
		case 'add_myaddress':
			$body = elgg_view("{$CONFIG->pluginname}/forms/checkout_edit_address",array('ajax'=>1,'type'=>'myaccount'));
			echo $body;
			break;
		case 'reload_myaccount_address':
			$user_guid = get_input('u_guid');
			$options = array('types'			=>	"object",
							 'subtypes'			=>	"address",
							 'owner_guids'		=>	$user_guid,
						);
			$address = elgg_get_entities($options);
			if($address){
				$list_address = elgg_view("{$CONFIG->pluginname}/list_address",array('entity'=>$address,'display'=>'list_with_action','selected'=>$selected_address,'type'=>'myaccount'));
				$load_action = $CONFIG->wwwroot."".$CONFIG->pluginname."/view_address"; 
				$body = <<<EOF
					<div>
						<div style="float:right;margin-bottom:10px;">
							<div class="buttonwrapper" style="float:left;">
								<a onclick="add_myaddress();" class="squarebutton"><span> Add New Address </span></a>
							</div>
						</div>
						<div class="clear" style="margin-bottom:10px;">
							{$list_address}
						</div>
					</div>
EOF;
			}else{
				$body = elgg_view("{$CONFIG->pluginname}/forms/checkout_edit_address",array('ajax'=>1,'type'=>'myaccount','first'=>1));
			}
			echo $body;
			break;
		case 'edit_myaddress':
			$address_guid = get_input('a_id');
			if($address_guid){
				$address = get_entity($address_guid);
				if($address){
					$body = elgg_view("{$CONFIG->pluginname}/forms/checkout_edit_address",array('entity'=>$address,'ajax'=>1,'type'=>'myaccount'));
				}else{
					$edit_address_not_posible = elgg_echo('address:edit:not:posible');
					$body = <<<EOF
						<div style="margin:10px;">
							<div>{$edit_address_not_posible}</div>
							<div style="margin:10px;">
								<div class="buttonwrapper" style="float:left;">
									<a onclick="myaccount_cancel_address();" class="squarebutton"><span> Cancel </span></a>
								</div>
								<div class="clear"></div>
							</div>
						</div>
EOF;
				}
			}
			echo $body;
			break;
	}
	exit;
?>