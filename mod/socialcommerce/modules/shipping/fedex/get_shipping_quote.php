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
	
	require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/engine/start.php");	
	
	global $CONFIG;
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
	}
	if(!$settings || $account_no <= 0){
		echo elgg_echo('notset:fedex:settings');
		exit;
	}
	require_once($CONFIG->path.'mod/'.$CONFIG->pluginname.'/modules/shipping/fedex/fedex.php');
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<script type="text/javascript" src="<?php echo $CONFIG->wwwroot; ?>vendors/jquery/jquery-1.3.2.min.js"></script> 
	<script type="text/javascript" src="<?php echo $CONFIG->wwwroot; ?>vendors/jquery/jquery-ui-1.7.2.custom.min.js"></script>
	<script type="text/javascript" src="<?php echo $CONFIG->wwwroot; ?>vendors/jquery/jquery.form.js"></script>
	<script type="text/javascript" src="<?php echo $CONFIG->wwwroot; ?>_css/js.php?lastcache=<?php echo $CONFIG->lastcache; ?>&js=initialise_elgg&viewtype=<?php echo $vars['view']; ?>"></script>
<?php
	global $pickerinuse;
	if (isset($pickerinuse) && $pickerinuse == true) {
?>
	<!-- only needed on pages where we have friends collections and/or the friends picker -->
	<script type="text/javascript" src="<?php echo $CONFIG->wwwroot; ?>vendors/jquery/jquery.easing.1.3.packed.js"></script>
	<script type="text/javascript" src="<?php echo $CONFIG->wwwroot; ?>_css/js.php?lastcache=<?php echo $CONFIG->lastcache; ?>&js=friendsPickerv1&viewtype=<?php echo $vars['view']; ?>"></script>
<?php
	}
?>
	<!-- include the default css file -->
	<link rel="stylesheet" href="<?php echo $CONFIG->wwwroot; ?>_css/css.css?lastcache=<?php echo $vars['config']->lastcache; ?>&viewtype=<?php echo $vars['view']; ?>" type="text/css" />
	
<?php 
	$address_reload_url = $CONFIG->wwwroot."{$CONFIG->pluginname}/{$_SESSION['user']->username}/country_state";
?>
	<script>
		var type = 'fedex';
		var time_out;
		function find_state_process(type){
			var country = $('#'+type+'_country').val();
			$('#'+type+'_state_list').load("<?php echo $address_reload_url;?>", {type:type,todo:'load_state',country:country,class:'fedex_fields'});
		}
		function find_state(type){
			if(time_out)
				clearTimeout(time_out);
			time_out = setTimeout ("find_state_process('"+type+"')", 600 );
		}
	</script>
