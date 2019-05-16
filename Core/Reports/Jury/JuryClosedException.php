<?php
namespace Minds\Core\Reports\Jury;

class JuryClosedException extends \Exception
{
    protected $message = "The jury has closed";
}
