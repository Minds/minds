<?php

// Go through list of themes. Activate only the theme you want to activate.

    admin_gatekeeper();

    global $CONFIG;

    if ($activated_theme = get_input('activated')) {
    
        foreach ($CONFIG->available_themes as $plugin) {
            disable_plugin($plugin);
        }
        
        // Now, enable the plugin we're actually wanting
        enable_plugin($activated_theme);
        elgg_set_plugin_setting('activated_theme', $activated_theme, 'minds_theme_selector');
        
        elgg_invalidate_simplecache();
        elgg_reset_system_cache();

    }
    
    