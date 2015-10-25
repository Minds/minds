<?php
$order = $vars['order'];
$buyer = $order->getOwnerEntity();
?>

<h1>You have a new order. REF#<?=$order->guid?></h1>

<p>
	ITEM: <?= $order->item['title'] ?>
</p>
<p>
	QUANTITY: <?= $order->item['quantity'] ?>
</p>
<p>
	TOTAL: <?= $order->item['price'] * $order->item['quantity'] ?>
</p>

<br/>
<p>
	Owner: <?= $buyer->name ?> (<?= $buyer->username ?>)
</p>
