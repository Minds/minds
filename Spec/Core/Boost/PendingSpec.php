<?php

namespace Spec\Minds\Core\Boost;

use Minds\Core\Blockchain\Pending;
use Minds\Core\Blockchain\Services\Ethereum;
use Minds\Core\Blockchain\Transactions\Manager;
use Minds\Core\Blockchain\Transactions\Transaction;
use Minds\Core\Blockchain\Util;
use Minds\Core\Boost\Repository;
use Minds\Core\Config;
use Minds\Core\Di\Di;
use Minds\Core\Util\BigNumber;
use Minds\Entities\Boost\Network;
use Minds\Entities\Boost\Peer;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PendingSpec extends ObjectBehavior
{
    protected $config;
    protected $pendingManager;
    protected $ethereumClient;
    protected $blockchainTx;

    function let(
        Config $config,
        Pending $pendingManager,
        Ethereum $ethereumClient,
        Manager $blockchainTx
    ) {
        $this->config = $config;
        $this->pendingManager = $pendingManager;
        $this->ethereumClient = $ethereumClient;
        $this->blockchainTx = $blockchainTx;

        $this->beConstructedWith($config, $pendingManager, $ethereumClient, $blockchainTx);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Boost\Pending');
    }

    function it_should_add(
        Network $boost,
        User $owner
    )
    {
        $owner->get('guid')->willReturn(1000);

        $boost->getOwner()->willReturn($owner);
        $boost->getHandler()->willReturn('network');
        $boost->getGuid()->willReturn(4000);
        $boost->getTimeCreated()->willReturn(123456);

        $this->blockchainTx->add(Argument::that(function (Transaction $tx) use ($boost) {
            return (
                $tx->getTx() === 'tx123' &&
                $tx->getContract() === 'boost'
            );
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->pendingManager->add([
            'type' => 'boost',
            'tx_id' => 'tx123',
            'sender_guid' => 1000,
            'data' => [
                'type' => 'network',
                'guid' => 4000
            ]
        ])
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->shouldNotThrow(\Exception::class)
            ->duringAdd('tx123', $boost);
    }

    function it_should_resolve(
        Repository $repository,
        Network $boost,
        User $owner
    )
    {
        Di::_()->bind('Boost\Repository', function () use ($repository) {
            return $repository->getWrappedObject();
        });

        $owner->get('guid')->willReturn(1000);

        $boost->getOwner()->willReturn($owner);

        $pending = [
            'type' => 'boost',
            'tx_id' => 'tx123',
            'sender_guid' => 1000,
            'data' => [
                'type' => 'network',
                'guid' => 4000
            ]
        ];

        $this->pendingManager->get('boost', 'tx123')
            ->shouldBeCalled()
            ->willReturn($pending);

        $repository->getEntity($pending['data']['type'], $pending['data']['guid'])
            ->shouldBeCalled()
            ->willReturn($boost);

        $boost->setState('review')
            ->shouldBeCalled()
            ->willReturn($boost);

        $boost->save()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->pendingManager->delete('boost', 'tx123')
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->resolve('tx123', 4000)
            ->shouldReturn(true);
    }

    function it_should_resolve_a_peer_boost(
        Repository $repository,
        Peer $boost,
        User $owner
    )
    {
        Di::_()->bind('Boost\Repository', function () use ($repository) {
            return $repository->getWrappedObject();
        });

        $owner->get('guid')->willReturn(1000);

        $boost->getOwner()->willReturn($owner);

        $pending = [
            'type' => 'boost',
            'tx_id' => 'tx123',
            'sender_guid' => 1000,
            'data' => [
                'type' => 'peer',
                'guid' => 4000
            ]
        ];

        $this->pendingManager->get('boost', 'tx123')
            ->shouldBeCalled()
            ->willReturn($pending);

        $repository->getEntity($pending['data']['type'], $pending['data']['guid'])
            ->shouldBeCalled()
            ->willReturn($boost);

        $boost->setState('created')
            ->shouldBeCalled()
            ->willReturn($boost);

        $boost->save()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->pendingManager->delete('boost', 'tx123')
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->resolve('tx123', 4000)
            ->shouldReturn(true);
    }

    function it_should_throw_if_no_pending_during_resolve(
        Repository $repository
    )
    {
        Di::_()->bind('Boost\Repository', function () use ($repository) {
            return $repository->getWrappedObject();
        });

        $pending = false;

        $this->pendingManager->get('boost', 'tx123')
            ->shouldBeCalled()
            ->willReturn($pending);

        $this
            ->shouldThrow(new \Exception('No pending Boost entry with hash tx123'))
            ->duringResolve('tx123', 4000);
    }

    function it_should_return_false_if_different_boost_guid_during_resolve(
        Repository $repository
    )
    {
        Di::_()->bind('Boost\Repository', function () use ($repository) {
            return $repository->getWrappedObject();
        });

        $pending = [
            'type' => 'boost',
            'tx_id' => 'tx123',
            'sender_guid' => 1000,
            'data' => [
                'type' => 'network',
                'guid' => 4004
            ]
        ];

        $this->pendingManager->get('boost', 'tx123')
            ->shouldBeCalled()
            ->willReturn($pending);

        $this
            ->resolve('tx123', 4000)
            ->shouldReturn(false);
    }

    function it_should_return_false_if_different_sender_during_resolve(
        Repository $repository,
        Network $boost,
        User $owner
    )
    {
        Di::_()->bind('Boost\Repository', function () use ($repository) {
            return $repository->getWrappedObject();
        });

        $owner->get('guid')->willReturn(1000);

        $boost->getOwner()->willReturn($owner);

        $pending = [
            'type' => 'boost',
            'tx_id' => 'tx123',
            'sender_guid' => 1001,
            'data' => [
                'type' => 'network',
                'guid' => 4000
            ]
        ];

        $this->pendingManager->get('boost', 'tx123')
            ->shouldBeCalled()
            ->willReturn($pending);

        $repository->getEntity($pending['data']['type'], $pending['data']['guid'])
            ->shouldBeCalled()
            ->willReturn($boost);

        $this
            ->resolve('tx123', 4000)
            ->shouldReturn(false);
    }

    function it_should_approve(
        Network $boost
    )
    {
        $boost->getGuid()->willReturn(1000);

        $this->config->get('blockchain')->willReturn([
            'boost_wallet_address' => '0xBWA',
            'boost_wallet_pkey' => '0xBWPKEY',
            'peer_boost_address' => '0xPBA'
        ]);

        $this->ethereumClient->encodeContractMethod('approve(uint256)', Argument::type('array'))
            ->shouldBeCalled()
            ->willReturn('0xDATA');

        $this->ethereumClient->sendRawTransaction('0xBWPKEY', [
            'from' => '0xBWA',
            'to' => '0xPBA',
            'gasLimit' => BigNumber::_(200000)->toHex(true),
            'data' => '0xDATA'
        ])
            ->shouldBeCalled()
            ->willReturn('0xTX');

        $this
            ->approve($boost)
            ->shouldReturn('0xTX');
    }

    function it_should_reject(
        Network $boost
    )
    {
        $boost->getGuid()->willReturn(1000);

        $this->config->get('blockchain')->willReturn([
            'boost_wallet_address' => '0xBWA',
            'boost_wallet_pkey' => '0xBWPKEY',
            'peer_boost_address' => '0xPBA'
        ]);

        $this->ethereumClient->encodeContractMethod('reject(uint256)', Argument::type('array'))
            ->shouldBeCalled()
            ->willReturn('0xDATA');

        $this->ethereumClient->sendRawTransaction('0xBWPKEY', [
            'from' => '0xBWA',
            'to' => '0xPBA',
            'gasLimit' => BigNumber::_(200000)->toHex(true),
            'data' => '0xDATA'
        ])
            ->shouldBeCalled()
            ->willReturn('0xTX');

        $this
            ->reject($boost)
            ->shouldReturn('0xTX');
    }
}
