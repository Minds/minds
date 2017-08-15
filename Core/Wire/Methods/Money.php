<?php

namespace Minds\Core\Wire\Methods;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Payments;
use Minds\Core\Wire\Counter;
use Minds\Entities;
use Minds\Entities\User;

class Money implements MethodInterface
{

    private $amount;
    private $entity;
    private $nonce;
    private $recurring; // monthly
    private $timestamp;
    private $manager;
    private $repository;
    private $cache;

    public function __construct($stripe = null, $manager = null, $repository = null, $cache = null)
    {
        $this->stripe = $stripe ?: Di::_()->get('StripePayments');
        $this->manager = $manager ?: Core\Di\Di::_()->get('Wire\Manager');
        $this->repository = $repository ?: Core\Di\Di::_()->get('Wire\Repository');
        $this->cache = $cache ?: Core\Di\Di::_()->get('Cache');
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    public function setPayload($payload = [])
    {
        $this->nonce = $payload['nonce'];
        return $this;
    }

    public function setRecurring($recurring)
    {
        $this->recurring = $recurring;
        return $this;
    }

    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
        return $this;
    }

    public function create()
    {
        $user = $this->entity->type == 'user' ?
            $this->entity :
            $this->entity->getOwnerEntity();

        if ($this->recurring) {
            return $this->createSubscription($user);
        }
        return $this->createSale($user);
    }

    /**
     * @return mixed
     */
    public function refund() {

    }

    private function createSubscription(User $user)
    {
        if (!$user->getMerchant()['id']) {
            throw new NotMonetizedException();
        }

        $customer = (new Payments\Customer())
            ->setUser(Core\Session::getLoggedInUser());

        $stripe = Core\Di\Di::_()->get('StripePayments');

        if (!$stripe->getCustomer($customer) || !$customer->getId()) {
            //create the customer on stripe
            $customer->setPaymentToken($this->nonce);
            $customer = $stripe->createCustomer($customer);
        }

        //look for current subscription
        $this->cancelSubscription($user);

        $plan = $stripe->getPlan('wire', $user->getMerchant()['id']);

        if ($plan) {
            $stripe->deletePlan($plan->id, $user->getMerchant()['id']);
        }

        $stripe->createPlan((object) [
            'id' => 'wire',
            'amount' => $this->amount * 100,
            'merchantId' => $user->getMerchant()['id']
        ]);

        $subscription = (new Payments\Subscriptions\Subscription())
            ->setPlanId('wire')
            ->setQuantity(1)
            ->setCustomer($customer)
            ->setMerchant($user);

        $subscription_id = $stripe->createSubscription($subscription);

        /**
         * Save the subscription to our user subscriptions list
         */
        $plan = (new Payments\Plans\Plan)
            ->setName('wire')
            ->setEntityGuid($this->entity->guid)
            ->setUserGuid(Core\Session::getLoggedInUser()->guid)
            ->setSubscriptionId($subscription_id)
            ->setStatus('active')
            ->setExpires(-1); //indefinite
        $repo = new Payments\Plans\Repository();
        $repo->add($plan);

        $this->saveWire($user);

        return ['subscriptionId' => $subscription_id];
    }

    private function createSale(User $user)
    {
        if (!$user->getMerchant()['id']) {
            throw new NotMonetizedException();
        }

        $customer = (new Payments\Customer())
            ->setUser(Core\Session::getLoggedInUser());

        $stripe = Core\Di\Di::_()->get('StripePayments');

        if (!$stripe->getCustomer($customer) || !$customer->getId()) { // if customer doesn't exist on Stripe, create it
            //create the customer on stripe
            $customer->setPaymentToken($this->nonce);
            $customer = $stripe->createCustomer($customer);
        }

        $sale = new Payments\Sale();
        $sale->setOrderId('wire-' . $this->entity->guid)
            ->setAmount($this->amount * 100)//cents to $
            ->setMerchant($user)
            ->setCustomer($customer)
            ->setSource($this->nonce)
            ->setFee(0)
            ->capture();
        $this->id = $this->stripe->setSale($sale);

        $this->saveWire($user);

        return $this->id;
    }

    /**
     * @param User $user
     * @return string wire_guid
     */
    private function cancelSubscription(User $user)
    {
        $repo = new Payments\Plans\Repository();
        $plan = $repo->setEntityGuid(0)
            ->setUserGuid(Core\Session::getLoggedInUser()->guid)
            ->getSubscription('wire');

        $subscription = (new Payments\Subscriptions\Subscription)
            ->setId($plan->getSubscriptionId());

        $stripe = Core\Di\Di::_()->get('StripePayments');

        $wires = $this->manager->get([
            'user_guid' => $user->guid,
            'type' => 'sent',
            'order' => 'DESC',
        ]);

        if (count($wires) > 0) {
            // get last recurring wire
            foreach ($wires as $wire) {
                if ($wire->isRecurring() && $wire->isActive() && $wire->getMethod() == 'usd') {
                    $wire->setActive(false)
                        ->save();
                    break;
                }
            }

        }

        try {
            $result = $stripe->cancelSubscription($subscription, ['stripe_account' => $user->getMerchant()['id']]);
            $repo->cancel('wire');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function saveWire($merchant)
    {
        $wire = (new Entities\Wire)
            ->setAmount($this->amount)
            ->setRecurring($this->recurring)
            ->setFrom(Core\Session::getLoggedInUser())
            ->setTo($merchant)
            ->setTimeCreated(time())
            ->setEntity($this->entity)
            ->setMethod('money');
        $wire->save();

        $repo = Di::_()->get('Wire\Repository');
        $repo->add($wire);

        // send email to receiver
        $description = 'Wire';

        if ($this->recurring) {
            $description .= ' Subscription';
        }

        Dispatcher::trigger('wire-payment-email', 'object', [
            'charged' => false,
            'amount' => $this->amount,
            'description' => $description,
            'user' => $merchant,
        ]);

        $this->cache->destroy(Counter::getIndexName($merchant->getGUID(), 'usd',null, false, false));
        $this->cache->destroy(Counter::getIndexName($this->entity->guid, 'usd',null, true));
        $this->cache->destroy(Counter::getIndexName(Core\Session::getLoggedInUser()->getGUID(), 'usd',null, false, true));
    }
}
