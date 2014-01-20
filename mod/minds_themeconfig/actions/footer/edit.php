<?php
    admin_gatekeeper();

elgg_set_plugin_setting('copyright', get_input('copyright', ''), 'minds_themeconfig');

$networks = get_input('networks');
 
foreach($networks as $network => $url){
    elgg_set_plugin_setting($network.':url', $url, "minds_themeconfig");
}
