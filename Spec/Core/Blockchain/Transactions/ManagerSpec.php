<?php

namespace Spec\Minds\Core\Blockchain\Transactions;

use Minds\Core\Blockchain\Events\WireEvent;
use Minds\Core\Blockchain\Services\Ethereum;
use Minds\Core\Blockchain\Transactions\Repository;
use Minds\Core\Blockchain\Transactions\Transaction;
use Minds\Core\Data\cache\Redis;
use Minds\Core\Events\EventsDispatcher;
use PhpSpec\ObjectBehavior;

class ManagerSpec extends ObjectBehavior
{
    /** @var Repository */
    private $repo;
    /** @var Ethereum */
    private $eth;
    /** @var \Minds\Core\Queue\RabbitMQ\Client */
    private $rabbit;
    /** @var Redis */
    private $cacher;
    /** @var EventsDispatcher */
    private $dispatcher;

    function let(
        Repository $repo,
        Ethereum $eth,
        \Minds\Core\Queue\RabbitMQ\Client $rabbit,
        Redis $cacher,
        EventsDispatcher $dispatcher
    ) {
        $this->repo = $repo;
        $this->eth = $eth;
        $this->rabbit = $rabbit;
        $this->cacher = $cacher;
        $this->dispatcher = $dispatcher;

        $this->beConstructedWith($repo, $eth, $rabbit, $cacher, $dispatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Transactions\Manager');
    }

    function it_should_add_a_transaction_to_the_queue(Transaction $transaction)
    {
        $transaction->getUserGuid()
            ->shouldBeCalled()
            ->willReturn('123');

        $transaction->getTimestamp()
            ->shouldBeCalled()
            ->willReturn(12345678);

        $transaction->getWalletAddress()
            ->shouldBeCalled()
            ->willReturn('0x123');

        $transaction->getTx()
            ->shouldBeCalled()
            ->willReturn('0x123123');

        $this->repo->add($transaction)
            ->shouldBeCalled();

        $this->rabbit->setQueue("BlockchainTransactions")
            ->shouldBeCalled()
            ->willReturn($this->rabbit);

        $this->rabbit->send([
            'user_guid' => '123',
            'timestamp' => 12345678,
            'wallet_address' => '0x123',
            'tx' => '0x123123',
        ])
            ->shouldBeCalled();

        $this->add($transaction);
    }

    function it_should_run_but_fail_because_the_transaction_does_not_exist()
    {
        $this->setUserGuid('123');
        $this->setTimestamp('12345678');
        $this->setWalletAddress('0x123123');
        $this->setTx('0x123');

        $this->repo->getList([
            'user_guid' => '123',
            'timestamp' => [
                'eq' => '12345678',
            ],
            'wallet_address' => '0x123123',
            'tx' => '0x123',
        ])
            ->shouldBeCalled()
            ->willReturn(null);

        $this->shouldThrow(new \Exception('Transaction 0x123 not found'))->during('run');
    }

    function it_should_run_but_fail_because_the_transaction_has_been_already_completed(Transaction $transaction)
    {
        $this->setUserGuid('123');
        $this->setTimestamp('12345678');
        $this->setWalletAddress('0x123123');
        $this->setTx('0x123');

        $this->repo->getList([
            'user_guid' => '123',
            'timestamp' => [
                'eq' => '12345678',
            ],
            'wallet_address' => '0x123123',
            'tx' => '0x123',
        ])
            ->shouldBeCalled()
            ->willReturn(['transactions' => [$transaction]]);

        $transaction->isCompleted()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->shouldThrow(new \Exception('Transaction already completed'))->during('run');
    }

    function it_should_run_and_add_the_receipt(Transaction $transaction)
    {
        $this->setUserGuid('123');
        $this->setTimestamp('12345678');
        $this->setWalletAddress('0x123123');
        $this->setTx('0x123');

        $this->repo->getList([
            'user_guid' => '123',
            'timestamp' => [
                'eq' => '12345678',
            ],
            'wallet_address' => '0x123123',
            'tx' => '0x123',
        ])
            ->shouldBeCalled()
            ->willReturn(['transactions' => [$transaction]]);

        $transaction->isCompleted()
            ->shouldBeCalled()
            ->willReturn(false);

        $transaction->getTx()
            ->shouldBeCalled()
            ->willReturn('0x123');

        $this->eth->request('eth_getTransactionReceipt', ['0x123'])
            ->shouldBeCalled()
            ->willReturn(null);

        // add
        $transaction->getUserGuid()
            ->shouldBeCalled()
            ->willReturn('123');

        $transaction->getTimestamp()
            ->shouldBeCalled()
            ->willReturn(12345678);

        $transaction->getWalletAddress()
            ->shouldBeCalled()
            ->willReturn('0x123123');

        $this->repo->add($transaction)
            ->shouldBeCalled();

        $this->rabbit->setQueue("BlockchainTransactions")
            ->shouldBeCalled()
            ->willReturn($this->rabbit);

        $this->rabbit->send([
            'user_guid' => '123',
            'timestamp' => 12345678,
            'wallet_address' => '0x123123',
            'tx' => '0x123',
        ])
            ->shouldBeCalled();

        $this->run();
    }

    function it_should_run_but_fail_because_the_receipt_has_a_wrong_status(
        Transaction $transaction,
        WireEvent $wireEvent
    ) {
        $this->setUserGuid('123');
        $this->setTimestamp('12345678');
        $this->setWalletAddress('0x123123');
        $this->setTx('0x123');

        $this->repo->getList([
            'user_guid' => '123',
            'timestamp' => [
                'eq' => '12345678',
            ],
            'wallet_address' => '0x123123',
            'tx' => '0x123',
        ])
            ->shouldBeCalled()
            ->willReturn(['transactions' => [$transaction]]);

        $transaction->isCompleted()
            ->shouldBeCalled()
            ->willReturn(false);

        $transaction->getTx()
            ->shouldBeCalled()
            ->willReturn('0x123');

        $this->eth->request('eth_getTransactionReceipt', ['0x123'])
            ->shouldBeCalled()
            ->willReturn([
                'status' => '0x0'
            ]);

        $transaction->setFailed(true)
            ->shouldBeCalled();

        $this->dispatcher->trigger('blockchain:listen', 'all', [], [])
            ->shouldBeCalled()
            ->willReturn([
                'blockchain:fail' => []
            ]);

        $transaction->getWalletAddress()
            ->shouldBeCalled()
            ->willReturn('0x123123');

        $this->cacher->destroy('blockchain:balance:0x123123')
            ->shouldBeCalled();

        $transaction->setCompleted(true)
            ->shouldBeCalled();
        $this->repo->add($transaction)
            ->shouldBeCalled();

        $this->run();
    }

    function it_should_run_and_call_topics(Transaction $transaction, WireEvent $wireEvent)
    {
        $this->setUserGuid('123');
        $this->setTimestamp('12345678');
        $this->setWalletAddress('0x123123');
        $this->setTx('0x123');

        $this->repo->getList([
            'user_guid' => '123',
            'timestamp' => [
                'eq' => '12345678',
            ],
            'wallet_address' => '0x123123',
            'tx' => '0x123',
        ])
            ->shouldBeCalled()
            ->willReturn(['transactions' => [$transaction]]);

        $transaction->isCompleted()
            ->shouldBeCalled()
            ->willReturn(false);

        $transaction->getTx()
            ->shouldBeCalled()
            ->willReturn('0x123');

        $this->eth->request('eth_getTransactionReceipt', ['0x123'])
            ->shouldBeCalled()
            ->willReturn([
                'status' => '0x1',
                'logs' => [
                    'topics' => '0x317c0f5ab60805d3e3fb6aaa61ccb77253bbb20deccbbe49c544de4baa4d7f8f'
                ]
            ]);

        $this->dispatcher->trigger('blockchain:listen', 'all', [], [])
            ->shouldBeCalled()
            ->willReturn([
                '0x317c0f5ab60805d3e3fb6aaa61ccb77253bbb20deccbbe49c544de4baa4d7f8f' => []
            ]);

        $transaction->getWalletAddress()
            ->shouldBeCalled()
            ->willReturn('0x123123');

        $this->cacher->destroy('blockchain:balance:0x123123')
            ->shouldBeCalled();

        $transaction->setCompleted(true)
            ->shouldBeCalled();
        $this->repo->add($transaction)
            ->shouldBeCalled();

        $this->run();
    }
}
