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
	
$buyer = $order->getOwnerEntity();
$seller = $order->getSellerEntity();
$item = new minds\plugin\market\entities\item($order->item);
?>
<div class="minds-market-full">
	<?= $menu ?>
	<h1>Order: <?= $order->guid ?></h1>
	
	<div class="minds-market-order-table">
		<div class="row">
			<div class="cell">
				<h3>FROM</h3> 
				<a href="<?=$buyer->getURL()?>"><?= $buyer->name ?></a>
			</div>
			<div class="cell">
				<h3>TO</h3> 
				<a href="<?=$seller->getURL()?>"><?= $seller->name ?></a>
			</div>
			<div class="cell">
				<h3>DATE</h3> 
				<?= date('d/m/Y', $order->time_created) ?>
			</div>
			<div class="cell">
				<h3>TOTAL</h3>
				<?= $order->total ?> USD
			</div>
		</div>
		
	</div>
	<hr/>
	<div class="minds-market-order-table">
		<div class="row labels">
			<div class="cell">
				ITEM
			</div>
			<div class="cell">
				QUANTITY
			</div>
			<div class="cell">
				TOTAL (USD)
			</div>
		</div>
		
		<div class="row">
			<div class="cell">
				<a href="<?=$item->getURL()?>">
					<?= $item->title ?>
				</a>
			</div>
			<div class="cell">
				<?= $item->quantity ?>
			</div>
			<div class="cell">
				<?= $item->price * $item->quantity ?> USD
			</div>
		</div>
	</div>

	
	<br/>
	
	<?= elgg_view_comments($order); ?>
	
	
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

