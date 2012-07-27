<?php
elgg_load_js('hj.framework.colorpicker');
elgg_load_css('hj.framework.colorpicker');

$vars['class'] = "{$vars['class']} hj-color-picker";
$vars['maxlength'] = '7';
$vars['size'] = '7';

echo elgg_view('input/text', $vars);
