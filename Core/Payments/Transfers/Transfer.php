<?php
/**
 * Payment Subscription Entity
 */
namespace Minds\Core\Payments\Transfers;

class Transfer
{
    protected $id;
    protected $amount;
    protected $currency = 'USD';
    protected $destination;
    protected $source = [];

    public function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    public function getDestination()
    {
        return $this->destination;
    }

    public function setDestination($destination)
    {
        $this->destination = $destination;
        return $this;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setSource(array $source)
    {
        $this->source = $source;
        return $this;
    }
}
