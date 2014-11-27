<?php

$vars['entity']->message = $vars['entity']->decryptMessage();
$vars['entity']->friendly_ts = elgg_get_friendly_time($vars['entity']->time_created);

echo 'json needs something physical.';
echo elgg_view('export/entity', $vars);