<?php
/**
 * file to handle form submission for recaptcha settings form
 */

// get all the POST variables
$require_recaptcha = get_input('require_recaptcha');
$recaptcha_public_key = trim(get_input('recaptcha_public_key'));
$recaptcha_private_key = trim(get_input('recaptcha_private_key'));

if($require_recaptcha == 'on' && ($recaptcha_public_key == '' || $recaptcha_private_key == '') ) {
    register_error(elgg_echo('recaptcha:form_error'));
    forward(REFERER);
}

// get the plugin entity
$plugin_entity = elgg_get_plugin_from_id('recaptcha');
/* @var ElggPlugin $plugin_entity */

$plugin_entity->setPrivateSetting('require_recaptcha', $require_recaptcha);
$plugin_entity->setPrivateSetting('recaptcha_public_key', $recaptcha_public_key);
$plugin_entity->setPrivateSetting('recaptcha_private_key', $recaptcha_private_key);
system_message(elgg_echo('recaptcha:settings_saved'));



