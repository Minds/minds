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
	 * Elgg view - product image
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	
	$product = $vars['entity'];
	
	if (elgg_instanceof($product, 'object', 'stores')) {
	 	// Get size
		if (!in_array($vars['size'],array('small','medium','large','tiny','master','topbar')))
			$vars['size'] = "medium";
			
		// Get display
		if (!in_array($vars['display'],array('full','image','url'))){
			$vars['display'] = "full";
		}
			
		// Get any align and js
		if (!empty($vars['align'])) {
			$align = " align=\"{$vars['align']}\" ";
		} else {
			$align = "";
		}
		
		if ($icontime = $product->icontime) {
			$icontime = "{$icontime}";
		} else {
			$icontime = "default";
		}
		
		if($vars['display'] == "full"){
?>
			<script>
				function display_zoome_image(guid,e){
					$("#zome_product_image_"+guid).css('display','block');
				}
				function hide_zoome_image(guid){
					$("#zome_product_image_"+guid).css('display','none');
				}
			</script>
			<div class="product_image">
				<a title="<?php echo $product->title; ?>" rel="<?php echo $product->getIconURL('large'); ?>" href="<?php echo $product->getURL(); ?>" class="preview icon" >
					<img class="elgg-photo" title="<?php echo $product->title; ?>" src="<?php echo $product->getIconURL($vars['size']); ?>" border="0" <?php echo $align; ?>  <?php echo $vars['js']; ?> />
				</a>
			</div>
<?php
		}else if ($vars['display'] == "image"){
?>
			<img title="<?php echo $product->title; ?>" src="<?php echo $product->getIconURL($vars['size']); ?>" border="0" <?php echo $align; ?> title="<?php echo $name; ?>" <?php echo $vars['js']; ?> />
<?php
		}else if ($vars['display'] == "url"){
			echo $product->getIconURL($vars['size']); 
		}
	}
?>