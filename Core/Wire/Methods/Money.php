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
        $merchant = new User($this->entity->owner_guid);

        if (!$merchant->getMerchant()['id']) {
            $message = 'Somebody wanted to send you a money wire, but you need to setup your merchant account first! You can monetize your account in your Wallet.';

            Core\Events\Dispatcher::trigger('notification', 'wire', [
              'to' => [ $this->entity->owner_guid ],
              'from' => 100000000000000519,
              'notification_view' => 'custom_message',
              'params' => [ 'message' => $message ],
              'message' => $message,
            ]);

            throw new \Exception('Sorry, this user cannot receive USD.');
        }

        $sale = new Payments\Sale();
        $sale->setOrderId('wire-' . $this->entity->guid)
             ->setAmount($this->amount * 100) //cents to $
             ->setMerchant($merchant)
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
