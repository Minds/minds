<?php

	$configured = $vars['configured'];

?>
<h3 class="settings"><?php echo elgg_echo('kalturavideo:admin:serverpart'); ?></h3>
<p>
	<?php echo elgg_echo('kalturavideo:server:info'); ?>
</p>
<p>
	<?php echo elgg_echo('kalturavideo:server:type'); ?>:
	<?php
		echo elgg_view('input/pulldown', array(
			'internalname' => 'kaltura_server_type',
			'internalid' => 'kaltura_server_type',
			'disabled'=>$configured,
			'options_values' => array(
				"corp" => elgg_echo('kalturavideo:server:kalturacorp'),
				"ce" => elgg_echo('kalturavideo:server:kalturace')
				),
			'value' => (get_plugin_setting('kaltura_server_type') ? get_plugin_setting('kaltura_server_type') : 'corp')
			)
		);
	?>
</p>
<div id="kaltura_video_layer_server_corp"<?php echo (get_plugin_setting('kaltura_server_type')=='ce' ? 'style="display:none;"' : ''); ?>>
<p>
	<?php echo elgg_echo('kalturavideo:server:corpinfo'); ?>
<br />
	<?php echo elgg_echo('kalturavideo:server:moreinfo'); ?> <a href="<?php echo KALTURA_SERVER_URL; ?>" onclick="window.open(this.href);return false;"><?php echo elgg_echo('kalturavideo:server:kalturacorp'); ?></a>
</p>

<p>
	<?php echo elgg_echo('kalturavideo:notpartner'); ?> <a href="?type=partner_wizard"><?php echo elgg_echo('kalturavideo:clickifnewpartner'); ?></a>
</p>
</div>
<div id="kaltura_video_layer_server_ce"<?php echo (get_plugin_setting('kaltura_server_type')=='ce' ? '' : 'style="display:none;"'); ?>>
<p>
	<?php echo elgg_echo('kalturavideo:server:ceurl'); ?>: <br />
	<?php
		echo elgg_view('input/text', array('internalname' => 'kaltura_server_url','internalid' => 'kaltura_server_url','disabled'=>$configured, 'value' => (get_plugin_setting('kaltura_server_url') ? get_plugin_setting('kaltura_server_url') : $CONFIG->wwwroot.'kalturaCE/') ));
	?>
</p>

<p>
	<?php echo elgg_echo('kalturavideo:server:ceinfo'); ?>
<br />
	<?php echo elgg_echo('kalturavideo:server:moreinfo'); ?> <a href="http://www.kaltura.org/project/community_edition_video_platform" onclick="window.open(this.href);return false;"><?php echo elgg_echo('kalturavideo:server:kalturace'); ?></a>
</p>

</div>
