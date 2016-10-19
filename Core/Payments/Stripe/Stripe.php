<?php
/**
 * Stripe service controller
 */

namespace Minds\Core\Payments\Stripe;

use Minds\Core;
use Minds\Core\Config\Config;
use Minds\Core\Guid;
use Minds\Core\Payments\PaymentServiceInterface;
use Minds\Core\Payments\Subscriptions\SubscriptionPaymentServiceInterface;
use Minds\Core\Payments\Sale;
use Minds\Core\Payments\Merchant;
use Minds\Core\Payments\Customer;
use Minds\Core\Payments\PaymentMethod;
use Minds\Core\Payments\Subscriptions\Subscription;
use Minds\Entities;

use Stripe as StripeSDK;

class Stripe implements PaymentServiceInterface, SubscriptionPaymentServiceInterface
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->setConfig($config->payments['stripe']);
    }

    public function setConfig($config)
    {
        $this->config = $config;
        StripeSDK\Stripe::setApiKey($config['api_key']);
        return $this;
    }


    /**
     * Return a client token
     */
    public function getToken()
    {
        return null;
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
          'currency' => 'usd',
          'source' => $sale->getNonce(),
          'metadata' => [
            'first_name' => $sale->getCustomerId()
          ],
        ];
        if ($sale->getFee()) {
            $opts['application_fee'] = $sale->getFee();
        }
        if ($sale->getMerchant()) {
            $opts['destination'] = $sale->getMerchant()->guid;
        }

        if ($result->success) {
            return $result->id;
        }

        return false;
    }

    /**
     * Charge the sale
     * @param Sale $sale
     * @return boolean
     */
    public function chargeSale(Sale $sale)
    {
        $charge = StripeSDK\Charge::retrieve($sale->getId());
        $charge->capture();
        return true;
    }

    /**
     * Void the sale
     * @param Sale $sale
     * @return boolean
     */
    public function voidSale(Sale $sale)
    {
    }

    /**
     * Refund the sale
     * @param Sale $sale
     * @return boolean
     */
    public function refundSale(Sale $sale)
    {
    }

    /**
     * Get a list of transactions
     * @param Merchant $merchant - the merchant
     * @param array $options - limit, offset
     * @return array
     */
    public function getSales(Merchant $merchant, array $options = array())
    {
        $results = StripeSDK\Charge::all(["limit" => 3]);

        $sales = [];
        foreach ($results->data as $transaction) {
            $sales[] = (new Sale)
              ->setId($transaction->id)
              ->setAmount($transaction->amount)
              ->setStatus($transaction->status)
              ->setMerchant($merchant)
              ->setOrderId($transaction->id)
              ->setCustomerId($transaction->customer['first_name'])
              ->setCreatedAt($transaction->created)
              ->setSettledAt($transaction->updated);
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
        $account = StripeSDK\Account::retrieve($merchant->getGuid());


        throw new \Exception($result->message);
    }

    /**
     * Add a merchant to braintree
     * @param Merchant $merchant
     * @return string - the ID of the merchant
     */
    public function addMerchant(Merchant $merchant)
    {

        $dob = explode('-', $merchant->getDateOfBirth());
        $result = StripeSDK\Account::create([
          'managed' => true,
          'country' => $merchant->getCountry(),
          'email' => $merchant->getEmail(),
          'legal_entity' => [
            'first_name' => $merchant->getFirstName(),
            'last_name' => $merchant->getLastName(),
            'type' => 'individual',
            'address' => [
              'line1' => $merchant->getStreet(),
              'city' => $merchant->getCity(),
              'postal_code' => $merchant->getPostCode()
            ],
            'dob' => [
              'day' => $dob[2],
              'month' => $dob[1],
              'year' => $dob[0]
            ]
          ],
          'external_account' => [
            'object' => 'bank_account',
            'account_number' => $merchant->getAccountNumber(),
            'routing_number' => $merchant->getRoutingNumber(),
            'country' => $merchant->getCountry(),
            'currency' => 'GBP'
          ]
        ]);

        if($result->id){
            $merchant->setGuid($result->id);
            return $result->id;
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
            $result = StripeSDK\Account::retrieve($id);

            $merchant = (new Merchant())
              ->setStatus('active')
              ->setFirstName($result->legal_entity['first_name'])
              ->setLastName($result->legal_entity['last_name'])
              //->setEmail($result->email)
              ->setDateOfBirth($result->legal_entity['dob']['year'] . '-' . $result->legal_entity['dob']['month'] . '-' . $result->legal_entity['dob']['day'])
              //->setSSN($result->individual['ssnLast4'])
              ->setStreet($result->legal_entity['address']['line1'])
              ->setCity($result->legal_entity['address']['city'])
              //->setRegion($result->legal_entity['address']['region'])
              ->setPostCode($result->legal_entity['address']['postal_code'])
              ->setAccountNumber($result->external_accounts->data[0]['last4'])
              ->setRoutingNumber($result->external_accounts->data[0]['routing_number'])
              ->setDestination('bank');

            //verifiction check
            if ($result->verification->disabled_reason == 'fields_needed') {
                if ($result->verification->fields_needed[0] == 'legal_entity.verification.document') {
                    $merchant->setStatus('awaiting-document');
                }
            }

            return $merchant;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function verifyMerchant($id, $file)
    {
        $result = StripeSDK\FileUpload::create([
          'purpose' => "identity_document",
          'file' => fopen($file['tmp_name'], 'r')
        ],
        [ 'stripe_account' => $id ]);

        $account = StripeSDK\Account::retrieve($id);
        $account->legal_entity->verification->document = $result->id;
        $account->save();

        return $result->id;
    }

    public function confirmMerchant(Merchant $merchant)
    {
    }

    /* Subscriptions */

    public function createCustomer(Customer $customer)
    {
    }

    public function createPaymentMethod(PaymentMethod $payment_method)
    {
    }

    public function createSubscription(Subscription $subscription)
    {
    }

    public function getSubscription($subscription_id)
    {
    }

    public function cancelSubscription(Subscription $subscription)
    {
    }

    public function updateSubscription(Subscription $subscription)
    {
    }
}
