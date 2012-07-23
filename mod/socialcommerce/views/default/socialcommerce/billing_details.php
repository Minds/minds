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
	 * Elgg view - billing details
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	//Depricated function replace
		$options = array('types'			=>	"object",
						'subtypes'			=>	"address",
						'owner_guids'		=>	elgg_get_page_owner_guid(),
					);
		$address = elgg_get_entities($options);
	if($address){
		if($_SESSION['CHECKOUT']['billing_address'])
			$selected_address = $_SESSION['CHECKOUT']['billing_address']->guid;
		$exist = elgg_echo('billing:address:exist');
		$exist_address = elgg_view("{$CONFIG->pluginname}/list_address",array('entity'=>$address,'display'=>'list','selected'=>$selected_address,'type'=>'billing'));
		
		$new = elgg_echo('billing:address:new');
		$address_add = elgg_view("{$CONFIG->pluginname}/forms/checkout_edit_address",array('ajax'=>1,'type'=>'billing'));
		
		$submit_input = elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('billing:address')));
		$action = $CONFIG->checkout_base_url."{$CONFIG->pluginname}/checkout_process";
		
		$address_details = <<<EOF
			<div>
				<form method="post" action="{$action}" onsubmit="return validate_billing_details();">
					<div style="margin-bottom:10px;">
						<input id="billing_address_exist" name="billing_address_type" checked="checked" type="radio" value="existing" onclick="toggle_address_type('billing','select');"/> {$exist}
						<div class="select_billing_address">
							{$exist_address}
						</div>
					</div>
					<div>
						<input id="billing_address_new" name="billing_address_type" type="radio" value="add" onclick="toggle_address_type('billing','add');"/> {$new}
						<div class="add_billing_address" style="display:none;">
							{$address_add}
						</div>
					</div>
					<div>
						{$submit_input}
						<input type="hidden" id="checkout_order" name="checkout_order" value="0">
					</div>
				</form>
			</div>
EOF;
	}else{
		$address_details = elgg_view("{$CONFIG->pluginname}/forms/checkout_edit_address",array('ajax'=>1,'type'=>'billing'));
	}
	
	echo $address_details;
?>