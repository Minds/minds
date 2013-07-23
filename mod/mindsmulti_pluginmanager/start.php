<?php
	/**
	 * Minds Elgg multisite plugin manager enhancements
	 *
	 * @package ElggMultisite
	 * @subpackage PluginManager
	 * @author Marcus Povey <marcus@marcus-povey.co.uk>
	 * @copyright Marcus Povey 2013
	 * @link http://www.marcus-povey.co.uk/
 	 */


	elgg_register_event_handler('init','system',function()
	{
		$base = elgg_get_plugins_path();
		
		elgg_register_action('admin/plugins/activate', $base . 'pluginmanager/actions/enable.php'); // Enable
		elgg_register_action('admin/plugins/deactivate', $base . 'pluginmanager/actions/disable.php'); // Disable
		elgg_register_action('admin/plugins/activate_all', $base . 'pluginmanager/actions/enableall.php'); // Enable all
		elgg_register_action('admin/plugins/deactiveate_all', $base . 'pluginmanager/actions/disableall.php'); // Disable all
	
		elgg_register_action('admin/site/update_advanced', $base . 'pluginmanager/actions/update_advanced.php'); // Disable all
		//register_action('admin/plugins/set_priority', false, $CONFIG->pluginspath . 'pluginmanager/actions/reorder.php', true); // Reorder
		
                
                // Remove admin menu options
                elgg_register_event_handler('pagesetup', 'system', function() {
                    
                    elgg_unregister_menu_item('admin_footer', 'faq');
                    elgg_unregister_menu_item('admin_footer', 'manual');
                    elgg_unregister_menu_item('admin_footer', 'blog');
                    elgg_unregister_menu_item('admin_footer', 'community_forums');
                    
                    // Remove archive page settings
                    elgg_unregister_menu_item('page', 'archive'); 
                }, 1001);
	},999);