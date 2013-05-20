<?php

elgg_register_event_handler('init','system',function(){
    elgg_register_admin_menu_item('configure', 'theme', 'appearance');
    
    elgg_register_action('theme/edit', dirname(__FILE__) . '/actions/edit.php', 'admin');
});	