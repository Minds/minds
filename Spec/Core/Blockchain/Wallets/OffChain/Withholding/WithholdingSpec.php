<?php

namespace Spec\Minds\Core\Blockchain\Wallets\OffChain\Withholding;

use Minds\Core\Blockchain\Wallets\OffChain\Withholding\Withholding;
use PhpSpec\ObjectBehavior;

class WithholdingSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Withholding::class);
    }

    function it_should_export()
    {
        $this->setUserGuid('123');
        $this->setTimestamp(12345678);
        $this->setTx('0xTX');
        $this->setType('boost');
        $this->setWalletAddress('0xWALLET');
        $this->setAmount(100);
        $this->setTtl(10);

        $this->export()->shouldReturn([
            'user_guid' => '123',
            'timestamp' => 12345678,
            'tx' => '0xTX',
            'type' => 'boost',
            'wallet_address' => '0xWALLET',
            'amount' => 100,
            'ttl' => 10
        ]);
    }
}
