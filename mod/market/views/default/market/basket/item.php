<?php
$item = $vars['item'];
?>

<div class="cell item">
	<?= $item->title ?>
</div>
<div class="cell price">
	$<?= $item->price * $item->quantity ?>
</div>
<div class="cell quantity">
	<?= $item->quantity ?>
</div>
<div class="cell remove">
	
</div>