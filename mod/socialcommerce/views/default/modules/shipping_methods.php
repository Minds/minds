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
	 * Elgg modules - shipping methods
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	$shipping_methods = get_shipping_methods();
	$checkoutmethods = "";
	
	$settings = $vars['entity'];
	$order = get_input('order');
	if(!$order)
		$order = 0;
	if($settings){
		$settings = $settings[0];
		$selected_shippingmethods_guid = $settings->guid;
		$selected_shippingmethods = $settings->shipping_methods;
		if(!is_array($selected_shippingmethods)){
			$selected_shippingmethods = array($selected_shippingmethods);
		}
	}
	if($shipping_methods && $selected_shippingmethods){
?>
		<div>
			<script type="text/javascript" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/js/chili-1.7.pack.js"></script>
			<script type="text/javascript" src="<?php echo $CONFIG->wwwroot; ?>mod/<?php echo $CONFIG->pluginname; ?>/js/jquery.accordion.js"></script>
			<script type="text/javascript">
				$(document).ready(function(){
					jQuery('#list1b').accordion({
						autoheight: false,
						header: 'h3',
						active: <?php echo $order; ?>
					});
				});
			</script>
			<div class="basic" id="list1b">
<?php 
				$i = 0;
				foreach ($selected_shippingmethods as $selected_shippingmethod){
					$method = $shipping_methods[$selected_shippingmethod];
					$shipping_contents = elgg_view("modules/shipping/{$selected_shippingmethod}/{$method->view}",array('entity'=>$settings,'method'=>$method,'base'=>$selected_shippingmethod,'order'=>$i));
?>
					<h3>
						<a>
							<span class="list1b_icon"></span>
							<B><?php echo $method->label; ?> :</B>
						</a>
					</h3>
					<div class="ui_content">
						<div class="content">
							<?php echo $shipping_contents; ?>
						</div>
					</div>
<?php 
					$i++;
				}
		echo "</div></div>";
	}
?>