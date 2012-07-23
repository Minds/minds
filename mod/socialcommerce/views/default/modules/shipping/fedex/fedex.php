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
	$options = array(	'metadata_name_value_pairs'	=>	array('shipping_method' => 'fedex'),
					'types'				=>	"object",
					'subtypes'			=>	"s_shipping",
					'limit'				=>	1,
				);
	$settings = elgg_get_entities_from_metadata($options);
	//$settings = get_entities_from_metadata('shipping_method','fedex','object','s_shipping',0,1);
	if($settings){
		$settings = $settings[0];	
		$account_no = $settings->account_no;
		$meter_no = $settings->meter_no;
		$service_types = $settings->service_types;
		$delivery_types = $settings->delivery_types;
		$drop_off_type = $settings->drop_off_type;
		$packaging_type = $settings->packaging_type;
		$rate_type = $settings->rate_type;
	}
	
	$action = $CONFIG->wwwroot."action/".$CONFIG->pluginname."/manage_socialcommerce";
	$method_view = $method->view;
	
?>
<script>
	function open_get_shipping_quote_form(){
		var width  = 500;
		var height = 400;
		var left   = (screen.width  - width)/2;
		var top    = (screen.height - height)/2;
		window.open (
				"<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/modules/shipping/fedex/get_shipping_quote.php",
				"get_shipping_quote",
				"resizable=no,menubar=no,scrollbars=1,status=no,toolbar=no,left="+left+",top="+top+",width="+width+",height="+height
			); 
	}
