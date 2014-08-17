<?php

// API used: /v1/payments/payment

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\CreditCard;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

$user = elgg_get_logged_in_user_entity();
$tier = new MindsTier(get_input('tier_guid'));
$domain = get_input('domain');

$apiContext = new ApiContext(
	new OAuthTokenCredential(
		'AWAkZhBE-utDCVpXbXlJhsbG1Kz5QsIZHXuK6vdZ7Qi3kbS3oI113y445VOT',
		'ENWFjBBNhJdmry2lvM39VakOEbgKzkojisIMqmYR-n3UQOwk_0FB-jn1T3lo'
	)
);

$apiContext->setConfig(
	array(
		'mode' => 'sandbox',
		'http.ConnectionTimeOut' => 30,
		'log.LogEnabled' => true,
		'log.FileName' => '../PayPal.log',
		'log.LogLevel' => 'FINE'
	)
);

/**
 * Add cards details
 */
$card = new CreditCard();
$card->setType(get_input('type'))
	->setNumber(get_input('number'))
	->setExpireMonth(get_input('month'))
	->setExpireYear(get_input('year'))
	->setCvv2(get_input('sec'))
	->setFirstName(get_input('name'))
	->setLastName(get_input('name2'));


/*
 * FundingInstrument
 * A resource representing a Payer's funding instrument.
 * for example, a recurring payments system would use the stored card
 */
$fi = new FundingInstrument();
$fi->setCreditCard($card);

/** Payer **/
$payer = new Payer();
$payer->setPaymentMethod("credit_card")
	->setFundingInstruments(array($fi));


/** Amount **/
$amount = new Amount();
$amount->setCurrency("USD")
	->setTotal("$tier->price.00");

/**
 * Transaction Object
 */
$transaction = new Transaction();
$transaction->setAmount($amount)
	->setDescription("Minds: $domain");

/*
 * Payment object
 */
$payment = new Payment();
$payment->setIntent("sale")
	->setPayer($payer)
	->setTransactions(array($transaction));

/**
 * Payment action
 */
try {
	$payment->create($apiContext);
} catch (PayPal\Exception\PPConnectionException $ex) {
	echo "Exception: " . $ex->getMessage() . PHP_EOL;
	var_dump($ex->getData());
	exit(1);
}


/**
 * Now save the card so we can continue to charge
 */
/**
 * Store the card (with paypal)
 * 
 */
try {
	$card->create($apiContext);	
	
	/**
	 * @todo create a card entity
	 */
} catch (PayPal\Exception\PPConnectionException $ex) {
	echo "Exception:" . $ex->getMessage() . PHP_EOL;
	var_dump($ex->getData());
	exit(1);
}

echo json_encode(array(
	'success' => array(
		'transaction_id' => $payment->getID()
)));

exit; 