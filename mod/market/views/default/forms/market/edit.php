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
	<p>
		<b>Title: </b>
		<?= $title ?>
	</p>
	<p>
		<b>Description: </b>
	<?= $description ?>
	</p>
	<p>
		<b>Price: </b>
	<?= $price ?>
	</p>
	<p>
		<b>Category: </b>
	<?= $category ?>
	</p>
	<p>
		<b>Image: </b>
	<?= $image ?>
	</p>
	<p> 
	<?= $save ?>
	</p>
	
</div>
