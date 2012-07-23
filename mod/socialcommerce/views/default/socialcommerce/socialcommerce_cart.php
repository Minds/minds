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
	 * Elgg view - tags menu
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	$product = $vars['entity'];
	$product_type_details = $vars['product_type_details'];
	$phase = $vars['phase'];
	if($product){
		if ($product->guid > 0 && (elgg_is_logged_in() || $CONFIG->allow_add_cart == 1)) {
			$cart_btn = "<input class='input_img' type='image' src=\"{$CONFIG->wwwroot}mod/{$CONFIG->pluginname}/images/shopping_cart_btn.jpg\">";
			
			$form_body = elgg_view('input/hidden', array('name' => 'stores_guid', 'value' => $product->getGUID()));
			$form_body .= $cart_btn;
			if($product->product_type_id == 1){
				$label = "<div style='float:left;margin-bottom:5px;'><label>".elgg_echo("enter:quantity").": </label></div>
				<div style='float:left;width:100px;'><p>" . elgg_view('input/text',array('name' => 'cartquantity','value' => '1')) . "</p></div>
				<div class='clear'></div>
				<div><div style='float:left;padding-left:20px;'>{$form_body}</div></div>";
			}elseif ($product->product_type_id == 2){
				$label = $form_body;
			}
			
			$form_body = <<<EOT
				<div class="add_to_cart_form">
            		<div style="float:left;width:310px;">
            			{$label}
            		</div>
            		<div style="clear:both;"></div>
            	</div>
EOT;
			
			if($phase == 1){
				if ($product->canEdit()) {
					if($_SESSION['user']->guid != $product->owner_guid && $product->status == 1 && $product_type_details->addto_cart == 1){
						if($stores->product_type_id == 2){
							$body = $form_body;
						}else{
							$body = $cart_btn;
						}
					}
				}else{
					if($product_type_details->addto_cart == 1) {
						if($stores->product_type_id == 2){
							$body = $form_body;
						}else{
							$body = $cart_btn;
						}
					}
				}
			}else{
				$body = $form_body;
			}
		}
	}
?>
<div class="product_cart_btn">
	<?php echo $body; ?>
</div>