<?php

namespace Spec\Minds\Core\Wire;

use Minds\Core\Blockchain\Pending;
use Minds\Core\Blockchain\Transactions\Manager as BlockchainManager;
use Minds\Core\Blockchain\Transactions\Repository as BlockchainRepo;
use Minds\Core\Blockchain\Transactions\Transaction;
use Minds\Core\Data\cache\Redis;
use Minds\Core\Di\Di;
use Minds\Core\Payments\Manager as PaymentsManager;
use Minds\Core\Wire\Exceptions\WalletNotSetupException;
use Minds\Core\Wire\Repository;
use Minds\Core\Wire\Subscriptions\Manager;
use Minds\Core\Wire\Wire as WireModel;
use Minds\Entities\User;
use Minds\Core\Queue\SQS\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Wire\Manager');
    }

    function it_should_create_an_onchain_wire(
        Repository $repo,
        BlockchainManager $txManager,
        Client $queue
    )
    {
        $this->beConstructedWith(null, $repo, null, $txManager, null, null, null, $queue);

        $txManager->add(Argument::that(function($transaction) {
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

        $queue->setQueue(Argument::any())
            ->shouldBeCalled()
            ->willReturn($queue);
        $queue->send(Argument::any())
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

    function it_should_confirm_a_wire_from_the_blockchain(
        Redis $cache,
        Repository $repo,
        BlockchainManager $txManager,
        BlockchainRepo $txRepo,
        Client $queue
    )
    {
        $this->beConstructedWith($cache, $repo, null, $txManager, $txRepo, null, null, $queue);

        $txRepo->add(Argument::that(function($transaction) {
                return $transaction->getUserGuid() == 123
                    && $transaction->getWalletAddress() == '0xRECEIVER'
                    && $transaction->getAmount() == 100001
                    && $transaction->isCompleted();
            }))
            ->shouldBeCalled()
            ->willReturn(true);
        
        $queue->setQueue(Argument::any())
            ->shouldBeCalled()
            ->willReturn($queue);
        $queue->send(Argument::any())
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

        $repo->add($wire)
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

    function it_should_create_a_creditcard_wire(
        BlockchainManager $txManager,
        Client $queue
    )
    {
        $this->beConstructedWith(null, null, null, $txManager, null, null, null, $queue);

        $txManager->add(Argument::that(function($transaction) {
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

        $queue->setQueue(Argument::any())
            ->shouldBeCalled()
            ->willReturn($queue);
        $queue->send(Argument::any())
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
    
}
