<?php
/**
 * Tidypics server configuration
 *
 */

elgg_load_library('elgg:markdown');

$faq = elgg_get_plugins_path() . 'tidypics/CONFIG.txt';
$text = Markdown(file_get_contents($faq));

$content = "<div class=\"elgg-markdown\">$text</div>";

echo elgg_view_module('inline', elgg_echo('tidypics:server_config'), $content);
