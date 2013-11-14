<?php
/**
 * Callback for payment handler
 *
 * @package Pay
 */
 
elgg_load_library('elgg:pay');

elgg_set_context('pay_callback');

error_log('PAYPAL: DEBUG Callback fired');

global $CONFIG;
$order_guid = $CONFIG->input['guid'];
$order = get_entity($order_guid, 'object');

register_pam_handler('pam_auth_usertoken');

$user_pam = new ElggPAM('user');
$user_auth_result = $user_pam->authenticate(array());

$handler = $order->payment_method ? $order->payment_method : $CONFIG->input['payment_handler'];

if($user_auth_result){
	pay_call_payment_handler_callback($handler, $order_guid);
	//pay_call_payment_handler_callback('paypal', $order_guid);
} else {
	echo 'Callback could not be authenticated';
        error_log('PAYPAL: DEBUG Callback could not be authenticated');
}