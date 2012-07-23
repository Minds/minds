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
	 * Elgg cart - view
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */

	$action_checkout_regi = $CONFIG->wwwroot."action/socialcommerce/checkout/register";
	$action_login =  $CONFIG->wwwroot."action/login";
	
	// Get all Cart Item For check any physical product exists
	$cart_items = $_SESSION['GUST_CART'];
	$flag_address_deatils = false;
	if($cart_items){		
		foreach ($cart_items as $cart_item){
			if(is_array($cart_item)){
					$cart_item = (object) array('product_id'=>$cart_item['product_id'],
												'quantity' => $cart_item['quantity'],
												'amount' => $cart_item['amount'],
												'time_created' => $cart_item['time_created'],
												'guid' => $cart_item['product_id'],
												'version_guid' => $cart_item['version_guid'],
												'version_release'=> $cart_item['version_release'],
												'version_summary'=> $cart_item['version_summary']
												);
				}
				if($product = get_entity($cart_item->product_id)){
					if($product->product_type_id == 1){
						$flag_address_deatils = true; 
					} 
				}
		}	
	}
	// return after error
	if (isset($_SESSION['address'])) {
		$bill_address_select = 'checked';
	}
	?>
<script language="javascript" type="text/javascript">
	function checkout_method_normal(name){
		$('#quick_checkout').fadeOut();
		$('#normal_checkout').fadeIn('slow');
	}
	function checkout_method_quick(name){
		$('#normal_checkout').fadeOut();
		$('#quick_checkout').fadeIn('slow');
	}		

</script>	
<div class="checkout_account">
	<div class="account_check_left">
		<form action="<?php echo $action_checkout_regi;?>" method = "post">
			<div class="heading"><?php echo elgg_echo('socialcommerce:account_checkout:newuser');?></div>
			<br />
			<?php if($flag_address_deatils === false){?>
				<p>
					<input type="radio" name="checkout_selection" value="quick" onclick="javascript:checkout_method_quick();"><span class="addres_labels"><?php echo elgg_echo('socialcommerce:account_checkout:quick')?></span></radio>
				</p>
				<p>
					<div id="quick_checkout" style="display: none;">
						<span class="addres_labels"><?php echo elgg_echo('Email')?>:</span>
						<input type="text" name="email"></input>			
					</div>
					<br />
				</p>
				<p>
					<input type="radio" name="checkout_selection" <?php echo $bill_address_select;?> value="normal" onclick="javascript:checkout_method_normal();"><span class="addres_labels"><?php echo elgg_echo('socialcommerce:account_checkout:normal')?></span></radio>
				</p>
			<?php
				$display = 'display: none;';
				if (isset($_SESSION['address'])) {
					 $display = "";
				}
			}?>	
			<p>
				<div id="normal_checkout"  style="<?php echo $display;?>">
					<?php echo elgg_view("{$CONFIG->pluginname}/forms/checkout_account_create",array('ajax'=>1,'type'=>'billing'));?>					
				</div>
			</p>
			<?php echo elgg_view('input/securitytoken');?>
			<?php echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('Continue')));?>
		</form>				
	</div>	
	<div class="account_check_right">
		<form action="<?php echo $action_login;?>" method = "post">
			<div class="heading"><?php echo elgg_echo('socialcommerce:account_checkout:registerdUser');?></div><br /> 
			<br />		
			<span class="addres_labels"><?php echo elgg_echo('username')?>:</span>
			<br /> 
			<input type="text" name="username"></input>
			<br />
			<span class="addres_labels"><?php echo elgg_echo('password')?>:</span>
			<br /> 
			<input type="password" name="password"></input>
			<br /> 
			<?php echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('Continue')));?>
			<br />
			<a href="<?php echo $CONFIG->wwwroot?>account/forgotten_password.php"><?php echo elgg_echo('user:password:lost')?></a>
			<?php echo elgg_view('input/securitytoken');?>
		</form>		
	</div>
	<div class="clear"></div>	
</div>