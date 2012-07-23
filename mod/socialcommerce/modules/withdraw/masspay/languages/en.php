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
	 * Elgg masspay withdraw - language
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	$english = array(
		'mass:pay' => "Mass Pay",
		'paypal:mass_pay:instructions' => "To integrate PayPal MassPay into your withdraw section you need to follow a few simple steps, which are shown below: ",
		'paypal:mass_pay:instruction1' => '<a target="_blank" class="ui_desc_link" href="%s">Register for a free PayPal account here</a>',
		'paypal:mass_pay:instruction2' => 'Fill in the other details below',
		'settings' => 'Settings',
		'display:name' => 'Display Name',
		'paypal:email:id' => 'PayPal Email ID',
		'paypal:api:usernaem' => 'PayPal API Username',
		'paypal:api:password' => 'PayPal API Password',
		'paypal:api:signature' => 'PayPal API Signature',
		'stores:paypal' => 'PayPal',
		'stores:sandbox' => 'Sandbox',
		'mode' => 'Mode',
		'amount' => 'Amount',
		
		'error:email:null' => 'Please Enter your PayPal Email ID',
		'error:email:not:valid' => 'Please Enter a valid Email ID',
		'error:amount:null' => 'Please Enter the amount',
		'error:amount:not:valid' => 'Please Enter a valid Amount',
		'error:amount:not:allow' => 'Sorry, you cannot withdraw this amount. Your account have only %s',
		
		'mass:pay:transaction:success' => 'Thank you! Your transaction was successfull',
		
		'mass:pay:transaction:failed' => 'Sorry! Your transaction was failed. Please try after some times.<br><B>Error From PayPal</B>%s',
		
		'not:fill:masspay:settings' => 'You have selected MassPay for Withdrawal. To integrate this method into your store you should fill the settings in <B>Withdraw</B> tab.',
		'masspay:missing:fields' => 'You have selected MassPay for Shipping. You are missing the following fields in this method. Your should fill the following fields in <B>Withdraw</B> tab.<br/>%s',
		
		'withdraw:masspay:help' => 'Mass Payment allows you to withdraw money money from social commerce account.',
	);
	
	add_translation("en",$english);
?>