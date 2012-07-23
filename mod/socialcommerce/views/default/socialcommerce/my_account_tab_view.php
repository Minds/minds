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
	 * Elgg view - my account tab
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	$base_view = elgg_extract('base_view', $vars, '');
	$filter = elgg_extract('filter', $vars, 'address');
	$url =  $CONFIG->checkout_base_url.'socialcommerce/my_account';
	
	$tabs = array(
		'address' => array(
			'text' => elgg_echo('address'),
			'href' => $url,
			'selected' => ($filter == 'address'),
			'priority' => 200,
		),
		'transactions' => array(
			'text' => elgg_echo('transactions'),
			'href' => $url."/transactions",
			'selected' => ($filter == 'transactions'),
			'priority' => 300,
		),
		'withdraw' => array(
			'text' => elgg_echo('withdraw'),
			'href' => $url."/withdraw",
			'selected' => ($filter == 'withdraw'),
			'priority' => 400,
		),
	);
	
	if(elgg_is_admin_logged_in()){
		$tabs['fee'] = array(
			'text' => elgg_echo('fee'),
			'href' => $url."/fee",
			'selected' => ($filter == 'fee'),
			'priority' => 400,
		);
	}
	if($CONFIG->withdraw_option == 'moderation' || $CONFIG->withdraw_option == 'moderation_escrow'){
		$tabs['request'] = array(
			'text' => elgg_echo('request'),
			'href' => $url."/request",
			'selected' => ($filter == 'request'),
			'priority' => 400,
		);
	}
	
	foreach ($tabs as $name => $tab) {
		$tab['name'] = $name;
		elgg_register_menu_item('filter', $tab);
	}
	
	echo elgg_view("{$CONFIG->pluginname}/accountTabExtend");
?>
<div class="bookraiser_profile">
	<?php echo elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));?>
	<?php echo $base_view; ?>
</div>