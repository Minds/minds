<?php
/**
 * Braintree service controller
 */

namespace Minds\Core\Payments\Braintree;

use Minds\Core;
use Minds\Core\Guid;
use Minds\Core\Payments\PaymentServiceInterface;
use Minds\Core\Payments\Subscriptions\SubscriptionPaymentServiceInterface;
use Minds\Core\Payments\Sale;
use Minds\Core\Payments\Merchant;
use Minds\Core\Payments\Customer;
use Minds\Core\Payments\PaymentMethod;
use Minds\Core\Payments\Subscriptions\Subscription;
use Minds\Entities;
use Braintree_ClientToken;
use Braintree_Configuration;
use Braintree_MerchantAccount;
use Braintree_Transaction;
use Braintree_TransactionSearch;
use Braintree_Customer;
use Braintree_CustomerSearch;
use Braintree_PaymentMethod;
use Braintree_Subscription;
//for testing purposes
use Braintree_Test_MerchantAccount;

class Braintree implements PaymentServiceInterface, SubscriptionPaymentServiceInterface
{
    private $config = array();

    public function __construct($options = array())
    {
        $this->setConfig($options);
    }

    private function setConfig($config)
    {
        $defaults = [
          'environment' => Core\Config::_()->payments['braintree']['environment'] ?: 'sandbox',
          'merchant_id' => Core\Config::_()->payments['braintree']['merchant_id'],
          'master_merchant_id' => Core\Config::_()->payments['braintree']['master_merchant_id'],
          'public_key' => Core\Config::_()->payments['braintree']['public_key'],
          'private_key' => Core\Config::_()->payments['braintree']['private_key']
        ];
        $this->config = array_merge($defaults, $config);
        Braintree_Configuration::environment($this->config['environment']);
        Braintree_Configuration::merchantId($this->config['merchant_id']);
        Braintree_Configuration::publicKey($this->config['public_key']);
        Braintree_Configuration::privateKey($this->config['private_key']);
    }

  /**
   * Return a client token
   */
  public function getToken()
  {
      return Braintree_ClientToken::generate();
  }

  /**
   * Create the sale
   * @param Sale $sale
   * @return string - the transaction id
   */
  public function setSale(Sale $sale)
  {
      $opts = [
        'amount' => $sale->getAmount(),
        'paymentMethodNonce' => $sale->getNonce(),
        'customer' => [
          'firstName' => $sale->getCustomerId()
        ],
        'orderId' => $sale->getOrderId(),
        'options' => [
          //'holdInEscrow' => true,
          'submitForSettlement' => $sale->getSettle() //let the seller approve or deny
        ]
      ];
      if($sale->getFee()){
        $opts['serviceFeeAmount'] = $sale->getFee();
      }
      if($sale->getMerchant()){
        $opts['merchantAccountId'] = $sale->getMerchant()->guid;
      }

      $result = Braintree_Transaction::sale($opts);

      if ($result->success) {
          return $result->transaction->id;
      } elseif ($result->transaction) {
          throw new \Exception("Transaction failed: ({$result->transaction->processorResponseCode}) {$result->transaction->processorResponseText}");
      } else {
          $errors = $result->errors->deepAll();
          throw new \Exception($errors[0]->message);
      }
  }

  /**
   * Charge the sale
   * @param Sale $sale
   * @return boolean
   */
  public function chargeSale(Sale $sale)
  {
      $result = Braintree_Transaction::submitForSettlement($sale->getId());

      if ($result->success) {
          return true;
      }

      $errors = $result->errors->deepAll();
      throw new \Exception($errors[0]->message);
  }

  /**
   * Void the sale
   * @param Sale $sale
   * @return boolean
   */
  public function voidSale(Sale $sale)
  {
      $result = Braintree_Transaction::void($sale->getId());
  }

  /**
   * Refund the sale
   * @param Sale $sale
   * @return boolean
   */
  public function refundSale(Sale $sale)
  {
      $result = Braintree_Transaction::refund($sale->getId());
  }

