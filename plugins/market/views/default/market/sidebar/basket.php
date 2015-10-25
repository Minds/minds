<?php
/**
 * The basket
 * 
 * @todo remove much of the logic out of here
 */

use minds\plugin\market\entities;

$basket = new entities\basket();
?>

<a href="<?= elgg_get_site_url()?>market/basket" class="market-menu-item">
	<?= $basket->countItems() ?> Items - $<?= $basket->total() ?>
</a>
<?php if($basket->countItems() > 0){ ?>
<a href="<?= elgg_get_site_url()?>market/checkout" class="market-menu-item">
	Checkout
</a>
<?php } ?>
