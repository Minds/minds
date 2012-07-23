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
	$teansactions = $vars['entity'];
	$filter = $vars['filter'];
	$nav = $vars['nav'];
	$class ="contentWrapper";
	$nll_msg = elgg_echo("my:account:no:data:found");
	$colspan = 6;
	if($filter == "transactions"){
		$action = $CONFIG->wwwroot."action/{$CONFIG->pluginname}/sold_price";
		
		$Script_J= <<< SCRIPTJ
		<script language="javascript" type="text/javascript">
			function product_sold_price_details(guid){
				var paramdata="Guid="+guid+"&__elgg_token="+$('[name=__elgg_token]').val()+"&__elgg_ts="+$('[name=__elgg_ts]').val();
				$.ajax({
					   type: "POST",
					   url: "$action",
					   data:paramdata,
					   success: function(data) {
					   $("#div_product_sold_price_details").html(data);		
					   handle_div(guid);			   
						}
				});
			}
	
			function divhide(){
				$('#div_product_sold_price_details').hide();
				$("#load_action").hide();
			}
			 function findPosX(obj)
			  {
			    var curleft = 0;
			    if(obj.offsetParent)
			        while(1) 
			        {
			          curleft += obj.offsetLeft;
			          if(!obj.offsetParent)
			            break;
			          obj = obj.offsetParent;
			        }
			    else if(obj.x)
			        curleft += obj.x;
			    return curleft;
			  }
			
			  function findPosY(obj)
			  {
			    var curtop = 0;
			    if(obj.offsetParent)
			        while(1)
			        {
			          curtop += obj.offsetTop;
			          if(!obj.offsetParent)
			            break;
			          obj = obj.offsetParent;
			        }
			    else if(obj.y)
			        curtop += obj.y;
			    return curtop;
			  }
	
			function handle_div(guid){
			if(document.getElementById('td_'+guid))
				var Leftpos = findPosX(document.getElementById('td_'+guid));
			if(document.getElementById('td_'+guid))
				var TopPos = findPosY(document.getElementById('td_'+guid));
			
			if(divwidth==0)
				divwidth =500;
				
			var window_width = $(document).width();
			var window_height = $(document).height();
			var scroll_pos = (document.all)?document.body.scrollTop:window.pageYOffset;
			scroll_pos = scroll_pos  + 300;
			$("#load_action").show();
			$("#load_action").css({'width':window_width+'px','height':window_height+'px'});
				
			$('#div_product_sold_price_details').show();
			var divwidth = document.getElementById('div_product_sold_price_details').offsetWidth;
			$("#div_product_sold_price_details").css('left',Leftpos-divwidth);
			$("#div_product_sold_price_details").css('top',TopPos);	
		}
	</script>
SCRIPTJ;
		echo $Script_J;
			
	}
	
	if($teansactions){
		$grand_total = 0;
			//Depricated function replace
		$options = array('types'			=>	"object",
						'subtypes'			=>	"splugin_settings",
						'limit'				=>	99999,
					);
		$settings = elgg_get_entities($options);	
		//$settings = get_entities('object','splugin_settings',0,'',9999);
		if($settings){
			$settings = $settings[0];
		}
		foreach ($teansactions as $teansaction){
			if($filter == "transactions"){
				$teansaction = get_entity($teansaction->guid);
			}
			$amount = $teansaction->amount;
			$transaction_date = date("d-m-Y",$teansaction->time_created);
			$title = elgg_echo($teansaction->title);
			$item_title = "";
			if($teansaction->order_item_guid){
				$oreder_item = get_entity($teansaction->order_item_guid);
				if($oreder_item && $oreder_item->product_id){
					$product = get_entity($oreder_item->product_id);
					$product_owner = get_user($product->owner_guid);
					if($product){
						$product_url = $product->getURL();
						$p_title = trim($product->title);
						if(strlen($p_title) > 20){
							$p_title = substr($p_title,0,20)."...";
						}
						$item_title = "<a href=\"{$product_url}\">{$p_title}</a>";
					}
				}
			}
			if(empty($item_title)){
				$item_title = "...";
			}
			$display_amount = get_price_with_currency($amount);
			if($filter == "fee"){
				$display_payment_fee = get_price_with_currency($teansaction->payment_fee);
				$grand_total += $payment_fee;
				$td = "<td style=\"text-align:right;\">{$display_amount}</td>";
				$td .= "<td style=\"text-align:right;\">{$display_payment_fee}</td>";
			}else{
				//$stores_percentage = $settings->socialcommerce_percentage;
				$stores_percentage = $CONFIG->socialcommerce_percentage;
				$site_fee = number_format((($teansaction->total_amount * $stores_percentage)/100), 2, '.', '');
				$net_amount = $site_fee + $amount;
				$grand_total += $amount;
				if($teansaction->trans_type == "debit"){
					$sign = "-";
				}else{
					$sign = "";
				}
				$display_net_amount = get_price_with_currency($net_amount);
				$display_site_fee = get_price_with_currency($site_fee);
				if($teansaction->trans_category == "withdraw_fund"){
					$td = "<td id=\"td_{$teansaction->guid}\" style=\"text-align:right;\">{$sign}{$display_amount}</td>";
				}else{
					$td = "<td id=\"td_{$teansaction->guid}\" style=\"text-align:right;\"><a href=\"javascript:product_sold_price_details({$teansaction->guid})\">{$sign}{$display_amount}</a></td>";
				}				
				//$td .= "<div id=\"div_product_sold_price_details\" class=\"sold_product_price_list\"></div>";
				// This moved to the js/socialcommerce.js file
			}
			
			$order_body .= <<<BODY
				<tr>
					<td>{$transaction_date}</td>
					<td>{$title}</td>
					<td>{$item_title}</td>
					{$td}
				</tr>
BODY;
		}
	}else{
		$order_body = "<td colspan=\"{$colspan}\" style=\"text-align:center;font-weight:bold;padding:15px;\">{$nll_msg}</td>";
	}
	if($filter == "fee"){
		$th = "<th>".elgg_echo('amount')."</th>";
		$th .= "<th>".elgg_echo('paypal:fee')."</th>";
	}else{
		$th .= "<th>".elgg_echo('trans:total')."</th>";
	}
	if($nav){
		$nav = "<div class=\"search_listing\">{$nav}</div>";
	}
	$date_txt = elgg_echo('date');
	$title_txt = elgg_echo('title');
	$product_txt = elgg_echo('product');
	$body = <<< WIDGET
		<div class="{$class}">
			<div class="stores">
				{$nav}
				<table id="my_account_table" style="border-collapse:collapse;">
					<tr>
						<th>{$date_txt}</th>
						<th>{$title_txt}</th>
						<th>{$product_txt}</th>
						{$th}
					</tr>
					{$order_body}
				</table>
				{$nav}
			</div>
		</div>
WIDGET;
	echo $body;
	echo elgg_view('input/securitytoken');
?>