  /**
   * Get a list of transactions
   * @param Merchant $merchant - the merchant
   * @param array $options - limit, offset
   * @return array
   */
  public function getSales(Merchant $merchant, array $options = array())
  {
      $results = Braintree_Transaction::search([
      Braintree_TransactionSearch::merchantAccountId()->is($merchant->getGuid()),
      Braintree_TransactionSearch::status()->in([
        Braintree_Transaction::SUBMITTED_FOR_SETTLEMENT,
        Braintree_Transaction::SETTLED,
        Braintree_Transaction::VOIDED
      ])
    ]);

      $sales = [];
      foreach ($results as $transaction) {
          $sales[] = (new Sale)
        ->setId($transaction->id)
        ->setAmount($transaction->amount)
        ->setStatus($transaction->disbursementDetails->success == true ? 'disbursed' : $transaction->status)
        ->setMerchant($merchant)
        ->setOrderId($transaction->orderId)
        ->setCustomerId($transaction->customer['firstName'])
        ->setCreatedAt($transaction->createdAt)
        ->setSettledAt($transaction->settledAt);
      }
      return $sales;
  }

  /**
   * Update a merchants details
   * @param Merchant $merchant
   * @return string
   */
  public function updateMerchant(Merchant $merchant)
  {
      $result = Braintree_MerchantAccount::update($merchant->getGuid(),
      [
        'individual' => [
          'firstName' => $merchant->getFirstName(),
          'lastName' => $merchant->getLastName(),
          'email' => $merchant->getEmail(),
          //'dateOfBirth' => $merchant->getDateOfBirth(),
          'ssn' => $merchant->getSSN(),
          //'address' => [
          //  'streetAddress' => $merchant->getStreet(),
          //  'locality' => $merchant->getCity(),
          //  'region' => $merchant->getRegion(),
          //  'postalCode' => $merchant->getPostCode()
          //]
        ],
        'funding' => [
          'descriptor' => $merchant->getName(),
          'destination' => $merchant->getDestination() == 'bank' ? Braintree_MerchantAccount::FUNDING_DESTINATION_BANK : Braintree_MerchantAccount::FUNDING_DESTINATION_EMAIL,
          'email' => $merchant->getEmail(),
          'accountNumber' => $merchant->getDestination() == 'bank' ? $merchant->getAccountNumber() : null,
          'routingNumber' => $merchant->getDestination() == 'bank' ? $merchant->getRoutingNumber() : null
        ],
        'tosAccepted' => true
      ]);

      if ($result->success) {
          return $result->merchantAccount->id;
      }

      throw new \Exception($result->message);
  }

  /**
   * Add a merchant to braintree
   * @param Merchant $merchant
   * @return string - the ID of the merchant
   */
  public function addMerchant(Merchant $merchant)
  {
      $result = Braintree_MerchantAccount::create([
        'individual' => [
          'firstName' => $merchant->getFirstName(),
          'lastName' => $merchant->getLastName(),
          'email' => $merchant->getEmail(),
          'dateOfBirth' => $merchant->getDateOfBirth(),
          'ssn' => $merchant->getSSN(),
          'address' => [
            'streetAddress' => $merchant->getStreet(),
            'locality' => $merchant->getCity(),
            'region' => $merchant->getRegion(),
            'postalCode' => $merchant->getPostCode()
          ]
        ],
        'funding' => [
          'descriptor' => $merchant->getName(),
          'destination' => $merchant->getDestination() == 'bank' ? Braintree_MerchantAccount::FUNDING_DESTINATION_BANK : Braintree_MerchantAccount::FUNDING_DESTINATION_EMAIL,
          'email' => $merchant->getEmail(),
          'accountNumber' => $merchant->getDestination() == 'bank' ? $merchant->getAccountNumber() : null,
          'routingNumber' => $merchant->getDestination() == 'bank' ? $merchant->getRoutingNumber() : null
        ],
        'tosAccepted' => true,
        'masterMerchantAccountId' => $this->config['master_merchant_id'],

        'id' => $merchant->getGuid()
      ]);

      if ($result->success) {
          return $result->merchantAccount->id;
      }

      throw new \Exception($result->message);
  }

