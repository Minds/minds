<?php
$current = $vars['current'];

$popular = elgg_view('output/url', array('href'=>'archive/wall/popular', 'text'=>elgg_echo('archive:popular:title'), 'class'=>'archive-wall-title'));
$mostviewed = elgg_view('output/url', array('href'=>'archive/wall/mostviewed', 'text'=>elgg_echo('archive:mostviewed:title'),'class'=>'archive-wall-title'));
$featured = elgg_view('output/url', array('href'=>'archive/wall/featured', 'text'=>elgg_echo('archive:featured:title'),'class'=>'archive-wall-title'));

if($current == 'popular'){
	$popular = strip_tags($popular);
}elseif($current == 'mostviewed'){
	$mostviewed = strip_tags($mostviewed);
}else{
	$featured = strip_tags($featured);
}

echo $featured . ' ' . $popular . ' ' . $mostviewed;
