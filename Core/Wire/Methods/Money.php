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

    protected $amount;
    /** @var Entities\User $owner */
    protected $owner;
    protected $entity;
    /** @var Entities\User $actor */
    protected $actor;
    protected $nonce;
    protected $recurring; // monthly
    protected $timestamp;
    protected $manager;
    protected $repository;
    protected $cache;
    /** @var Payments\Stripe\Stripe $stripe */
    protected $stripe;
    /** @var Core\Payments\Subscriptions\Manager $subscriptionsManager */
    protected $subscriptionsManager;
    /** @var Core\Payments\Subscriptions\Repository $subscriptionsRepository */
    protected $subscriptionsRepository;

    public function __construct(
        $stripe = null,
        $manager = null,
        $repository = null,
        $cache = null,
        $subscriptionsManager = null,
        $subscriptionsRepository = null
    )
    {
        $this->stripe = $stripe ?: Di::_()->get('StripePayments');
        $this->manager = $manager ?: Core\Di\Di::_()->get('Wire\Manager');
        $this->repository = $repository ?: Core\Di\Di::_()->get('Wire\Repository');
        $this->cache = $cache ?: Core\Di\Di::_()->get('Cache');
        $this->subscriptionsManager = $subscriptionsManager ?: Di::_()->get('Payments\Subscriptions\Manager');
        $this->subscriptionsRepository = $subscriptionsRepository ?: Di::_()->get('Payments\Subscriptions\Repository');        
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function setActor(User $user)
    {
        $this->actor = $user;
        return $this;
    }

    public function setEntity($entity)
    {
        if (!is_object($entity)) {
            $entity = Entities\Factory::build($entity);
        }

        $this->owner = $entity->type != 'user' ?
            Entities\Factory::build($entity->owner_guid) :
            $entity;

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
        if ($this->recurring) {
            return $this->createSubscription();
        }
        return $this->createSale();
    }

    /**
     * @return mixed
     */
    public function refund() {
        throw new \Exception('Cannot refund a money operation');
    }

    private function createSubscription()
    {
        if (!$this->owner->getMerchant()['id']) {
            throw new NotMonetizedException();
        }

        $merchantId = $this->owner->getMerchant()['id'];

        $customer = (new Payments\Customer())
            ->setUser($this->actor);

        $stripe = Core\Di\Di::_()->get('StripePayments');

        if (!$stripe->getCustomer($customer) || !$customer->getId()) {
            //create the customer on stripe
            $customer->setPaymentToken($this->nonce);
            $customer = $stripe->createCustomer($customer);
        }

        //look for current subscription
        $this->cancelSubscription();

        $plan = $stripe->getPlan('wire', $merchantId);
        $wireNominal = 100; //wire subscriptions are all $1

        if (!$plan) {
            $stripe->createPlan((object) [
                'id' => 'wire',
                'amount' => $wireNominal,
                'merchantId' => $merchantId
            ]);
        }

        $subscription = (new Payments\Subscriptions\Subscription())
            ->setPlanId('wire')
            ->setPaymentMethod('money')
            ->setQuantity($this->amount)
            ->setAmount($this->amount)
            ->setUser($this->actor)
            ->setFee($this->calculateFee($this->amount * $wireNominal))
            ->setMerchant($this->owner);

        $subscription->setId($stripe->createSubscription($subscription));

        if (!$subscription->getId()) {
            throw new \Exception('Cannot create subscription');
        }

        $this->subscriptionsManager->setSubscription($subscription);
        $this->subscriptionsManager->create();

        $this->saveWire();

        return [ 'subscriptionId' => $this->subscription->getId() ];
    }

    private function createSale()
    {
        if (!$this->owner->getMerchant()['id']) {
            throw new NotMonetizedException();
        }

        $customer = (new Payments\Customer())
            ->setUser($this->actor);

        $stripe = Core\Di\Di::_()->get('StripePayments');

        if (!$stripe->getCustomer($customer) || !$customer->getId()) { // if customer doesn't exist on Stripe, create it
            //create the customer on stripe
            $customer->setPaymentToken($this->nonce);
            $customer = $stripe->createCustomer($customer);
            $this->nonce = $customer->getId();
        }

        $sale = new Payments\Sale();
        $sale->setOrderId('wire-' . $this->entity->guid)
            ->setAmount($this->amount * 100)//cents to $
            ->setMerchant($this->owner)
            ->setCustomer($customer)
            ->setSource($this->nonce)
            ->setFee($this->calculateFee($this->amount * 100))
            ->capture();

        $saleId = $this->stripe->setSale($sale);

        $this->saveWire();

        /** @var Core\Payments\Manager $manager */
        $manager = Di::_()->get('Payments\Manager');
        $manager
            ->setType('wire')
            ->setUserGuid($this->actor->guid)
            ->setTimeCreated($this->timestamp ?: time())
            ->create([
                'payment_method' => 'money',
                'amount' => $this->amount,
                'description' => 'Wire @' . $this->owner->username,
                'status' => 'paid'
            ]);

        return $saleId;
    }

    /**
     * @return string wire_guid
     */
    public function cancelSubscription()
    {

        $subscriptions = $this->subscriptionsRepository->getList([
            'plan_id' => 'wire',
            'payment_method' => 'money',
            'entity_guid' => $this->owner->guid,
            'user_guid' => $this->actor->guid
        ]);

        if (!$subscriptions) {
            return false;
        }

        $subscription = $subscriptions[0];

        try {
    
            $subscription->setMerchant($this->owner);

            $result = $this->stripe->cancelSubscription($subscription);

            if ($result) {
                $this->subscriptionsManager->setSubscription($subscription);
                $this->subscriptionsManager->cancel();
            }

            return (bool) $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Calculate the processing fee
     * @param int $gross - the gross amount
     * @return int
     */
    private function calculateFee($gross)
    {
        $stripe = ($gross * 0.029) + 30;
        $net = $gross - $stripe;
        $fee = $net * 0.04;
        $pct = $fee / $gross;
        return round($pct, 2);
    }

    private function saveWire()
    {
        $wire = (new Entities\Wire)
            ->setAmount($this->amount)
            ->setRecurring($this->recurring)
            ->setFrom($this->actor)
            ->setTo($this->owner)
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
            'user' => $this->owner,
        ]);

        // $this->cache->destroy("counter:wire:sums:" . $this->owner->guid . ":*");
        $this->cache->destroy(Counter::getIndexName($this->entity->guid, null, 'money', null, true));
        //$this->cache->destroy("counter:wire:sums:" . Core\Session::getLoggedInUser()->getGUID() . ":*");
    }

    public function onRecurring($subscription_id)
    {
        return true;
    }

}
