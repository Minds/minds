<?php
/**
 * Pay - update quantity
 *
 * @package Pay
 */
 
elgg_load_library('elgg:pay');

$user = elgg_get_page_owner_entity();
$balance = pay_get_user_balance(elgg_get_logged_in_user_guid());
//Labels
$amount_label = elgg_echo('pay:withdraw:label:amount', array($balance));

$amount_input =  elgg_view('input/text',array('name' => 'amount', 'value'=>$balance, 'size' => '3'));

$pp_label = elgg_echo('pay:withdraw:paypal_address');

$pp_input =  elgg_view('input/text',array('name' => 'paypal_address', 'value'=>$user->email));

$user_guid = elgg_view('input/hidden',array('name' => 'user_guid','value'=>elgg_get_logged_in_user_guid()));

$submit_button = elgg_view('input/submit', array('value'=>'Request'));

echo <<<FORM
<div>
	<label>
	$amount_label
	</label>
	$amount_input
</div>

<div>
	<label>
	$pp_label
	</label>
	$pp_input
</div>

<div>
$user_guid
$submit_button
</div>
FORM;
?>