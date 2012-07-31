<?php	
	$announcement_content = elgg_get_plugin_setting('announcement_content','minds_theme');
	echo "<div id=\"announcement\">" . "<h2>" . elgg_echo('minds:riverdashboard:annoucement') . "</h2>" . $announcement_content . "</div>";
	
	if (elgg_is_admin_logged_in()){
?>
	<a target="blank" href="<?php echo $vars['url']; ?>admin/plugin_settings/minds_theme"><?php echo elgg_echo('minds:riverdashboard:changeannoucement'); ?></a>
<?php } ?>
