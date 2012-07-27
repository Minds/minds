<?php
/**
 * Ouptut for file input
 * @uses $vars['value']
 */

if (is_int($vars['value'])) {
    $file = get_entity($vars['value']);
    echo elgg_view_entity($file);
} else if (elgg_instanceof($vars['value'], ElggFile)) {
    echo elgg_view_entity($vars['value']);
} else {
    echo elgg_view('output/url', array('href' => $vars['value'], 'is_action' => false, 'text' => elgg_echo('file')));
}

