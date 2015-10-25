<?php

echo "<label>Enabled ads</label>";
	echo elgg_view('input/radio',array('name'=>"enabled", 'value'=>elgg_get_plugin_setting('enabled', 'minds_themeconfig')?:'on','options'=> array('on'=>'on', 'off'=>'off')));


$ads = elgg_get_plugin_setting('ads', 'minds_themeconfig') ?: array();

$blocks = array( 'side-1',
		'side-2',
		'side-3',
		'content-1',
		'content-2'
	);

foreach($blocks as $block){
	$value = elgg_get_plugin_setting('ads-'.$block,'minds_themeconfig')?:'';
	echo "<label>$block</label>";
	echo elgg_view('input/plaintext',array('name'=>"ads[$block]", 'value'=>$value));
}

echo elgg_view('input/submit', array('value' => elgg_echo('update'), 'class'=>'elgg-button-action'));