</script>
<div>
	<div>
		<?php echo elgg_echo('default:shipping:instructions'); ?>
	</div>
	<div style="margin-top:10px;">
		<h4 style="margin-bottom:10px;"><?php echo elgg_echo('fedex:settings'); ?></h4>
		<div>
			<div>
				<div>
					<div class="flatrate_item">
						<form method="post" action="<?php echo $action; ?>">
							<table class="stores_settings" style="float:left;" width="61%">
								<tr>
									<td style="text-align:right;width:150px;"><span style="color:red;">*</span>&nbsp;<?php echo elgg_echo('account:no'); ?></td>
									<td>:</td>
									<td style="text-align:left;"><?php echo elgg_view('input/text',array('name'=>'account_no','value'=>$account_no)); ?></td>
								</tr>
								<tr>
									<td style="text-align:right;"><span style="color:red;">*</span>&nbsp;<?php echo elgg_echo('meter:no'); ?></td>
									<td>:</td>
									<td style="text-align:left;"><?php echo elgg_view('input/text',array('name'=>'meter_no','value'=>$meter_no)); ?></td>
								</tr>
								<tr>
									<td style="text-align:right;vertical-align:top;"><span style="color:red;">*</span>&nbsp;<?php echo elgg_echo('service:types'); ?></td>
									<td style="vertical-align:top;">:</td>
									<td style="text-align:left;">
										<select class="fedex_fields" style="padding:1px;" name="service_types[]" size="5" multiple>
										<?php
											$options = $CONFIG->fedex_service_types;
											foreach ($options as $value=>$display){
											if($service_types != "" || is_array($service_types)){
												if(is_array($service_types)){
														if(in_array($value,$service_types)){
															echo "<option selected label='".$display."'  title='".$display."' value='".$value."'>".$display."</option>";
														}else{
															echo "<option label='".$display."'  title='".$display."' value='".$value."'>".$display."</option>";
														}
													}else{
														if($value == $service_types){
															echo "<option selected label='".$display."'  title='".$display."' value='".$value."'>".$display."</option>";
														}else{
															echo "<option label='".$display."'  title='".$display."' value='".$value."'>".$display."</option>";
														}
													}
												}else{
													echo "<option label='".$display."'  title='".$display."' value='".$value."'>".$display."</option>";
												}
											}
											//echo elgg_view('input/dropdown',array('name'=>'service_types','value'=>$service_types,'options_values'=>$options)); 
										?>
										</select>
									</td>
								</tr>
								<tr>
									<td style="text-align:right;vertical-align:top;"><span style="color:red;">*</span>&nbsp;<?php echo elgg_echo('delivery:types'); ?></td>
									<td style="vertical-align:top;">:</td>
									<td style="text-align:left;">
										<select class="fedex_fields" style="padding:1px;" name="delivery_types[]" size="2" multiple>
										<?php
											$options = $CONFIG->fedex_delivery_types;
											foreach ($options as $value=>$display){
												if($delivery_types != "" || is_array($delivery_types)){
													if(is_array($delivery_types)){
														if(in_array($value,$delivery_types)){
															echo "<option selected label='".$display."'  title='".$display."' value='".$value."'>".$display."</option>";
														}else{
															echo "<option label='".$display."'  title='".$display."' value='".$value."'>".$display."</option>";
														}
													}else{
														if($value == $delivery_types){
															echo "<option selected label='".$display."'  title='".$display."' value='".$value."'>".$display."</option>";
														}else{
															echo "<option label='".$display."'  title='".$display."' value='".$value."'>".$display."</option>";
														}
													}
												}else{
													echo "<option label='".$display."'  title='".$display."' value='".$value."'>".$display."</option>";
												}
											}
											//echo elgg_view('input/dropdown',array('name'=>'delivery_types','value'=>$delivery_types,'options_values'=>$options)); 
										?>
										</select>
									</td>
								</tr>
								<tr>
									<td style="text-align:right;"><span style="color:red;">*</span>&nbsp;<?php echo elgg_echo('drop:off:type'); ?></td>
									<td>:</td>
									<td style="text-align:left;">
										<?php 
											$options = $CONFIG->fedex_drop_off_type;
											echo elgg_view('input/dropdown',array('class'=>'fedex_fields','name'=>'drop_off_type','value'=>$drop_off_type,'options_values'=>$options)); 
										?>
									</td>
								</tr>
								<tr>
									<td style="text-align:right;"><span style="color:red;">*</span>&nbsp;<?php echo elgg_echo('packaging:type'); ?></td>
									<td>:</td>
									<td style="text-align:left;">
										<?php 
											$options = $CONFIG->fedex_packaging_type;
											echo elgg_view('input/dropdown',array('class'=>'fedex_fields','name'=>'packaging_type','value'=>$packaging_type,'options_values'=>$options)); 
										?>
									</td>
								</tr>
								<tr>
									<td style="text-align:right;"><span style="color:red;">*</span>&nbsp;<?php echo elgg_echo('rate:type'); ?></td>
									<td>:</td>
									<td style="text-align:left;">
										<?php 
											$options = $CONFIG->fedex_rate_type;
											echo elgg_view('input/dropdown',array('class'=>'fedex_fields','name'=>'rate_type','value'=>$rate_type,'options_values'=>$options)); 
										?>
									</td>
								</tr>
								<tr>
									<td colspan="2"></td>
									<td>
										<?php echo elgg_view('input/submit', array('name' => 'btn_submit', 'value' => elgg_echo('save')));?>
										<input type='hidden'"' name='method' value="<?php echo $base; ?>">
										<input type='hidden'"' name='manage_action' value="shipping">
										<input type='hidden'"' name='guid' value="<?php echo $settings->guid; ?>">
										<input type='hidden'"' name='order' value="<?php echo $order; ?>">
										<?php echo elgg_view('input/securitytoken'); ?>
									</td>
								</tr>
							</table>
						</form>
						<div style="float:left;margin:5px 0 0 20px;">
							<img src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/views/default/modules/shipping/fedex/images/fedex_logo.gif">
							<div style="margin-top:15px;">
								<a onclick="open_get_shipping_quote_form();" style="padding:5px 15px;font-weight:bold;color:#0054A7;" href="javascript:void(0)"><?php echo elgg_echo('get:shipping:quote');?></a>
							</div>
						</div>
						<div class="clear"></div>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>