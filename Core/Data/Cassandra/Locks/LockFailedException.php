<?php
namespace Minds\Core\Data\Cassandra\Locks;

class LockFailedException extends \Exception
{

    protected $message = 'A lock could not be created';

}
