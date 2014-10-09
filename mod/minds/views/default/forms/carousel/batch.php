<?php
/**
 * Carousel batch
 */
elgg_load_js('minicolors');
elgg_load_css('minicolor');
elgg_load_js('spectrum');
elgg_load_css('spectrum');

$buttons = elgg_view('input/submit', array('name'=>'add','class'=>'elgg-button elgg-button-action', 'value'=>'+'));
$buttons .= elgg_view('input/submit', array('name'=>'submit','class'=>'elgg-button elgg-button-action', 'value'=>'Save'));

if(!elgg_get_page_owner_guid()){
	if(elgg_get_plugin_setting('style','minds') != 'fat'){
		$buttons .= elgg_view('input/submit', array('name'=>'fat','class'=>'elgg-button elgg-button-action', 'value'=>'Use Fat (full page)'));
	} else {
		$buttons .= elgg_view('input/submit', array('name'=>'thin','class'=>'elgg-button elgg-button-action', 'value'=>'Use Thin'));
	}
}

echo $buttons;
 
if(!isset($vars['items'])){
	$items = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'carousel_item',
		'limit' => 0
	));
	echo elgg_view('input/hidden', array('name'=>'owner_guid', 'value'=>0));
	echo elgg_view('input/hidden', array('name'=>'admin', 'value'=>'admin'));
} else {
	$items = $vars['items'];
	echo elgg_view('input/hidden', array('name'=>'owner_guid', 'value'=>elgg_get_page_owner_guid()));
}

//sort the tiers by price
usort($items, function($a, $b){
	return $a->order - $b->order;
});

echo '<div class="carousel-admin-items">';

foreach($items as $item){
	echo '<div class="carousel-admin-wrapper" style="background:#888 url('. elgg_get_site_url() . 'carousel/background/'.$item->guid.'/'.$item->last_updated .'/0/'.elgg_get_plugin_setting('style','minds').')">';
		echo elgg_view('input/submit', array('name'=>'delete:'.$item->guid,'class'=>'elgg-button elgg-button-action remove', 'value'=>'x'));
		echo "<div class=\"drag entypo\">&#59404;</div>";
		$href = elgg_view('input/text', array('name'=>"$item->guid:href", 'class'=>'carousel-href', 'value'=>$item->href, 'placeholder'=>'Enter a url here... (optional)'));
		$shadow = elgg_view('input/text', array('name'=>"$item->guid:shadow", 'class'=>'shadow-color', 'value'=>$item->shadow?:'transparent', 'size' => 1));
		$color = elgg_view('input/text', array('name'=>"$item->guid:color", 'class'=>'text-color carousel-colorpicker', 'value'=>$item->color, 'size' => 1));
		$file = elgg_view('input/file', array('name'=>"$item->guid:background", 'class'=>'bg-input'));
	echo <<<HTML
		<div class="actions"> 
			<div class="href">
				<span>url</span>
				$href
			</div>
			<div class="shadow">
				<span>shadow</span>
				$shadow
			</div>
			<div class="color">
				<span>text color</span>
				$color
			</div>
			<div class="file">
				<span>background</span>
				$file
			</div>
		</div>
HTML;

		echo elgg_view('input/plaintext', array('name'=>"$item->guid:title", 'value'=>$item->title, 'placeholder'=>'Type here..', 'style'=>'color:'.$item->color .'; background:'.$item->shadow, 'rows'=>1));
		echo elgg_view('input/hidden', array('name'=>"$item->guid:order", 'value'=>$item->order, 'id'=>"order"));
	echo '</div>';
}

echo '</div>';
