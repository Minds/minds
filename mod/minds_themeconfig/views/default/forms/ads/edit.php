<?php
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


