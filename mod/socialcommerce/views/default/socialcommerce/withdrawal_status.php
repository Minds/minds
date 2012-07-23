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
	$wth_requeststatus = $vars['entity'];
	$filter = $vars['filter'];
	$nav = $vars['nav'];
	$class ="contentWrapper";
	$nll_msg = "No Data Found";
	
	if($wth_requeststatus){
		foreach ($wth_requeststatus as $wthdwl_requeststatus){
			
			$wth_request_date = date("d-m-Y",$wthdwl_requeststatus->time_created);
			$wth_request_approval = $wthdwl_requeststatus->approval;
			$wth_request_processed = $wthdwl_requeststatus->processed;
			
			if($wth_request_processed){
       			$status_from =  elgg_echo('processed');
			}else{
				switch($wth_request_approval){
	               	case 1 :
	                   $status_from = elgg_echo('approved');
	                   break;
	               	case 2:
	                   $status_from = elgg_echo('denied');
	                   break;
	               	default:
	                   $status_from = elgg_echo('pending');
	                   break;
	            }
	       	}
	       	$transaction_amount = '';
	       	$transaction = $wthdwl_requeststatus->transaction;
	       	if($transaction){
	       		$transaction = get_entity($transaction);
	       		if($transaction){
	       			$transaction_amount = get_price_with_currency($transaction->amount);
	       		}
	       	}else{
	       		$transaction_amount = '----';
	       	}
			$requeststatus_body .= <<<BODY
				<tr>
					<td style="text-align:center">{$wth_request_date}</td>
					<td style="text-align:center">{$status_from}</td>
					<td style="text-align:right;width:100px;padding-right:10px;">{$transaction_amount}</td>
				</tr>
BODY;
		}
	$date_txt = elgg_echo('requested:date');
	$status = elgg_echo('requested:status');
	$withdraw_amount = elgg_echo('withdraw:amount');
	
	$action = $CONFIG->wwwroot."action/{$CONFIG->pluginname}/manage_socialcommerce";

	$body = <<< WIDGET
		<div class="{$class}">
			<div class="stores">
				{$nav}
				<table id="my_request_table">
					<tr>
						<th>{$date_txt}</th>
						<th>{$status}</th>
						<th>{$withdraw_amount}</th>
					</tr>
					{$requeststatus_body}
				</table>
				{$nav}
			</div>
		</div>
WIDGET;
	echo $body;
	}else{
			$nostatus_text = elgg_echo('wth_request:status:desc');
			$body = <<< WIDGET
				<div class="contentWrapper">
					<div>
					{$nostatus_text}	
					</div>
				</div>
WIDGET;
	echo $body;
	}
?>