<?php
admin_gatekeeper();

elgg_set_plugin_setting('themeset', get_input('themeset', ''), 'minds_themeconfig');

elgg_regenerate_simplecache();
