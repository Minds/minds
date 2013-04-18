<?php 

$entry_id = $vars['entry_id'];
$default = $vars['default'];

$kmodel = KalturaModel::getInstance();
$mediaEntry = $kmodel->getEntry($entry_id);

$length = $mediaEntry->duration;

$width = 210;
$height = 120;

$thumb_1 = elgg_view('output/img', array('src'=> kaltura_get_thumnail($entry_id, $width, $height, $quality=100, $vid_sec = $length / 10)));

$thumb_2 = elgg_view('output/img', array('src'=> kaltura_get_thumnail($entry_id, $width, $height, $quality=100, $vid_sec = $length / 8)));

$thumb_3 = elgg_view('output/img', array('src'=> kaltura_get_thumnail($entry_id, $width, $height, $quality=100, $vid_sec = $length / 5)));

$thumb_4 = elgg_view('output/img', array('src'=> kaltura_get_thumnail($entry_id, $width, $height, $quality=100, $vid_sec = $length / 2)));

echo elgg_view('input/radio', array('name'=>'thumbnail_selector', 'value'=>$default, 'options'=>array($thumb_1=>$length / 10, $thumb_2=>$length / 8, $thumb_3=>$length / 5, $thumb_4=>$length / 2), 'align'=>'horizontal'));