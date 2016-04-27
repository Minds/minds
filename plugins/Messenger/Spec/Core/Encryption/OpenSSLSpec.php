<?php

namespace Messenger\Spec\Minds\Plugin\Messenger\Core\Encryption;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OpenSSLSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Plugin\Messenger\Core\Encryption\EncryptionInterface');
    }

}
