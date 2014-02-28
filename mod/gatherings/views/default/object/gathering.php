<?php
if($vars['full_view']){
	echo elgg_view('gatherings/gathering', array('gathering'=>$vars['entity']));
} else {
	$owner = $vars['entity']->getOwnerEntity();
	$title = elgg_view('output/url', array('href'=> $vars['entity']->getUrl(), 'text'=>elgg_view_title($vars['entity']->title)));
	echo elgg_view_image_block(elgg_view_entity_icon($owner, 'small'), $title . elgg_view_friendly_time($vars['entity']->time_created));
}

