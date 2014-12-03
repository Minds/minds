<?php
/**
 * Sidebar
 */
 
?>

<a href="<?= elgg_get_site_url()?>market/featured" class="market-menu-item <?= $_SERVER['REQUEST_URI'] == '/market/featured' ? 'active' : '' ?>">
	Market: Featured
</a>
<a href="<?= elgg_get_site_url()?>market/all" class="market-menu-item">
	Market: All
</a>
<?php if(elgg_is_logged_in()): ?>
<a href="<?= elgg_get_site_url()?>market/orders" class="market-menu-item">
	My Orders
</a>
<?php endif; ?>
<div class="market-sidebar-section market-sidebar-section-categories">
	<h3>Filter</h3>
	<?= elgg_view('market/sidebar/categories') ?>
</div>

<div class="market-sidebar-section market-sidebar-section-basket">
	<h3>Basket</h3>
	<?= elgg_view('market/sidebar/basket') ?>
</div>

<?php if(elgg_is_logged_in()): ?>
<div class="market-sidebar-section market-sidebar-section-categories">
	<h3>Seller</h3>
	<a href="<?= elgg_get_site_url(); ?>market/add" class="market-menu-item market-menu-item-add">Add an item</a>
	<a href="<?= elgg_get_site_url(); ?>market/owner/<?=elgg_get_logged_in_user_entity()->username?>" class="market-menu-item">My items</a>
	<a href="<?= elgg_get_site_url(); ?>market/seller/orders" class="market-menu-item">Placed orders</a>
</div>
<?php endif; ?>