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
use Minds\Core\Payments\Transfers\Transfer;
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
          'capture' => $sale->shouldCapture(),
          'currency' => 'usd',
          'source' => $sale->getNonce() ?: $sale->getCustomerId(),
          'metadata' => [
            'orderId' => $sale->getOrderId(),
            'first_name' => $sale->getCustomerId()
          ],
        ];
        if ($sale->getFee()) {
            $opts['application_fee'] = round($sale->getFee());
        }
        if ($sale->getMerchant()) {
            $user = new Entities\User($sale->getMerchant()->guid);
            $opts['destination'] = $user->getMerchant()['id'];
        }

        $result = StripeSDK\Charge::create($opts);

        if ($result->status == 'succeeded') {
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
        StripeSDK\Refund::create([
          "charge" => $sale->getId()
        ]);
    }

    /**
     * Refund the sale
     * @param Sale $sale
     * @return boolean
     */
    public function refundSale(Sale $sale)
    {
        StripeSDK\Refund::create([
          "charge" => $sale->getId()
        ]);
    }

    /**
     * Get a list of transactions
     * @param Merchant $merchant - the merchant
     * @param array $options - limit, offset
     * @return array
     */
    public function getSales(Merchant $merchant, array $options = array())
    {
        $results = StripeSDK\Charge::all(
          [
            'limit' => 3
          ],
          [
            'stripe_account' => $merchant->getId()
          ]);


        $sales = [];
        foreach ($results->data as $transaction) {
            $sales[] = (new Sale)
              ->setId($transaction->id)
              ->setAmount($transaction->amount / 100)
              ->setStatus($transaction->outcome['seller_message'])
              ->setMerchant($merchant)
              ->setOrderId($transaction->metadata['orderId'])
              ->setCustomerId($transaction->customer['first_name'])
              ->setCreatedAt($transaction->created)
              ->setSettledAt($transaction->updated);
        }
        return $sales;
    }

   /**
     * Get a list of transactions
     * @param Merchant $merchant - the merchant
     * @param array $options - limit, offset
     * @return array
     */
    public function getBalance(Merchant $merchant, array $options = array())
    {
        $results = StripeSDK\BalanceTransaction::all(
          [
            'limit' => $options['limit'] ?: 50
          ],
          [
            'stripe_account' => $merchant->getId()
          ]);
        return $results;
    }

    /**
     * Add a merchant to Stripe
     * @param Merchant $merchant
     * @return string - the ID of the merchant
     */
    public function addMerchant(Merchant $merchant)
    {
        $dob = explode('-', $merchant->getDateOfBirth());
        $data = [
          'managed' => true,
          'country' => $merchant->getCountry(),
          'legal_entity' => [
            'type' => 'individual',
            'first_name' => $merchant->getFirstName(),
            'last_name' => $merchant->getLastName(),
            'address' => [
              'city' => $merchant->getCity(),
              'line1' => $merchant->getStreet(),
              'postal_code' => $merchant->getPostCode(),
              'state' => $merchant->getState(),
            ],
            'dob' => [
              'day' => $dob[2],
              'month' => $dob[1],
              'year' => $dob[0]
            ],
          ],
          'tos_acceptance' => [
            'date' => time(),
            'ip' => '0.0.0.0' // @todo: Should we set the actual IP?
          ],
          'external_account' => [
            'object' => 'bank_account',
            'account_number' => $merchant->getAccountNumber(),
            'country' => $merchant->getCountry(),
            'currency' => $this->getCurrencyFor($merchant->getCountry())
          ]
        ];

        if ($merchant->getGender()) {
            $data['legal_entity']['gender'] = $merchant->getGender();
        }

        if ($merchant->getPhoneNumber()) {
            $data['legal_entity']['phone_number'] = $merchant->getPhoneNumber();
        }

        if ($merchant->getSSN()) {
            $data['legal_entity']['ssn_last_4'] = $merchant->getSSN();
        }

        if ($merchant->getPersonalIdNumber()) {
            $data['legal_entity']['personal_id_number'] = $merchant->getPersonalIdNumber();
        }

        if ($merchant->getRoutingNumber()) {
            $data['external_account']['routing_number'] = $merchant->getRoutingNumber();
        }

        $result = StripeSDK\Account::create($data);

        if($result->id){
            $merchant->setGuid($result->id);
            return $result;
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
              ->setCountry($result->country)
              ->setFirstName($result->legal_entity['first_name'])
              ->setLastName($result->legal_entity['last_name'])
              ->setGender($result->legal_entity['gender'])
              ->setDateOfBirth($result->legal_entity['dob']['year'] . '-' . str_pad($result->legal_entity['dob']['month'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($result->legal_entity['dob']['day'], 2, '0', STR_PAD_LEFT))
              ->setStreet($result->legal_entity['address']['line1'])
              ->setCity($result->legal_entity['address']['city'])
              ->setPostCode($result->legal_entity['address']['postal_code'])
              ->setState($result->legal_entity['address']['state'])
              ->setPhoneNumber($result->legal_entity['phone_number'])
              ->setSSN($result->legal_entity['ssn_last_4'])
              ->setPersonalIdNumber($result->legal_entity['personal_id_number'])
            //   ->setAccountNumber($result->external_accounts->data[0]['last4'])
              ->setRoutingNumber($result->external_accounts->data[0]['routing_number'])
              ->setDestination('bank');

            //verifiction check
            if ($result->legal_entity->verification->status === 'verified') {
                $merchant->markAsVerified();
            }

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

    /**
     * Updates a merchant in Stripe
     * @return Merchant
     */
    public function updateMerchant(Merchant $merchant)
    {
        try {
            $account = StripeSDK\Account::retrieve($merchant->getId());

            if ($account->legal_entity->verification->status !== 'verified') {
                $account->legal_entity->first_name = $merchant->getFirstName();
                $account->legal_entity->last_name = $merchant->getLastName();

                $account->legal_entity->address->city = $merchant->getCity();
                $account->legal_entity->address->line1 = $merchant->getStreet();
                $account->legal_entity->address->postal_code = $merchant->getPostCode();
                $account->legal_entity->address->state = $merchant->getState();

                $dob = explode('-', $merchant->getDateOfBirth());
                $account->legal_entity->dob->day = $dob[2];
                $account->legal_entity->dob->month = $dob[1];
                $account->legal_entity->dob->year = $dob[0];

                if ($merchant->getGender()) {
                    $account->legal_entity->gender = $merchant->getGender();
                }

                if ($merchant->getPhoneNumber()) {
                    $account->legal_entity->phone_number = $merchant->getPhoneNumber();
                }
            } else {
                if (!$account->legal_entity->ssn_last_4_provided && $merchant->getSSN()) {
                    $account->legal_entity->ssn_last_4 = $merchant->getSSN();
                }

                if (!$account->legal_entity->personal_id_number_provided && $merchant->getPersonalIdNumber()) {
                    $account->legal_entity->personal_id_number = $merchant->getPersonalIdNumber();
                }
            }

            if ($merchant->getAccountNumber()) {
                $account->external_account->account_number = $merchant->getAccountNumber();
            }

            if ($merchant->getRoutingNumber()) {
                $account->external_account->routing_number = $merchant->getRoutingNumber();
            }

            $account->save();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $account->id;
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

        $opts = [
          'metadata' => [
            'user_guid' => $customer->getUser()->getGuid()
          ]
        ];

        if ($customer->getPaymentToken()) {
            $opts['source'] = $customer->getPaymentToken();
        }

        $result = StripeSDK\Customer::create($opts);

        $customer->setId($result->id);
        return $customer;
    }

    public function getCustomer(Customer $customer)
    {
        try {
            $result = StripeSDK\Customer::retrieve($customer->getId());
        } catch (\Exception $e) {
            return false;
        }

        $customer->setPaymentMethods($result->sources->data);

        return $customer;
    }

    public function createPaymentMethod(PaymentMethod $payment_method)
    {
    }

    public function getPlan($id, $merchant){
        try {
            $result = StripeSDK\Plan::retrieve($id, [ 'stripe_account' => $merchant ]);
        } catch (\Exception $e) {
            return false;
        }
        return $result;
    }

    public function createPlan($plan)
    {
        $result = StripeSDK\Plan::create(
          [
            'amount' => $plan->amount,
            'interval' => 'month',
            'name' => $plan->id,
            'currency' => "usd",
            'id' => $plan->id
          ],
          [
            'stripe_account' => $plan->merchantId
          ]
        );
        return $result;
    }

    public function deletePlan($id, $merchant)
    {
        $plan = StripeSDK\Plan::retrieve($id,[ 'stripe_account' => $merchant ]);
        $plan->delete();
    }

    public function createSubscription(Subscription $subscription)
    {

        try {

            //subscriptions need to clone customers
            $token = StripeSDK\Token::create(
              [
                'customer' => $subscription->getCustomer()->getId()
              ],
              [
                'stripe_account' => $subscription->getMerchant()->getId()
              ]
            );

            $customer = StripeSDK\Customer::create(
              [
                'source' => $token->id,
              ],
              [
                'stripe_account' => $subscription->getMerchant()->getId()
              ]
            );

            $result = StripeSDK\Subscription::create(
              [
                'customer' => $customer->id,
                'plan' => $subscription->getPlanId(),
                'application_fee_percent' => 5.00
              ],
              [
                'stripe_account' => $subscription->getMerchant()->getId()
              ]
            );

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $result->id;
    }

    public function getSubscription($subscriptionId)
    {
    }

    public function cancelSubscription(Subscription $subscription)
    {
        try {
            StripeSDK\Subscription::retrieve($subscription->getId(), [
                'stripe_account' => $subscription->getMerchant()->getId()
            ])->cancel();
        } catch (StripeSDK\Error\InvalidRequest $e) {
            return false;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage()); // :v
        }
    }

    public function updateSubscription(Subscription $subscription)
    {
    }

    /* Transfers */

    public function transfer(Transfer $transfer)
    {
        try {
            $result = StripeSDK\Transfer::create([
                'amount' => $transfer->getAmount(),
                'currency' => $transfer->getCurrency(),
                'destination' => $transfer->getDestination(),
                'metadata' => $transfer->getSource(),
                'statement_descriptor' => 'MINDS',
                'source_type' => $this->config['source_type']
            ]);

            $transfer->setId($result->id);
        } catch (StripeSDK\Error\InvalidRequest $e) {
            // var_dump($e);die;
            return false;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage()); // :v
        }

        return $transfer;
    }

    public function getCurrencyFor($country)
    {
        $countryToCurrency = [
            'AU' => 'AUD',
            'CA' => 'CAD',
            'GB' => 'GBP',
            'HK' => 'HKD',
            'JP' => 'JPY',
            'SG' => 'SGD',
            'US' => 'USD',
            'NZ' => 'NZD',
        ];

        if (!isset($countryToCurrency[$country])) {
            return 'EUR';
        }

        return $countryToCurrency[$country];
    }
}
