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
	 * Elgg view - withdraw
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global  $CONFIG;
	$ts = time();
	$teansactions = $vars['entity'];
	$filter = $vars['filter'];
	$method = get_input('method');
	if(isset($_SESSION['WITHDRAW']) && empty($method)){
		unset($_SESSION['WITHDRAW']);
	}
	$token = "?__elgg_token=".generate_action_token($ts)."&__elgg_ts={$ts}";
	$action = $CONFIG->checkout_base_url."action/{$CONFIG->pluginname}/manage_socialcommerce{$token}";
	
	if($teansactions){
		$total_amount = $total_holding_amount = 0;
		$total_withdraw_amount = 0;
		foreach ($teansactions as $teansaction){
				$teansaction = get_entity($teansaction->guid);
			
			if($CONFIG->holding_days > 0){
				$teansaction_date = $teansaction->time_created;
				$withdraw_holding_date = strtotime("+".$CONFIG->holding_days." days",$teansaction_date);
				if($withdraw_holding_date > time()){
					if($teansaction->trans_category == "sold_product"){
						$total_holding += $teansaction->amount;
					}elseif ($teansaction->trans_category == "withdraw_fund"){
						$total_holding_withdraw_amount += $teansaction->amount;
					}
					continue;
				}
			}
			if($teansaction->trans_category == "sold_product"){
				$total_amount += $teansaction->amount;
				
			}elseif ($teansaction->trans_category == "withdraw_fund"){
				$total_withdraw_amount += $teansaction->amount;
			}
		}
		$total = $total_amount - $total_withdraw_amount;
		$total_holding_amount = $total_holding - $total_holding_withdraw_amount;
	}
	/*$total = 1;*/
	if(!$total)
		$total = 0;
	/*----------- For display withdraw method -----------------*/	
	if($total >= $CONFIG->min_withdraw_amount || elgg_is_admin_logged_in()){
		//Depricated function replace
		$options = array('types'			=>	"object",
						'subtypes'			=>	"splugin_settings",
					);
		$splugin_settings = elgg_get_entities($options);	
		//$splugin_settings = get_entities('object','splugin_settings');
		$passed_withdraw_method = get_input('method');
		
		if($splugin_settings){
			$splugin_settings = $splugin_settings[0];
			$selected_withdrawmethods = $splugin_settings->fund_withdraw_methods;
			
			if(!is_array($selected_withdrawmethods))
				$selected_withdrawmethods = array($selected_withdrawmethods);
			
			$withdraw_methods = get_fund_withdraw_methods();
			foreach ($selected_withdrawmethods as $selected_withdrawmethod){
				//Depricated function replace
				$options = array(	'metadata_name_value_pairs'	=>	array('withdraw_method' => $selected_withdrawmethod),
								'types'				=>	"object",
								'subtypes'			=>	"s_withdraw",					
								'limit'				=>	1,
								
							);
				$withdraw_settings = elgg_get_entities_from_metadata($options);
				//$withdraw_settings = get_entities_from_metadata('withdraw_method',$selected_withdrawmethod,'object','s_withdraw',0,1);
				
				if($withdraw_settings)
					$withdraw_settings = $withdraw_settings[0];
				
					$display_name = $withdraw_settings->display_name;
				
				if(!$display_name)
					$display_name = $withdraw_methods[$selected_withdrawmethod]->label;
				
					if($selected_withdrawmethod == $passed_withdraw_method){
					$checked = "checked='checked'";
				}else{
					$checked = "";
				}
				
				$action_gatekeepper = elgg_view('input/securitytoken');
				$method_display .= <<<EOF
				{$action_gatekeepper}
					<div style='padding:5px;'>
						<input onclick="show_withdraw_settings('{$selected_withdrawmethod}')" type='radio' name='withdraw_method' value='{$selected_withdrawmethod}' {$checked}> 
						<span style='margin-left:5px;'>{$display_name}</span>
					</div>
					<div id="withdraw_method_settings"></div>
					<div>
						<!-- <input type="submit" name="btn_withdraw" value="Withdraw"> -->
						<div style="text-align:center;">
							<div class="buttonwrapper">
								<a style="color:#000000;" onclick="withdraw_fund();" class="squarebutton"><span> Withdraw </span></a>
							</div>
						</div>
						<input type="hidden" name="total_amount" id="total_amount" value="{$total}">
						<input type="hidden" name="selected_method" id="selected_method" value="">
						<input type="hidden" name="manage_action" value="withdraw_action">
					</div>
					<div style="clear:both;"></div>
EOF;
			}
			$withdraw_method_text = elgg_echo('withdraw:methods');
			$withdraw_method = <<<EOF
				<div style="margin:10px 0">
					<B>{$withdraw_method_text}</B>
				</div>
				<div>
					<form name="form_withdraw" id="form_withdraw" method="post" action="{$action}">
						{$method_display}
					</form>
				</div>			
EOF;
		}
	}
	/*----------- For display Request section -----------------*/
	$request_text = elgg_echo('withdraw:request:head');
	$btn = elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('withdraw:request:send'), 'style'=>"margin-left:10px;margin-top:15px;"));
	$status_request =  	<<< WIDGET
		<div><b>{$request_text}</b></div>
		<form onsubmit="return validate_request();" name="form_request" id="form_request" method="post" action="{$action}">
			<div style="margin-top:15px;">
				<div style="float:left;">
					<textarea rows="2" cols="50" name="request_desc" id="request_desc"></textarea>
				</div>
				<div>
					{$btn}
					<input type="hidden" name="total_useramt" value="{$total}">
					<input type="hidden" name="manage_action" value="withdraw_request">
				</div>
				<div style="clear:both;"></div>
			</div>
		</form>
