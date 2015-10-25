<?php

$menu = elgg_view_menu('footer');
?>

<div class="cms-pages-sidebar">
	<?=$menu ?>
	
	<?php if(elgg_is_admin_logged_in()): ?>
	<div class="cms-pages-sidebar-admin">
		<a class="cms-pages-sidebar-admin-link" href="<?= elgg_get_site_url() ?>p/add">Add a new page</a>
	</div>
	<?php endif; ?>
</div>


