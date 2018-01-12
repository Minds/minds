<?php

namespace Minds\Core\Wire\Methods;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Wire\Counter;
use Minds\Entities;
use Minds\Entities\User;
use Minds\Helpers;

class Points implements MethodInterface
{
    private $amount;
    /** @var Entities\User $actor */
    protected $actor;
    protected $entity;
    /** @var Entities\User $owner */
    protected $owner;
    protected $from;
    protected $recurring; // monthly
    protected $timestamp;
    protected $manager;
    protected $repository;
    protected $cache;

    public function __construct($manager = null, $repository = null, $cache = null)
    {
        $this->manager = $manager ?: Core\Di\Di::_()->get('Wire\Manager');
        $this->repository = $manager ?: Core\Di\Di::_()->get('Wire\Repository');
        $this->cache = $cache ?: Core\Di\Di::_()->get('Cache');
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

    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    public function setPayload($payload = [])
    {
        return $this;
    }

    public function setRecurring($recurring)
    {
        $this->recurring = $recurring;
        return $this;
    }

    /**
     * @param mixed $timestamp
     * @return $this
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * @return mixed|void
     * @throws \Exception
     */
    public function create()
    {
        if ($this->recurring) {
            $this->createSubscription();
            return;
        }

        $this->createWire();
    }

    /**
     * @return mixed|void
     * @throws \Exception
     */
    public function refund()
    {
        throw new \Exception('Cannot refund a points operation');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private function createSubscription() {
        /** @var Core\Payments\Subscriptions\Manager $recurringSubscriptionsManager */
        $recurringSubscriptionsManager = Di::_()->get('Payments\Subscriptions\Manager');

        $recurringSubscriptionsManager
            ->setType('wire')
            ->setPaymentMethod('points')
            ->setEntityGuid($this->owner->guid)
            ->setUserGuid($this->actor->guid);

        // Cancel old subscription first
        $recurringSubscriptionsManager->cancel();

        $now = time();

        $subscription_id = $recurringSubscriptionsManager->create([
            'recurring' => 'monthly',
            'amount' => $this->amount,
            'last_billing' => $now
        ]);

        if ($subscription_id) {
            $wire = $this->createWire([
                'subscription_id' => $subscription_id
            ]);

            if (!$wire) {
                throw new \Exception('Error wiring');
            }
        }

        return $subscription_id;
    }

    /**
     * @param array $options
     * @return bool
     * @throws \Exception
     */
    protected function createWire(array $options = [])
    {
        $options = array_merge([
            'subscription_id' => null
        ], $options);

        $description = $this->recurring ? 'Wire (Recurring)' : 'Wire';

        if ($this->amount > (int) Helpers\Counters::get($this->actor->guid, 'points', false)) {
            throw new \Exception('Not enough points');
        }

        Helpers\Wallet::createTransaction($this->actor->guid, -$this->amount, $this->entity->guid,
            $description);

        Helpers\Wallet::createTransaction($this->owner->guid, $this->amount, $this->entity->guid,
            $description);

        $wire = (new Entities\Wire)
            ->setAmount($this->amount)
            ->setRecurring($this->recurring)
            ->setFrom($this->actor)
            ->setTo($this->owner)
            ->setTimeCreated(time())
            ->setEntity($this->entity)
            ->setMethod('points');
        $wire->save();

        $repo = Di::_()->get('Wire\Repository');

        $repo->add($wire);

        // $this->cache->destroy("counter:wire:sums:" . $user->getGUID() . "*");
        $this->cache->destroy(Counter::getIndexName($this->entity->guid, null, 'points', null, true));
        // if ($this->from) {
        //     $this->cache->destroy("counter:wire:sums:" . $this->from->getGUID() . "*");
        // }

        /** @var Core\Payments\Manager $paymentsManager */
        $paymentsManager = Di::_()->get('Payments\Manager');

        $paymentData = [
            'payment_method' => 'points',
            'amount' => $this->amount,
            'description' => 'Wire @' . $this->owner->username,
            'status' => 'paid'
        ];

        if ($options['subscription_id']) {
            $paymentData['subscription_id'] = $options['subscription_id'];
        }

        $paymentId = $paymentsManager
            ->setType('wire')
            ->setUserGuid($this->actor->guid)
            ->setTimeCreated($this->timestamp ?: time())
            ->create($paymentData);

        return $paymentId;
    }

    public function onRecurring($subscription_id)
    {
        return $this->createWire([
            'subscription_id' => $subscription_id
        ]);
    }
}
