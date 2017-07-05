<?php

namespace Minds\Core\Plus;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Payments;
use Minds\Entities\User;

class Subscription
{

    private $stripe;
    private $repo;
    protected $user;

    public function __construct($stripe = null, $repo = null)
    {
        $this->stripe = $stripe ?: Di::_()->get('StripePayments');
        $this->repo = $repo ?: new Payments\Plans\Repository();
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function isActive()
    {
        $subscription = $this->getPlan();
        if (!$subscription) {
            return false;
        }

        return $subscription->getStatus() == 'active';
    }

    public function create($plan)
    {
        $this->repo->add($plan);
        return $this;
    }

    public function cancel()
    {
        $plan = $this->getPlan();

        $subscription = (new Payments\Subscriptions\Subscription)
          ->setId($plan->getSubscriptionId());
        if ($this->user->referrer){
            $referrer = new User($user->referrer);
            $subscription->setMerchant($referrer->getMerchant());
        }

        try{
            $result = $this->stripe->cancelSubscription($subscription);
            $this->repo->setEntityGuid(0)
              ->setUserGuid($this->user->guid)
              ->cancel('plus');
        } catch (\Exception $e) {
        }

        return $this;
    }

    public function getPlan()
    {
        $plan = $this->repo->setEntityGuid(0)
          ->setUserGuid($this->user->guid)
          ->getSubscription('plus');

        return $plan;

        /*$subscription = (new Payments\Subscriptions\Subscription)
          ->setId($plan->getSubscriptionId());
        if ($user->referrer){
            $referrer = new User($user->referrer);
            $subscription->setMerchant($referrer->getMerchant());
        }

        $subscription = $this->stripe->getSubscription($subscription);*/
        return $subscription;
    }

}
