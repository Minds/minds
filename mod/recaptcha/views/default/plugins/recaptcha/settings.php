<?php
/**
 * recaptcha plugin settings form.
 * form submission handled at /mod/recaptcha/actions/recaptcha/save.php
 */

$require_recaptcha = '';
$recaptcha_public_key = '';
$recaptcha_private_key = '';

// get the plugin settings
$plugin_entity = elgg_get_plugin_from_id('recaptcha');
$plugin_settings = $plugin_entity->getAllSettings();

if (isset($plugin_settings['require_recaptcha'])) $require_recaptcha = $plugin_settings['require_recaptcha'];
if (isset($plugin_settings['recaptcha_public_key'])) $recaptcha_public_key = $plugin_settings['recaptcha_public_key'];
if (isset($plugin_settings['recaptcha_private_key'])) $recaptcha_private_key = $plugin_settings['recaptcha_private_key'];


$form_body = "<div>";

$recaptcha_signup_link = elgg_view('output/url', array(
    'text' => 'reCaptcha Sign Up Page',
    'href' => 'http://www.google.com/recaptcha/whyrecaptcha',
));
$form_body .= '<label>'.elgg_echo('recaptcha:signup', array($recaptcha_signup_link)).'</label><br><br>';

// use recaptcha for registration option
//checkbox
$options = array(
    'name' => 'require_recaptcha',
    'default' => '',
);
if($require_recaptcha != '') $options['checked'] = '';
$form_body .= "<div>"
    . elgg_view("input/checkbox", $options)
    . '<b>'. elgg_echo('recaptcha:use_recaptcha_registration') .'</b>'
    . "</div><br>";

// public key
$form_body .= '<label>'.elgg_echo('recaptcha:public_key') . '</label><br>';
$form_body .= elgg_view(
	"input/text",
	array(	
		'name' => 'recaptcha_public_key',
		'value' => $recaptcha_public_key
));

$form_body .= "<br><br>";

/* private key
 * shuffle the string to avoid anyone from looking at it by using the "element properties"
 */
$form_body .= '<label>'.elgg_echo('recaptcha:private_key') . '</label><br>';
$form_body .= elgg_view(
	"input/password",
	array(	
		'name' => 'recaptcha_private_key',
		'value' => $recaptcha_private_key
));

$form_body .= "</div>";
	
echo $form_body;


