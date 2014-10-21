<?php
/**
 * Sidebar
 */
 
?>


<a href="<?= elgg_get_site_url()?>market/all" class="market-menu-item">
	Market: All
</a>
<a href="<?= elgg_get_site_url()?>market/orders" class="market-menu-item">
	My Orders
</a>
<div class="market-sidebar-section market-sidebar-section-categories">
	<h3>Filter</h3>
	<?= elgg_view('market/sidebar/categories') ?>
</div>

<div class="market-sidebar-section market-sidebar-section-basket">
	<h3>Basket</h3>
	<?= elgg_view('market/sidebar/basket') ?>
</div>

<div class="market-sidebar-section market-sidebar-section-categories">
	<h3>Seller</h3>
	<a href="<?= elgg_get_site_url(); ?>market/add" class="market-menu-item market-menu-item-add">Add an item</a>
</div>