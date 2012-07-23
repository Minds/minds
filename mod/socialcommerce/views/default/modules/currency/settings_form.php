<?PHP
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
	 * Elgg currency - view page
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	$status = $vars['status'];
	$entity = $vars['entity'];
	if($entity){
		$currency_name = $entity->currency_name;
		$currency_country = $entity->currency_country;
		$currency_code = $entity->currency_code;
		$exchange_rate = $entity->exchange_rate;
		$currency_token = $entity->currency_token;
		$token_location = $entity->token_location;
		$decimal_token = $entity->decimal_token;
		$default = $entity->set_default;
		if($default == 1){
			$exchange_rate = 1;
			$disable = 'disabled';
		}else {
			$disable = '';
		}
	}else{
		$currency_name = '';
		$currency_country = '';
		$currency_code = 'USD';
		if($status == 'default'){
			$exchange_rate = 1;
			$disable = 'disabled';
			$default = 1;
		}else {
			$exchange_rate = '';
			$disable = '';
			$default = 0;
		}
		$currency_token = '$';
		$token_location = 'left';
		$decimal_token = 2;
	}
?>
<h3><?php echo elgg_echo('currency:details'); ?></h3>
<div>
	<table class="currency content" width="100%">
		<tr>
			<td style="text-align:right;width:140px;"><B><span style="color:red;">*</span> <?php echo elgg_echo('currency:name'); ?></B></td>
			<td>:</td>
			<td style="width:140px;" style="text-align:left;"><?php echo elgg_view('input/text',array('name'=>'currency_name','value'=>$currency_name)); ?></td>
			<td style="width:50%"></td>
		</tr>
		<tr>
			<td style="text-align:right;"><B><span style="color:red;">*</span> <?php echo elgg_echo('currency:country'); ?></B></td>
			<td>:</td>
			<td style="text-align:left;">
			<select name="currency_country" class="elgg-input-text">
				<?php 
					if($CONFIG->country){
						foreach ($CONFIG->country as $country){
							if($currency_country == $country['iso3']){
								$selected = "selected";
							}else{
								$selected = "";
							}
							echo "<option value='".$country['iso3']."' ".$selected.">".$country['name']."</option>";
						}	
					}
				?>
			</select>
			<?php /* echo elgg_view('input/text',array('name'=>'currency_country','value'=>$currency_country)); */ ?></td>
			<td style="width:50%"></td>
		</tr>
		<tr>
			<td style="text-align:right;"><B><span style="color:red;">*</span> <?php echo elgg_echo('currency:code'); ?></B></td>
			<td>:</td>
			<td style="text-align:left;"><?php echo elgg_view('input/text',array('name'=>'currency_code','value'=>$currency_code)); ?></td>
			<td style="width:50%"></td>
		</tr>
		<tr>
			<td style="text-align:right;"><B><span style="color:red;">*</span> <?php echo elgg_echo('exchange:rate'); ?></B></td>
			<td>:</td>
			<td style="text-align:left;"><?php echo elgg_view('input/text',array('name'=>'exchange_rate','value'=>$exchange_rate,'disabled'=>$disable)); ?></td>
			<td>
				<?php if($default != 1 || $status != 'default'){ ?>
					<div class="buttonwrapper" style="position:relative;top:5px;float:left;">
						<a onclick="get_exchange_rate();" class="squarebutton"><span> <?php echo elgg_echo('get:exchange:rate'); ?></span></a>
					</div>
					<span><img id="run_exchange_rate" style="position:relative;left:10px;top:5px;display:none;" src="<?php echo $CONFIG->wwwroot."mod/".$CONFIG->pluginname; ?>/images/working.gif"> </span>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td style="text-align:right;width:140px;"><B><span style="color:red;">*</span> <?php echo elgg_echo('currency:token'); ?></B></td>
			<td>:</td>
			<td style="text-align:left;"><?php echo elgg_view('input/text',array('name'=>'currency_token','value'=>$currency_token)); ?></td>
		</tr>
		<tr>
			<td style="text-align:right;width:140px;"><B><span style="color:red;">*</span> <?php echo elgg_echo('token:location'); ?></B></td>
			<td>:</td>
			<td style="text-align:left;">
				<?php echo elgg_view('input/dropdown',array('name'=>'token_location','value'=>$token_location,'options_values'=>array('left'=>'Left','right'=>'Right'))); ?>
			</td>
		</tr>
		<tr>
			<td style="text-align:right;"><B><span style="color:red;">*</span> <?php echo elgg_echo('decimal:token'); ?></B></td>
			<td>:</td>
			<td style="text-align:left;"><?php echo elgg_view('input/text',array('name'=>'decimal_token','value'=>$decimal_token)); ?></td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td style="text-align:left;">
				<div>
					<div class="buttonwrapper" style="float:left;">
						<a onclick="save_currency_settings();" class="squarebutton"><span> <?php echo elgg_echo('stores:save'); ?> </span></a>
					</div>
					<?php if($default == 0 || $status != 'default'){ ?>
						<div class="buttonwrapper">
							<a onclick="cancel_currency_settings();" class="squarebutton"><span> <?php echo elgg_echo('stores:cancel'); ?> </span></a>
						</div>
					<?php } ?>
				</div>
				<input type='hidden'"' id='manage_action' name='manage_action' value="add_currency">
				<input type='hidden'"' name='guid' value="<?php echo $entity->guid; ?>">
				<input type='hidden'"' name='order' value="<?php echo $order; ?>">
				<input type='hidden'"' name='set_default' value="<?php echo $default; ?>">
				<?php echo elgg_view('input/securitytoken'); ?>
			</td>
		</tr>
	</table>
</div>