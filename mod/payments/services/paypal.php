<?php

namespace minds\plugin\payments\services;

use minds\core;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\CreditCard;
use PayPal\Api\CreditCardToken;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

class paypal extends core\base{
    
	protected $context;
	
	/**
	 * Init
	 */
	public function init(){
		$this->context = new ApiContext(
			new OAuthTokenCredential(
				elgg_get_plugin_setting('paypalKey', 'payments') ?: 'AaUOIRC8rTb2jXZtnUvjMXWH1BH-5spBnL2kILF2AEPygMxvWOqME3e06hnj',
				elgg_get_plugin_setting('paypalSecret', 'payments')?: 'EOWEZBB5n4Kc84mxXQhqF1rgz0GMKXyJ_fmWi5s1sk7k_35GeWTtXIwU6p2t'
				//'ATAByBA7wVln5oky2XKkglEoH7k0DJmZVOz3S-DGJYkrNrHcIjZCdX1HHLwH',
				//'EAJfIhCZXGo6L4YAiyFjlpPVKVspjwD5pYUanSPIDzTHU0lRLf8SP22BX2Q9'
			)
		);
		$this->context->setConfig(
			array(
		//		'mode' => 'live',
				'mode' => 'sandbox',
				'http.ConnectionTimeOut' => 30,
				'log.LogEnabled' => true,
				'log.FileName' => '/tmp/PayPal.log',
				'log.LogLevel' => 'FINE'
			)
		);
	}
	
	/**
	 * Create a credit card in the vault
	 * 
	 * @param array $params 
	 * @return CreditCard Object (paypal)
	 */
	public function createCard($params = array()){
		
		$params = array_merge(array(
			'type' => NULL,
			'number' => NULL,
			'month' => NULL,
			'year' => NULL,
			'sec' => NULL,
			'name' => NULL,
			'name2' => NULL
		), $params);
		
		$card = new CreditCard();
		$card->setType($params['type'])
			->setNumber($params['number'])
			->setExpireMonth($params['month'])
			->setExpireYear($params['year'])
			->setCvv2($params['sec'])
			->setFirstName($params['name'])
			->setLastName($params['name2']);
			
		return $card->create($this->context);
	}

	/**
	 * Return credit card information from the vault (not the info but just what we need from paypal)
	 * 
	 * @param string $id
	 * @return CreditCard Object (paypal)
	 */
	public function getCard($id){
		return CreditCard::get($id, $this->context);
	}	
	
	/**
	 * Perform a payment
	 */
	public function payment($amount_val = 0, $currency = 'USD', $description, $card){
		/*
		 * FundingInstrument
		 * A resource representing a Payer's funding instrument.
		 * for example, a recurring payments system would use the stored card
		 */
		$fi = new FundingInstrument();
		if(is_string($card)){
			$creditCardToken = new CreditCardToken();
			$creditCardToken->setCreditCardId($card);
			$fi->setCreditCardToken($creditCardToken);
		}else{ 
			$fi->setCreditCard($card);
		}
		
		/** Payer **/
		$payer = new Payer();
		$payer->setPaymentMethod("credit_card")
			->setFundingInstruments(array($fi));
		
		
		/** Amount **/
		$amount = new Amount();
		$amount->setCurrency("USD")
			->setTotal(number_format($amount_val, 2));
		
		/**
		 * Transaction Object
		 */
		$transaction = new Transaction();
		$transaction->setAmount($amount)
			->setDescription("Minds: $description");
		
		/*
		 * Payment object
		 */
		$payment = new Payment();
		$payment->setIntent("sale")
			->setPayer($payer)
			->setTransactions(array($transaction));
			
		return $payment->create($this->context);
	}
	
	/**
	 * Factory
	 */
	public static function factory(){
		return new paypal();
	}

}
