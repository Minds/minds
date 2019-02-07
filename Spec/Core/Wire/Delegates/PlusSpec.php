<?php

namespace Spec\Minds\Core\Wire\Delegates;

use Minds\Entities\User;
use Minds\Core\Config\Config;
use Minds\Core\Wire\Wire;
use Minds\Core\Wire\Delegates\Plus;
use Minds\Core\EntitiesBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PlusSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType(Plus::class);
    }

    function it_should_make_a_user_plus_if_offchain_wire_sent(
        Config $config,
        User $receiver,
        User $sender,
        EntitiesBuilder $entitiesBuilder
    )
    {
        $this->beConstructedWith($config, $entitiesBuilder);

        $config->get('blockchain')
            ->willReturn([
                'contracts' => [
                    'wire' => [
                        'plus_guid' => 123,
                        'plus_address' => '0xaddr',
                    ]
                ]
            ]);

        $sender->getGUID()
            ->willReturn(123);

        // Cache false is important
        $entitiesBuilder->single(123, [ 'cache' => false])
            ->shouldBeCalled()
            ->willReturn($sender);

        $receiver->get('guid')
            ->willReturn(123);

        $sender->setPlusExpires(Argument::any())
            ->shouldBeCalled();
        $sender->save()
            ->shouldBeCalled();

        $wire = new Wire();
        $wire->setReceiver($receiver)
            ->setAmount("20000000000000000000")
            ->setSender($sender->getWrappedObject());

        $this->onWire($wire, 'offchain');
    }

    function it_should_not_make_a_user_plus_if_offchain_wire_is_wrong_guid_sent(
        Config $config
    )
    {
        $this->beConstructedWith($config);

        $config->get('blockchain')
            ->willReturn([
                'contracts' => [
                    'wire' => [
                        'plus_guid' => 123,
                        'plus_address' => '0xaddr',
                    ]
                ]
            ]);

        $receiver = new User;
        $receiver->guid = 123;

        $sender = new User;
        $sender->guid = 456;

        $wire = new Wire();

        $wire->setReceiver($receiver)
            ->setAmount("10000000000000000000")
            ->setSender($sender);

        $wire = $this->onWire($wire, 'offchain');
        $wire->getSender()->isPlus()->shouldBe(false);
    }

    function it_should_make_a_user_plus_if_onchain_wire_sent(
        Config $config,
        User $receiver,
        User $sender,
        EntitiesBuilder $entitiesBuilder
    )
    {
        $this->beConstructedWith($config, $entitiesBuilder);

        $config->get('blockchain')
            ->willReturn([
                'contracts' => [
                    'wire' => [
                        'plus_guid' => 123,
                        'plus_address' => '0xaddr',
                    ]
                ]
            ]);

        $entitiesBuilder->single(123, [ 'cache' => false ])
            ->shouldBeCalled()
            ->willReturn($sender);

        $receiver->get('guid')
            ->willReturn(123);

        $sender->getGUID()
            ->willReturn(123);

        $sender->setPlusExpires(Argument::any())
            ->shouldBeCalled();
        $sender->save()
            ->shouldBeCalled();

        $wire = new Wire();

        $wire->setReceiver($receiver)
            ->setAmount("20000000000000000000")
            ->setSender($sender->getWrappedObject());

        $this->onWire($wire, '0xaddr');
    }

    function it_should_not_make_a_user_plus_if_onchain_wire_wrong(
        Config $config
    )
    {
        $this->beConstructedWith($config);

        $config->get('blockchain')
            ->willReturn([
                'contracts' => [
                    'wire' => [
                        'plus_guid' => 123,
                        'plus_address' => '0xaddr',
                    ]
                ]
            ]);

        $receiver = new User;
        $receiver->guid = 123;

        $sender = new User;
        $sender->guid = 456;

        $wire = new Wire();

        $wire->setReceiver($receiver)
            ->setAmount("20000000000000000000")
            ->setSender($sender);

        $wire = $this->onWire($wire, '0xwrongaddr');
        $wire->getSender()->isPlus()->shouldBe(false);
    }

}
