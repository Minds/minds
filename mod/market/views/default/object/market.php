<?php
$item = $vars['entity'];
$full = elgg_extract('full_view', $vars, false);

$menu= elgg_view_menu('entity', array(
	'entity'=>$item,
	'handler' => 'market',
    'class' => 'elgg-menu-hz',
    'sort_by' => 'priority',
));

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
<?= $menu ?>
<div class="minds-market-item">
	<a href="<?= $item->getURL() ?>">
	</a>
	<a href="<?= $item->getURL() ?>">
		<h3><?= $title ?> <span class="price"> $<?= $item->price ?> </span> </h3>
	</a>
	<a class="elgg-button elgg-button-action" href="">Add to basket</a>
</div>	
<?php }

