<?php

$guids = $vars['value'];
$guids = explode(',', $guids);

if (is_array($guids)) {
    foreach ($guids as $guid) {
        $guid = trim($guid);
        $tag = get_entity($guid);
        echo elgg_view_entity($tag, array('full_view' => false));
    }
}

