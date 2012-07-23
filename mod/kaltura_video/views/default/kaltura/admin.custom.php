<?php

	require_once($CONFIG->pluginspath."kaltura_video/kaltura/api_client/definitions.php");
	global $KALTURA_GLOBAL_UICONF;

	$configured = $vars['configured'];

?>
<h3 class="settings"><?php echo elgg_echo('kalturavideo:admin:player'); ?></h3>

<p><?php echo elgg_echo('kalturavideo:changeplayer'); ?></p>
<p>
	<?php echo elgg_echo('kalturavideo:label:defaultplayer'); ?>:
	<?php
		$t = get_plugin_setting('kaltura_server_type');
		if(empty($t)) $t = 'corp';
		$widgets = $KALTURA_GLOBAL_UICONF['kdp'][$t];

		$default = get_plugin_setting('defaultplayer');
		$vals = array();
		foreach($widgets as $k => $v) {
			$vals[$k] = $v['name'].' ('.elgg_echo("kalturavideo:generic").' - ' .$v['width'].'x'.$v['height'].'px)';
		}

		$vals['custom'] = elgg_echo("kalturavideo:customplayer");

		reset($widgets);
		if(empty($default)) $default = key($widgets);
	?>
		<?php
		echo elgg_view('input/pulldown', array(
			'internalname' => 'defaultplayer',
			'internalid' => 'defaultplayer',
			'options_values' => $vals,
			'value' => $default
		));
	?>
</p>

<div id="kaltura_video_layer_defaultplayer"<?php echo (get_plugin_setting('defaultplayer')!='custom' ? 'style="display:none;"' : ''); ?> rel="1">
<p>
	<?php echo elgg_echo('kalturavideo:uiconf1'); ?>:
	<?php
		echo elgg_view('input/url', array('internalname' => 'custom_kdp','internalid' => 'custom_kdp', 'value' => get_plugin_setting('custom_kdp'), 'class' => 'input-short' ));
		echo '<a href="#" id="kaltura_video_getlist_custom_kdp">&larr;'.elgg_echo("kalturavideo:uiconf:getlist").'</a>'
	?>
</p>
<p><?php echo sprintf(elgg_echo('kalturavideo:text:uiconf1'),'<a href="'.KalturaHelpers::getServerUrl().'/index.php/kmc" onclick="window.open(this.href);return false;">'.elgg_echo('kalturavideo:login').'</a>'); ?></p>

</div>




<h3 class="settings"><?php echo elgg_echo('kalturavideo:admin:editor'); ?></h3>

<p>
	<?php echo elgg_echo('kalturavideo:label:defaultkcw'); ?>:
	<?php
		$t = get_plugin_setting('kaltura_server_type');
		if(empty($t)) $t = 'corp';
		$widgets = $KALTURA_GLOBAL_UICONF['kcw'][$t];

		$default = get_plugin_setting('defaultkcw');
		$vals = array();
		foreach($widgets as $k => $v) {
			$vals[$k] = $v['name'].' ('.elgg_echo("kalturavideo:generic").')';
		}

		$vals['custom'] = elgg_echo("kalturavideo:customkcw");

		reset($widgets);
		if(empty($default)) $default = key($widgets);
	?>
		<?php
		echo elgg_view('input/pulldown', array(
			'internalname' => 'defaultkcw',
			'internalid' => 'defaultkcw',
			'options_values' => $vals,
			'value' => $default
		));
	?>
</p>

<div id="kaltura_video_layer_defaultkcw"<?php echo (get_plugin_setting('defaultkcw')!='custom' ? 'style="display:none;"' : ''); ?> rel="2">
<p>
	<?php echo elgg_echo('kalturavideo:uiconf2'); ?>:
	<?php
		echo elgg_view('input/url', array('internalname' => 'custom_kcw','internalid' => 'custom_kcw', 'value' => get_plugin_setting('custom_kcw'), 'class' => 'input-short' ));
		echo '<a href="#" id="kaltura_video_getlist_custom_kcw">&larr;'.elgg_echo("kalturavideo:uiconf:getlist").'</a>'
	?>
</p>
</div>

<p>
	<?php echo elgg_echo('kalturavideo:label:defaulteditor'); ?>:
	<?php
		$t = get_plugin_setting('kaltura_server_type');
		if(empty($t)) $t = 'corp';
		$widgets = $KALTURA_GLOBAL_UICONF['kse'][$t];

		$default = get_plugin_setting('defaulteditor');
		$vals = array();
		foreach($widgets as $k => $v) {
			$vals[$k] = $v['name'].' ('.elgg_echo("kalturavideo:generic").')';
		}

		$vals['custom'] = elgg_echo("kalturavideo:customeditor");

		reset($widgets);
		if(empty($default)) $default = key($widgets);
	?>
		<?php
		echo elgg_view('input/pulldown', array(
			'internalname' => 'defaulteditor',
			'internalid' => 'defaulteditor',
			'options_values' => $vals,
			'value' => $default
		));
	?>
</p>

<div id="kaltura_video_layer_defaulteditor"<?php echo (get_plugin_setting('defaulteditor')!='custom' ? 'style="display:none;"' : ''); ?> rel="3">
<p>
	<?php echo elgg_echo('kalturavideo:uiconf3'); ?>:
	<?php
		echo elgg_view('input/url', array('internalname' => 'custom_kse','internalid' => 'custom_kse', 'value' => get_plugin_setting('custom_kse'), 'class' => 'input-short' ));
		echo '<a href="#" id="kaltura_video_getlist_custom_kse">&larr;'.elgg_echo("kalturavideo:uiconf:getlist").'</a>';
	?>
</p>
</div>
