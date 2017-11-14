<?php

namespace Spec\Minds\Core\Boost\Network;

use Minds\Core\Boost\Payment;
use Minds\Core\Boost\Repository;
use Minds\Core\Data\MongoDB;
use Minds\Core\Di\Di;
use Minds\Entities\Boost\Network;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReviewSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Boost\Network\Review');
    }

    function it_should_throw_an_exception_when_accepting_if_boost_isnt_set(MongoDb\Client $mongo)
    {
        $this->beConstructedWith($mongo);
        $this->shouldThrow(new \Exception('Boost wasn\'t set'))->during('accept');
    }

    function it_shouldnt_accept_a_boost_if_payment_failed(MongoDb\Client $mongo, Payment $payment, Network $boost)
    {
        Di::_()->bind('Boost\Payment', function ($di) use ($payment) {
            return $payment->getWrappedObject();
        });

        $payment->charge(Argument::any())
            ->shouldBeCalled()
            ->willReturn(false);


        $mongo->update('boost', Argument::any(), Argument::any())
            ->shouldNotBeCalled();

        $this->beConstructedWith($mongo);

        $this->setBoost($boost);
        $this->shouldThrow(new \Exception('error while accepting the boost'))->during('accept');
    }

    function it_should_accept_a_boost(MongoDb\Client $mongo, Payment $payment, Network $boost)
    {
        Di::_()->bind('Boost\Payment', function ($di) use ($payment) {
            return $payment->getWrappedObject();
        });

        $payment->charge(Argument::any())
            ->shouldBeCalled()
            ->willReturn(true);


        $mongo->update('boost', Argument::any(), Argument::any())
            ->shouldBeCalled()
            ->willReturn(true);

        $this->beConstructedWith($mongo);

        $this->setBoost($boost);
        $this->accept();
        $boost->save()->shouldHaveBeenCalled();
    }


    function it_should_throw_an_exception_when_rejecting_if_boost_isnt_set(MongoDb\Client $mongo)
    {
        $this->beConstructedWith($mongo);
        $this->shouldThrow(new \Exception('Boost wasn\'t set'))->during('reject', [1]);
    }


    function it_should_reject_a_boost(MongoDb\Client $mongo, Payment $payment, Network $boost)
    {
        Di::_()->bind('Boost\Payment', function ($di) use ($payment) {
            return $payment->getWrappedObject();
        });

        $payment->refund(Argument::any())
            ->shouldBeCalled();


        $mongo->remove('boost', Argument::any())
            ->shouldBeCalled()
            ->willReturn(true);

        $boost->getId()
            ->willReturn('1');
        $owner = new \stdClass();
        $owner->guid = '123';
        $boost->getOwner()
            ->willReturn($owner);
        $boost->setState(Argument::containingString('rejected'))
            ->willReturn($boost);
        $boost->setRejectionReason(Argument::any())
            ->shouldBeCalled()
            ->willReturn($boost);
        $boost->getRejectionReason()
            ->willReturn(3);
        $boost->save()
            ->shouldBeCalled()
            ->willReturn();

        $entity = new \stdClass();
        $entity->title = 'title';
        $boost->getEntity()
            ->willReturn($entity);

        $this->beConstructedWith($mongo);

        $this->setBoost($boost);
        $this->reject(3);
        $boost->save()->shouldHaveBeenCalled();
    }

    function it_should_throw_an_exception_when_revoking_if_boost_isnt_set(MongoDb\Client $mongo)
    {
        $this->beConstructedWith($mongo);
        $this->shouldThrow(new \Exception('Boost wasn\'t set'))->during('revoke');
    }

    function it_should_revoke_a_boost(MongoDb\Client $mongo, Network $boost)
    {
        $mongo->remove('boost', Argument::any())
            ->shouldBeCalled()
            ->willReturn(true);

        $boost->getId()
            ->willReturn('1');
        $owner = new \stdClass();
        $owner->guid = '123';
        $boost->getOwner()
            ->willReturn($owner);

        $boost->setState(Argument::containingString('revoked'))
            ->shouldBeCalled()
            ->willReturn($boost);

        $boost->save()
            ->shouldBeCalled()
            ->willReturn();

        $entity = new \stdClass();
        $entity->title = 'title';
        $boost->getEntity()
            ->willReturn($entity);

        $this->beConstructedWith($mongo);

        $this->setBoost($boost);
        $this->revoke();
        $boost->save()->shouldHaveBeenCalled();
    }

    function it_should_get_the_boost_outbox(MongoDB\Client $mongo, Repository $repository)
    {
        $boosts = [
            [
                'guid' => '789'
            ],
            [
                'guid' => '102'
            ]
        ];

        Di::_()->bind('Boost\Repository', function ($di) use ($repository) {
            return $repository->getWrappedObject();
        });

        $repository->getAll('newsfeed', Argument::is([
            'owner_guid' => '123',
            'limit' => 12,
            'offset' => '456',
            'order' => 'DESC'
        ]))
            ->shouldBeCalled()
            ->willReturn($boosts);


        $this->beConstructedWith($mongo);
        $this->setType('newsfeed');

        $this->getOutbox('123', 12, '456')->shouldReturn($boosts);
    }
}
