<?php

namespace Minds\Core\Wire\Methods;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Payments;
use Minds\Entities\User;

class Money implements MethodInterface
{

    private $amount;
    private $entity;
    private $id;
    private $nonce;

    public function __construct($stripe = null)
    {
        $this->stripe = $stripe ?: Di::_()->get('StripePayments');
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    public function setPayload($payload = [])
    {
        $this->nonce = $payload['nonce'];
        return $this;
    }

    public function execute()
    {
        $sale = new Payments\Sale();
        $sale->setOrderId('wire-' . $this->entity->guid)
             ->setAmount($this->amount * 100) //cents to $
             ->setMerchant(new User($this->entity->owner_guid))
             ->setCustomerId(Core\Session::getLoggedInUser()->guid)
             ->setNonce($this->nonce)
             ->capture();
        $this->id = $this->stripe->setSale($sale);
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

}
