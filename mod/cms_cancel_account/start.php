<?php

elgg_register_event_handler('init', 'system', 'cms_cancel_account_init');

/**
 * Initialize the cms_cancel_account plugin.
 */
function cms_cancel_account_init() {

	$filename =  elgg_get_plugins_path() . "cms_cancel_account/actions/request.php";
	elgg_register_action('cms_cancel_account/request', $filename);	

	$action_path = dirname(__FILE__) . '/actions';
	elgg_register_action('cms_cancel_account/bulk_action', "$action_path/bulk_action.php", 'admin');
	elgg_register_action('cms_cancel_account/delete', "$action_path/delete.php", 'admin');
		

	elgg_extend_view('css/admin', 'cms_cancel_account/css');
	elgg_extend_view('js/elgg', 'cms_cancel_account/js');	

	elgg_register_event_handler('pagesetup', 'system', 'cms_cancel_account_plugin_pagesetup');
	
	elgg_register_page_handler('cms_cancel_account','cms_cancel_account_page_handler');
	
	// Opción para el menú de Administración
	elgg_register_admin_menu_item('administer', 'cancellations', 'users');	
	
}


/**
 * Cancel_account settings sidebar menu
 *
 */
function cms_cancel_account_plugin_pagesetup() {
	if (elgg_get_context() == "settings" && elgg_get_logged_in_user_guid()) {
		$user = elgg_get_logged_in_user_entity();

		
		$url = "cms_cancel_account/cancel";
		//$url = elgg_add_action_tokens_to_url($url);		
		$item = new ElggMenuItem('3_cancel_account', elgg_echo('cms_cancel_account:cancelaccount'), $url);
		$item->setLinkClass('elgg-requires-confirmation');	

		elgg_register_menu_item('page', $item);	
			
	}
}

/**
 * Page handler
 */
function cms_cancel_account_page_handler($page) {
	
	$base = elgg_get_plugins_path() . 'cms_cancel_account/pages/cms_cancel_account';	

	switch ($page[0]) {
		case 'cancel':
			require_once "$base/cancel.php";
			break;	

	}
	return true;	
} 
 
 