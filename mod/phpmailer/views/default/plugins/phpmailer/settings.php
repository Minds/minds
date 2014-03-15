<?php
/**
 * PHPMailer plugin settings
 */

// override Elgg mail handler
echo '<div>';
$checked = $vars['entity']->phpmailer_override != 'disabled' ? 'checked' : false;
echo elgg_view('input/checkbox', array(
	'name' => 'params[phpmailer_override]',
	'value' => 'enabled',
	'checked' => $checked,
	'default' => 'disabled',
));
echo ' ' . elgg_echo('phpmailer:override') . '</div>';

// SMTP Settings
echo '<fieldset class="elgg-border-plain mbm pas">';
echo '<div>';
$checked = elgg_get_plugin_setting('phpmailer_smtp', 'phpmailer') ? 'checked' : false;
echo elgg_view('input/checkbox', array(
	'name' => 'params[phpmailer_smtp]',
	'value' => 1,
	'checked' => $checked,
	'default' => 0,
	'id' => 'phpmailer-smtp',
));
echo ' ' . elgg_echo('phpmailer:smtp') . '<br/>';

echo elgg_echo('phpmailer:host') . ': ';
echo elgg_view('input/text', array(
	'name' => 'params[phpmailer_host]',
	'value' => elgg_get_plugin_setting('phpmailer_host', 'phpmailer'),
	'class' => 'phpmailer-smtp elgg-input-natural',
));

echo '<br /><br />';
$checked = elgg_get_plugin_setting('phpmailer_smtp_auth', 'phpmailer') ? 'checked' : false;
echo elgg_view('input/checkbox', array(
	'name' => 'params[phpmailer_smtp_auth]',
	'value' => 1,
	'checked' => $checked,
	'default' => 0,
	'class' => 'phpmailer-smtp',
	'id' => 'phpmailer-smtp-auth',
));
echo ' ' . elgg_echo('phpmailer:smtp_auth') . '<br/>';

echo elgg_echo('phpmailer:username') . ': ';
echo elgg_view('input/text', array(
	'name' => 'params[phpmailer_username]',
	'value' => elgg_get_plugin_setting('phpmailer_username', 'phpmailer'),
	'class' => 'phpmailer-smtp phpmailer-smtp-auth elgg-input-natural',
));

echo '<br />';
echo elgg_echo('phpmailer:password') . ':';
echo elgg_view('input/password', array(
	'name' => 'params[phpmailer_password]',
	'value' => elgg_get_plugin_setting('phpmailer_password', 'phpmailer'),
	'class' => 'phpmailer-smtp phpmailer-smtp-auth elgg-input-natural mts',
));
echo '</div>';

 // ssl connection for smtp (with port info)
echo '<div>';
$checked = elgg_get_plugin_setting('ep_phpmailer_ssl', 'phpmailer') ? 'checked' : false;
echo elgg_view('input/checkbox', array(
	'name' => 'params[ep_phpmailer_ssl]',
	'value' => 1,
	'checked' => $checked,
	'default' => 0,
	'class' => 'phpmailer-smtp',
	'id' => 'phpmailer-ssl',
));
echo ' ' . elgg_echo('phpmailer:ssl') . '<br/>';

echo elgg_echo('phpmailer:port') . ':';
echo elgg_view('input/text', array(
	'name' => 'params[ep_phpmailer_port]',
	'value' => elgg_get_plugin_setting('ep_phpmailer_port', 'phpmailer'),
	'class' => 'phpmailer-smtp phpmailer-ssl elgg-input-natural',
));
echo '</div>';
echo '</fieldset>';

// Non-standard MTA setting
echo '<div>';
$checked = elgg_get_plugin_setting('nonstd_mta', 'phpmailer') ? 'checked' : false;
echo elgg_view('input/checkbox', array(
	'name' => 'params[nonstd_mta]',
	'value' => 1,
	'checked' => $checked,
	'default' => 0,
));
echo ' ' . elgg_echo('phpmailer:nonstd_mta');
echo '</div>';

?>

<script type="text/javascript">
	$(document).ready(function() {
		phpmailer_form_update();

		$('#phpmailer-smtp').change(phpmailer_form_update);
		$('#phpmailer-smtp-auth').change(phpmailer_form_update);
		$('#phpmailer-ssl').change(phpmailer_form_update);
	});

	function phpmailer_form_update() {
		if ($('#phpmailer-ssl').attr('checked')) {
			$('.phpmailer-ssl').removeAttr('disabled');
		}
		if ($('#phpmailer-smtp-auth').attr('checked')) {
			$('.phpmailer-smtp-auth').removeAttr('disabled');
		}

		if (!$('#phpmailer-smtp').attr('checked')) {
			$('.phpmailer-smtp').attr('disabled', 'disabled');
		} else {
			$('.phpmailer-smtp').removeAttr('disabled');
		}

		if (!$('#phpmailer-smtp-auth').attr('checked')) {
			$('.phpmailer-smtp-auth').attr('disabled', 'disabled');
		}
		if (!$('#phpmailer-ssl').attr('checked')) {
			$('.phpmailer-ssl').attr('disabled', 'disabled');
		}
	}
</script>
