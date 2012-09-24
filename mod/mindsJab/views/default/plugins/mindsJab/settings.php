<?php
/**
 *	Barter Plugin
 *	@package Barters
 **/
	$domain = elgg_get_plugin_setting("domain", "mindsJab");
	$dbname = elgg_get_plugin_setting("dbname", "mindsJab");
	$dbhost = elgg_get_plugin_setting("dbhost", "mindsJab");
	$dbuser = elgg_get_plugin_setting("dbuser", "mindsJab");
	$dbpassword = elgg_get_plugin_setting("dbpassword", "mindsJab");
?>
<p>
	<?php echo elgg_echo('beechat:domain'); ?>
	<?php echo elgg_view('input/text', array('internalname' => 'params[domain]','value' => $domain)); ?>
	<?php echo elgg_echo('beechat:dbname'); ?>
	<?php echo elgg_view('input/text', array('internalname' => 'params[dbname]','value' => $dbname)); ?>
	<?php echo elgg_echo('beechat:dbhost'); ?>
	<?php echo elgg_view('input/text', array('internalname' => 'params[dbhost]','value' => $dbhost)); ?>
	<?php echo elgg_echo('beechat:dbuser'); ?>
	<?php echo elgg_view('input/text', array('internalname' => 'params[dbuser]','value' => $dbuser)); ?>
	<?php echo elgg_echo('beechat:dbpassword'); ?>
	<?php echo elgg_view('input/password', array('internalname' => 'params[dbpassword]','value' => $dbpassword)); ?>

</p>

