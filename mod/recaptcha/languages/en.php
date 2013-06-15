<?php
/**
 * recaptcha language pack.
 */

$english = array(
	
	'recaptcha:public_key' => 'Enter Public Key:',
    'recaptcha:private_key' => 'Enter Private Key:',
    'recaptcha:use_recaptcha_registration' => 'Use Recaptcha for user registration.',
    'recaptcha:form_error' => 'Both Public and Private keys are required',
    'recaptcha:settings_saved' => 'All settings successfully saved',
    'recaptcha:label:human_verification' => 'Human Verification: ',
	'recaptcha:human_verification_failed' => 'Human Verification Failed.<br>Please enter the correct values for the human verification field<br>
	    You can get a different challenge by clicking the refresh button indise the image',
    'recaptcha:signup' => 'Please sign up for recaptcha to get you public and private keys at %s',
    'recaptcha:verified' => 'Verified',
);

add_translation("en", $english);
