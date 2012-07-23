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
	 * Elgg Authorize.net checkout - language
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	$english = array(
		'authorizenet' => "Authorize.net",
                /*
                <li>Choose 'Yes' from the test mode dropdown box below to make a test purchase in test mode</li>
                <li>When everything appears to be working change test mode to 'No' to accept live payments from your store</li>
                 */
		'authorizenet:instructions' => "To integrate Authorize.net into your store you need to follow a few simple steps, which are shown below:<br>
										<ul style='list-style-type:circle;'>
											<li><a style='text-decoration:underline;padding:0;margin:0;background-image:none;display:inline;' href='http://www.authorize.net/solutions/merchantsolutions/merchantinquiryform/'>Register for an Authorize.net merchant account here</a></li>
											<li>Type the API login ID you received from Authorize.net into the 'API login ID' field below</li>
											<li>Login to your Authorize.net account and generate a transaction key from the Settings -> Security -> Obtain Transaction Key link</li>
											<li>Copy the transaction key that you generated into the 'Transaction Key' field below</li>
                                                                                        <li>To make the purchase in test mode, login to your Authorize.net account and '<b>Test ON</b>' in Settings -> Test Mode </li>
                                                                                        <li>When everything appears to be working change test mode to '<b>Test OFF</b>' to accept live payments from your store.</li>
                                                                                        <li>Also choose '<b>No</b>' from the test account options below to accept live payments in live account. </li>
										</ul>",
		'settings' => 'Settings',
		'mode' => "Mode",
		'display:name' => "Display Name",
		'api:login:id' => "API Login ID",
		'transaction:key' => 'Transaction Key',
		'transaction:type' => 'Transaction Type',
		'transaction:yes' => 'Yes',
		'transaction:no' => 'No',
		'require:card:code' => 'Require Card Code',
		'test:account' => 'Test Account',
		'stores:sandbox' => 'Sandbox',
		'not:fill:Authorize.net:settings' => 'You have selected Authorize.net Website Payments (Standard) for Checkout. To integrate Authorize.net Website Payments (Standard) into your store you should fill the settings in <B>Checkout</B> tab.',
		'missing:fields' => 'You have selected Authorize.net Website Payments (Standard) for Checkout. You are missing the following fields in Authorize.net Website Payments (Standard). Your should fill the following fields in <B>Checkout</B> tab.<br/>%s',
		'checkout:authorizenet:help' => 'This is the one type of checkout method',

                /*
                * Authorize.net payment details
                */
                'Authorize.net Details' => 'Authorize.net Details',
                'Payment Information' => 'Payment Information',
                'Credit Card Number' => 'Credit Card Number:',
                'Security Code (CVV)' => 'Security Code (CVV):',
                'Expiration Date' => 'Expiration Date:',
                'Billing Information' => 'Billing Information',
                'First Name' => 'First Name:',
                'Last Name' => 'Last Name:',
                'Billing Address 1' => 'Billing Address 1:',
                'Billing Address 2' => 'Billing Address 2:',
                'City' => 'City:',
                'State' => 'State:',
                'Zip Code' => 'Zip Code:',

                'checkout:back:text' => "Go To Home",
                'cart:success:authorizecontent' => "Your order was completed successfully.<br> Authorize.net transaction has approved.<br> A confirmation email has been sent to your email address.",
                'cart:cancel:authorizecontent' => "Sorry, Your purchase has been canceled.<br> Authorize.net transaction has declined.<br> Please try after some times and check your authorize.net settings",

                'approval:code' => "Approval Code:",
                'transaction:id' => "Transaction ID:",

	);
					
	add_translation("en",$english);
?>