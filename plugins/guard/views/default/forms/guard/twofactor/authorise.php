<?php
/**
 * Two factor setup
 */
$user = elgg_get_logged_in_user_entity();
?>
<p>We have just sent you an sms with an authentication code. Please enter it below in order to access Minds.</p>
<?php
echo elgg_view('input/text', array('name'=>'code', 'placeholder'=>'Enter your code'));
echo elgg_view('input/submit');
