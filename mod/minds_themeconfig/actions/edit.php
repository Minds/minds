<?php
    admin_gatekeeper();
    
    // Save logo (generate a couple of sizes)
    $files = array();
    foreach (array(
        'logo_main' => array(
            'w' => 200,
            'h' => 90,
            'square' => false,
            'upscale' => true
        ),
        'logo_topbar' => array(
            'w' => 78,
            'h' => 30,
            'square' => false,
            'upscale' => true
        ),
    ) as $name => $size_info) {
            $resized = get_resized_image_from_uploaded_file('logo', $size_info['w'], $size_info['h'], $size_info['square'], $size_info['upscale']);

            if ($resized) {
                global $CONFIG;
                $theme_dir = $CONFIG->dataroot . 'minds_themeconfig/';
                @mkdir($theme_dir);

                file_put_contents($theme_dir . $name.'.jpg', $resized);
                
                elgg_set_plugin_setting('logo_override', 'true', 'minds_themeconfig');
            }
            
    }
    
    // Save background colour
    elgg_set_plugin_setting('background_colour', preg_replace("/[^a-fA-F0-9\s]/", "", get_input('background_colour')), "minds_themeconfig");
    
    // Save text colour
    elgg_set_plugin_setting('text_colour', preg_replace("/[^a-fA-F0-9\s]/", "", get_input('text_colour')), "minds_themeconfig");