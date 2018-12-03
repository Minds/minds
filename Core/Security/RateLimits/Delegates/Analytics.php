<?php
/**
 * RateLimit analytics delegate
 */

namespace Minds\Core\Security\RateLimits\Delegates;

use Minds\Core\Analytics\Metrics\Event;

class Analytics
{

    private $event;

    public function __construct($event = null)
    {
        $this->event = $event ?: new Event();
    }

    public function emit($user, $key, $period)
    {
        $this->event->setType('action')
            ->setProduct('platform')
            ->setUserGuid((string) $user->getGuid())
            ->setUserPhoneNumberHash($user->getPhoneNumberHash())
            ->setAction("ratelimit")
            ->setRatelimitKey($key)
            ->setRatelimitPeriod($period)
            ->push();
    }

}