  /**
   * Return a merchant from an id
   * @return Merchant
   */
  public function getMerchant($id)
  {
      try {
          $result = Braintree_MerchantAccount::find($id);

          $merchant = (new Merchant())
        ->setStatus($result->status)
        ->setFirstName($result->individual['firstName'])
        ->setLastName($result->individual['lastName'])
        ->setEmail($result->individual['email'])
        ->setDateOfBirth($result->individual['dateOfBirth'])
        ->setSSN($result->individual['ssnLast4'])
        ->setStreet($result->individual['address']['streetAddress'])
        ->setCity($result->individual['address']['locality'])
        ->setRegion($result->individual['address']['region'])
        ->setPostCode($result->individual['address']['postalCode'])
        ->setAccountNumber($result->funding['accountNumberLast4'])
        ->setRoutingNumber($result->funding['routingNumber'])
        ->setDestination($result->funding['destination']);

          return $merchant;
      } catch (\Exception $e) {
          if($e instanceof \Braintree_Exception_NotFound)
              return false;
          throw new \Exception($e->getMessage());
      }
  }

    public function confirmMerchant(Merchant $merchant)
    {
    }

    /* Subscriptions */

    public function createCustomer(Customer $customer)
    {

        $id = $customer->getId() ?: Guid::build();

        try {
            $braintree_customer = Braintree_Customer::find($id);
        } catch (\Braintree_Exception_NotFound $e) {
            $braintree_customer = null;
        }

        if ($braintree_customer)
            $customer->setId($braintree_customer->id);
        else {
            $result = Braintree_Customer::create([
                'id' => $id,
                'email' => strtolower($customer->getEmail())
            ]);

            if ($result->success)
                $customer->setId($result->customer->id);
            else {
                $errors = $result->errors->deepAll();
                throw new \Exception($errors[0]->message);
            }
        }

        return $customer;
    }

    public function createPaymentMethod(PaymentMethod $payment_method)
    {

        $result = Braintree_PaymentMethod::create([
            'customerId' => $payment_method->getCustomer()->getId(),
            'paymentMethodNonce' => $payment_method->getPaymentMethodNonce(),
            'options' => [
                'verifyCard' => true
            ]
        ]);

        if ($result->success) {
            $payment_method->setToken($result->paymentMethod->token);
            return $payment_method;
        } else {
            $errors = $result->errors->deepAll();
            throw new \Exception($errors[0]->message);
        }

    }

    public function createSubscription(Subscription $subscription)
    {

        $result = Braintree_Subscription::create([
            'paymentMethodToken' => $subscription->getPaymentMethod()->getToken(),
            'planId' => $subscription->getPlanId(),
            'addOns' => [
                'add' => $subscription->getAddOns()
            ]
        ]);

        if ($result->success) {
            $subscription->setId($result->subscription->id);
            return $subscription;
        } else {
            $errors = $result->errors->deepAll();
            throw new \Exception($errors[0]->message);
        }

    }

    public function getSubscription($subscription_id)
    {

        try {

            $result = Braintree_Subscription::find($subscription_id);

            $addOns = [];
            foreach($result->addOns as $addOn){
                $addOns[] = [
                  'id' => $addOn->id,
                  'quantity' => $addOn->quantity,
                  'amount' => $addOn->amount
                ];
            }

            return (new Subscription)
              ->setBalance($result->balance)
              ->setCreatedAt($result->createdAt)
              ->setNextBillingPeriodAmount($result->nextBillingPeriodAmount)
              ->setNextBillingDate($result->nextBillingDate)
              ->setPlanId($result->planId)
              ->setTrialPeriod($result->trialPeriod)
              ->setAddOns($addOns);

        } catch (\Braintree_Exception_NotFound $e) {
            return null;
        }

    }

    public function updateSubscription(Subscription $subscription)
    {

        $result = Braintree_Subscription::update($subscription->getId(), [
          //  'id' => $subscription->getId(),
            'paymentMethodToken' => $subscription->getPaymentMethod()->getToken(),
            'planId' => $subscription->getPlanId(),
            'addOns' => [
                'update' => $subscription->getAddOns()
            ]
        ]);

        if ($result->success) {
            $subscription->setId($result->subscription->id);
            return $subscription;
        } else {
            $errors = $result->errors->deepAll();
            throw new \Exception($errors[0]->message);
        }

    }

}
