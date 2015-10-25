<?php
    admin_gatekeeper();
    
    // Save logo (generate a couple of sizes)
    $files = array();
    foreach (array(
        'logo_main' => array(
         //   'w' => 400,
            'h' => 400,
            'square' => false,
            'upscale' => true
        ),
        'logo_topbar' => array(
        //    'w' => 400,
	    'h' => 100,
            'square' => false,
            'upscale' => true
        ),
 /*       'logo_favicon' => array(
            'w' => 32,
            'h' => 32,
            'square' => true,
            'upscale' => true
        )*/
    ) as $name => $size_info) {
	    global $CONFIG;
	    $theme_dir = $CONFIG->dataroot . 'minds_themeconfig/';
	    
	   // $resized = get_resized_image_from_uploaded_file('logo', $size_info['w'], $size_info['h'], $size_info['square'], $size_info['upscale'], 'png');
		$resized = get_resized_image_from_existing_file($_FILES['logo']['tmp_name'], $size_info['w'], $size_info['h'], $size_info['square'], 0, 0, 0, 0, $size_info['upscale'], 'png');
	            
		if ($resized) {
	        	
	        @mkdir($theme_dir);
	                
			if (!file_put_contents($theme_dir . $name.'.png', $resized)) {
			    register_error("The file was resized, but there was a problem saving it.");
			}
	                
	       	elgg_set_plugin_setting('logo_override', 'true', 'minds_themeconfig');
			elgg_set_plugin_setting('logo_override_ts', time(), 'minds_themeconfig');
		} else { 
				register_error("There was a problem generating your image.");
		}
	            
		if (isset($_FILES['logo']) && ($_FILES['logo']['error'] != UPLOAD_ERR_NO_FILE) && $_FILES['logo']['error'] != 0) {
			register_error(minds_themeconfig_codeToMessage($_FILES['logo']['error'])); // Debug uploads
		}
		    
		if (get_input('logo_remove') == 'y') {
			elgg_set_plugin_setting('logo_override', '', 'minds_themeconfig');
			elgg_set_plugin_setting('logo_override_ts', '', 'minds_themeconfig');
		}
    }
    
    // Favicon
    foreach (array(
        'logo_favicon' => array(
            'w' => 32,
            'h' => 32,
            'square' => true,
            'upscale' => true
        )
    ) as $name => $size_info) {

    	if(!$_FILES['favicon']['tmp_name'])
    		continue;
    		
        $resized = get_resized_image_from_uploaded_file('favicon', $size_info['w'], $size_info['h'], $size_info['square'], 0, 0, 0, 0, $size_info['upscale'], 'jpeg');

        if ($resized) {
            global $CONFIG;
            $theme_dir = $CONFIG->dataroot . 'minds_themeconfig/';
            @mkdir($theme_dir);

            if (!file_put_contents($theme_dir . $name.'.jpg', $resized)) {
				register_error("The file was resized, but there was a problem saving it.");
	    	}

            elgg_set_plugin_setting('logo_favicon', 'true', 'minds_themeconfig');
            elgg_set_plugin_setting('logo_favicon_ts', time(), 'minds_themeconfig');
            
        } else {
			register_error("There was a problem generating your favicon.");
		}

        if (isset($_FILES['favicon']) && ($_FILES['favicon']['error'] != UPLOAD_ERR_NO_FILE) && $_FILES['favicon']['error'] != 0) {
            register_error(minds_themeconfig_codeToMessage($_FILES['favicon']['error'])); // Debug uploads
        }
	
	
		if (get_input('favicon_remove') == 'y') {
			elgg_set_plugin_setting('logo_favicon', '', 'minds_themeconfig');
			elgg_set_plugin_setting('logo_favicon_ts', '', 'minds_themeconfig');
		}
    }
    
    // Background image
    if (isset($_FILES['background']) && $_FILES['background']['error'] == 0) {
        global $CONFIG;
        $theme_dir = $CONFIG->dataroot . 'minds_themeconfig/';
        @mkdir($theme_dir);
        
        if (move_uploaded_file($_FILES['background']['tmp_name'], $theme_dir . 'background')){
            elgg_set_plugin_setting('background_override', 'true', 'minds_themeconfig');
            elgg_set_plugin_setting('background_override_mime', $_FILES['background']['type'], 'minds_themeconfig');
            elgg_set_plugin_setting('background_override_ts', time(), 'minds_themeconfig');
        }
    }
	
    if (isset($_FILES['background']) && ($_FILES['background']['error'] != UPLOAD_ERR_NO_FILE) && ($_FILES['background']['error'] != 0)) {
        register_error(minds_themeconfig_codeToMessage($_FILES['background']['error'])); // Debug uploads
    }
    
    if (get_input('background_remove') == 'y') {
		elgg_set_plugin_setting('background_override', '', 'minds_themeconfig');
		elgg_set_plugin_setting('background_override_ts', '', 'minds_themeconfig');
		elgg_set_plugin_setting('background_override_mime', '', 'minds_themeconfig');
    }
    
    // Save background colour
    elgg_set_plugin_setting('background_colour', preg_replace("/[^a-fA-F0-9\s]/", "", get_input('background_colour')), "minds_themeconfig");
    
    // Sidebar colour
    elgg_set_plugin_setting('sidebar_colour', preg_replace("/[^a-fA-F0-9\s]/", "", get_input('sidebar_colour')), "minds_themeconfig");
    
    // Main bar colour
    elgg_set_plugin_setting('main_colour', preg_replace("/[^a-fA-F0-9\s]/", "", get_input('main_colour')), "minds_themeconfig");
    
    // Minds topbar colour
    elgg_set_plugin_setting('topbar_colour', preg_replace("/[^a-fA-F0-9\s]/", "", get_input('topbar_colour')), "minds_themeconfig");
    
    // Button colour
    elgg_set_plugin_setting('button_colour', preg_replace("/[^a-fA-F0-9\s]/", "", get_input('button_colour')), "minds_themeconfig");
    
    // carousel colour
    elgg_set_plugin_setting('carousel_colour', preg_replace("/[^a-fA-F0-9\s]/", "", get_input('carousel_colour')), "minds_themeconfig");
    
    // Save text colour
    elgg_set_plugin_setting('text_colour', preg_replace("/[^a-fA-F0-9\s]/", "", get_input('text_colour')), "minds_themeconfig");
    
