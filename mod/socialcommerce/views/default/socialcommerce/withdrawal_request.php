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
	 * Elgg view - my account
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global  $CONFIG;
	$wth_request = $vars['entity'];
	$filter = $vars['filter'];
	$nav = $vars['nav'];
	$class ="contentWrapper";
	$nll_msg = "No Data Found";
	
	if($wth_request){
		foreach ($wth_request as $wthdwl_requests){
			$wth_request_date = date("d-m-Y",$wthdwl_requests->time_created);
			$wth_request_approval = $wthdwl_requests->approval;
			$wth_request_processed = $wthdwl_requests->processed;
			$wth_request_decription = $wthdwl_requests->description;
			$wth_request_user=get_entity($wthdwl_requests->owner_guid);
			$wth_request_username=$wth_request_user->name;
			
			$transactions = get_purchased_orders('trans_category','sold_product,withdraw_fund','object','transaction','','','','','','','','',$wth_request_user->guid);
			if($transactions){
				$total_amount = $total_withdraw_amount = 0;
				foreach ($transactions as $teansaction){
					$teansaction = get_entity($teansaction->guid);
					if($teansaction->trans_category == "sold_product"){
						$total_amount += $teansaction->amount;
					}elseif ($teansaction->trans_category == "withdraw_fund"){
						$total_withdraw_amount += $teansaction->amount;
					}
					$total = $total_amount - $total_withdraw_amount;
				}
			}
			$total = get_price_with_currency($total);
			
			if($wth_request_processed){
       			$status_from =  elgg_echo('processed');
       			$balance_display = '';
				$transaction = $wthdwl_requests->transaction;
		       	if($transaction){
		       		$transaction = get_entity($transaction);
		       		if($transaction){
		       			$transaction_amount = get_price_with_currency($transaction->amount);
		       			$balance_display = "<div style='margin:0 0 5px 0;'><b>Withdraw Amount: {$transaction_amount}</b></div>";
		       		}
		       	}
       		}else{
				switch($wth_request_approval){
	               	case 1 :
	                   $status_from = elgg_echo('Approved');
	                   break;
	               	case 2:
	                   $status_from = elgg_echo('Denied');
	                   break;
	               	default:
	                   $status_from = elgg_echo('Pending');
	                   break;
	            }
	            $available_bal_text = elgg_echo('available:balance');
	            $balance_display = "<div style='margin:0 0 5px 0;'><b>{$available_bal_text} : {$total}</b></div>";
	       	}
	       	
			$moreimg = $vars['url'] . "mod/{$CONFIG->pluginname}/graphics/arrow.jpg";
			$wth_requset_guid=$wthdwl_requests->guid;
			
			if($wth_request_approval){
				$approve_class = "with_app_den_success";
				//$app_mor_class = "with_mor_den_success";
				$with_app_js = "";
				$with_den_js = "";
			}else{
				$approve_class = "with_app_den";
				$with_app_js = "onclick='wthdwl_request_approval({$wth_requset_guid})'";
				$with_den_js = "onclick='wthdwl_request_denied({$wth_requset_guid})'";
			}
			$with_mor_js = "onclick='with_more_details({$wth_requset_guid})'";
			$status_text = elgg_echo('status');
			$request_text = elgg_echo('request');
			$request_body .= <<<BODY
				<tr>
					<td style="text-align:center">{$wth_request_date}</td>
					<td><a href="{$wth_request_user->geturl()}">{$wth_request_username}</a></td>
					<td style="text-align:center"><a id="with_app_{$wth_requset_guid}" class="{$approve_class}" href='javascript:void(0);' {$with_app_js} >Approval</a></td>
					<td style="text-align:center"><a id="with_den_{$wth_requset_guid}" class="{$approve_class}" href='javascript:void(0);' {$with_den_js} >Denied</a></td>
					<td style="text-align:center;width:75px;padding:0;" id="more_{$wth_requset_guid}"><a style="display:block;" id="with_mor_{$wth_requset_guid}"  href='javascript:void(0);' {$with_mor_js}>More <img src="{$moreimg}"></a></td>
				</tr>
				<tr id="more_desc_$wth_requset_guid" class="with_more_desc">
					<td colspan="5" style="border-top:0;background:#EFFEFF;padding:6px 0;">
						<div style="position:relative;">
							<div style="width:75px;height:6px;border-right:1px solid #B09B8A;position:absolute;right:-1px;top:-12px;background:#EFFEFF;"></div>
							<div style="width:557px;height:6px;border-bottom:1px solid #B09B8A;border-right:1px solid #B09B8A;position:absolute;left:-1px;top:-12px;"></div>
							<div style="margin:0 0 0 10px;">
								{$balance_display}
								<div style="margin:0 0 5px 0;"><b>{$status_text} :</b> {$status_from}</div>
								<div style="font-weight:bold;">{$request_text}</div>
								<div>{$wth_request_decription}</div>
							</div>
						</div>
					</td>
				</tr>
BODY;
		}
	$date_txt = elgg_echo('date');
	$name_txt = elgg_echo('Name');
	//$amount_txt = elgg_echo('Amount');"<th>{$amount_txt}</th>";"<td style="text-align:center">{$total}</td>";
	$approval_txt = elgg_echo('Approval');
	$denied_txt = elgg_echo('Denied');
	$more_txt = elgg_echo('More');
	
	$action = $CONFIG->checkout_base_url."action/{$CONFIG->pluginname}/manage_socialcommerce";
	$entity_hidden = elgg_view('input/securitytoken');
	$body = <<< WIDGET
		<script>
			function wthdwl_request_approval(wth_requset_guid){
				var elgg_token = $('[name=__elgg_token]');
				var elgg_ts = $('[name=__elgg_ts]');
				$.post("{$action}", {
					wth_requset_id: wth_requset_guid,
					manage_action:'wthdwl_request_approval',
					__elgg_token: elgg_token.val(),
					__elgg_ts: elgg_ts.val()
				}, function(data){
					if(data > 0){
						$("#with_app_"+wth_requset_guid).removeAttr("onclick");
						$("#with_den_"+wth_requset_guid).removeAttr("onclick");
												
						$("#with_app_"+wth_requset_guid).removeClass('with_app_den');
						$("#with_den_"+wth_requset_guid).removeClass('with_app_den');
												
						$("#with_app_"+wth_requset_guid).addClass('with_app_den_success');
						$("#with_den_"+wth_requset_guid).addClass('with_app_den_success');
											
						alert("You have succefully approved the withdrawal payment");
					}else{
						alert(data);
					}
				});
			}
			function wthdwl_request_denied(wth_requset_guid){
				var elgg_token = $('[name=__elgg_token]');
				var elgg_ts = $('[name=__elgg_ts]');
				$.post("{$action}", {
					wth_requset_id: wth_requset_guid,
					manage_action:'wthdwl_request_denied',
					__elgg_token: elgg_token.val(),
					__elgg_ts: elgg_ts.val()
				}, function(data){
					if(data > 0){
						$("#with_app_"+wth_requset_guid).removeAttr("onclick");
						$("#with_den_"+wth_requset_guid).removeAttr("onclick");
												
						$("#with_app_"+wth_requset_guid).removeClass('with_app_den');
						$("#with_den_"+wth_requset_guid).removeClass('with_app_den');
												
						$("#with_app_"+wth_requset_guid).addClass('with_app_den_success');
						$("#with_den_"+wth_requset_guid).addClass('with_app_den_success');
												
						alert("You have succefully denied the withdrawal payment");
					}else{
						alert(data);
					}
				});
			}
			function with_more_details(guid){
				var selected_request = $("#selected_request").val();
				if(selected_request > 0){
					if(selected_request == guid){
						$("#more_desc_"+guid).hide();
						$("#selected_request").val(0);
						
						$("#more_"+guid).css({'background':'none'});
						
					}else{
						$("#more_desc_"+guid).show();
						$("#more_desc_"+selected_request).hide();
						$("#selected_request").val(guid);
						
						$("#more_"+guid).css({'background':'#EFFEFF'});
						$("#more_"+selected_request).css({'background':'none'});
					}
				}else{
					$("#more_desc_"+guid).show();
					$("#selected_request").val(guid);
					
					$("#more_"+guid).css({'background':'#EFFEFF'});
				}
			}
		</script>
		<div class="{$class}">
			<div class="stores">
				{$nav}
				<table id="my_request_table">
					<tr>
						<th>{$date_txt}</th>
						<th>{$name_txt}</th>
						<th>{$approval_txt}</th>
						<th>{$denied_txt}</th>
						<th>{$more_txt}</th>
					</tr>
					{$request_body}
				</table>
				{$nav}
			</div>
			<input type="hidden" id="selected_request" value=0/>
			{$entity_hidden}
		</div>
WIDGET;
	echo $body;
	}else{
			$norequest_text = elgg_echo('wth_request:norequest:desc');
			$body = <<< WIDGET
				<div class="contentWrapper">
					<div>
						{$norequest_text}	
					</div>
				</div>
WIDGET;
	echo $body;
	}
?>