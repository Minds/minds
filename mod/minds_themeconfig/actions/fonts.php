<?php

admin_gatekeeper();

global $CONFIG;


foreach ($CONFIG->theme_fonts as $element => $code) {
    
    $f = get_input('font_' . $code);
    $s = get_input('font_size_' . $code);
    $c = get_input('font_colour_' . $code);

    elgg_set_plugin_setting('font::' . $code, $f, 'minds_themeconfig');
    elgg_set_plugin_setting('font_size::' . $code, $s, 'minds_themeconfig');
    elgg_set_plugin_setting('font_colour::' . $code, $c, 'minds_themeconfig');
    
    
}