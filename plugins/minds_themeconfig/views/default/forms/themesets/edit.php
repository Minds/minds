<?php
$themesets = minds_themeconfig_get_themesets();

$options = array();
foreach($themesets as $themeset){
	$icon = elgg_view('output/img', array('src'=>minds_themeconfig_get_themeset_icon($themeset)));
	$content = "$icon <h3>$themeset</h3>";
	$options[$content] = $themeset;
}

echo elgg_view('input/submit', array('value' => elgg_echo('update'), 'class'=>'elgg-button-action'));
echo elgg_view('input/radio', array('name'=>'themeset', 'options'=>$options, 'class'=>'themesets', 'value'=>elgg_get_plugin_setting('themeset','minds_themeconfig')?:'minds-default'));
