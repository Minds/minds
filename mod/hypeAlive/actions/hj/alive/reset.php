<?php

if (elgg_set_plugin_setting('hj:alive:setup', false, 'hypeAlive')) {
    system_message('Reset Successful');
}

forward(REFERER);
