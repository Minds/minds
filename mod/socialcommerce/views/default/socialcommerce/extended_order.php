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
	 * Elgg oder -extended
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */
	$order = $vars['order'];
	$order_item = $vars['order_item'];
	$product_id = $order_item->product_id;
	$product = get_entity($product_id);
	$diplay_extension = "";
	if($order_item->download_newversion_days>0 && $product->product_type_id == 2){
		$options = array(
							'relationship' => 'version_release',
							'relationship_guid' => $product_id,
							'types'=>'object',
							'subtypes'=>'digital_product_versions',
							'limit'=>1,
						);
		$latest_versions = elgg_get_entities_from_relationship($options);
		$latest_version = $latest_versions[0];		
		$latest_version_avilable = false;
		if($latest_version->guid > $order_item->version_guid){
			$latest_version_avilable = true;
		}
		$created_date = $order_item->time_created;
		$current_date = time();
		$date_diff = $current_date - $created_date;
		$diff = abs($date_diff); 
		$years   = floor($diff / (365*60*60*24)); 
		$months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
		$no_day  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
		
		$remains_offer = $order_item->download_newversion_days - $no_day;
		if($latest_version_avilable === true && $no_day <= $order_item->download_newversion_days){			
			$download_action_url = $CONFIG->wwwroot."action/".$CONFIG->pluginname."/download?product_guid=".$order_item->guid."&version_guid=$latest_version->guid";
			$download_action_url = elgg_add_action_tokens_to_url($download_action_url);
			$download_link = "<a href=\"{$download_action_url}\">".elgg_echo('download:newversion:label:clickhere')."</a>";
			$lan_download = sprintf(elgg_echo('download:newversion:label'),$latest_version->version_release,$download_link);
			if($remains_offer == 1){
				$remain_label =elgg_echo('download:newversion:label:remain');
			}else{
				$remain_label = sprintf(elgg_echo('download:newversion:label:remains'),$remains_offer);
			}
			$diplay_extension = <<<EOF
			<div class="latest_version_outer">
				<div class="latest_version">$lan_download</div>
				<div class="latest_version">$remain_label</div>
			</div>
EOF;
		}
	}
	echo $diplay_extension;