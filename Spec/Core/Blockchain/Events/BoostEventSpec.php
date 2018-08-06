<?php

namespace Spec\Minds\Core\Blockchain\Events;

use Minds\Core\Blockchain\Transactions\Repository;
use Minds\Core\Blockchain\Transactions\Transaction;
use Minds\Core\Config;
use Minds\Core\Data\MongoDB\Client;
use Minds\Entities\Boost\Network;
use PhpSpec\ObjectBehavior;

class BoostEventSpec extends ObjectBehavior
{
    protected $mongo;
    protected $txRepository;
    protected $boostRepository;
    protected $config;

    function let(Client $mongo, Repository $txRepository, \Minds\Core\Boost\Repository $boostRepository, Config $config)
    {
        $this->beConstructedWith($mongo, $txRepository, $boostRepository, $config);

        $this->mongo = $mongo;
        $this->txRepository = $txRepository;
        $this->boostRepository = $boostRepository;
        $this->config = $config;

        $this->config->get('blockchain')
            ->willReturn([
                'contracts' => [
                    'boost' => [
                        'contract_address' => '0xasd'
                    ]
                ]
            ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Events\BoostEvent');
    }

    function it_should_get_the_topics()
    {
        $this->getTopics()->shouldReturn([
            '0x68170a430a4e2c3743702c7f839f5230244aca61ed306ec622a5f393f9559040',
            '0xd7ccb5dc8647fd89286a201b04b5e65fb7b5e281603e972695fd35f52bbd244b',
            '0xc43f9053be9f0ee374d3f8eb929d2e0aa990d33a7d4a51423cb715228d39ab89',
            '0x0b869ea800008714ae430dc6c4e12a2c880d50fb92937d51a4b223af34040971',
            'blockchain:fail'
        ]);
    }

    function it_should_execute_a_boost_sent_event(Transaction $transaction, Network $boost)
    {
        $transaction->getData()
            ->shouldBeCalled()
            ->willReturn([
                'handler' => 'newsfeed',
                'guid' => '1234'
            ]);

        $transaction->getTx()
            ->shouldBeCalled()
            ->willReturn('0x123123asdasd');

        $this->boostRepository->getEntity('newsfeed', '1234')
            ->shouldBeCalled()
            ->willReturn($boost);

        $boost->getState()
            ->shouldBeCalled()
            ->willReturn('pending');

        $boost->setState('created')
            ->shouldBeCalled()
            ->willReturn($boost);

        $boost->save()
            ->shouldBeCalled();

        $boost->getGuid()
            ->shouldBeCalled()
            ->willReturn('456');

        $this->event('0x68170a430a4e2c3743702c7f839f5230244aca61ed306ec622a5f393f9559040', ['address' => '0xasd'],
            $transaction);
    }

    function it_should_execute_a_boost_sent_event_but_not_find_the_boost(Transaction $transaction)
    {
        $transaction->getData()
            ->shouldBeCalled()
            ->willReturn([
                'handler' => 'newsfeed',
                'guid' => '1234'
            ]);

        $transaction->getTx()
            ->shouldBeCalled()
            ->willReturn('0x123123asdasd');

        $this->boostRepository->getEntity('newsfeed', '1234')
            ->shouldBeCalled()
            ->willReturn(null);

        $this->shouldThrow(new \Exception("No boost with hash 0x123123asdasd"))->during('event',
            [
                '0x68170a430a4e2c3743702c7f839f5230244aca61ed306ec622a5f393f9559040',
                ['address' => '0xasd'],
                $transaction
            ]);
    }

    function it_should_execute_a_boost_sent_event_but_boost_has_been_processed_already(
        Transaction $transaction,
        Network $boost
    ) {
        $transaction->getData()
            ->shouldBeCalled()
            ->willReturn([
                'handler' => 'newsfeed',
                'guid' => '1234'
            ]);

        $transaction->getTx()
            ->shouldBeCalled()
            ->willReturn('0x123123asdasd');

        $this->boostRepository->getEntity('newsfeed', '1234')
            ->shouldBeCalled()
            ->willReturn($boost);

        $boost->getState()
            ->shouldBeCalled()
            ->willReturn('created');

        $this->shouldThrow(new \Exception("Boost with hash 0x123123asdasd already processed. State: created"))->during('event',
            [
                '0x68170a430a4e2c3743702c7f839f5230244aca61ed306ec622a5f393f9559040',
                ['address' => '0xasd'],
                $transaction
            ]);
    }

    function it_shoud_execute_a_boost_fail_event(Transaction $transaction, Network $boost)
    {
        $transaction->getContract()
            ->shouldBeCalled()
            ->willReturn('boost');

        $transaction->getData()
            ->shouldBeCalled()
            ->willReturn([
                'handler' => 'newsfeed',
                'guid' => '1234'
            ]);

        $this->boostRepository->getEntity('newsfeed', '1234')
            ->shouldBeCalled()
            ->willReturn($boost);

        $transaction->getTx()
            ->shouldBeCalled()
            ->willReturn('0x123123asdasd');

        $boost->getState()
            ->shouldBeCalled()
            ->willReturn('pending');

        $transaction->setFailed(true)
            ->shouldBeCalled();

        $this->txRepository->update($transaction, ['failed'])
            ->shouldBeCalled();

        $boost->setState('failed')
            ->shouldBeCalled()
            ->willReturn($boost);

        $boost->save()
            ->shouldBeCalled();

        $this->mongo->remove("boost", ["_id" => 'boost_id']);

        $boost->getId()
            ->shouldBeCalled()
            ->willReturn('boost_id');

        $this->event('blockchain:fail', ['address' => '0xasd'], $transaction);
    }

    function it_should_execute_a_boost_fail_event_but_not_a_boost(Transaction $transaction)
    {
        $transaction->getContract()
            ->shouldBeCalled()
            ->willReturn('wire');

        $this->shouldThrow(new \Exception("Failed but not a boost"))->during('event',
            ['blockchain:fail', ['address' => '0xasd'], $transaction]);
    }

    function it_should_execute_a_boost_fail_event_but_boost_isnt_found(Transaction $transaction)
    {
        $transaction->getContract()
            ->shouldBeCalled()
            ->willReturn('boost');

        $transaction->getData()
            ->shouldBeCalled()
            ->willReturn([
                'handler' => 'newsfeed',
                'guid' => '1234'
            ]);

        $this->boostRepository->getEntity('newsfeed', '1234')
            ->shouldBeCalled()
            ->willReturn(null);

        $transaction->getTx()
            ->shouldBeCalled()
            ->willReturn('0x123123asdasd');

        $this->shouldThrow(new \Exception("No boost with hash 0x123123asdasd"))->during('event',
            ['blockchain:fail', ['address' => '0xasd'], $transaction]);
    }

    function it_should_execute_a_boost_fail_event_but_boost_already_processed(Transaction $transaction, Network $boost)
    {
        $transaction->getContract()
            ->shouldBeCalled()
            ->willReturn('boost');

        $transaction->getData()
            ->shouldBeCalled()
            ->willReturn([
                'handler' => 'newsfeed',
                'guid' => '1234'
            ]);

        $this->boostRepository->getEntity('newsfeed', '1234')
            ->shouldBeCalled()
            ->willReturn($boost);

        $transaction->getTx()
            ->shouldBeCalled()
            ->willReturn('0x123123asdasd');

        $boost->getState()
            ->shouldBeCalled()
            ->willReturn('created');

        $this->shouldThrow(new \Exception("Boost with hash 0x123123asdasd already processed. State: created"))->during('event',
            ['blockchain:fail', ['address' => '0xasd'], $transaction]);
    }

    function it_should_record_as_failed(
        Network $boost
    ) {
        $boost->getState()
            ->willReturn('pending');

        $boost->getId()
            ->willReturn('boostID');

        $boost->setState('failed')
            ->shouldBeCalled()
            ->willReturn($boost);

        $boost->save()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->boostRepository->getEntity(null, null)
            ->shouldBeCalled()
            ->willReturn($boost);


        $transaction = new Transaction();
        $transaction->setTx('testTX')
            ->setContract('boost')
            ->setFailed(false);

        $this->txRepository->update($transaction, ['failed'])
            ->shouldBeCalled();

        $this->mongo->remove('boost', ['_id' => 'boostID'])
            ->shouldBeCalled();

        $this->boostFail(['address' => '0xasd'], $transaction);
    }

    function it_should_fail_if_address_is_wrong(Transaction $transaction)
    {
        $log = [
            'address' => '0xaaa',
            'data' => [
                '0xs123',
                '0xr123',
                '0x123123'
            ]
        ];
        $this->shouldThrow(new \Exception('Event does not match address'))->during('event',
            ['0x68170a430a4e2c3743702c7f839f5230244aca61ed306ec622a5f393f9559040', $log, $transaction]);

    }
}
