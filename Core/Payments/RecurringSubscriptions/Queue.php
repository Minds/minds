<?php

/**
 * Recurring Subscriptions Queue
 *
 * @author emi
 */

namespace Minds\Core\Payments\RecurringSubscriptions;

use Minds\Core\Di\Di;

class Queue
{
    /** @var Repository $repository */
    protected $repository;

    public function __construct($repository = null)
    {
        $this->repository = $repository ?: Di::_()->get('Payments\RecurringSubscriptions\Repository');
    }

    /**
     * Gets all recurring subscriptions that are due
     * @param \DateTime|int $timestamp
     * @return array|\Cassandra\Rows
     */
    public function get($timestamp)
    {
        if ($timestamp instanceof \DateTime) {
            $timestamp = $timestamp->getTimestamp();
        }

        $rows = $this->repository->select([
            'status' => 'active',
            'next_billing' => $timestamp
        ]);

        if (!$rows) {
            return [];
        }

        return $rows;
    }

    /**
     * Marks a recurring subscription as processed (updating billing dates)
     * @param array $recurring_subscription
     * @throws \Exception
     */
    public function processed(array $recurring_subscription)
    {
        /** @var Manager $manager */
        $manager = Di::_()->get('Payments\RecurringSubscriptions\Manager');
        $manager
            ->setType($recurring_subscription['type'])
            ->setPaymentMethod($recurring_subscription['payment_method'])
            ->setEntityGuid($recurring_subscription['entity_guid'])
            ->setUserGuid($recurring_subscription['user_guid'])
            ->updateBilling($recurring_subscription['last_billing'], $recurring_subscription['recurring']);
    }
}
