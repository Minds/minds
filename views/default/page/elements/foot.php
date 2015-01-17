<?php

echo elgg_view('footer/analytics');

$js = Minds\Core\resources::getLoaded('js', 'footer');
foreach ($js as $script) { ?>
	<script type="text/javascript" src="<?php echo $script['src']; ?>"></script>
<?php
}

?>
<script type="text/javascript">
		<?php echo elgg_view('js/initialize_elgg'); ?>
	</script>
