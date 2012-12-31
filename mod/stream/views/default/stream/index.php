<?php
 	
$owner = elgg_get_logged_in_user_entity();
elgg_set_context('archive');
?>
<div class="stream">
	<div class="elgg-col-1of3">
		<a href="#">
			<img src="<?php echo elgg_get_site_url();?>mod/stream/graphics/circle.png"/>
			<h3><?php echo elgg_echo('stream:livestream');?></h3>
		</a>
	</div>
	
	<div class="elgg-col-1of3">
		<a href="<?php echo elgg_get_site_url();?>archive/upload/videoaudio">
			<img src="<?php echo elgg_get_site_url();?>mod/stream/graphics/ven.png"/>
			<h3><?php echo elgg_echo('stream:webcam');?></h3>
		</a>
	</div>
	
	<div class="elgg-col-1of3">
		<a href="<?php echo elgg_get_site_url();?>gatherings">
			<img src="<?php echo elgg_get_site_url();?>mod/stream/graphics/three.png"/>
			<h3><?php echo elgg_echo('stream:gatherings');?></h3>
		</a>
	</div>
</div>
