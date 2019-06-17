<?php

namespace Spec\Minds\Core\Boost\Network;

use Minds\Core\Boost\Payment;
use Minds\Core\Boost\Repository;
use Minds\Core\Boost\Network\Manager;
use Minds\Core\Boost\Network\Boost;
use Minds\Core\Di\Di;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Minds\Core\Boost\Delegates\OnchainBadgeDelegate;
use Minds\Entities\User;

class ReviewSpec extends ObjectBehavior
{
    private $manager;
    private $onchainBadge;

    function let(Manager $manager, OnchainBadgeDelegate $onchainBadge)
    {
        $this->beConstructedWith($manager, null, $onchainBadge);
        $this->manager = $manager;
        $this->onchainBadge = $onchainBadge;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Boost\Network\Review');
    }

    function it_should_throw_an_exception_when_accepting_if_boost_isnt_set()
    {
        $this->shouldThrow(new \Exception('Boost wasn\'t set'))
            ->during('accept');
    }

    function it_shouldnt_accept_a_boost_if_payment_failed(Payment $payment, Boost $boost)
    {
        Di::_()->bind('Boost\Payment', function ($di) use ($payment) {
            return $payment->getWrappedObject();
        });

        $payment->charge(Argument::any())
            ->shouldBeCalled()
            ->willReturn(false);

        $this->manager->update($boost)
            ->shouldNotBeCalled();

        $this->setBoost($boost);
        $this->shouldThrow(new \Exception('error while accepting the boost'))
            ->during('accept');
    }

    function it_should_accept_a_boost(Payment $payment, Boost $boost, User $user)
    {
        $boost->setOwner($user);
        
        Di::_()->bind('Boost\Payment', function ($di) use ($payment) {
            return $payment->getWrappedObject();
        });

        $payment->charge(Argument::any())
            ->shouldBeCalled()
            ->willReturn(true);

        $this->manager->update($boost)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->setBoost($boost);
        $this->accept();
    }


    function it_should_accept_an_onchain_boost_and_call_onchain_badge_delegate(Payment $payment, Boost $boost)
    {
        $boost->isOnChain()
            ->shouldBeCalled()
            ->willReturn(true);

        $boost->setReviewedTimestamp(Argument::approximate(time() * 1000, -4))
            ->shouldBeCalled()
            ->willReturn(true);
            
        $this->setBoost($boost);
  
        Di::_()->bind('Boost\Payment', function ($di) use ($payment) {
            return $payment->getWrappedObject();
        });
        
        $payment->charge(Argument::any())
            ->shouldBeCalled()
            ->willReturn(true);
        
        $this->onchainBadge->dispatch($boost)
            ->shouldBeCalled()
            ->willReturn(true);
            
        $this->manager->update($boost)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->accept();
    }

    function it_should_accept_an_offchain_boost_and_not_call_onchain_badge_delegate(Payment $payment, Boost $boost, User $user)
    {
        $boost->isOnChain()
            ->shouldBeCalled()
            ->willReturn(false);

        $boost->setReviewedTimestamp(Argument::approximate(time() * 1000, -4))
            ->shouldBeCalled()
            ->willReturn(true);
            
        $this->setBoost($boost);
  
        Di::_()->bind('Boost\Payment', function ($di) use ($payment) {
            return $payment->getWrappedObject();
        });
        
        $payment->charge(Argument::any())
            ->shouldBeCalled()
            ->willReturn(true);
        
        $this->onchainBadge->dispatch($boost)
            ->shouldNotBeCalled()
            ->willReturn(true);
            
        $this->manager->update($boost)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->accept();
    }

    function it_should_throw_an_exception_when_rejecting_if_boost_isnt_set()
    {
        $this->shouldThrow(new \Exception('Boost wasn\'t set'))
            ->during('reject', [1]);
    }


    function it_should_reject_a_boost(Payment $payment, Boost $boost)
    {
        Di::_()->bind('Boost\Payment', function ($di) use ($payment) {
            return $payment->getWrappedObject();
        });

        $payment->refund(Argument::any())
            ->shouldBeCalled();

        $this->manager->update($boost)
            ->shouldBeCalled()
            ->willReturn(true);

        $owner = new \stdClass();
        $owner->guid = '123';
        $boost->getOwner()
            ->willReturn($owner);
        $boost->setReviewedTimestamp(Argument::any())
            ->shouldBeCalled();
        $boost->setRejectedTimestamp(Argument::any())
            ->shouldBeCalled();
        $boost->setRejectedReason(3)
            ->shouldBeCalled()
            ->willReturn($boost);
        $boost->getRejectedReason()
            ->willReturn(3);

        $entity = new \stdClass();
        $entity->title = 'title';
        $boost->getEntity()
            ->willReturn($entity);

        $this->setBoost($boost);
        $this->reject(3);
    }

    function it_should_throw_an_exception_when_revoking_if_boost_isnt_set()
    {
        $this->shouldThrow(new \Exception('Boost wasn\'t set'))
            ->during('revoke');
    }

    function it_should_revoke_a_boost(Boost $boost)
    {
        $this->manager->update($boost)
            ->shouldBeCalled()
            ->willReturn(true);

        $owner = new \stdClass();
        $owner->guid = '123';
        $boost->getOwner()
            ->willReturn($owner);

        $boost->setRevokedTimestamp(Argument::approximate(time() * 1000, -4))
            ->shouldBeCalled()
            ->willReturn($boost);

        $entity = new \stdClass();
        $entity->title = 'title';
        $boost->getEntity()
            ->willReturn($entity);

        $this->setBoost($boost);
        $this->revoke();
    }

    function it_should_get_the_boost_outbox(Repository $repository)
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

        $this->setType('newsfeed');

        $this->getOutbox('123', 12, '456')->shouldReturn($boosts);
    }
}
