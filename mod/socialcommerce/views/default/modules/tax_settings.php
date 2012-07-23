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
	 * Elgg modules - currency methods
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 

	global $CONFIG;
	$taxrate_name == '';
	$taxrateid == '';
	$taxrate_val == '';
	$cntry_tax_rate = '';
	$cntry_tax_name = '';
	$country_guid = $_SESSION['getcontry_id'];
	$taxrate_cnty = get_entity($country_guid);
	?>
	<script type="text/javascript">

	function awardto(){
		$('#show_load').show();
		var na = '';
		var codes = $('#tax_country').val();
		var urls="<?php echo $CONFIG->wwwroot; ?>action/socialcommerce/contry_tax";
		var paramdata="code="+codes+"&__elgg_token="+$('[name=__elgg_token]').val()+"&__elgg_ts="+$('[name=__elgg_ts]').val();
		$.ajax({
		   type: "POST",
		   url: urls,
		   data:paramdata,
		   success: function(data) {
				$('#show_load').hide();
				if(!isNaN(data)){
			   		$('#tax_rate').val(data);
			   	}
			   	else
			   	{
			   		$('#tax_rate').val(na);
			   	}    
		   }
		});
	}


	</script>
	<?php 
	if(!empty($taxrate_cnty)) {
	 	 $taxrate_name_cnty = $taxrate_cnty->tax_country;
	 	 $cntry_tax_rate = $taxrate_cnty->taxrate;
	}
	//Depricated function replace
	$options = array('types'			=>	"object",
					'subtypes'			=>	"addtax_common",
					'limit'				=>	1,
				);
	$taxrate = elgg_get_entities($options);
	//$taxrate = get_entities('object','addtax_common',0,'',1);
	foreach($taxrate as $taxrates)
	{
		$taxrateid = $taxrates->guid;
		$taxrate_name = $taxrates->taxrate_name;
		$taxrate_val = $taxrates->taxrate;
	}

	$allow_tax_method = '';
	$style = '';
	$action = $CONFIG->pluginname."/addcountry_tax";
	$action1 = $CONFIG->pluginname."/addcommon_tax";
	$settings = $vars['entity'];
	if($settings){
		$settings = $settings[0];
		$allow_tax_method = $settings->allow_tax_method;
	}
	if($allow_tax_method == '2')
	{
		$style3 = "style = 'display:none;'";
	}
	if($allow_tax_method == '3')
	{
		$style2 = "style = 'display:none;'";
	}
	?>

   	<div class="basic">
		<h4 class="tax_head"><?php echo elgg_echo('tax:rate:details'); ?></h4>
		<form action="<?php echo $vars['url']; ?>action/<?echo $action;?>" method="POST" onSubmit="return chkform()">
			<div <?php echo $style2; ?>>
				<table class="stores_settings content" width="90%">
					<!--<tr>
						<td><B><span style="color:red;">*</span> <?php echo elgg_echo('tax:settings:name'); ?></B></td>
						<td width="5%">:</td>
						<td ><input class="elgg-input-text" type="text" size="34" name="tax_name" value="<?php echo $cntry_tax_name; ?>"></td>
					</tr>
						--><tr>
						<td ><B><span style="color:red;">*</span> <?php echo elgg_echo('tax:settings:basedon'); ?></B></td>
						<td width="5%">:</td>
						<td >
							<select name="based_on">
							<option value="1">Subtotal</option>
							</select>
						<input type="hidden" name="taxrate_country_id" value = "<?php echo $taxrateid_cnty ;?>" > 
						</td>
					</tr>
					<tr>
						<td><B><span style="color:red;">*</span> <?php echo elgg_echo('tax:settings:applyto'); ?></B></td>
						<td width="10%">:</td>
						<td >
							<select name="tax_country" id="tax_country"  onChange = "javascript:awardto()">
								<?php 
									if($CONFIG->country){
										echo "<option>-Select Country-</option>";
										foreach ($CONFIG->country as $country){
											if($taxrate_name_cnty == $country['iso3']){
												$selected = "selected";
											}else{
												$selected = "";
											}
											echo "<option value='".$country['iso3']."' ".$selected.">".$country['name']."</option>";
										}	
									}
								?>
							</select>
						</td>
					</tr>
								
					<tr>
						<td><B><span style="color:red;">*</span> <?php echo elgg_echo('tax:settings:rate'); ?></B></td>
						<td width="5%">:</td>
						<td height="65px" ><input class="elgg-input-text" type="text"  name="tax_rate" id="tax_rate" style="width:50px;" value="<?php echo $cntry_tax_rate; ?>">%<div style="display:none; padding:0px 0px 0px 120px;" align="left" id="show_load">
					<img src="<?php echo $CONFIG->wwwroot.'mod/'.$CONFIG->pluginname?>/graphics/ajax-loader.gif"></img>
					</div></td>
						
					</tr>
								
					<tr>
						<td >
							<?php echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('save')));?>
						</td>
						<td ></td>
					</tr>
					
				</table>
				<?php echo elgg_view('input/securitytoken'); ?>
				<div style="display:none" align="center" id="show_load">
					<img src="<?php echo $CONFIG->wwwroot.'mod/'.$CONFIG->pluginname?>/graphics/ajax-loader.gif"></img>
					</div>
			</div>
		</form>
		<div id="outerDiv"></div>
		<form action="<?php echo $vars['url']; ?>action/<?echo $action1;?>" method="POST" onSubmit="return chkforms()">
			<div <?php echo $style3; ?>>
				<table class="stores_settings content" width="80%">
					<tr>
						<td><B><span style="color:red;"></span> <?php echo elgg_echo('tax:settings:rate:name'); ?></B></td>
						<td width="5%">:</td>
						<td ><input style="width:200px;" class="elgg-input-text" type="text" size="34" name="taxrate_name" value="<?php echo $taxrate_name; ?>"></td>
					</tr>
						<tr>
						<td><B><span style="color:red;">*</span> <?php echo elgg_echo('tax:settings:basedon'); ?></B></td>
						<td width="5%">:</td>
						<td >
							<select  name="tax_basedon">
							<option value="1">Subtotal</option>
							</select>
								<input type="hidden" name="taxrate_id" value = "<?php echo $taxrateid ;?>" > 
						</td>
					</tr>
					
					<tr>
						<td><B><span style="color:red;">*</span> <?php echo elgg_echo('tax:settings:rate'); ?></B></td>
						<td width="5%">:</td>
						
						<td ><input style="width:40px;" class="elgg-input-text" type="text" size="3" name="taxrate" id="taxrate" value="<?php echo $taxrate_val; ?>">%</td>
					</tr>
					
					<tr>
						<td >
							<?php echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('save')));?>
						</td>
						<td ></td>
					</tr>
					
				</table>
				<?php echo elgg_view('input/securitytoken'); ?>
			</div>
		</form>
	</div>