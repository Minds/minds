<?php

namespace Minds\Core\Boost\Peer;


use Minds\Core\Boost\Repository;
use Minds\Core\Data;
use Minds\Core\Di\Di;

class Metrics
{
    protected $mongo;

    public function __construct(Data\Interfaces\ClientInterface $mongo = null)
    {
        $this->mongo = $mongo ?: Data\Client::build('MongoDB');
    }

}