</head>
<body style="background:#FFFFFF;">
	<div class="basic">
		<div class="content">
			<div>
				<?php $submit = get_input('btn_submit');
					if(!empty($submit)){
						$delivery_types = get_input('delivery_types');
						$drop_off_type = get_input('drop_off_type');
						$service_types = get_input('service_types');
						$packaging_type = get_input('packaging_type');
						$weight = get_input('weight');
						
						$fedex = new Fedex;
					    $fedex->setServer("https://gatewaybeta.fedex.com/GatewayDC");
					    $fedex->setAccountNumber($account_no);
					    $fedex->setMeterNumber($meter_no);
					    $fedex->setCarrierCode($delivery_types);
					    $fedex->setDropoffType($drop_off_type);
					    $fedex->setService($service_types, $CONFIG->fedex_service_types[$service_types]);
					    $fedex->setPackaging($packaging_type);
					    $fedex->setWeightUnits("LBS");
					    $fedex->setWeight($weight);
					    
					    $fedex->setOriginStateOrProvinceCode("OH");
					    $fedex->setOriginPostalCode(44333);
					    $fedex->setOriginCountryCode("US");
					    
					    $fedex->setDestStateOrProvinceCode("CA");
					    $fedex->setDestPostalCode(90210);
					    $fedex->setDestCountryCode("US");
					    
					    $fedex->setPayorType("SENDER");
					    
					    $price = $fedex->getPrice();
					
				?>
					<div style="margin:0 auto;width:90%;font-size:13px;padding:5px;border:1px solid #cccccc;">
						<?php 
							if(!empty($price->price)){
								$price = $price->price;
								$charges = $price->response['FDXRATEREPLY'][0]['ESTIMATEDCHARGES'][0];
								$currency_code = $charges['CURRENCYCODE'][0]['VALUE'];
								$display = "<div>Service: {$price->service}</div>";
								$display .= "<div>Rate: {$price->rate} {$currency_code}</div>";
							}else if(!empty($price->error)){
								$error = $price->error;
								$error_no = $error->number;
								$error_descr = $error->description;
								$display = "<div style='color:red;'>Error: {$error_no} {$error_descr}</div>";
							}
							echo $display;
						?>
					</div>
				<?php }?>
				<div class="flatrate_item">
					<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
						<table class="stores_settings" style="margin:0 auto;;" width="400">
							<tr>
								<td style="text-align:right;vertical-align:top;"><span style="color:red;">*</span>&nbsp;<?php echo elgg_echo('service:types'); ?></td>
								<td style="vertical-align:top;">:</td>
								<td style="text-align:left;">
									<select class="fedex_fields" name="service_types">
									<?php
										$options = $CONFIG->fedex_service_types;
										foreach ($options as $value=>$display){
											echo "<option label='".$display."'  title='".$display."' value='".$value."'>".$display."</option>";
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
									<select class="fedex_fields" name="delivery_types">
									<?php
										$options = $CONFIG->fedex_delivery_types;
										foreach ($options as $value=>$display){
											echo "<option label='".$display."'  title='".$display."' value='".$value."'>".$display."</option>";
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
							<?php
								$type = 'fedex';
								$selected_country = "USA";
								if($CONFIG->country){
									$country_list = '<select onkeyup="find_state(\''.$type.'\')"  onkeydown="find_state(\''.$type.'\')" onchange="find_state(\''.$type.'\')" name="currency_country" id="'.$type.'_country" class="fedex_fields">';
									foreach ($CONFIG->country as $country){
										if($selected_country == $country['iso3']){
											$selected = "selected";
										}else{
											$selected = "";
										}
										$country_list .= "<option value='".$country['iso3']."' ".$selected.">".$country['name']."</option>";
									}
									$country_list .= "</select>";
									if($selected_country){
										$states = get_state_by_fields('iso3',$selected_country);
										if(!empty($states)){
											$state_list = '<select name="state" id="'.$type.'_state" class="fedex_fields">';
											foreach ($states as $state){
												if($selected_state == $state->name){
													$selected = "selected";
												}else{
													$selected = "";
												}
												$state_list .= "<option value='" . $state->name . "' " . $selected . ">" . $state->name . "</option>";
											}
											$state_list .= '</select>';
										}else{
											$state_list = '<input class="fedex_fields" type="text" value="'.$selected_state.'" id="'.$type.'_state" name="state"/>';
										}
									}
								}else {
									$country_list = '<input class="fedex_fields" type="text" value="'.$selected_country.'" id="'.$type.'_country" name="country"/>';
								}
							?>
							<tr>
								<td style="text-align:right;"><span style="color:red">*</span> <?php echo elgg_echo('country'); ?></td>
								<td>:</td>
								<td>
									<?php echo $country_list; ?>
								</td>
							</tr>
							<tr>
								<td style="text-align:right;"><span style="color:red">*</span> <?php echo elgg_echo('state'); ?></td>
								<td>:</td>
								<td>
									<div id="<?php echo $type; ?>_state_list">
										<?php echo $state_list; ?>
									</div>
								</td>
							</tr>
							<tr>
								<td style="text-align:right;"><span style="color:red;">*</span>&nbsp;<?php echo elgg_echo('postal:code'); ?></td>
								<td>:</td>
								<td style="text-align:left;">
									<div style="float:left;width:20%;"><?php echo elgg_view('input/text',array('name'=>'postal_code','value'=>'')); ?></div>
								</td>
							</tr>
							<tr>
								<td style="text-align:right;"><span style="color:red;">*</span>&nbsp;<?php echo elgg_echo('weight'); ?></td>
								<td>:</td>
								<td style="text-align:left;">
									<div style="float:left;width:20%;"><?php echo elgg_view('input/text',array('name'=>'weight','value'=>'')); ?></div>
									<div style="float:left;margin:5px 0 0 8px;"><b>LBS</b></div>
									<div class="clear"></div>
								</td>
							</tr>
							<tr>
								<td colspan="2"></td>
								<td>
									<?php echo elgg_view('input/submit', array('name' => 'btn_submit', 'value' => elgg_echo('List')));?>
								</td>
							</tr>
						</table>
					</form>
					<div class="clear"></div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</body>
</html>