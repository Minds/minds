<?php

/**
 * Subscriptions Manager
 *
 * @author emi / mark
 */

namespace Minds\Core\Payments\Subscriptions;

use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;
use Minds\Core\Guid;
use Minds\Core\Payments;
use Minds\Core\Events\Dispatcher;
use Minds\Entities\Factory;
use Minds\Entities\User;

class Manager
{
    public static $allowedRecurring = [
        'daily',
        'monthly',
        'yearly',
        'custom'
    ];

    /** @var Repository $repository */
    protected $repository;

    /** @var Subscription $subscription */
    protected $subscription;

    /** @var User */
    protected $user;

    public function __construct($repository = null)
    {
        $this->repository = $repository ?: Di::_()->get('Payments\Subscriptions\Repository');
    }

    /**
     * @param Subscription
     * @return Manager
     */
    public function setSubscription($subscription)
    {
        $this->subscription = $subscription;
        return $this;
    }

    /**
     * Charge
     * @return bool
     */
    public function charge()
    {
        try {
            $result = Dispatcher::trigger('subscriptions:process', $this->subscription->getPlanId(), [
                'subscription' => $this->subscription
            ]);

            $this->subscription->setLastBilling(time());
            $this->subscription->setNextBilling($this->getNextBilling());
        } catch (\Exception $e) {
            error_log("Payment failed: " . $e->getMessage());
            $this->subscription->setStatus('failed');
        }

        $this->repository->add($this->subscription);

        return $result;
    }


    /////
    /// BELOW NEEDS REFACTORING TO MATCH MANAGER STYLE /MH
    /////

    /**
     * @param User $user
     * @return Manager
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function create()
    {
        $this->subscription->isValid();

        $this->subscription->setNextBilling($this->getNextBilling());

        return (bool) $this->repository->add($this->subscription);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function update()
    {
        $this->subscription->isValid();

        $result = $this->repository->update($this->subscription);

        return (bool) $result;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function cancel()
    {
        $this->subscription->isValid();

        return (bool) $this->repository->delete($this->subscription);
    }


    /**
     * @return int|null
     * @throws \Exception
     */
    public function getNextBilling()
    {
        if (!$this->subscription->getLastBilling()) {
            return null;
        }

        $date = new \DateTime("@{$this->subscription->getLastBilling()}");

        switch ($this->subscription->getInterval()) {
            case 'daily':
                $date->modify('+1 day');
                break;
            case 'monthly':
                $date->modify('+1 month');
                break;
            case 'yearly':
                $date->modify('+1 year');
                break;
        }

        return $date->getTimestamp();
    }

    /**
     * Cancels all subscriptions from and to a User
     * @return bool
     * @throws \Exception
     */
    public function cancelAllSubscriptions()
    {
        if (!$this->user) {
            return false;
        }

        //get user's own subscriptions
        $ownSubscriptions = $this->repository
            ->getList([
                'user_guid' => $this->user->guid
            ]);

        $guid = $this->user->guid;

        //get subscriptions TO the user
        $othersSubscriptions = $this->repository->getList([
            'entity_guid' => $guid,
            'status' => 'active'
        ]);

        $subs = array_merge($ownSubscriptions, $othersSubscriptions);

        // cancel subscriptions
        foreach ($subs as $sub) {
            $this->repository->delete($sub);
        }

        return true;
    }

}
