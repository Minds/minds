<?php

namespace Spec\Minds\Core\Monetization;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Di\Di;
use Minds\Core\Config\Config;
use Minds\Core\Payments\Stripe;
use Minds\Core\Payments\Transfers\Transfer;
use Minds\Core\Monetization\Ads;
use Minds\Core\Monetization\Manager;
use Minds\Core\Monetization\Merchants;

class PayoutsSpec extends ObjectBehavior
{
    private $_ads;
    private $_manager;
    private $_merchants;
    private $_stripe;
    private $_getLastPayout;

    static private $_payoutsMock;

    function let(
        Config $config,
        Stripe\Stripe $stripe,
        Ads $ads,
        Manager $manager,
        Merchants $merchants
    )
    {
        $this->beConstructedWith($config, $stripe);
        $this->_stripe = $stripe;

        Di::_()->bind('Monetization\Ads', function($di) use ($ads) {
            return $ads->getWrappedObject();
        });
        $this->_ads = $ads;

        Di::_()->bind('Monetization\Manager', function($di) use ($manager) {
            return $manager->getWrappedObject();
        });
        $this->_manager = $manager;

        Di::_()->bind('Monetization\Merchants', function($di) use ($merchants) {
            return $merchants->getWrappedObject();
        });
        $this->_merchants = $merchants;

        // Config
        $config->get('payouts')->willReturn([
            'initialDate' => '2015-11-01',
            'retentionDays' => 2,
            'minimumAmount' => 1,
            'userPercentage' => 0.5
        ]);

        // $this->getLastPayout()
        $this::$_payoutsMock = (object) [
            'inprogress' => [
                'user_guid' => 10,
                'guid' => '2',
                'status' => 'inprogress',
                'start' => strtotime('2016-01-01'),
                'end' => strtotime('2016-02-01'),
                'amount' => 199,
            ],
            'paid' => [
                'user_guid' => 10,
                'guid' => '2',
                'status' => 'paid',
                'start' => strtotime('2016-01-01'),
                'end' => strtotime('2016-02-01'),
                'amount' => 199,
            ],
        ];
        $this->_getLastPayout = $manager->get([
            'type' => 'credit',
            'user_guid' => 10,
            'limit' => 1,
            'order' => 'DESC'
        ])->willReturn([
            $this::$_payoutsMock->inprogress
        ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Monetization\Payouts');
    }

    function it_should_set_and_get_an_user_guid_from_literal()
    {
        $this->setUser(10);
        $this->getUser()->shouldReturn(10);
    }

    function it_should_set_and_get_an_user_guid_from_object()
    {
        $this->setUser((object) [ 'guid' => 10 ]);
        $this->getUser()->shouldReturn(10);
    }

    function it_should_get_last_payout_for_an_user()
    {
        $this->_getLastPayout->shouldBeCalled();

        $this->setUser(10);
        $this->getLastPayout()->shouldReturn($this::$_payoutsMock->inprogress);
    }

    function it_should_get_last_payout_inprogress_status_for_an_user()
    {
        $this->_getLastPayout->shouldBeCalled();

        $this->setUser(10);
        $this->getPayoutStatus()->shouldReturn('inprogress');
    }

    function it_should_get_last_payout_available_status_for_an_user()
    {
        $this->_manager->get([
            'type' => 'credit',
            'user_guid' => 10,
            'limit' => 1,
            'order' => 'DESC'
        ])->willReturn([
            $this::$_payoutsMock->paid
        ])->shouldBeCalled();

        $this->setUser(10);
        $this->getPayoutStatus()->shouldReturn('available');
    }

    function it_should_check_if_can_request_payout()
    {
        $this->_manager->get([
            'type' => 'credit',
            'user_guid' => 10,
            'limit' => 1,
            'order' => 'DESC'
        ])->willReturn([
            $this::$_payoutsMock->paid
        ])->shouldBeCalled();

        $this->_ads->setUser(10)->shouldBeCalled();
        $this->_ads->getTotalRevenue(Argument::type('\\DateTime'), Argument::type('\\DateTime'))
            ->willReturn(5000)
            ->shouldBeCalled();

        $this->setUser(10);
        $this->canRequestPayout()->shouldReturn(true);
    }

    function it_should_check_if_cant_request_payout()
    {
        $this->setUser(10);
        $this->canRequestPayout()->shouldReturn(false);
    }

    function it_should_check_if_cant_request_payout_due_to_revenue()
    {
        $this->_manager->get([
            'type' => 'credit',
            'user_guid' => 10,
            'limit' => 1,
            'order' => 'DESC'
        ])->willReturn([
            $this::$_payoutsMock->paid
        ])->shouldBeCalled();

        $this->_ads->setUser(10)->shouldBeCalled();
        $this->_ads->getTotalRevenue(Argument::type('\\DateTime'), Argument::type('\\DateTime'))
            ->willReturn(0)
            ->shouldBeCalled();

        $this->setUser(10);
        $this->canRequestPayout()->shouldReturn(false);
    }

    function it_should_not_request_payout_if_in_progress()
    {
        $this->setUser(10);
        $this->shouldThrow('\\Exception')->duringRequestPayout();
    }

    function it_should_not_request_payout_due_to_revenue()
    {
        $this->_manager->get([
            'type' => 'credit',
            'user_guid' => 10,
            'limit' => 1,
            'order' => 'DESC'
        ])->willReturn([
            $this::$_payoutsMock->paid
        ])->shouldBeCalled();

        $this->_ads->setUser(10)->shouldBeCalled();
        $this->_ads->getTotalRevenue(Argument::type('\\DateTime'), Argument::type('\\DateTime'))
            ->willReturn(0)
            ->shouldBeCalled();

        $this->setUser(10);
        $this->shouldThrow('\\Exception')->duringRequestPayout();
    }

    function it_should_request_payout()
    {
        $this->_manager->get([
            'type' => 'credit',
            'user_guid' => 10,
            'limit' => 1,
            'order' => 'DESC'
        ])->willReturn([
            $this::$_payoutsMock->paid
        ])->shouldBeCalled();

        $this->_manager->insert(Argument::type('array'))->shouldBeCalled();

        $this->_ads->setUser(10)->shouldBeCalled();
        $this->_ads->getTotalRevenue(Argument::type('\\DateTime'), Argument::type('\\DateTime'))
            ->willReturn(5000)
            ->shouldBeCalled();

        $this->setUser(10);
        $this->shouldNotThrow('\\Exception')->duringRequestPayout();
    }

    function it_should_payout()
    {
        $this->_manager->resolve('1')
            ->willReturn($this::$_payoutsMock->inprogress)
            ->shouldBeCalled();

        $this->_merchants->setUser('10')
            ->shouldBeCalled();

        $this->_merchants->getId()
            ->willReturn('phpspec_10')
            ->shouldBeCalled();

        $this->_stripe->transfer(Argument::type(Transfer::class))
            ->will(function ($args, $mock) {
                $args[0]->setId('phpspec_t10_01');
                return $args[0];
            })
            ->shouldBeCalled();

        $this->_manager->update('1', Argument::type('array'), Argument::type('array'))
            ->willReturn(true)
            ->shouldBeCalled();

        $this->setUser(10);
        $this->shouldNotThrow('\\Exception')->duringPayout('1');
    }

    function it_should_cancel_payout()
    {
        $this->_manager->resolve('1')
            ->willReturn($this::$_payoutsMock->inprogress)
            ->shouldBeCalled();

        $this->_manager->update('1', Argument::type('array'), Argument::type('array'))
            ->shouldBeCalled();

        $this->setUser(10);
        $this->shouldNotThrow('\\Exception')->duringCancel('1');
    }

    function it_should_calc_user_amount()
    {
        $this->calcUserAmount(130.50)->shouldReturn(65.25);
    }

    function it_should_calc_user_amount_for_zero()
    {
        $this->calcUserAmount(0)->shouldReturn(0.0);
    }

    function it_should_build_retention_datestring()
    {
        $this->getRetentionDateString()->shouldReturn('2 days ago');
    }
}
