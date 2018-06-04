<?php

/**
 * Payments Manager
 *
 * @author emi
 */

namespace Minds\Core\Payments;

use Minds\Core\Di\Di;
use Minds\Core\Guid;
use Minds\Helpers\Cql;

class Manager
{
    /** @var string $type */
    protected $type;

    /** @var integer|string $user_guid */
    protected $user_guid;

    /** @var integer $time_created */
    protected $time_created;

    /** @var string $payment_id */
    protected $payment_id;

    /** @var Repository $repository */
    protected $repository;

    public function __construct($repository = null)
    {
        $this->repository = $repository ?: Di::_()->get('Payments\Repository');
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
     * @return int
     */
    public function getTimeCreated()
    {
        return $this->time_created;
    }

    /**
     * @param int $time_created
     */
    public function setTimeCreated($time_created)
    {
        $this->time_created = $time_created;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentId()
    {
        return $this->payment_id;
    }

    /**
     * @param string $payment_id
     */
    public function setPaymentId($payment_id)
    {
        $this->payment_id = $payment_id;
        return $this;
    }

    /**
     * @param array $data
     * @return string
     * @throws \Exception
     */
    public function create(array $data)
    {
        if (!$this->getType()) {
            throw new \Exception('Type is required');
        }

        if (!$this->getUserGuid()) {
            throw new \Exception('User GUID is required');
        }

        if (!$this->getTimeCreated()) {
            throw new \Exception('Time created is required');
        }

        if (!$this->getPaymentId()) {
            $this->setPaymentId('guid:' . Guid::build());
        }

        $result = $this->repository->upsert(
            $this->getType(),
            $this->getUserGuid(),
            $this->getTimeCreated(),
            $this->getPaymentId(),
            $data
        );

        if (!$result) {
            throw new \Exception('Cannot save payment');
        }

        return $this->getPaymentId();
    }

    /**
     * @param array $data
     * @return string|bool
     * @throws \Exception
     */
    public function updatePaymentById(array $data)
    {
        $row = $this->repository->getByPaymentId($this->getPaymentId());

        if (!$row) {
            return false;
        }

        $row = Cql::toPrimitiveType($this->repository->getByPaymentId($this->getPaymentId()));

        $this
            ->setType($row['type'])
            ->setUserGuid($row['user_guid'])
            ->setTimeCreated($row['time_created']);

        $result = $this->repository->upsert(
            $this->getType(),
            $this->getUserGuid(),
            $this->getTimeCreated(),
            $this->getPaymentId(),
            $data
        );

        if (!$result) {
            throw new \Exception('Cannot update payment');
        }

        return $this->getPaymentId();
    }
}
