<?php
$order = $vars['order'];
?>

<h1>Your order has been placed. REF#<?=$order->guid?></h1>

<p>
	ITEM: <?= $order->item['title'] ?>
</p>
<p>
	QUANTITY: <?= $order->item['quantity'] ?>
</p>
<p>
	TOTAL: <?= $order->item['price'] * $order->item['quantity'] ?>
</p>