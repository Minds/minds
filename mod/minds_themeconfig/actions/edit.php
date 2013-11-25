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
        'logo_favicon' => array(
            'w' => 32,
            'h' => 32,
            'square' => true,
            'upscale' => true
        )
    ) as $name => $size_info) {
            $resized = get_resized_image_from_uploaded_file('logo', $size_info['w'], $size_info['h'], $size_info['square'], $size_info['upscale']);

            if ($resized) {
                global $CONFIG;
                $theme_dir = $CONFIG->dataroot . 'minds_themeconfig/';
                @mkdir($theme_dir);

                file_put_contents($theme_dir . $name.'.jpg', $resized);
                
                elgg_set_plugin_setting('logo_override', 'true', 'minds_themeconfig');
            }
    
            if (isset($_FILES['logo']) && ($_FILES['logo']['error'] != UPLOAD_ERR_NO_FILE) && $_FILES['logo']['error'] != 0) {
                register_error(minds_themeconfig_codeToMessage($_FILES['logo']['error'])); // Debug uploads
            }
    }
    
    // Background image
    if (isset($_FILES['background']) && $_FILES['background']['error'] == 0) {
        global $CONFIG;
        $theme_dir = $CONFIG->dataroot . 'minds_themeconfig/';
        @mkdir($theme_dir);
        
        if (move_uploaded_file($_FILES['background']['tmp_name'], $theme_dir . 'background'))
        {
            elgg_set_plugin_setting('background_override', 'true', 'minds_themeconfig');
            elgg_set_plugin_setting('background_override_mime', $_FILES['background']['type'], 'minds_themeconfig');
        }
    }
    if (isset($_FILES['background']) && ($_FILES['background']['error'] != UPLOAD_ERR_NO_FILE) && ($_FILES['background']['error'] != 0)) {
        register_error(minds_themeconfig_codeToMessage($_FILES['background']['error'])); // Debug uploads
    }
    
    // Save frontpage text
    elgg_set_plugin_setting('frontpagetext', get_input('frontpagetext'), 'minds_themeconfig');
    
    // Save background colour
    elgg_set_plugin_setting('background_colour', preg_replace("/[^a-fA-F0-9\s]/", "", get_input('background_colour')), "minds_themeconfig");
    
    // Save text colour
    elgg_set_plugin_setting('text_colour', preg_replace("/[^a-fA-F0-9\s]/", "", get_input('text_colour')), "minds_themeconfig");
    
    // Save custom CSS
    elgg_set_plugin_setting('custom_css', get_input('custom_css'), "minds_themeconfig");