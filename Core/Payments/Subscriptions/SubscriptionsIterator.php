<?php
namespace Minds\Core\Payments\Subscriptions;

use Minds\Core;
use Minds\Entities\User;

class SubscriptionsIterator implements \Iterator
{


    /** @var int $from - timestamp */
    private $from;

    /** @var string $plan_id  */
    private $plan_id;

    /** @var string $payment_method  */
    private $payment_method;

    /** @var int $limit */
    private $limit = 2000;

    /** @var string $token */
    private $token = "";

     /** @var int $cursor  */
    private $cursor = -1;

    /** @var array $data */
    private $data = [];

    /** @var boolean $valid */
    private $valid = true;

    /** @var Repository */
    private $repository;

    public function __construct($repository = null)
    {
        $this->repository = $repository ?: Core\Di\Di::_()->get('Payments\Subscriptions\Repository');
    }

    /**
     * @param string $from
     * @return SubscriptionsIterator
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @param string $id
     * @return SubscriptionsIterator
     */
    public function setPlanId($id)
    {
        $this->plan_id = $id;
        return $this;
    }

    /**
     * @param string $method
     * @return SubscriptionsIterator
     */
    public function setPaymentMethod($method)
    {
        $this->payment_method = $method;
        return $this;
    }

    /**
     * @param string $token
     * @return SubscriptionsIterator
     */
    public function setToken($token = '')
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return SubscriptionsIterator
     */
    public function getData()
    {
        if (!isset($this->token)) {
            $this->valid = false;
            return;
        }

        $options = [
            'plan_id' => $this->plan_id,
            'payment_method' => $this->payment_method,
            'limit' => $this->limit,
            //'offset' => base64_decode($this->token),
            'status' => 'active',
            'next_billing' => $this->from,
        ];

        $subscriptions = $this->repository->getList($options);

        if (!$subscriptions) {
            $this->valid = false;
            return;
        }

        $this->token = null;

        foreach ($subscriptions as $subscription) {
            $this->data[] = $subscription;
        }

        return $this;
    }

    /**
     * Rewind the array cursor
     * @return null
     */
    public function rewind()
    {
        if ($this->cursor >= 0) {
            $this->getData();
        }
        $this->next();
    }

    /**
     * Get the current cursor's data
     * @return mixed
     */
    public function current()
    {
        return $this->data[$this->cursor];
    }

    /**
     * Get cursor's key
     * @return mixed
     */
    public function key()
    {
        return $this->cursor;
    }

    /**
     * Goes to the next cursor
     * @return null
     */
    public function next()
    {
        $this->cursor++;
        if (!isset($this->data[$this->cursor])) {
            $this->getData();
        }
    }

    /**
     * Checks if the cursor is valid
     * @return bool
     */
    public function valid()
    {
        return $this->valid && isset($this->data[$this->cursor]);
    }
}
