<?php
namespace Minds\Core\Rewards;

class Reward
{

    protected $type;
    protected $timestamp;
    protected $amount = 0;
    protected $user;
    protected $tx;

    /**
     * 
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    public function getTimestamp()
    {
        return $this->timestamp ?: time() * 1000;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setTx($tx)
    {
        $this->tx = $tx;
        return $this;
    }

    public function getTx()
    {
        return $this->tx;
    }

    public function export() {
        return [
            'type' => $this->type,
            'timestamp' => $this->timestamp,
            'amount' => $this->amount,
            'user' => $this->user
        ];
    }
}
