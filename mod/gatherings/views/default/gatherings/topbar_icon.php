<span class="gatherings">
	<a href="<?= elgg_get_site_url() ?>gatherings/conversations" class="entypo">&#59168;
	<?php 
		$count = minds\plugin\gatherings\counter::get();
		if((int)$count):
	?>
	<span class="count"><?=$count?></span>
	<?php endif; ?>
	</a>
</span>
