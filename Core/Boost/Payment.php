<?php

namespace Minds\Core\Boost;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Payments;
use Minds\Entities\User;
use Minds\Helpers\Wallet;

class Payment
{
    public function __construct()
    {
    }

    /**
     * @param \Minds\Entities\Boost\Network $boost
     * @param $paymentMethodNonce
     * @return null
     * @throws \Exception
     */
    public function pay($boost, $paymentMethodNonce)
    {
        $currency = method_exists($boost, 'getMethod') ?
            $boost->getMethod() : $boost->getBidType();

        switch ($currency) {
            case 'points':
                return Wallet::createTransaction($boost->getOwner()->guid, 0 - $boost->getBid(), $boost->getGuid(), "Boost");

            case 'usd':
            case 'money':
                $stripe = Di::_()->get('StripePayments');
                $customer = (new Payments\Customer())
                    ->setUser($boost->getOwner());

                $source = $paymentMethodNonce;

                if (!$customer->getId()) {
                    $customer->setPaymentToken($paymentMethodNonce);
                    $customer = $stripe->createCustomer($customer);

                    // Token already consumed to set default payment method, let's use the
                    // customer itself
                    $source = $customer->getId();
                }

                $sale = (new Payments\Sale())
                    ->setOrderId('boost-' . $boost->getGuid())
                    ->setAmount($boost->getBid())
                    ->setCustomerId($customer->getId())
                    ->setCustomer($customer)
                    ->setSource($source)
                    ->setSettle(false);

                if ($boost->getOwner()->referrer) {
                    $referrer = new User($boost->getOwner()->referrer);
                    $sale->setMerchant($referrer)
                        ->setFee(0.75); //payout 25% to referrer
                }

                return $stripe->setSale($sale);

            case 'tokens':
                // Pending, actually

                Di::_()->get('Boost\Pending')
                    ->add($paymentMethodNonce['txHash'], $boost);

                return $paymentMethodNonce['txHash'];
        }

        throw new \Exception('Unsupported Bid Type');
    }

    public function charge($boost)
    {
        $currency = method_exists($boost, 'getMethod') ?
            $boost->getMethod() : $boost->getBidType();

        switch ($currency) {
            case 'points':
                return true; // Already charged

            case 'usd':
            case 'money':
                $stripe = Di::_()->get('StripePayments');
                $sale = (new Payments\Sale())
                    ->setId($boost->getTransactionId());

                if ($boost->getOwner()->referrer) {
                    $referrer = new User($boost->getOwner()->referrer);
                    $sale->setMerchant($referrer);
                }

                return $stripe->chargeSale($sale);

            case 'tokens':
                if ($boost->subtype == 'network') {
                    Di::_()->get('Boost\Pending')
                        ->approve($boost);
                }

                return true;
        }

        throw new \Exception('Unsupported Bid Type');
    }

    public function refund($boost)
    {
        $currency = method_exists($boost, 'getMethod') ?
            $boost->getMethod() : $boost->getBidType();

        switch ($currency) {
            case 'points':
                return Wallet::createTransaction($boost->getOwner()->guid, $boost->getBid(), $boost->getGuid(), "Boost Refund");

            case 'usd':
            case 'money':
                $stripe = Di::_()->get('StripePayments');
                $sale = (new Payments\Sale())
                    ->setId($boost->getTransactionId());

                if ($boost->getOwner()->referrer) {
                    $referrer = new User($boost->getOwner()->referrer);
                    $sale->setMerchant($referrer);
                }

                return $stripe->voidOrRefundSale($sale, true);

            case 'tokens':
                if ($boost->subtype == 'network') {
                    Di::_()->get('Boost\Pending')
                        ->reject($boost);
                }

                return true;
        }

        throw new \Exception('Unsupported Bid Type');
    }
}
