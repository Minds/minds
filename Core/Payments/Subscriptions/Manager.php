<?php

/**
 * Subscriptions Manager
 *
 * @author emi / mark
 */

namespace Minds\Core\Payments\Subscriptions;

use Minds\Core\Di\Di;
use Minds\Core\Guid;
use Minds\Core\Payments;
use Minds\Entities\Factory;

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

    public function __construct($repository = null)
    {
        $this->repository = $repository ?: Di::_()->get('Payments\Subscriptions\Repository');
    }

    /**
     * @param Subscription
     */
    public function setSubscription($subscription)
    {
        $this->subscription = $subscription;
        return $this;
    }

    /**
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function create()
    {
        $this->subscription->isValid();

        $this->subscription->setNextBilling($this->getNextBilling());

        return (bool) $this->repository->add($this->subscription);
    }

    /**
     * @param $last_billing
     * @param $recurring
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
     * @param \DateTime|int $last_billing
     * @param string $recurring
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

}
