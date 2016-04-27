<?php
/**
 * Minds encryption factory
 */

namespace Minds\Plugin\Messenger\Core;

class Encryption
{

    private $handler;

    public function __construct($handler = NULL)
    {
        $this->handler = $handler ?: new Encryption\OpenSSL();
    }

    public function encrypt($message)
    {
        $this->handler->encrypt($message);
    }

}
