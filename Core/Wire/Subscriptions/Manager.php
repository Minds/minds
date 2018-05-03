<?php

namespace Minds\Core\Wire\Subscriptions;


use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Wire\Exceptions\WalletNotSetupException;
use Minds\Entities;
use Minds\Entities\User;

class Manager
{

    /** @var Core\Payments\Subscriptions\Manager $subscriptionsManager */
    protected $subscriptionsManager;

    /** @var Core\Payments\Subscriptions\Repository $subscriptionsRepository */
    protected $subscriptionsRepository;

    /** @var Config */
    protected $config;

    /** @var int $amount */
    protected $amount;

    /** @var User $sender */
    protected $sender;
    
    /** @var User $receiver */
    protected $receiver;

    /** @var string $address */
    protected $address = 'offchain';

    public function __construct(
        $subscriptionsManager = null,
        $subscriptionsRepository = null,
        $config = null
    ) {
        $this->subscriptionsManager = $subscriptionsManager ?: Di::_()->get('Payments\Subscriptions\Manager');
        $this->subscriptionsRepository = $subscriptionsRepository ?: Di::_()->get('Payments\Subscriptions\Repository');
        $this->config = $config ?: Di::_()->get('Config');
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function setSender(User $sender)
    {
        $this->sender = $sender;
        return $this;
    }

    public function setReceiver(User $receiver)
    {
        $this->receiver = $receiver;
        return $this;
    }

    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return mixed
     * @throws WalletNotSetupException
     * @throws \Exception
     */
    public function create()
    {
        $this->cancelSubscription();

        $subscription = (new Core\Payments\Subscriptions\Subscription())
            ->setId($this->address)
            ->setPlanId('wire')
            ->setPaymentMethod('tokens')
            ->setAmount($this->amount)
            ->setUser($this->sender)
            ->setEntity($this->receiver);

        $this->subscriptionsManager->setSubscription($subscription);
        $this->subscriptionsManager->create();

        return $subscription->getId();
    }

    protected function cancelSubscription()
    {
        $subscriptions = $this->subscriptionsRepository->getList([
            'plan_id' => 'wire',
            'payment_method' => 'tokens',
            'entity_guid' => $this->receiver->guid,
            'user_guid' => $this->sender->guid
        ]);

        if (!$subscriptions) {
            return false;
        }

        $subscription = $subscriptions[0];

        $this->subscriptionsManager->setSubscription($subscription);

        // Cancel old subscription first
        $this->subscriptionsManager->cancel();
    }
}