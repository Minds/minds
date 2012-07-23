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
	 * Elgg category - view
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	
	$manage_case = get_input('manage_case');
	
	switch ($manage_case){
		case "add_coupon":
				$coupon_guid = get_input('coupon_guid');
				if($coupon_guid){
					$coupon = get_entity($coupon_guid);
				}
				$form_view = elgg_view("{$CONFIG->pluginname}/forms/edit_coupon",array('entity'=>$coupon));
				$out = <<<EOF
					<div id="coupon_edit_view">
						<div class="coupon_contents" id="coupon_contents">
							{$form_view}
						</div>
					</div>	
EOF;
				echo $out;
			break;
		case "cancel":
				$list_coupon = elgg_view("{$CONFIG->pluginname}/list_coupon");
				$create_btn_text = elgg_echo('coupon:code:new:btn');
				$out = <<<EOF
					<div id="coupon_list_view">
						<div>
							{$list_coupon}
						</div>
						<div class="coupon_btn">
							<div class="buttonwrapper">
								<a onclick="add_coupon();" class="squarebutton"><span> {$create_btn_text} </span></a>
							</div>
							<div class="clear"></div>
						</div>
						<div class="clear"></div>
					</div>
EOF;
				echo $out;
			break;
	}
	exit;
?>