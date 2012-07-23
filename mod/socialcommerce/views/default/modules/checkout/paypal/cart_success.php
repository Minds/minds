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
	 * Elgg checkout - paypal - success page
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	gatekeeper();
	// Get objects
	
		if(isset($_SESSION['CHECKOUT'])){
			$method = $_SESSION['CHECKOUT']['checkout_method'];
			$function = 'makepayment_'.$method;
			
			// For checking the cart updating or not
			$cart_sucess_load =get_input('cart_sucess_load');
			$success =get_input('success');	
			
			if(function_exists($function)){
				if($cart_sucess_load!=2){
					$success = $function();
				}
				if($success){
					if($cart_sucess_load!=2){
						// For Resubmit beacuse for the proper cart updation Then only the LHS My Cart updation Affet in display
						$redirect =  elgg_add_action_tokens_to_url($CONFIG->wwwroot."action/{$CONFIG->pluginname}/manage_socialcommerce?manage_action=cart_success&cart_sucess_load=2&success={$success}");
						forward($redirect);
						exit();
					}else{					
						$body = elgg_echo('cart:success:content');
						$action = $CONFIG->wwwroot."{$CONFIG->pluginname}/all";
						$btn = elgg_view('input/submit', array('name' => 'btn_submit', 'value' => elgg_echo('checkout:back:text')));
						$area2 = <<< AREA2
							<br>{$body}<br><br>
							<form action="$action" method="post">
								{$btn}
							</form>
AREA2;
					}
				}else{
					$redirect =  elgg_add_action_tokens_to_url($CONFIG->wwwroot."action/{$CONFIG->pluginname}/manage_socialcommerce?manage_action=checkout_error");
					forward($redirect);
				}
				echo $area2;
			}
			unset($_SESSION['CHECKOUT']);
		}else{
			forward($CONFIG->wwwroot."".$CONFIG->pluginname."/".$_SESSION['user']->username."/all");
		}
?>