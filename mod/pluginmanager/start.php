<?php
	/**
	 * Elgg multisite plugin manager.
	 * 
	 * Replaces the standard Elgg plugin manager with one linked to the accounts system.
	 *
	 * @package ElggMultisite
	 * @subpackage PluginManager
	 * @author Marcus Povey <marcus@marcus-povey.co.uk>
	 * @copyright Marcus Povey 2010
	 * @link http://www.marcus-povey.co.uk/
 	 */


	function pluginmanager_init()
	{
		$base = elgg_get_plugins_path();
		
		elgg_register_action('admin/plugins/activate', $base . 'pluginmanager/actions/enable.php'); // Enable
		elgg_register_action('admin/plugins/deactivate', $base . 'pluginmanager/actions/disable.php'); // Disable
		elgg_register_action('admin/plugins/activate_all', $base . 'pluginmanager/actions/enableall.php'); // Enable all
		elgg_register_action('admin/plugins/deactiveate_all', $base . 'pluginmanager/actions/disableall.php'); // Disable all
	
		elgg_register_action('admin/site/update_advanced', $base . 'pluginmanager/actions/update_advanced.php'); // Disable all
		//register_action('admin/plugins/set_priority', false, $CONFIG->pluginspath . 'pluginmanager/actions/reorder.php', true); // Reorder
		
	}
	
	/**
	 * Hook into boot events, make sure settings are correctly preserved.
	 * @global type $CONFIG 
	 */
	function pluginmanager_multisite_boot() {
	    
	    global $CONFIG;
	    
	    $CONFIG->dataroot = $CONFIG->elgg_multisite_settings->dataroot;
	}
		
	elgg_register_event_handler('init','system','pluginmanager_init',999);
	elgg_register_event_handler('plugins_boot', 'system', 'pluginmanager_multisite_boot', 999);