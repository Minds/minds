<?php
namespace Minds\Core\Rewards\Withdraw;

class Request
{

    private $tx;
    private $address;
    private $user_guid;
    private $gas;
    private $amount;

    public function setTx($tx)
    {
        $this->tx = $tx;
        return $this;
    }

    public function getTx()
    {
        return $this->tx;
    }

    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setUserGuid($user_guid)
    {
        $this->user_guid = $user_guid;
        return $this;
    }

    public function getUserGuid()
    {
        return $this->user_guid;
    }

    public function setGas($gas)
    {
        $this->gas = $gas;
        return $this;
    }

    public function getGas()
    {
        return $this->gas;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

}