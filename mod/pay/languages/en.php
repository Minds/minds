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
        'pay:paypal:api_user' => 'API User',
        'pay:paypal:api_password' => 'API Password',
        'pay:paypal:api_signature' => 'API Signature',
	
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
	/**
	 * Notifications
	 */
	 'pay:notification:order' => 'Thankyou for placing your order. Please check your payment account for updates',
	 'pay:notification:order_paid' => 'Thankyou for payment. Order:%s has now been paid.',
	 'pay:notification:seller:order' => '%s has placed an order with you. %s',
	 'pay:notification:seller:order_paid' => '%s has made payment for Order:%s',
	 'pay:notification:withdraw' => 'Your request for funds to be transfered to your account has been accepted. Please allow 3-4 days before the funds appear in your account.',
);

add_translation("en", $english);