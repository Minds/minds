<?php
/**
 * Braintree service controller
 */

namespace Minds\Core\Payments\Braintree;

use Minds\Core;
use Minds\Core\Payments\PaymentServiceInterface;

use Braintree_ClientToken;
use Braintree_Configuration;
use Braintree_Transaction;

class Braintree implements PaymentServiceInterface{

  public function __construct($options = array()){
    $this->setConfig($options);
  }

  private function setConfig($config){
    $defaults = array(
      'environment' => Core\Config::_()->payments['braintree']['environment'] ?: 'sandbox',
      'merchant_id' => Core\Config::_()->payments['braintree']['merchant_id'],
      'public_key' => Core\Config::_()->payments['braintree']['public_key'],
      'private_key' => Core\Config::_()->payments['braintree']['private_key']
    );
    $config = array_merge($defaults, $config);
    Braintree_Configuration::environment($config['environment']);
    Braintree_Configuration::merchantId($config['merchant_id']);
    Braintree_Configuration::publicKey($config['public_key']);
    Braintree_Configuration::privateKey($config['private_key']);
  }

  /**
   * Return a client token
   */
  public function getToken(){
    return Braintree_ClientToken::generate();
  }

  public function setSale($sale){
    $result = Braintree_Transaction::sale([
      'amount' => $sale->getAmount(),
      'paymentMethodNonce' => $sale->getNonce(),
    //  'serviceFeeAmount' => $sale->getFee(),
    //  'merchantAccountId' => $sale->getMerchant()->guid,
      'options' => [
      //  'holdInEscrow' => true,
        'submitForSettlement' => true
      ]
    ]);

    if($result->success){
      return $result->transaction->id;
    } else if ($result->transaction) {
      throw new \Exception("Transaction failed: ({$result->transaction->processorResponseCode}) {$result->transaction->processorResponseText}");
    } else {
      $errors = $result->errors->deepAll();
      throw new \Exception($errors[0]->message);
    }

  }

  public function updateMerchant($merchant){

  }

  public function addMerchant($merchant){

  }

  public function confirmMerchant($merchant){

  }

}
