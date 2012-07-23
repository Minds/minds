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
	 * Elgg form - confirm cart lists
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	if(elgg_is_logged_in()){
		if($vars['not_allow'] == 1){
			$hidden = "<input type=\"hidden\" name=\"not_allow\" value=\"1\">";
			$action = "#";
		}else{
			$action = $CONFIG->checkout_base_url."{$CONFIG->pluginname}/checkout_process";
		}
		$submit_input = elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('check:out')));
	}elseif ($CONFIG->allow_add_cart == 1 && isset($_SESSION['GUST_CART']) && !empty($_SESSION['GUST_CART'])){
		$action = "#";
		//$java_function = 'onsubmit="return checkout_error_report();"';
		$alert = elgg_echo('checkout:with:nologin');
		$post_url = $CONFIG->wwwroot."action/".$CONFIG->pluginname."/manage_socialcommerce";
		//$redirect_url = $CONFIG->wwwroot."".$CONFIG->pluginname."/login";
		$redirect_url = $CONFIG->wwwroot."".$CONFIG->pluginname."/checkout_account/";
		$current_page = current_page_url();
		$hidden .= "<input type=\"hidden\" name=\"forward_url\" value=\"{$current_page}\">";
		$java_script = <<<EOF
			<script>
				function checkout_error_report(){
					var elgg_token = $('[name=__elgg_token]');
					var elgg_ts = $('[name=__elgg_ts]');
					$.post("{$post_url}", {
						url:'{$current_page}',
						manage_action: 'set_checkout_session',
						__elgg_token: elgg_token.val(),
						__elgg_ts: elgg_ts.val()
					},function(data){
						$("#checkout_form").attr("action","{$redirect_url}");
						$("#checkout_form").submit();
					});
				}
			</script>
EOF;
		$value = elgg_echo('check:out');
		$submit_input = elgg_view('input/button', array('name' => 'submit_btn', 'value' => $value, 'onclick'=>'checkout_error_report()'));
	}
	
	$byu_more = elgg_echo('buy:more');
	$hidden_values = elgg_view('input/securitytoken');
	$buy_more_link = $CONFIG->wwwroot.$CONFIG->pluginname."/all";
	$form_body = <<< BOTTOM
		{$java_script}
		<form method="post" name="checkout_form" id="checkout_form" action="{$action}" {$java_function}>
			<div class="content_area_user_bottom">
				<div class="bottom_content">
					<span class="buy_more"><a href="$buy_more_link">$byu_more</a></span>
					<span>$submit_input</span>&nbsp;
					<span class="space"></span>
					{$hidden}
				</div>
			</div>
		</form>
BOTTOM;
echo $form_body;
?>