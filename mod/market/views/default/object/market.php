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
<div class="minds-market-full">
	<?= $menu ?>
	<h1><?= $item->title ?></h1>
	
	<div class="minds-market-subbanner">
		<a href="<?= elgg_get_site_url() ?>market/basket/add/<?=$item->guid?>" class="elgg-button elgg-button-action add-to-basket">$<?= $item->price ?> - Add to basket</a>
		
		<div class="market-owner-block">
			<a href="<?= $item->getURL() ?>">
				<img src="<?= $item->getOwnerEntity()->getIconURL('small'); ?>"/>
				<?= $item->getOwnerEntity()->name; ?>
			</a>
		</div>
	</div>
	
	<div class="minds-market-description">
		<?= $item->description ?>
	</div>
	
	
</div>
<?php } else {
	/**
	 * Brief view
	 */
	$title = $item->title;
	$price = $item->price;
?>
<?= $menu ?>
<div class="minds-market-item">
	<a href="<?= $item->getURL() ?>" class="minds-market-thumbnail">
		<img src="<?= $item->getIconURL('thumb') ?>"/>
	</a>
	<a href="<?= $item->getURL() ?>">
		<h3><?= $title ?> <span class="price"> $<?= $item->price ?> </span> </h3>
	</a>
	<a class="elgg-button elgg-button-action" href="<?= elgg_get_site_url() . "market/basket/add/$item->guid" ?>">$<?= $item->price ?> - Add to basket</a>
</div>	
<?php }

