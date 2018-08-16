<?php

namespace Spec\Minds\Core\Blockchain\Purchase\Delegates;

use Minds\Core\Blockchain\Purchase\Delegates\NewPurchaseNotification;
use Minds\Core\Blockchain\Purchase\Purchase;
use Minds\Core\Config;
use Minds\Core\Events\EventsDispatcher;
use PhpSpec\ObjectBehavior;

class NewPurchaseNotificationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(NewPurchaseNotification::class);
    }

    function it_should_notify(Config $config, EventsDispatcher $dispatcher, Purchase $purchase)
    {
        $this->beConstructedWith($config, $dispatcher);

        $purchase->getRequestedAmount()
            ->shouldBeCalled()
            ->willReturn(10000000000000000000);

        $purchase->getUserGuid()
            ->shouldBeCalled()
            ->willReturn('1234');

        $dispatcher->trigger('notification', 'all', [
            'to' => ['1234'],
            'from' => 100000000000000519,
            'notification_view' => 'custom_message',
            'params' => [
                'message' => 'Your purchase of 10 Tokens is being processed.'
            ],
            'message' => 'Your purchase of 10 Tokens is being processed.'
        ])
            ->shouldBeCalled();

        $this->notify($purchase);
    }
}
