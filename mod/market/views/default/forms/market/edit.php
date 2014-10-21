<?php
$vars = array_merge(array(
	'title' => '',
	'description' => '',
	'price',
	'category'
), $vars);

$title = elgg_view('input/text', array('value'=>$vars['title'], 'name'=>'title', 'placeholder'=>'Title'));
$description = elgg_view('input/longtext', array('value'=>$vars['description'], 'name'=>'description', 'placeholder'=>'A brief description...'));
$price = elgg_view('input/text', array('value'=>$vars['price'], 'name'=>'price', 'placeholder'=>'eg. 0.10'));
$category = elgg_view('input/dropdown', array('value'=>$vars['category'], 'options'=> \minds\plugin\market\start::getCategories(), 'name'=>'category'));
$image = elgg_view('input/file', array('name'=>'image'));

$save = elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('save')));

?>

<div>
	<?= $title ?>
	<?= $description ?>
	<?= $price ?>
	<?= $category ?>
	<?= $image ?>
	<?= $save ?>
	
</div>
