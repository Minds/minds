<?php

namespace Minds\Core\Wire\Methods;

class WalletNotSetupException extends \Exception
{
    public function __construct() {
        $this->message = 'Sorry, this user cannot receive MindsCoin.';
    }
}
