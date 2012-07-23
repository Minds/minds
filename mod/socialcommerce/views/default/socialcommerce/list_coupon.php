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
 * Elgg view - caet page
 * 
 * @package Elgg SocialCommerce
 * @author Cubet Technologies
 * @copyright Cubet Technologies 2009-2010
 * @link http://elgghub.com
 */
global $CONFIG;
$baseurl = $CONFIG->wwwroot."{$CONFIG->pluginname}/coupon/";
$options = array('types'			=>	"object",
				'subtypes'			=>	"coupons",
				'owner_guids'		=>	$_SESSION['user']->guid,
				'count'				=>	TRUE,
			);
$count = elgg_get_entities($options);

$offset = get_input('offset');
if(!$offset)
	$offset = 0;
$limit = 10;
$nav = elgg_view('navigation/pagination',array(
								'baseurl' => $baseurl,
								'offset' => $offset,
								'count' => $count,
								'limit' => $limit
										));

$options = array('types'			=>	"object",
				'subtypes'			=>	"coupons",
				'owner_guids'		=>	$_SESSION['user']->guid,
				'limit'				=>	$limit,
				'offset'			=>	$offset,
			);
$coupons = elgg_get_entities($options);										

if($coupons){
	$coupon_list = "";
	foreach($coupons as $coupon){
		if($coupon->coupon_type != 1)
			$amount = $coupon->coupon_amount."%";
		else
			$amount = $CONFIG->default_currency_sign.$coupon->coupon_amount;
		if($coupon->exp_date)
			$exp_date = date("d M Y",$coupon->exp_date);
		else
			$exp_date = '';
		$coupon_list .= <<<EOF
			<tr>
				<td>{$coupon->coupon_name}</td>
				<td>{$coupon->coupon_code}</td>
				<td>{$amount}</td>
				<td>{$exp_date}</td>
				<td>{$coupon->coupon_maxuses}</td>
				<td>
					<a onclick="edit_coupon({$coupon->guid});" class="coupon_edit"> </a>
					<a onclick="delete_coupon({$coupon->guid});" class="coupon_delete"> </a>
				</td>
			</tr>
EOF;
	}
}else{
	$coupon_list = '<tr><td colspan="6">'.elgg_echo("no:coupon").'</td></tr>';
}
?>

<div class="list_coupons">
	<?php echo $nav; ?>
	<table>
		<tr>
			<th><?php echo elgg_echo('coupon:name');?></th>
			<th><?php echo elgg_echo('coupon:code');?></th>
			<th><?php echo elgg_echo('coupon:discount');?></th>
			<th><?php echo elgg_echo('coupon:exp:date');?></th>
			<th><?php echo elgg_echo('coupon:no:of:users');?></th>
			<th></th>
		</tr>
		<?php echo $coupon_list; ?>
	</table>
	<?php echo elgg_view('input/securitytoken'); ?>
</div>