<?php

elgg_load_library('hj:alive:setup');

$name = get_input('type', 'generic_comment');

hj_alive_import_annotations($name);

system_message('Import performed');

forward(REFERER);