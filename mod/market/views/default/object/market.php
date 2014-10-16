<?php
$item = $vars['entity'];
$full = elgg_extract('full_view', $vars, false);

if ($full) {
?>
	<h1><?= $item->title ?></h1>
	<a href="<?= elgg_get_site_url() ?>market/basket/add/<?=$item->guid?>">$<?= $item->price ?>Add to basket</a>
	
<?php } else {
	/**
	 * Brief view
	 */
	$image = elgg_view('output/img', array());
	$title = $item->title;
	$price = $item->price;
?>

	<a href="<?= $item->getURL() ?>">
	<?= $title ?>
	<?= $item->price ?>
	</a>
	
<?php }

