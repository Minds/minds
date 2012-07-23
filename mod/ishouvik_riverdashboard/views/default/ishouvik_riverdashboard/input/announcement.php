<?php
	$announcement_content = elgg_get_plugin_setting('announcement_content','ishouvik_riverdashboard');
?>

	<textarea style="width: 980px; height:250px;" name="<?php echo $vars['name']; ?>" ><?php echo $announcement_content; ?></textarea>
