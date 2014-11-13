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
		<?php if($item->size){ ?>
			<p>Size: <?= $item->size ?></p>
		<?php } ?>
		<?php if($item->color){ ?>
			<p>Color: <?= $item->color ?></p>
		<?php } ?>
		<?php if($item->stock){ ?>
			<p>Stock: <?= $item->stock ?></p>
		<?php } ?>
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
	<?php if($item->image):?>
	<a href="<?= $item->getURL() ?>" class="minds-market-thumbnail">
		<img src="<?= $item->getIconURL('thumb') ?>"/>
	</a>
	<?php endif; ?>
	<a href="<?= $item->getURL() ?>">
		<h3><?= $title ?> <span class="price"> $<?= $item->price ?> </span> </h3>
	</a>
	<a class="elgg-button elgg-button-action" href="<?= elgg_get_site_url() . "market/basket/add/$item->guid" ?>">$<?= $item->price ?> - Add to basket</a>
</div>	
<?php }

