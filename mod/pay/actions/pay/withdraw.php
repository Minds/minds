<?php
/**
 * Pay - withdraw funds from a users account
 *
 * @package Pay
 */
elgg_load_library('elgg:pay');

//variables
$user_guid = get_input('user_guid');
$user = get_entity($user_guid, 'user');
$amount = get_input('amount');
$paypal_address = get_input('paypal_address');

if($amount > pay_get_user_balance($user_guid)){
	register_error(elgg_echo("pay:withdraw:insufficientfunds"));
	forward();
}

//We create a new order object								
$order = new ElggObject();
$order->subtype = 'pay';

$order->withdraw = true;

//temp variables
$order->seller_guid = $user_guid;

$order->paypal_address = $paypal_address;

$order->amount = abs($amount) * -1;
$order->status = 'created';

$order->payment_method = 'paypal';

if($order->save()){
	system_message(elgg_echo("pay:withdraw:request:success"));
} else {
	register_error(elgg_echo("pay:withdraw:request:failed"));
}

forward('pay/account/seller/'.$user->username);