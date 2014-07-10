<?php
elgg_load_js('player');
elgg_load_css('player');
elgg_load_js('player-res');
elgg_load_css('player-res');
?>
<div class="player-container">
	<video id="archive_player2" preload="auto" poster="MY_VIDEO_POSTER.jpg" controls class="video-js vjs-default-skin" width="100%" height="460">
		  <?php foreach($vars['sources'] as $uri => $array):?>
		 <source src="<?= $uri?>" type="<?= $array['type'] ?>" data-res="<?= $array['name']?>">
		 <?php endforeach; ?>
	</video>
</div>
