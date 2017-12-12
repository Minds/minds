<?php

/**
 * Recurring Subscriptions Manager
 *
 * @author emi
 */

namespace Minds\Core\Payments\RecurringSubscriptions;

use Minds\Core\Di\Di;
use Minds\Core\Guid;
use Minds\Core\Payments;

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

    /** @var string $type */
    protected $type;

    /** @var string $payment_method */
    protected $payment_method;

    /** @var integer|string $entity_guid */
    protected $entity_guid;

    /** @var integer|string $user_guid */
    protected $user_guid;

    /** @var string $subscription_id */
    protected $subscription_id;

    public function __construct($repository = null)
    {
        $this->repository = $repository ?: Di::_()->get('Payments\RecurringSubscriptions\Repository');
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->payment_method;
    }

    /**
     * @param string $payment_method
     */
    public function setPaymentMethod($payment_method)
    {
        $this->payment_method = $payment_method;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getEntityGuid()
    {
        return $this->entity_guid;
    }

    /**
     * @param int|string $entity_guid
     */
    public function setEntityGuid($entity_guid)
    {
        $this->entity_guid = $entity_guid;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getUserGuid()
    {
        return $this->user_guid;
    }

    /**
     * @param int|string $user_guid
     */
    public function setUserGuid($user_guid)
    {
        $this->user_guid = $user_guid;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubscriptionId()
    {
        return $this->subscription_id;
    }

    /**
     * @param string $subscription_id
     */
    public function setSubscriptionId($subscription_id)
    {
        $this->subscription_id = $subscription_id;
        return $this;
    }

    /**
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function create(array $data)
    {
        if (!$this->getType()) {
            throw new \Exception('Type is required');
        }

        if (!$this->getPaymentMethod()) {
            throw new \Exception('Payment Method is required');
        }

        if (!$this->getEntityGuid()) {
            throw new \Exception('Entity GUID is required');
        }

        if (!$this->getUserGuid()) {
            throw new \Exception('User GUID is required');
        }

        if (!isset($data['recurring']) || !in_array($data['recurring'], static::$allowedRecurring)) {
            throw new \Exception('Recurring is invalid');
        }

        if (!isset($data['amount']) || $data['amount'] <= 0) {
            throw new \Exception('Amount is invalid');
        }

        if (!isset($data['subscription_id']) || !$data['subscription_id']) {
            $data['subscription_id'] = 'guid:' . Guid::build();
        }

        if (!isset($data['last_billing']) || !$data['last_billing']) {
            $data['last_billing'] = time();
        }

        if (!isset($data['next_billing']) || !$data['next_billing']) {
            $data['next_billing'] = $this->getNextBilling($data['last_billing'], $data['recurring']);
        }

        $data['status'] = 'active';

        $result = $this->repository->upsert(
            $this->getType(),
            $this->getPaymentMethod(),
            $this->getEntityGuid(),
            $this->getUserGuid(),
            $data
        );

        if (!$result) {
            throw new \Exception('Cannot save recurring subscription');
        }

        return $data['subscription_id'];
    }

    /**
     * @param $last_billing
     * @param $recurring
     * @return bool
     * @throws \Exception
     */
    public function updateBilling($last_billing, $recurring)
    {
        if (!$this->getType()) {
            throw new \Exception('Type is required');
        }

        if (!$this->getPaymentMethod()) {
            throw new \Exception('Payment Method is required');
        }

        if (!$this->getEntityGuid()) {
            throw new \Exception('Entity GUID is required');
        }

        if (!$this->getUserGuid()) {
            throw new \Exception('User GUID is required');
        }

        if ($last_billing instanceof \DateTime) {
            $last_billing = $last_billing->getTimestamp();
        }

        $result = $this->repository->upsert(
            $this->getType(),
            $this->getPaymentMethod(),
            $this->getEntityGuid(),
            $this->getUserGuid(),
            [
                'status' => 'active',
                'last_billing' => $last_billing,
                'next_billing' => $this->getNextBilling($last_billing, $recurring)
            ]
        );

        if (!$result) {
            throw new \Exception('Cannot update recurring subscription');
        }

        return (bool) $result;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function cancel()
    {
        if (!$this->getType()) {
            throw new \Exception('Type is required');
        }

        if (!$this->getPaymentMethod()) {
            throw new \Exception('Payment Method is required');
        }

        if (!$this->getEntityGuid()) {
            throw new \Exception('Entity GUID is required');
        }

        if (!$this->getUserGuid()) {
            throw new \Exception('User GUID is required');
        }

        return $this->repository->delete(
            $this->getType(),
            $this->getPaymentMethod(),
            $this->getEntityGuid(),
            $this->getUserGuid()
        );
    }

    /**
     * @param array $data
     */
    public function createPayment(array $data)
    {
        if (!isset($data['time_created'])) {
            $data['time_created'] = time();
        }

        if (!isset($data['payment_method'])) {
            $data['payment_method'] = $this->getPaymentMethod();
        }

        if (!isset($data['subscription_id'])) {
            $data['subscription_id'] = $this->getSubscriptionId();
        }

        /** @var Payments\Manager $paymentsManager */
        $paymentsManager = Di::_()->get('Payments\Manager');

        return $paymentsManager
            ->setType($this->getType())
            ->setUserGuid($this->getUserGuid())
            ->setTimeCreated($data['time_created'])
            ->setPaymentId(isset($data['payment_id']) ? $data['payment_id'] : null)
            ->create($data);
    }

    /**
     * @param \DateTime|int $last_billing
     * @param string $recurring
     * @return int|null
     * @throws \Exception
     */
    public function getNextBilling($last_billing, $recurring)
    {
        if (!$last_billing || $recurring === 'custom') {
            return null;
        }

        if (!in_array($recurring, static::$allowedRecurring)) {
            throw new \Exception('Invalid recurring value');
        }

        if ($last_billing instanceof \DateTime) {
            $last_billing = $last_billing->getTimestamp();
        }

        $date = new \DateTime("@{$last_billing}");

        switch ($recurring) {
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
