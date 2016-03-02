<?php

namespace Spec\Minds\Core\Payments\Braintree;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Braintree_WebhookTesting;
use Braintree_WebhookNotification;

use Minds\Core\Payments\Subscriptions;
use Minds\Core\Payments\Hooks;
use Minds\Core\Payments\HookInterface;
use Minds\Core\Payments\Subscriptions\SubscriptionsHookInterface;
use Minds\Core\Payments\Braintree\Braintree as BT;

class WebhooksSpec extends ObjectBehavior
{
    private $hooks;

    function it_is_initializable(BT $bt)
    {
        $this->beConstructedWith(false, $bt);
        $this->shouldHaveType('Minds\Core\Payments\Braintree\Webhooks');
    }

    /*function it_should_call_a_charge_hook(BT $bt)
    {
        $this->beConstructedWith(false, $bt);
        $mock = Braintree_WebhookTesting::sampleNotification(
            Braintree_WebhookNotification::SUBSCRIPTION_CHARGED_SUCCESSFULLY,
            'charge-001'
        );

        $this
          ->setSignature($mock['bt_signature'])
          ->setPayload($mock['bt_payload'])
          ->run();

    }*/

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
