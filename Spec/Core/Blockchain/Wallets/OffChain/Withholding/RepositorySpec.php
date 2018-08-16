<?php

namespace Spec\Minds\Core\Blockchain\Wallets\OffChain\Withholding;

use Minds\Core\Blockchain\Wallets\OffChain\Withholding\Repository;
use Minds\Core\Blockchain\Wallets\OffChain\Withholding\Withholding;
use Minds\Core\Data\Cassandra\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    /** @var Client */
    private $db;

    function let(Client $db)
    {
        $this->db = $db;

        $this->beConstructedWith($db);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_add_a_withholding(Withholding $w)
    {
        $w->getUserGuid()
            ->shouldBeCalled()
            ->willReturn(123);
        $w->getTimestamp()
            ->shouldBeCalled()
            ->willReturn(12345678);
        $w->getTx()
            ->shouldBeCalled()
            ->willReturn('0xTX');
        $w->getType()
            ->shouldBeCalled()
            ->willReturn('boost');
        $w->getWalletAddress()
            ->shouldBeCalled()
            ->willReturn('0xWALLET');
        $w->getAmount()
            ->shouldBeCalled()
            ->willReturn(10);
        $w->getTtl()
            ->shouldBeCalled()
            ->willReturn(10);


        $this->db->batchRequest(Argument::that(function ($requests) {
            $request = $requests[0];
            return $request['string'] === "INSERT INTO withholdings (user_guid, timestamp, tx, type, wallet_address, amount) VALUES (?, ?, ?, ?, ?, ?) USING TTL ?"
                && $request['values'][0]->value() === '123'
                && $request['values'][1]->time() === 12345678
                && $request['values'][2] === '0xTX'
                && $request['values'][3] === 'boost'
                && $request['values'][4] === '0xWALLET'
                && $request['values'][5]->value() === '10'
                && $request['values'][6] === 10;
        }), \Cassandra::BATCH_UNLOGGED)
            ->shouldBeCalled();

        $this->add($w)->shouldReturn(true);
    }
}
