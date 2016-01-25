<?php

namespace Spec\Minds\Core\Payments\Braintree;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Braintree_WebhookTesting;
use Braintree_WebhookNotification;

use Minds\Core\Payments\Subscriptions;
use Minds\Core\Payments\Hooks;
use Minds\Core\Payments\HookInterface;
use Spec\Minds\Core\Payments\MockHook;
use Minds\Core\Payments\Subscriptions\SubscriptionsHookInterface;

class WebhooksSpec extends ObjectBehavior
{
    private $hooks;

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Payments\Braintree\Webhooks');
    }

    function it_should_call_a_charge_hook()
    {

        $mock = Braintree_WebhookTesting::sampleNotification(
            Braintree_WebhookNotification::SUBSCRIPTION_CHARGED_SUCCESSFULLY,
            'charge-001'
        );

        $this
          ->setSignature($mock['bt_signature'])
          ->setPayload($mock['bt_payload'])
          ->run();


    }

    /*function it_should_call_an_active_hook(Hooks $hooks, SubscriptionsHookInterface $hook)
    {

        $hooks->__call('onActive', [])->willReturn(true);
        $this->beConstructedWith($hooks);

        $mock = Braintree_WebhookTesting::sampleNotification(
            Braintree_WebhookNotification::SUBSCRIPTION_WENT_ACTIVE,
            'active-001'
        );

        $this->setSignature($mock['bt_signature'])
          ->setPayload($mock['bt_payload'])
          ->run();

    }*/


}
