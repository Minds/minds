<?php

namespace Minds\Core\Wire\Exceptions;

class WalletNotSetupException extends \Exception
{
    public function __construct() {
        $this->message = 'Sorry, this user cannot receive Tokens.';
    }
}
