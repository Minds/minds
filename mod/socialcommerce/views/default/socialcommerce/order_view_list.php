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
 * Elgg view - order list view
 * 
 * @package Elgg SocialCommerce
 * @author Cubet Technologies
 * @copyright Cubet Technologies 2009-2010
 * @link http://elgghub.com
 */ 
global $CONFIG;

$order_item = elgg_extract('order_item', $vars, FALSE);
if($order_item){
	$options = array('relationship'		=> 	'order_item',
					 'relationship_guid'=>	$order_item->guid,
					 'inverse_relationship' => true,
					 'types'			=>	"object",
					 'subtypes'			=>	"order",
					 'limit'			=>	1);
	$order = elgg_get_entities_from_relationship($options);
	if($order){
		$order = $order[0];
		
		$order_datre = date("dS M Y", $order->time_created);
		if($order->s_first_name && $order->s_last_name){
			$order_recipient = elgg_echo('order:recipient').": ".$order->s_first_name." ".$order->s_last_name;
		}else{
			$order_recipient = elgg_echo('order:recipient').": ".$order->b_first_name." ".$order->b_last_name;
		}
	}
		
	$owner = $order_item->getOwnerEntity();
	$owner_link = elgg_view('output/url', array(
		'href' => $CONFIG->pluginname."/owner/$owner->username",
		'text' => $owner->name,
	));
	$author_text = elgg_echo('byline', array($owner_link));
	$date = elgg_view_friendly_time($order_item->time_created);
	
	$subtitle = "$author_text $date";
	
	$total = get_price_with_currency($order_item->quantity * $order_item->price);
	$status = elgg_view("{$CONFIG->pluginname}/product_status",array('entity'=>$order_item,'status'=>$order_item->status,'action'=>'view'));
		
		$excerpt .= <<<EOF
			<div class="storesqua_stores">
				<table>
					<tr>
						<td><B>{$quantity_text}:</B> {$order_item->quantity}</td>
						<td style="width:50px;"></td>
						<td><B>{$price_text}:</B> {$total}</td>
						<td style="width:50px;"></td>
						<td>{$status}</td>
						<td style="width:50px;"></td>
						<td class="more_btn">{$more}</td>
					</tr>
				</table>
				<div id="order_action_outer" class="order"><div id="order_action_bg"></div></div>
			</div>
EOF;
?>
<div class="search_listing">
	<h3><a href="<?php echo $CONFIG->wwwroot.$CONFIG->pluginname."/orderadmin/details/".$order_item->guid;?>"><?php echo sprintf(elgg_echo('order:item:heading'),$order->guid, $order_item->title); ?></a></h3>
	<div class="order_sub_con">
		<div><?php echo $subtitle; ?> </div>
		<div>
			<div class="left span_space" style="padding-left:0;"><?php echo elgg_echo('quantity').": ".$order_item->quantity;?></div>
			<div class="left span_space"><?php echo elgg_echo('price').": ".$total;?></div>
			<div class="left span_space"><?php echo elgg_echo('stores:status').": ".$status;?></div>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php 
}
?>