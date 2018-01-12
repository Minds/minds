<?php

/**
 * Subscriptions Queue
 *
 * @author emi / mark
 */

namespace Minds\Core\Payments\Subscriptions;

use Minds\Core\Di\Di;

class Queue
{
    /** @var Repository $repository */
    protected $repository;

    public function __construct($repository = null)
    {
        $this->repository = $repository ?: Di::_()->get('Payments\Subscriptions\Repository');
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

        $rows = $this->repository->getList([
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
        $manager = Di::_()->get('Payments\Subscriptions\Manager');
        $manager
            ->setType($recurring_subscription['type'])
            ->setPaymentMethod($recurring_subscription['payment_method'])
            ->setEntityGuid($recurring_subscription['entity_guid'])
            ->setUserGuid($recurring_subscription['user_guid'])
            ->updateBilling($recurring_subscription['last_billing'], $recurring_subscription['recurring']);
    }

}
