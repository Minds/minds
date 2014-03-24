<?php
/**
 * Carousel batch
 */

$buttons = elgg_view('input/submit', array('name'=>'add','class'=>'elgg-button elgg-button-action', 'value'=>'+'));
$buttons .= elgg_view('input/submit', array('name'=>'submit','class'=>'elgg-button elgg-button-action', 'value'=>'Save'));

echo $buttons;
 
$items = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'carousel_item'
));

foreach($items as $item){
	echo '<div class="carousel-admin-wrapper" style="background:#888 url('. elgg_get_site_url() . 'carousel/background/'.$item->guid.')">';
		echo elgg_view('input/file', array('name'=>"$item->guid:background", 'class'=>'bg-input'));
		echo elgg_view('input/plaintext', array('name'=>"$item->guid:title", 'value'=>$item->title, 'placeholder'=>'Type here..'));
	echo '</div>';
}
