<?php
/**
 * Elgg Pay - the language file EN
 *
 * @package Pay
 */

$english = array(

	/**
	 * Menu items and titles
	 */
	'pay' => 'Pay',
	
	'pay:topbar:count' => '%s items',
	
	'pay:admin:withdraw' => 'Withdrawal Admin',
	
	'pay:basket' => 'Basket',
	'pay:basket:total' => '<b>Your total is </b>',
	'pay:basket:empty' => 'Your basket is empty',
	'pay:basket:item:add:success' => 'Item successfuly added to basket',
	'pay:basket:item:add:failed' => 'Item could not be added to the basket',
	
	'pay:checkout' => 'Checkout',
	'pay:account' => 'Payment Account',
	'pay:account:none' => 'You have no activity.',
	'pay:account:order' => 'Order',
	'pay:account:order:view' => 'View order',
	'pay:account:order:status' => 'Order status: ',
	'pay:account:order:status:awaitingpayment' => 'Awaiting payment',
	'pay:account:order:status:created' => 'Created',
	'pay:account:order:status:Completed' => 'Completed',
	'pay:account:seller' => 'Seller account',
	
	'pay:payment:paypal' => 'Pay with PayPal', 
	
	'pay:paypal:business' => 'PayPal business email address', 
	
	'pay:withdraw' => 'Withdraw',
	'pay:withdraw:balance' => 'Balance: $%s',
	'pay:withdraw:label:amount' => 'How much do you want to withdraw? (You have $%s available)',
	'pay:withdraw:title' => 'Withdrawal',
	'pay:withdraw:insufficientfunds' => 'Sorry, you do not have sufficient funds to withdraw that amount. Please try again.',
	'pay:withdraw:mark:completed' => 'Mark as completed',
	'pay:withdraw:paypal_address' => 'Paypal email address',
	/* Settings
	 */
	'pay:settings:currency' => '<b>Currency</b> - enter the currency symbol EG. GBP, USD, EUR ...',
	'pay:settings:enabled_payment_handlers' => '<b>Payment Handlers</b> - select the payment handlers you wish to enable',
);

add_translation("en", $english);