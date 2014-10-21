<?php
/**
 * Load basket items
 */
 
$items = $vars['items'];
?>

<div class="minds-market-basket">

	<div class="row labels">
		<div class="cell item"></div>
		<div class="cell price">Price</div>
		<div class="cell quantity">Quantity</div>
		<div class="cell quantity"></div>
	</div>
	<?php foreach($items as $item): ?>
		<div class="row">
			<?= elgg_view('market/basket/item', array('item'=>$item)); ?>
		</div>
	<?php endforeach; ?>

</div>

<a class="minds-market-button minds-market-button-checkout" href="<?= elgg_get_site_url() ?>market/checkout">
	Checkout
</a>
