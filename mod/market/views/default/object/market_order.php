<?php
$order = $vars['entity'];
$full = elgg_extract('full_view', $vars, false);

$menu= elgg_view_menu('entity', array(
	'entity'=>$order,
	'handler' => 'market_order',
    'class' => 'elgg-menu-hz',
    'sort_by' => 'priority',
));

if ($full) {
?>
<div class="minds-market-full">
	<?= $menu ?>
	<h1><?= $order->title ?></h1>
	
	<div class="minds-market-subbanner">
		<a href="<?= elgg_get_site_url() ?>market/basket/add/<?=$order->guid?>" class="elgg-button elgg-button-action add-to-basket">$<?= $order->price ?> - Add to basket</a>
		
		<div class="market-owner-block">
			<a href="<?= $order->getURL() ?>">
				<img src="<?= $order->getOwnerEntity()->getIconURL('small'); ?>"/>
				<?= $order->getOwnerEntity()->name; ?>
			</a>
		</div>
	</div>
	
	<div class="minds-market-description">
		<?= $order->description ?>
	</div>
	
	
</div>
<?php } else {
	/**
	 * Brief view
	 */
	$title = $order->title;
	$total = $order->total ?: ($order->item['price'] * $order->item['quantity']);
	$created = elgg_view_friendly_time($order->time_created);
?>
<?= $menu ?>
<div class="minds-market-order-item">
	<a href="<?= $order->getURL() ?>">
		<h3><?= $order->guid ?> <span class="price"> $<?= $total ?> </span> </h3>
		<p><?=$created?></p>
	</a>
</div>	
<?php }

