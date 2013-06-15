<?php

/**
 * view to include recaptcha field in the user registration form
 * overrides Elggs default view /views/default/input/captcha.php
 * called in /views/default/forms/register.php [ echo elgg_view('input/captcha') ]
 *
 */

//add the recaptcha only if it has been enabled in the plugin setting
$plugin_entity = elgg_get_plugin_from_id('recaptcha');
$plugin_settings = $plugin_entity->getAllSettings();

if(array_key_exists('recaptcha_verified', $_SESSION) && $_SESSION['recaptcha_verified'] == 1) {

    // no need for recaptcha again - user has already entered the correct value previously
   $output = "<label>".elgg_echo('recaptcha:label:human_verification')."</label><b>".elgg_echo('recaptcha:verified')."</b><br><br>";
}
else {
    if($plugin_settings['require_recaptcha'] == 'on') {

        // include the recaptcha lib
        require_once(elgg_get_plugins_path() . 'recaptcha/lib/recaptchalib.php');

        $publickey = $plugin_settings['recaptcha_public_key'];
        $output = "<label>".elgg_echo('recaptcha:label:human_verification')."</label><br>";
        $output .= recaptcha_get_html($publickey);
        $output .= '<br>';
    }
    else $output = '';
}

echo $output;