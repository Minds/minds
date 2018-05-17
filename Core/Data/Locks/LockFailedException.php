<?php
namespace Minds\Core\Data\Locks;

class LockFailedException extends \Exception
{

    protected $message = 'Sorry, there was a problem. Please try again';

}
