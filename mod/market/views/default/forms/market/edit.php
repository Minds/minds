<?php
$vars = array_merge(array(
	'title' => '',
	'description' => '',
	'price',
	'category',
	'color' => ''
), $vars);

$title = elgg_view('input/text', array('value'=>$vars['title'], 'name'=>'title', 'placeholder'=>'Title'));
$description = elgg_view('input/longtext', array('value'=>$vars['description'], 'name'=>'description', 'placeholder'=>'A brief description...'));
$price = elgg_view('input/text', array('value'=>$vars['price'], 'name'=>'price', 'placeholder'=>'eg. 0.10'));
$category = elgg_view('input/dropdown', array('value'=>$vars['category'], 'options'=> \minds\plugin\market\start::getCategories(), 'name'=>'category'));
$image = elgg_view('input/file', array('name'=>'image'));
$color = elgg_view('input/text', array('value'=>$vars['color'], 'name'=>'color', 'placeholder'=>'optional'));
$size =  elgg_view('input/text', array('value'=>$vars['size'], 'name'=>'size', 'placeholder'=>'optional'));
$stock = elgg_view('input/text', array('value'=>$vars['stock'], 'name'=>'stock', 'placeholder'=>'optional'));

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
                <b>Color: </b>
		<?= $color ?>
        </p>
	<p>
                <b>Size: </b>
        	<?= $size ?>
        </p>
	<p>
                <b>Stock: </b>
        	<?= $stock ?>
        </p>

	<p> 
	<?= $save ?>
	</p>
	
</div>
