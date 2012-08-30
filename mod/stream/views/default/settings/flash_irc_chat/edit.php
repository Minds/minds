<?php
/**
 *	Barter Plugin
 *	@package Barters
 **/
	$ircnetwork = get_plugin_setting("ircnetwork", "flash_irc_chat");
	$ircport = get_plugin_setting("ircport", "flash_irc_chat");
	$ircchan = get_plugin_setting("ircchan", "flash_irc_chat");
	$ircpolicy = get_plugin_setting("ircpolicy", "flash_irc_chat");
?>
<p>
	<?php echo elgg_echo('irc network (irc.freenode.net)'); ?>
	<?php echo elgg_view('input/text', array('internalname' => 'params[ircnetwork]','value' => $ircnetwork)); ?>
	<?php echo elgg_echo('port (6667)'); ?>
	<?php echo elgg_view('input/text', array('internalname' => 'params[ircport]','value' => $ircport)); ?>
	<?php echo elgg_echo('channel (#elgg)'); ?>
	<?php echo elgg_view('input/text', array('internalname' => 'params[ircchan]','value' => $ircchan)); ?>
	<?php echo elgg_echo('policy port (843)'); ?>
	<?php echo elgg_view('input/text', array('internalname' => 'params[ircpolicy]','value' => $ircpolicy)); ?>

</p>