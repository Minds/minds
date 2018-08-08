<?php

namespace Spec\Minds\Core\Wire;

use Minds\Core;
use Minds\Core\Blockchain\Transactions\Manager as BlockchainManager;
use Minds\Core\Blockchain\Transactions\Transaction;
use Minds\Core\Config;
use Minds\Core\Data\cache\Redis;
use Minds\Core\Queue\SQS\Client;
use Minds\Core\Wire\Repository;
use Minds\Core\Wire\Subscriptions\Manager as SubscriptionsManager;
use Minds\Core\Wire\Wire as WireModel;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{

    protected $cache;
    protected $repo;
    protected $subscriptionsManager;
    protected $txManager;
    protected $txRepo;
    protected $config;
    protected $queue;
    protected $client;
    protected $token;
    protected $cap;
    protected $dispatcher;

    protected $call;

    protected $balance;
    protected $redisLock;

    function let(
        Redis $cache,
        Repository $repo,
        SubscriptionsManager $subscriptionsManager,
        BlockchainManager $txManager,
        Core\Blockchain\Transactions\Repository $txRepo,
        Config $config,
        Client $queue,
        Core\Blockchain\Services\Ethereum $client,
        Core\Blockchain\Token $token,
        Core\Blockchain\Wallets\OffChain\Cap $cap,
        Core\Events\EventsDispatcher $dispatcher,
        Core\Data\Call $call,
        Core\Blockchain\Wallets\OffChain\Balance $balance,
        Core\Data\Locks\Redis $redisLock
    ) {
        $this->beConstructedWith($cache, $repo, $subscriptionsManager, $txManager, $txRepo, $config, $queue, $client,
            $token, $cap, $dispatcher);

        Core\Di\Di::_()->bind('Database\Cassandra\Entities', function ($di) use ($call) {
            return $call->getWrappedObject();
        });

        Core\Di\Di::_()->bind('Database\Cassandra\UserIndexes', function ($di) use ($call) {
            return $call->getWrappedObject();
        });

        Core\Di\Di::_()->bind('Blockchain\Transactions\Repository', function ($di) use ($txRepo) {
            return $txRepo->getWrappedObject();
        });
        Core\Di\Di::_()->bind('Blockchain\Wallets\OffChain\Balance', function ($di) use ($balance) {
            return $balance->getWrappedObject();
        });
        Core\Di\Di::_()->bind('Database\Locks', function ($di) use ($redisLock) {
            return $redisLock->getWrappedObject();
        });

        $this->cache = $cache;
        $this->repo = $repo;
        $this->subscriptionsManager = $subscriptionsManager;
        $this->txManager = $txManager;
        $this->txRepo = $txRepo;
        $this->config = $config;
        $this->queue = $queue;
        $this->client = $client;
        $this->token = $token;
        $this->cap = $cap;
        $this->dispatcher = $dispatcher;

        $this->call = $call;

        $this->balance = $balance;
        $this->redisLock = $redisLock;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Wire\Manager');
    }

    function it_should_create_an_onchain_wire()
    {
        $this->txManager->add(Argument::that(function ($transaction) {
            $data = $transaction->getData();
            return $transaction->getUserGuid() == 123
                && $transaction->getAmount() == -100001
                && $transaction->getWalletAddress() == '0xSPEC'
                && $transaction->getContract() == 'wire'
                && $transaction->getTx() == '0xTX'
                && $transaction->isCompleted() == false
                && $data['amount'] == 100001
                && $data['receiver_address'] == '0xRECEIVER'
                && $data['receiver_guid'] == 456
                && $data['sender_address'] == '0xSPEC'
                && $data['sender_guid'] == 123
                && $data['entity_guid'] == 456;
        }))
            ->shouldBeCalled();

        $sender = new User();
        $sender->guid = 123;

        $receiver = new User();
        $receiver->guid = 456;
        $receiver->eth_wallet = '0xRECEIVER';

        $payload = [
            'receiver' => '0xRECEIVER',
            'address' => '0xSPEC',
            'txHash' => '0xTX',
            'method' => 'onchain',
        ];

        $this->setSender($sender)
            ->setEntity($receiver)
            ->setPayload($payload)
            ->setAmount(100001)
            ->create()
            ->shouldReturn(true);
    }

    function it_should_confirm_a_wire_from_the_blockchain()
    {
        $this->txRepo->add(Argument::that(function ($transaction) {
            return $transaction->getUserGuid() == 123
                && $transaction->getWalletAddress() == '0xRECEIVER'
                && $transaction->getAmount() == 100001
                && $transaction->isCompleted();
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->queue->setQueue(Argument::any())
            ->shouldBeCalled()
            ->willReturn($this->queue);
        $this->queue->send(Argument::any())
            ->shouldBeCalled();

        $receiver = new User;
        $receiver->guid = 123;
        $sender = new User;
        $sender->guid = 123;
        $wire = new WireModel();
        $wire->setReceiver($receiver)
            ->setSender($sender)
            ->setEntity($receiver)
            ->setAmount(100001);

        $this->repo->add($wire)
            ->shouldBeCalled()
            ->willReturn(true);

        $transaction = new Transaction();
        $transaction->setUserGuid(123)
            ->setData([
                'amount' => 100001,
                'receiver_address' => '0xRECEIVER',
            ]);

        $this->confirm($wire, $transaction)
            ->shouldReturn(true);

    }

    function it_should_create_a_creditcard_wire()
    {
        $this->txManager->add(Argument::that(function ($transaction) {
            $data = $transaction->getData();
            return $transaction->getUserGuid() == 123
                && $transaction->getAmount() == -100001
                && $transaction->getWalletAddress() == '0xSPEC'
                && $transaction->getContract() == 'wire'
                && $transaction->getTx() == '0xTX'
                && $transaction->isCompleted() == false
                && $data['amount'] == 100001
                && $data['receiver_address'] == '0xRECEIVER'
                && $data['receiver_guid'] == 456
                && $data['sender_address'] == '0xSPEC'
                && $data['sender_guid'] == 123
                && $data['entity_guid'] == 456;
        }))
            ->shouldBeCalled();

        $sender = new User();
        $sender->guid = 123;

        $receiver = new User();
        $receiver->guid = 456;
        $receiver->eth_wallet = '0xRECEIVER';

        $payload = [
            'receiver' => '0xRECEIVER',
            'address' => '0xSPEC',
            'txHash' => '0xTX',
            'method' => 'onchain',
        ];

        $this->setSender($sender)
            ->setEntity($receiver)
            ->setPayload($payload)
            ->setAmount(100001)
            ->create()
            ->shouldReturn(true);
    }

    function it_should_charge_a_recurring_onchain_subscription(
        User $user,
        User $user2,
        Core\Payments\Subscriptions\Subscription $subscription
    ) {

        $this->call->getRow(Argument::any(), Argument::any())
            ->shouldBeCalled()
            ->willReturn([
                'guid' => '1234',
                'type' => 'user',
                'eth_wallet' => 'wallet',
            ]);

        $this->config->get('blockchain')
            ->shouldBeCalled()
            ->willReturn([
                'contracts' => [
                    'wire' => [
                        'wallet_pkey' => 'key',
                        'wallet_address' => 'address',
                        'contract_address' => 'contract_address'
                    ]
                ]
            ]);

        $subscription->getUser()
            ->shouldBeCalled()
            ->willReturn($user);

        $subscription->getEntity()
            ->shouldBeCalled()
            ->willReturn($user2);

        $user2->get('guid')
            ->shouldBeCalled()
            ->willReturn('5678');

        $subscription->getAmount()
            ->shouldBeCalled()
            ->willReturn(1000000000000000000);

        $subscription->getId()
            ->shouldBeCalled()
            ->willReturn('0x123');

        $this->client->encodeContractMethod('wireFromDelegate(address,address,uint256)', [
            '0x123',
            'wallet',
            Core\Util\BigNumber::_(1000000000000000000)->toHex(true)
        ])
            ->shouldBeCalled()
            ->willReturn('data hash');

        $this->token->toTokenUnit(1000000000000000000)
            ->shouldBeCalled()
            ->willReturn(1000000000000000000);

        $this->client->sendRawTransaction('key', [
            'from' => 'address',
            'to' => 'contract_address',
            'gasLimit' => Core\Util\BigNumber::_(200000)->toHex(true),
            'data' => 'data hash'
        ])
            ->shouldBeCalled()
            ->willReturn('0x123asd');

        $this->dispatcher->trigger('wire:email', 'wire', Argument::any())
            ->shouldBeCalled();

        $this->onRecurring($subscription);
    }

    function it_should_charge_a_recurring_offchain_subscription(
        User $user,
        User $user2,
        Core\Payments\Subscriptions\Subscription $subscription
    ) {

        $this->call->getRow(Argument::any(), Argument::any())
            ->shouldBeCalled()
            ->willReturn([
                'guid' => '1234',
                'type' => 'user',
                'eth_wallet' => 'wallet',
            ]);

        $subscription->getUser()
            ->shouldBeCalled()
            ->willReturn($user);

        $subscription->getEntity()
            ->shouldBeCalled()
            ->willReturn($user2);

        $user2->get('guid')
            ->shouldBeCalled()
            ->willReturn('5678');

        $subscription->getAmount()
            ->shouldBeCalled()
            ->willReturn(1000000000000000000);

        $subscription->getId()
            ->shouldBeCalled()
            ->willReturn('offchain');

        $this->cap->setUser(Argument::any())
            ->shouldBeCalled()
            ->willReturn($this->cap);

        $this->cap->setContract('wire')
            ->shouldBeCalled();

        $this->cap->isAllowed(1000000000000000000)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->redisLock->setKey('balance:')
            ->shouldBeCalled();

        $this->redisLock->setKey('balance:guid')
            ->shouldBeCalled();


        $this->redisLock->isLocked()
            ->shouldBeCalled()
            ->willReturn(false);

        $this->redisLock->setTTL(120)
            ->shouldBeCalled()
            ->willReturn($this->redisLock);

        $this->redisLock->lock()
            ->shouldBeCalled();

        $this->balance->setUser(Argument::any())
            ->shouldBeCalled()
            ->willReturn($this->balance);

        $this->balance->get()
            ->shouldBeCalled()
            ->willReturn(10 ** 18);

        $this->txRepo->add(Argument::any())
            ->shouldBeCalled()
            ->willReturn(true);

        $this->redisLock->unlock()
            ->shouldBeCalled();

        $this->queue->setQueue('WireNotification')
            ->shouldBeCalled()
            ->willReturn($this->queue);

        $this->queue->send(Argument::any())
            ->shouldBeCalled();

        $this->dispatcher->trigger('wire:email', 'wire', Argument::any())
            ->shouldBeCalled();

        $this->onRecurring($subscription);
    }

}
