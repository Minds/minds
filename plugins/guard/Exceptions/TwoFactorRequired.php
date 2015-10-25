<?php
/**
 * Twofactor required Exception
 */
namespace Minds\Plugin\Guard\Exceptions;

class TwoFactorRequired extends \Exception{

  protected $code = "403";

}
