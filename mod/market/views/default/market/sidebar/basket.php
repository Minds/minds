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
	$<?= $basket->total() ?> / <?= $basket->countItems() ?> Items
</a>