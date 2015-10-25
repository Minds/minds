<?php 
$transaction = $vars['transaction'];
?>

<h1>Thanks for your order..</h1> 

<p>Your order has been succesfully placed and we are now processing payment. Your confirmation code is: <b><?= $transaction->guid ?></b></p>

<p><b><?=$transaction->description?></b> - $<?= $transaction->amount ?> USD</p>