<?php

namespace Spec\Minds\Core\Blockchain\Transactions;

use Minds\Core\Blockchain\Transactions\Transaction;
use Minds\Core\Data\Call;
use Minds\Core\Data\lookup;
use Minds\Core\Di\Di;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TransactionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Transaction::class);
    }

    function it_should_export(Call $call, lookup $lookup)
    {
        Di::_()->bind('Database\Cassandra\Indexes', function ($di) use ($call) {
            return $call->getWrappedObject();
        });

        Di::_()->bind('Database\Cassandra\Data\Lookup', function ($di) use ($lookup) {
            return $lookup->getWrappedObject();
        });

        $this->setUserGuid('123');
        $this->setWalletAddress('0x123');
        $this->setTx('0x123123');
        $this->setAmount(300);
        $this->setFailed(false);
        $this->setTimestamp(12345678);

        $this->setData([
            'sender_guid'=> '123',
            'receiver_guid' => '456',
        ]);

        $this->export()->shouldHaveKeyWithValue('user_guid', '123');
        $this->export()->shouldHaveKeyWithValue('wallet_address', '0x123');
        $this->export()->shouldHaveKeyWithValue('tx', '0x123123');
        $this->export()->shouldHaveKeyWithValue('amount', 300);
        $this->export()->shouldHaveKeyWithValue('failed', false);
        $this->export()->shouldHaveKeyWithValue('timestamp', 12345678);
        $this->export()['user']->shouldBeArray();
        $this->export()['sender']->shouldBeArray();
        $this->export()['receiver']->shouldBeArray();
    }
}
