<?php
/* hypeFramework
 * Provides classes, libraries and views for hypeJunction plugins
 *
 * @package hypeJunction
 * @subpackage hypeFramework
 * @author Ismayil Khayredinov <ismayil.khayredinov@gmail.com>
 * @copyright Copyrigh (c) 2011, Ismayil Khayredinov
 */

// Initialize hypeFramework
elgg_register_event_handler('init', 'system', 'hj_framework_init');

function hj_framework_init() {
    $path = elgg_get_plugins_path();
    $plugin = 'hypeFramework';

    elgg_register_library('hj:framework:base', $path . $plugin . '/lib/framework/base.php');
    elgg_load_library('hj:framework:base');

    $shortcuts = hj_framework_path_shortcuts($plugin);

    // hypeFramework will contain all necessary classes for hypeJunction plugins
    elgg_register_classes($shortcuts['classes']);

    // Helper functions to load necessary libraries
    // Avoiding huge init function
    hj_framework_register_libraries();
    hj_framework_register_actions();
    hj_framework_register_hooks();
    hj_framework_register_page_handlers();
    hj_framework_register_css();
    hj_framework_register_js();
    hj_framework_register_view_extentions();

    //Check if the initial setup has been performed, if not perform it
    if (!elgg_get_plugin_setting('hj:framework:setup')) {
        elgg_load_library('hj:framework:setup');
        if (hj_framework_setup())
            system_message('hypeFramework was successfully configured');
    }
}