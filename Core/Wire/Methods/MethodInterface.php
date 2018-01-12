<?php

namespace Minds\Core\Wire\Methods;

use Minds\Entities\User;

interface MethodInterface
{

    public function setAmount($amount);

    public function setActor(User $user);

    public function setEntity($entity);

    public function setPayload($payload = []);

    /**
     * @return mixed
     */
    public function create();

    /**
     * @return mixed
     */
    public function refund();

    public function onRecurring($subscription_id);
}
