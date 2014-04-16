<?php

    admin_gatekeeper();

    // Save custom CSS
    elgg_set_plugin_setting('custom_css', get_input('custom_css'), "minds_themeconfig");
