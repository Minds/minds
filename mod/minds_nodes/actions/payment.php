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

use minds\plugin\payments\entities;

$user = elgg_get_logged_in_user_entity();
$tier = new MindsTier(get_input('tier_guid'));
$domain = get_input('domain');

$apiContext = new ApiContext(
	new OAuthTokenCredential(
		//'AWAkZhBE-utDCVpXbXlJhsbG1Kz5QsIZHXuK6vdZ7Qi3kbS3oI113y445VOT',
		//'ENWFjBBNhJdmry2lvM39VakOEbgKzkojisIMqmYR-n3UQOwk_0FB-jn1T3lo'
		'ATAByBA7wVln5oky2XKkglEoH7k0DJmZVOz3S-DGJYkrNrHcIjZCdX1HHLwH',
		'EAJfIhCZXGo6L4YAiyFjlpPVKVspjwD5pYUanSPIDzTHU0lRLf8SP22BX2Q9'
	)
);

$apiContext->setConfig(
	array(
		'mode' => 'live',
		'http.ConnectionTimeOut' => 30,
		'log.LogEnabled' => true,
		'log.FileName' => '../PayPal.log',
		'log.LogLevel' => 'FINE'
	)
);

/**
 * Add cards details
 */
$card = new entities\card();
$card_obj = $card->create(array(
	'type' => get_input('type'),
	'number' => get_input('number'),
	'month' => get_input('month'),
	'year' => get_input('year'),
	'sec' => get_input('sec'),
	'name' => get_input('name'),
	'name2' => get_input('name2')
	));
$card->save();

$id = minds\plugin\payments\start::createPayment('Hosting for '.$domain, $tier->price, $card->card_id);

echo json_encode(array(
	'success' => array(
		'transaction_id' => $id
	)));

exit; 