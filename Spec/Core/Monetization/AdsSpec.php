<?php

namespace Spec\Minds\Core\Monetization;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Di\Di;
use Minds\Core\Monetization;

class AdsSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Monetization\Ads');
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

    function it_should_proxy_total_revenue_from_service(Monetization\Services\Adsense $service)
    {
        $this->beConstructedWith($service);

        $service->getTotalRevenue(10, new \DateTime('2017-01-01'), new \DateTime('2017-02-01'))
            ->shouldBeCalled()
            ->willReturn(123.45);

        $this->setUser(10);
        $this->getTotalRevenue(new \DateTime('2017-01-01'), new \DateTime('2017-02-01'))->shouldReturn(123.45);
    }

    function it_should_get_revenue_list_from_service_and_format_it(
        Monetization\Services\Adsense $service,
        Monetization\Payouts $payouts
    )
    {
        $this->beConstructedWith($service);
        Di::_()->bind('Monetization\Payouts', function($di) use ($payouts) {
            return $payouts->getWrappedObject();
        });

        $service->getRevenuePerPage(10, new \DateTime('2017-01-01'), new \DateTime('2017-02-01'), '', 50)
            ->shouldBeCalled()
            ->willReturn([[
                [ 'url' => '/blog/view/1', 'title' => 'Blog 1', 'views' => 100, 'revenue' => 1.50 ],
                [ 'url' => '/blog/view/2', 'title' => 'Blog 2', 'views' => 50, 'revenue' => 1.00 ],
            ], '']);
        
        $payouts->calcUserAmount(Argument::cetera())->will(function ($args, $payouts) {
            return $args[0] * 0.5;
        });

        $this->setUser(10);
        $this->getList(new \DateTime('2017-01-01'), new \DateTime('2017-02-01'), '', 50)->shouldReturn([
            [
                'entity' => [ 'guid' => '1', 'title' => 'Blog 1' ],
                'views' => 100, 'revenue' => 0.75, 'rpm' => 7.5,
            ],
            [
                'entity' => [ 'guid' => '2', 'title' => 'Blog 2' ],
                'views' => 50, 'revenue' => 0.50, 'rpm' => 10.0,
            ],
        ]);
    }

    function it_should_get_the_last_offset(
        Monetization\Services\Adsense $service,
        Monetization\Payouts $payouts
    )
    {
        $this->beConstructedWith($service);
        Di::_()->bind('Monetization\Payouts', function($di) use ($payouts) {
            return $payouts;
        });

        $service->getRevenuePerPage(10, new \DateTime('2017-01-01'), new \DateTime('2017-02-01'), '', 50)
            ->shouldBeCalled()
            ->willReturn([[], '2']);
        
        $this->setUser(10);
        $this->getList(new \DateTime('2017-01-01'), new \DateTime('2017-02-01'), '', 50);
        
        $this->getLastOffset()->shouldReturn('2');
    }
}
