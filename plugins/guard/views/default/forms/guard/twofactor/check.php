<?php
/**
 * Two factor setup
 */
$user = elgg_get_logged_in_user_entity();
echo elgg_view('input/text', array('name'=>'code', 'placeholder'=>'You should have recieved an sms'));
echo elgg_view('input/submit');
