<?php
/**
 * Carousel batch
 */
elgg_load_js('minicolors');
elgg_load_css('minicolor');

$buttons = elgg_view('input/submit', array('name'=>'add','class'=>'elgg-button elgg-button-action', 'value'=>'+'));
$buttons .= elgg_view('input/submit', array('name'=>'submit','class'=>'elgg-button elgg-button-action', 'value'=>'Save'));

echo $buttons;
 
$items = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'carousel_item',
	'limit' => 0
));

//sort the tiers by price
usort($items, function($a, $b){
	return $a->order - $b->order;
});

echo '<div class="carousel-admin-items">';

foreach($items as $item){
	echo '<div class="carousel-admin-wrapper" style="background:#888 url('. elgg_get_site_url() . 'carousel/background/'.$item->guid.'/'.$item->last_updated .')">';
		echo elgg_view('input/submit', array('name'=>'delete:'.$item->guid,'class'=>'elgg-button elgg-button-action remove', 'value'=>'x'));
		echo elgg_view('input/text', array('name'=>"$item->guid:color", 'class'=>'text-color carousel-colorpicker', 'value'=>$item->color, 'size' => 1));
		echo elgg_view('input/file', array('name'=>"$item->guid:background", 'class'=>'bg-input'));
		echo elgg_view('input/plaintext', array('name'=>"$item->guid:title", 'value'=>$item->title, 'placeholder'=>'Type here..', 'style'=>'color:'.$item->color));
		echo elgg_view('input/hidden', array('name'=>"$item->guid:order", 'value'=>$item->order, 'id'=>"order"));
	echo '</div>';
}

echo '</div>';
