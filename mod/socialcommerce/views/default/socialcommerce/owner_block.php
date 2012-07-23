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
	 * Elgg view - over write owner block
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	if(elgg_is_logged_in()){
		// Check Membership Privileges
		$permission = membership_privileges_check('buy');
?>
		<div id="owner_block_stores">
			<?php if (elgg_is_admin_logged_in()) { ?>
				<!--My Account-->
				<div class="scommerce_settings">
					<a href="<?php echo $CONFIG->wwwroot . ''.$CONFIG->pluginname.'/settings'; ?>" />
						<?php echo elgg_echo('socialcommerce:settings'); ?>
					</a>
				</div>
			<?php } ?>
			<!--My Account-->
			<div class="my_account">
				<a href="<?php echo $CONFIG->checkout_base_url ?><?php echo $CONFIG->pluginname; ?>/my_account" />
					<?php echo elgg_echo('stores:my:account'); ?>
				</a>
			</div>
			<!--Cart-->
			<?php 
				if($permission == 1) {
					if(isset($CONFIG->cart_item_count) && !empty($CONFIG->cart_item_count)){
						$c_count = " (".$CONFIG->cart_item_count.")";
					}else{
						$c_count = "";
					}
			?>
				<div class="cart">
					<a href="<?php echo $CONFIG->wwwroot ?><?php echo $CONFIG->pluginname; ?>/cart" />
						<?php echo elgg_echo('stores:my:cart').$c_count; ?>
					</a>
				</div>
				<?php }?>
			<!--Wishlist-->
			<?php 
				if(isset($CONFIG->wishlist_item_count) && !empty($CONFIG->wishlist_item_count)){
					$w_count = " (".$CONFIG->wishlist_item_count.")";
				}else{
					$w_count = "";
				}
			?>
			<div class="wishlist">
				<a href='<?php echo $CONFIG->wwwroot."{$CONFIG->pluginname}/wishlist"; ?>' />
					<?php echo elgg_echo('stores:my:wishlist').$w_count ?>
				</a>
			</div>
			<!--orders-->
			<?php if($permission == 1) {?>
			<div class="orders">
				<a href='<?php echo $CONFIG->wwwroot."{$CONFIG->pluginname}/order/"; ?>' />
					<?php echo elgg_echo('stores:my:order') ?>
				</a>
			</div>
			<?php }?>
			<!--Orders Admin-->
			<?php if(elgg_is_admin_logged_in()){?>
				<div class="view_order_btn" style="margin:10px 0 0;">
					<a href='<?php echo $CONFIG->wwwroot."{$CONFIG->pluginname}/orderadmin"; ?>' />
						<?php echo elgg_echo('stores:order:admin') ?>
					</a>
				</div>
			<?php }?>
			<!--create orders-->
			<?php if(elgg_is_admin_logged_in()){?>
				<div class="orders">
					<a href='<?php echo $CONFIG->wwwroot."{$CONFIG->pluginname}/create_order/"; ?>' />
						<?php echo elgg_echo('stores:order:create') ?>
					</a>
				</div>
			<?php }?>
			<!--Coupon Code-->
			<?php 
				if($CONFIG->allow_add_coupon_code || elgg_is_admin_logged_in()){
					$permission1 = membership_privileges_check('sell');
					if($permission1 == 1) {
			?>
				<div class="coupon_code">
					<a href='<?php echo $CONFIG->wwwroot."{$CONFIG->pluginname}/coupon/"; ?>' />
						<?php echo elgg_echo('stores:coupon:code') ?>
					</a>
				</div>
			<?php } }?>
		</div>
<?php
	}else{
		if($CONFIG->cart_item_count){
			$c_count = " (".$CONFIG->cart_item_count.")";
?>
		<div id="owner_block_stores">
			<!--Cart-->
			<div class="cart">
				<a href="<?php echo $CONFIG->wwwroot ?><?php echo $CONFIG->pluginname; ?>/cart/gust" />
					<?php echo elgg_echo('stores:gust:cart').$c_count; ?>
				</a>
			</div>
		</div>
<?php
		}
	}
?>
