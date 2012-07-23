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
	 * Elgg shipping - default - view page
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	$method = $vars['method'];
	$base = $vars['base'];
	$order = $vars['order'];
	//Depricated function replace
	$options = array(	'metadata_name_value_pairs'	=>	array('shipping_method' => 'default'),
					'types'				=>	"object",
					'subtypes'			=>	"s_shipping",
					'limit'				=>	1,
				);
	$settings = elgg_get_entities_from_metadata($options);
	//$settings = get_entities_from_metadata('shipping_method','default','object','s_shipping',0,1);
	if($settings){
		$settings = $settings[0];	
	}
	
	$action = $CONFIG->wwwroot."action/".$CONFIG->pluginname."/manage_socialcommerce";
	$method_view = $method->view;
	$display_name = $settings->display_name;
	if(!$display_name)
		$display_name = 'Flat Rate Per Item';
	$shipping_per_item = $settings->shipping_per_item;
?>
<div>
	<div>
		<?php echo elgg_echo('default:shipping:instructions'); ?>
	</div>
	<div style="margin-top:10px;">
		<h4 style="margin-bottom:10px;"><?php echo elgg_echo('settings'); ?></h4>
		<div>
			<form method="post" action="<?php echo $action; ?>">
				<div>
					<div>
						<!--<input type="checkbox" name="ship_method" value="flatrate_per_item">--> <B><?php echo elgg_echo('flat:rate:per:item'); ?></B>
						<div class="flatrate_item">
							<table class="stores_settings" width="40%">
								<tr>
									<td style="text-align:right;"><b><span style="color:red;">*</span>&nbsp;<?php echo elgg_echo('display:name'); ?></b></td>
									<td>:</td>
									<td style="text-align:left;"><?php echo elgg_view('input/text',array('name'=>'display_name','value'=>$display_name,'class'=>'shipping_input')); ?></td>
								</tr>
								<tr>
									<td style="text-align:right;"><b><span style="color:red;">*</span>&nbsp;<?php echo elgg_echo('shipping:cost:per:item'); ?></b></td>
									<td>:</td>
									<td style="text-align:left;"><?php echo elgg_view('input/text',array('name'=>'shipping_per_item','value'=>$shipping_per_item,'class'=>'shipping_input')); ?></td>
								</tr>
							</table>
						</div>
					</div>
					<div>
						<?php echo elgg_view('input/submit', array('name' => 'btn_submit', 'value' => elgg_echo('save')));?>
						<input type='hidden'"' name='method' value="<?php echo $base; ?>">
						<input type='hidden'"' name='manage_action' value="shipping">
						<input type='hidden'"' name='guid' value="<?php echo $settings->guid; ?>">
						<input type='hidden'"' name='order' value="<?php echo $order; ?>">
						<?php echo elgg_view('input/securitytoken'); ?>
					</div>
				</div>
				<div class="clear"></div>
			</form>
		</div>
	</div>
</div>