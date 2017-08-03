<?php

namespace Minds\Core\Wire\Methods;

interface MethodInterface
{

    public function setAmount($amount);

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
}
