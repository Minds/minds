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

    public function pay($boost, $paymentMethodNonce)
    {
        if ($boost->getBidType() == 'points') {
            return Wallet::createTransaction($boost->getOwner()->guid, 0 - $boost->getBid(), $boost->getGuid(), "Boost");
        } elseif ($boost->getBidType() == 'usd') {

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
        }
        throw new \Exception('Unsupported Bid Type');
    }

    public function charge($boost)
    {
        if ($boost->getBidType() == 'points') {
            return true; // Already charged
        } elseif ($boost->getBidType() == 'usd') {
            $stripe = Di::_()->get('StripePayments');
            $sale = (new Payments\Sale())
                ->setId($boost->getTransactionId());

            if ($boost->getOwner()->referrer) {
                $referrer = new User($boost->getOwner()->referrer);
                $sale->setMerchant($referrer);
            }

            return $stripe->chargeSale($sale);
        }
        throw new \Exception('Unsupported Bid Type');
    }

    public function refund($boost)
    {
        if ($boost->getBidType() == 'points') {
            return Wallet::createTransaction($boost->getOwner()->guid, $boost->getBid(), $boost->getGuid(), "Boost Refund");
        } elseif ($boost->getBidType() == 'usd') {
            $stripe = Di::_()->get('StripePayments');
            $sale = (new Payments\Sale())
                ->setId($boost->getTransactionId());

            if ($boost->getOwner()->referrer) {
                $referrer = new User($boost->getOwner()->referrer);
                $sale->setMerchant($referrer);
            }

            return $stripe->voidOrRefundSale($sale, true);
        }
        throw new \Exception('Unsupported Bid Type');
    }
}
