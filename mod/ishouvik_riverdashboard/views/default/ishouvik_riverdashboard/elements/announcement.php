<?php	
	$announcement_content = elgg_get_plugin_setting('announcement_content','ishouvik_riverdashboard');
	echo "<div id=\"announcement\">" . "<h2>" . elgg_echo('ishouvik:riverdashboard:annoucement') . "</h2>" . $announcement_content . "</div>";
	
	if (elgg_is_admin_logged_in()){
?>
	<a target="blank" href="<?php echo $vars['url']; ?>admin/plugin_settings/ishouvik_riverdashboard"><?php echo elgg_echo('ishouvik:riverdashboard:changeannoucement'); ?></a>
<?php } ?>
