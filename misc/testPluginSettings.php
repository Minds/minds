<?php

require_once(dirname(dirname(__FILE__)) . '/engine/start.php');


var_dump(new ElggPlugin('gatherings'));
echo elgg_get_plugin_setting('server_url', 'gatherings'); 
