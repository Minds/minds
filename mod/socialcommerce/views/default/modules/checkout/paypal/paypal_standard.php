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
	 * Elgg checkout - paypal - view page
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	$method = $vars['method'];
	$base = $vars['base'];
	//Depricated function replace
	$options = array(	'metadata_name_value_pairs'	=>	array('checkout_method' => 'paypal'),
					'types'				=>	"object",
					'subtypes'			=>	"s_checkout",
					'limit'				=>	1,
				);
	$settings = elgg_get_entities_from_metadata($options);
	//$settings = get_entities_from_metadata('checkout_method','paypal','object','s_checkout',0,1);
	if($settings){
		$settings = $settings[0];	
	}
	$order = $vars['order'];
	$action = $CONFIG->wwwroot."action/".$CONFIG->pluginname."/manage_socialcommerce";
	$method_view = $method->view;
	$display_name = $settings->display_name;
	if(empty($display_name))
		$display_name = $method->label;
	$stores_paypal_email = $settings->socialcommerce_paypal_email;
	$paypal_environment = $settings->socialcommerce_paypal_environment;
	if(!$paypal_environment)
		$paypal_environment = $base;
?>
<div>
	<div>
		<?php echo elgg_echo('paypal:instructions'); ?>
	</div>
	<div>
		<ul>
			<li><?php echo sprintf(elgg_echo('paypal:instruction1'),'https://www.paypal.com/us/cgi-bin/webscr?cmd=_registration-run'); ?></li>
		   	<li><?php echo elgg_echo('paypal:instruction2'); ?></li>
		</ul>
	</div>
	<div>
		<h4><?php echo elgg_echo('settings'); ?></h4>
		<div>
			<form method="post" action="<?php echo $action; ?>">
				<table class="stores_settings" width="50%" style="float:left;">
					<tr>
						<td style="text-align:right;"><B><span style="color:red;">*</span> <?php echo elgg_echo('display:name'); ?></B></td>
						<td>:</td>
						<td style="text-align:left;"><?php echo elgg_view('input/text',array('name'=>'display_name','value'=>$display_name)); ?></td>
					</tr>
					<tr>
						<td style="text-align:right;"><B><span style="color:red;">*</span> <?php echo elgg_echo('paypal:email'); ?></B></td>
						<td>:</td>
						<td style="text-align:left;"><?php echo elgg_view('input/text',array('name'=>'socialcommerce_paypal_email','value'=>$stores_paypal_email)); ?></td>
					</tr>
					<tr>
						<td style="text-align:right;">
							<B><span style="color:red;">*</span> <?php echo elgg_echo('mode'); ?></B>
						</td>
						<td>:</td>
						<td style="text-align:left;">
							<input type="radio" name="socialcommerce_paypal_environment" value="paypal" <?php if($paypal_environment == "paypal"){ echo "checked = 'checked'";} ?> class="input-radio" />
							<B><?php echo elgg_echo('stores:paypal'); ?></B>
							&nbsp;
							<input type="radio" name="socialcommerce_paypal_environment" value="sandbox" <?php if($paypal_environment == "sandbox"){ echo "checked = 'checked'";} ?> class="input-radio" />
							<B><?php echo elgg_echo('stores:sandbox'); ?></B>
						</td>
					</tr>
					<tr>
						<td colspan="2"></td>
						<td style="text-align:left;">
							<?php echo elgg_view('input/submit', array('name' => 'btn_submit', 'value' => elgg_echo('stores:save')));?>
							<input type='hidden'"' name='method' value="<?php echo $base; ?>">
							<input type='hidden'"' name='manage_action' value="checkout">
							<input type='hidden'"' name='guid' value="<?php echo $settings->guid; ?>">
							<input type='hidden'"' name='order' value="<?php echo $order; ?>">
							<?php echo elgg_view('input/securitytoken'); ?>
						</td>
					</tr>
				</table>
				<div style="float:left;margin:18px 0 0 20px;">
					<img src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/views/default/modules/checkout/paypal/images/paypal_logo.gif">
				</div>
				<div class="clear"></div>
			</form>
		</div>
	</div>
</div>