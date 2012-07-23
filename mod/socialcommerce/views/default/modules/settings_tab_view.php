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
	 * Elgg modules - settings tab view
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	$base_view = $vars['base_view'];
	$filter = $vars['filter'];
	$settings = $vars['entity'];
	$url = $CONFIG->wwwroot.$CONFIG->pluginname."/settings";
	$tabs = array(
		'settings' => array(
			'text' => elgg_echo('general:settings:tab'),
			'href' => $url,
			'selected' => ($filter == 'settings'),
			'priority' => 200,
		)
	);
	
	if($settings[0]->checkout_methods){
		$tabs['checkout'] = array(
			'text' => elgg_echo('checkout:methods:tab'),
			'href' => $url."/checkout",
			'selected' => ($filter == 'checkout'),
			'priority' => 300,
		);
	}
	if($settings[0]->shipping_methods){
		$tabs['shipping'] = array(
			'text' => elgg_echo('shipping:methods:tab'),
			'href' => $url."/shipping",
			'selected' => ($filter == 'shipping'),
			'priority' => 400,
		);
	}
	if($settings[0]->fund_withdraw_methods){
		$tabs['withdraw'] = array(
			'text' => elgg_echo('fund:withdraw:methods:tab'),
			'href' => $url."/withdraw",
			'selected' => ($filter == 'withdraw'),
			'priority' => 500,
		);
	}
	
	$tabs['currency'] = array(
		'text' => elgg_echo('currency:tab'),
		'href' => $url."/currency",
		'selected' => ($filter == 'currency'),
		'priority' => 700,
	);
	
	if(($CONFIG->allow_tax_method == 2) ||($CONFIG->allow_tax_method == 3) ){
		$tabs['tax'] = array(
			'text' => elgg_echo('tax:name'),
			'href' => $url."/tax",
			'selected' => ($filter == 'tax'),
			'priority' => 600,
		);
	}
	if(elgg_is_active_plugin('cubet_membership')) {
		$tabs['membership'] = array(
			'text' => elgg_echo('membership:tab'),
			'href' => $url."/membership",
			'selected' => ($filter == 'membership'),
			'priority' => 700,
		);
	}
	
	foreach ($tabs as $name => $tab) {
		$tab['name'] = $name;
		elgg_register_menu_item('filter', $tab);
	}
	
	echo elgg_view("{$CONFIG->pluginname}/settingsTabExtend");
?>
<div class="">
	<?php echo elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));?>
	<?php echo $base_view; ?>
</div>