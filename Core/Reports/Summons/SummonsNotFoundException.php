<?php
namespace Minds\Core\Reports\Summons;

class SummonsNotFoundException extends \Exception
{
    protected $message = "A summons could not be found";
}
