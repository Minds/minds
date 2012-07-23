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
	 * Elgg form - Edit or add coupon
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	if (isset($vars['entity'])) {
		$entity = get_entity($vars['entity']->guid);
		$action = "{$CONFIG->pluginname}/edit_coupon";
		$coupon_code = $entity->coupon_code;
		$coupon_name = $entity->coupon_name;
		$coupon_amount = $entity->coupon_amount;
		$coupon_type = $entity->coupon_type;
		$exp_date = $entity->exp_date;
		if(!empty($exp_date))
			$exp_date = date("d M Y",$exp_date);
		$coupon_min_purchase = $entity->coupon_min_purchase;
		$coupon_maxuses = $entity->coupon_maxuses;
		if($coupon_maxuses == 'Unlimited')
			$coupon_maxuses = 0;
	} else {
		$action = "{$CONFIG->pluginname}/add_coupon";
		$coupon_code = GenerateCouponCode();
		$coupon_name = '';
		$coupon_amount = '';
		$coupon_type = '';
		$exp_date = '';
		$coupon_min_purchase = '';
		$coupon_maxuses = '';
		$access_id = 2;
	}

	// Just in case we have some cached details
	if (isset($vars['coupon'])) {
		$coupon_code = $vars['coupon']['coupon_code'];
		$coupon_name = $vars['coupon']['coupon_name'];
		$coupon_amount = $vars['coupon']['$entity->coupon_amount'];
		$coupon_type = $vars['coupon']['$entity->coupon_type'];
		$exp_date = $vars['coupon']['exp_date'];
		$coupon_min_purchase = $vars['coupon']['coupon_min_purchase'];
		$coupon_maxuses = $vars['coupon']['coupon_maxuses'];
	}
	$action = $vars['url']."action/".$action;
