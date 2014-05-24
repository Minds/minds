<?php

    admin_gatekeeper();

    // Save custom CSS
    elgg_set_plugin_setting('custom_css_preview', get_input('custom_css'), "minds_themeconfig");

    echo json_encode(array('success' => true));