<?php
/**
 * Twofactor required Exception
 */
namespace Minds\Exceptions;

class TwoFactorRequired extends \Exception
{
    protected $code = "403";
}