?>
<div>
	<script>
		function validate_coupon_form(){
			var coupon_code = $("#coupon_code").val();
			var coupon_name = $("#coupon_name").val();
			var coupon_amount = $("#coupon_amount").val();
			if(coupon_code == ''){
				alert("Please enter the Coupon Code");
				$("#coupon_code").focus();
				return false;
			}
			if(coupon_name == ''){
				alert("Please enter the Coupon Name");
				$("#coupon_name").focus();
				return false;
			}
			if(coupon_amount == ''){
				alert("Please enter the Discount Amount");
				$("#coupon_amount").focus();
				return false;
			}
			$('#create_coupon_form').submit();
		}
	</script>
	<form id="create_coupon_form" action="<?php echo $action; ?>" enctype="multipart/form-data" method="post">
		<table class="edit_coupon">
			<tr>
				<td class="label">
					<span class="required">*</span> <?php echo elgg_echo('coupon:code');?>:
				</td>
				<td>
					<input type="text" value="<?php echo $coupon_code; ?>" name="coupon_code" id="coupon_code"/>
					<img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('coupon:code'); ?>', '<?php echo elgg_echo('coupon:code:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0">
				</td>
			</tr>
			<tr>
				<td class="label">
					<span class="required">*</span> <?php echo elgg_echo('coupon:name');?>:
				</td>
				<td>
					<input type="text" value="<?php echo $coupon_name; ?>" name="coupon_name" id="coupon_name"/>
					<img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('coupon:name'); ?>', '<?php echo elgg_echo('coupon:name:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0">
				</td>
			</tr>
			<tr>
				<td class="label">
					<span class="required">*</span> <?php echo elgg_echo('coupon:discount');?>:
				</td>
				<td>
					<input type="text" style="width:50px" value="<?php echo $coupon_amount; ?>" name="coupon_amount" id="coupon_amount"/>
					<select style="width: 50px;" name="coupon_type" id="coupon_type">
						<option value="0" <?php if($coupon_type == 0){echo 'selected="selected"';}?> >%</option>
						<option value="1" <?php if($coupon_type == 1){echo 'selected="selected"';}?>><?php echo $CONFIG->default_currency_sign; ?></option>
					</select>
					<img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('coupon:discount'); ?>', '<?php echo elgg_echo('coupon:discount:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0">
				</td>
			</tr>
			<tr>
				<td class="label">
					   <?php echo elgg_echo('coupon:exp:date');?>:
				</td>
				<td>
				<div class="date-outer">
					<?php echo elgg_view('input/scalendar',array('name'=>'exp_date','value'=>$exp_date));?>
					<div style="position:absolute; top:0px; right:0px;"><img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('coupon:exp:date'); ?>', '<?php echo elgg_echo('coupon:exp:date:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0"></div>
				</div>
					
				</td>
			</tr>
			<tr>
				<td class="label">
					   <?php echo elgg_echo('coupon:min:purchase')." (".$CONFIG->default_currency_sign.")";?>:
				</td>
				<td>
					<input type="text" style="width:50px;" value="<?php echo $coupon_min_purchase; ?>" name="coupon_min_purchase" id="coupon_min_purchase"/> 
					<img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('coupon:min:purchase'); ?>', '<?php echo elgg_echo('coupon:min:purchase:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0">
				</td>
			</tr>
			<tr>
				<td class="label">
					   <?php echo elgg_echo('coupon:no:of:users');?>:
				</td>
				<td>
					<input type="text" style="width:50px;" value="<?php echo $coupon_maxuses; ?>" name="coupon_maxuses" id="coupon_maxuses"/>
					<img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('coupon:no:of:users'); ?>', '<?php echo elgg_echo('coupon:no:of:users:help'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0">
				</td>
			</tr>
			<tr>
				<td class="label">
					   <?php echo elgg_echo('coupon:applay:products');?>:
				</td>
				<td>
					<div class="stores_select_box" style="float:left">
						<ul>
							<?php 
							$limit = 9999;
							//Depricated function replace
							$options = array(	'metadata_name_value_pairs'	=>	array('status' => 1),
											'types'				=>	"object",
											'subtypes'			=>	"stores",
											'owner_guids'		=>	elgg_get_page_owner_guid(),						
											'limit'				=>	$limit,
										);
							$users_products = elgg_get_entities_from_metadata($options);
							//$users_products = get_entities_from_metadata('status',1,"object","stores",elgg_get_page_owner_guid(),$limit);
							if($users_products){
								foreach($users_products as $users_product){ 
									$coupon_products = $entity->coupon_products;
									if(!is_array($coupon_products))
										$coupon_products = array($coupon_products);
									if(in_array($users_product->guid,$coupon_products))
										$checked = 'checked="checked"';
									else
										$checked = '';
							?>
									<li>
										<input <?php echo $checked; ?> type="checkbox" value="<?php echo $users_product->guid; ?>" name="coupon_products[]"/><?php echo $users_product->title; ?>
									</li>
							<?php 
								}
							}
							?>
						</ul>
					</div>					
					<img class="help_img" onMouseOut="HideHelp(this);" onMouseOver="ShowHelp(this, '<?php echo elgg_echo('coupon:applay:products'); ?>', '<?php echo elgg_echo('coupon:applies:to:desc'); ?>')" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/images/help.gif" border="0">
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<?php if(isset($vars['entity'])){?>
						<input type="hidden" name="coupon_guid" value="<?php echo $vars['entity']->guid; ?>" />
					<?php }?>
					<table>
						<tr>
							<td>
								<div class="coupon_btn">
									<div class="buttonwrapper">
										<a onclick="coupon_cancel();" class="squarebutton"><span> <?php echo elgg_echo('Cancel'); ?> </span></a>
									</div>
									<div class="clear"></div>
								</div>
							</td>
							<td>
								<div class="coupon_btn">
									<div class="buttonwrapper">
										<a onclick="javascript:validate_coupon_form();" class="squarebutton"><span> <?php echo elgg_echo('save'); ?> </span></a>
									</div>
									<div class="clear"></div>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?php echo elgg_view('input/securitytoken'); ?>
	</form>
</div>