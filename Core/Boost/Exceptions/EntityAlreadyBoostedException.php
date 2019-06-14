<?php

namespace Minds\Core\Boost\Exceptions;

use Throwable;

class EntityAlreadyBoostedException extends \Exception
{
    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct("There's already an ongoing boost for this entity", $code, $previous);
    }
}
