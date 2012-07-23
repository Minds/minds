<?php
	$configured = $vars['configured'];

?>
<h3 class="settings"><?php echo elgg_echo('kalturavideo:admin:partnerpart'); ?></h3>
<p>
	<?php echo elgg_echo('kalturavideo:enterkmcdata'); ?>:
</p>
<p>
	<?php echo elgg_echo('kalturavideo:label:partner_id'); ?>:<br />
	<?php
		echo elgg_view('input/text', array('internalname' => 'partner_id','internalid' => 'partner_id','disabled'=>$configured, 'value' => get_plugin_setting('partner_id')));
	?>
</p>
<p>
	<?php echo elgg_echo('email'); ?>: <br />
	<?php
		echo elgg_view('input/text', array('internalname' => 'email','internalid' => 'email','disabled'=>$configured, 'value' => get_plugin_setting('email') ));
	?>
</p>
<p>
	<?php echo elgg_echo('password'); ?>:
	<?php
		echo elgg_view('input/password', array('internalname' => 'password','internalid' => 'password','disabled'=>$configured, 'value' => get_plugin_setting('password') ));

	if($configured) {
	?>
	<a href="#" id="kaltura_video_change_admin_data">&larr;<?php echo elgg_echo('kalturavideo:editpassword'); ?></a>
	<?php
	}
	?>
	<a href="<?php echo KalturaHelpers::getServerUrl(); ?>/index.php/kmc" id="kaltura_video_change_password" onclick="window.open(this.href);return false;"<?php echo ($configured ? ' style="display:none;"' : '') ?>><?php echo elgg_echo('kalturavideo:forgotpassword'); ?></a>
</p>
<p>
<?php echo sprintf(elgg_echo('kalturavideo:logintokaltura'),'<a href="'.KalturaHelpers::getServerUrl().'/index.php/kmc" onclick="window.open(this.href);return false;">'.elgg_echo('kalturavideo:login').'</a>'); ?>
</p>