WIDGET;
	switch($CONFIG->withdraw_option){
		/*----------- For Moderation -----------------*/
		case 'moderation':
				$withdraw_conditions = sprintf(elgg_echo('moderation:condition'),$CONFIG->holding_days);
				$withdraw_conditions .= sprintf(elgg_echo('withdraw:amount:moderation'),get_price_with_currency($total),get_currency_name());
				if(!elgg_is_admin_logged_in()){
					if($total >= $CONFIG->min_withdraw_amount){
						//Depricated function replace
						$options = array('types'			=>	"object",
										'subtypes'			=>	"wth_request",
										'owner_guids'		=>	$_SESSION['user']->guid,						
										'limit'				=>	1,
									);
						$wth_request = elgg_get_entities($options);	
						//$wth_request = get_entities('object','wth_request',$_SESSION['user']->guid,'','1');
						if($wth_request ){
							if($wth_request[0]->processed){
				       			$body = $status_request;
				       			$withdraw_conditions .= elgg_echo('moderation:withdraw:request:allow');
							}else{
								switch($wth_request[0]->approval){
				               		case 1:
				                       $body = $withdraw_method;
				                       $withdraw_conditions .= elgg_echo('moderation:withdraw:amount:allow');
									   break;
				               		case 2:
				                       $body = $status_request;
				                       $withdraw_conditions .= elgg_echo('moderation:withdraw:request:allow');
									   break;
				               		default:
				                       $withdraw_conditions .= elgg_echo('status:pending:request');
									   break;
				       			}
							}
						}else{
							$body = $status_request;
							$withdraw_conditions .= elgg_echo('moderation:withdraw:request:allow');
						}
					}else{
						$withdraw_conditions .= elgg_echo('moderation:withdraw:amount:less');
					}
				}else{
					if($total > 0)
						$body = $withdraw_method;
					else
						$body = elgg_echo('no:balance:withdrow');
				}
			break;
		/*----------- For Escrow -----------------*/
		case 'escrow':
				if($CONFIG->holding_days > 0){
					$withdraw_conditions = sprintf(elgg_echo('escrow:condition'),$CONFIG->holding_days);
				}
				$withdraw_conditions .= sprintf(elgg_echo('withdraw:amount:escrow'),get_price_with_currency($total),get_currency_name(),get_price_with_currency($total_holding_amount),get_currency_name());
				if(!elgg_is_admin_logged_in()){
					if($total >= $CONFIG->min_withdraw_amount){
						$withdraw_conditions .= elgg_echo('escrow:withdraw:amount:allow');
						$body = $withdraw_method;
					}else
						$withdraw_conditions .= elgg_echo('escrow:withdraw:amount:less');
				}else{
					if($total > 0)
						$body = $withdraw_method;
					else
						$body = elgg_echo('no:balance:withdrow');
				}
			break;
		/*----------- For Moderation with Escrow -----------------*/
		case 'moderation_escrow':
				if($CONFIG->holding_days > 0){
					$withdraw_conditions = sprintf(elgg_echo('moderation_escrow:condition'),$CONFIG->holding_days);
				}
				$withdraw_conditions .= sprintf(elgg_echo('withdraw:amount:escrow'),get_price_with_currency($total),get_currency_name(),get_price_with_currency($total_holding_amount),get_currency_name());
				if(!elgg_is_admin_logged_in()){
					if($total >= $CONFIG->min_withdraw_amount){
						//Depricated function replace
						$options = array('types'			=>	"object",
										'subtypes'			=>	"wth_request",
										'owner_guids'		=>	$_SESSION['user']->guid,						
										'limit'				=>	1,
									);
						$wth_request = elgg_get_entities($options);	
						//$wth_request = get_entities('object','wth_request',$_SESSION['user']->guid,'','1');
						if($wth_request ){
							if($wth_request[0]->processed){
				       			$body = $status_request;
				       			$withdraw_conditions .= elgg_echo('moderation:withdraw:request:allow');
							}else{
								switch($wth_request[0]->approval){
				               		case 1:
				                       $body = $withdraw_method;
				                       $withdraw_conditions .= elgg_echo('moderation:withdraw:amount:allow');
									   break;
				               		case 2:
				                       $body = $status_request;
				                       $withdraw_conditions .= elgg_echo('moderation:withdraw:request:allow');
									   break;
				               		default:
				                       $withdraw_conditions .= elgg_echo('status:pending:request');
									   break;
				       			}
							}
						}else{
							$body = $status_request;
							$withdraw_conditions .= elgg_echo('moderation:withdraw:request:allow');
						}
					}else
						$withdraw_conditions .= elgg_echo('escrow:withdraw:amount:less');
				}else{
					if($total > 0)
						$body = $withdraw_method;
					else
						$body = elgg_echo('no:balance:withdrow');
				}
			break;
		/*----------- For Instants -----------------*/
		default:
				$withdraw_conditions = sprintf(elgg_echo('withdraw:amount:instant'),get_price_with_currency($total),get_currency_name());
				if($total >= $CONFIG->min_withdraw_amount){
					$withdraw_conditions .= elgg_echo('instant:withdraw:amount:allow');
					$body = $withdraw_method;
				}else
					$withdraw_conditions .= elgg_echo('instant:withdraw:amount:less');
			break;
	}
	if(elgg_is_admin_logged_in())
		$withdraw_conditions .= elgg_echo('withdraw:amount:admin');
	
	$condition_head_text = elgg_echo('withdraw:condition:head');
	$min_amount_condition = sprintf(elgg_echo('min:amount:condition'),get_price_with_currency($CONFIG->min_withdraw_amount),get_currency_name());
	/*----------- Main Body -----------------*/
	$body = <<<EOF
		<script>
			var passed_withdraw_method = '{$passed_withdraw_method}';
			if($.trim(passed_withdraw_method) != ""){
				show_withdraw_settings(passed_withdraw_method);
			}
			function validateWithdrawRequest(){
				if ($("input[name='withdraw_method']").is(":checked")){
					return true;
				}else{
					alert("Please choose one withdraw method.");
					return false;
				}
				var method = $("#selected_method").val();
				var function_name ="validate_" + method;
				return window[function_name]()
			}
			function show_withdraw_settings(method){
				var post_action = "{$action}";
				var window_width = $(document).width();
				var window_height = $(document).height();
				var scroll_pos = (document.all)?document.body.scrollTop:window.pageYOffset;
				scroll_pos = scroll_pos  + 300;
				$("#load_action").show();
				$("#load_action").css({'width':window_width+'px','height':window_height+'px'});
				$("#load_action_div").css("top",scroll_pos+"px");
				$("#load_action_div").css({'width':window_width+'px'});
				$("#load_action_div").show();
				var elgg_token = $('[name=__elgg_token]');
				var elgg_ts = $('[name=__elgg_ts]');
				$.post(post_action, {
					method: method,
					manage_action: 'show_wsettings'
				},
				function(data){
					$("#selected_method").val(method);
					$("#withdraw_method_settings").html(data);
					$("#load_action").hide();
					$("#load_action_div").hide();
				});
			}
			function withdraw_fund(){
				if(validateWithdrawRequest()){
					$("#form_withdraw").submit();
				}
			}
			function validate_request(){
				if($('#request_desc').val() == ""){ 
					alert("Please enter your request message to site owner.");
					$('#request_desc').focus();
					return false;
				}
			}
		</script>
		<div class="contentWrapper">
			<div class="withdraw">
				<div class="condition_head">{$condition_head_text}</div>
				<ul>
					{$min_amount_condition}
					{$withdraw_conditions}
				</ul>
				<div>
					{$body}
				</div>
			</div>
		</div>
EOF;
echo $body;
?>