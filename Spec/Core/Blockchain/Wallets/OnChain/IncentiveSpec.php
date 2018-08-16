<?php

namespace Spec\Minds\Core\Blockchain\Wallets\OnChain;

use Minds\Core\Blockchain\Services\Ethereum;
use Minds\Core\Config;
use Minds\Core\Events\EventsDispatcher;
use Minds\Core\Util\BigNumber;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IncentiveSpec extends ObjectBehavior
{
    protected $config;
    protected $eth;
    protected $dispatcher;

    function let(
        Config $config,
        Ethereum $eth,
        EventsDispatcher $dispatcher
    ) {
        $this->config = $config;
        $this->eth = $eth;
        $this->dispatcher = $dispatcher;

        $this->beConstructedWith($config, $eth, $dispatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Wallets\OnChain\Incentive');
    }

    function it_should_send(User $user)
    {
        $user->getEthWallet()
            ->shouldBeCalled()
            ->willReturn('0xTEST');

        $user->getEthIncentive()
            ->shouldBeCalled()
            ->willReturn('');

        $this->config->get('blockchain')
            ->shouldBeCalled()
            ->willReturn([
                'testnet' => true,
                'incentive_wallet_pkey' => '0xWALLETPKEY',
                'incentive_wallet_address' => '0xWALLET',

            ]);

        $this->eth->sendRawTransaction('0xWALLETPKEY', [
            'from' => '0xWALLET',
            'to' => '0xTEST',
            'gasLimit' => BigNumber::_(300000)->toHex(true),
            'value' => BigNumber::toPlain('0.002', 18)->toHex(true),
        ])
            ->shouldBeCalled()
            ->willReturn('0xTX');

        $user->setEthIncentive('0xTX')
            ->shouldBeCalled()
            ->willReturn($user);

        $user->save()
            ->shouldBeCalled()
            ->willReturn(true);

        $user->get('guid')
            ->shouldBeCalled()
            ->willReturn('123');

        $address = substr('0xTEST', 0, 5) . '...' . substr('0xTEST', -5);

        $message = 'Hey! We\'ve sent you 0.002 ETH to your wallet ' . $address . '. It might take some minutes to arrive.';

        $this->dispatcher->trigger('notification', 'onchain:incentive', [
            'to' => ['123'],
            'from' => 100000000000000519,
            'notification_view' => 'custom_message',
            'params' => ['message' => $message],
            'message' => $message,
        ])
            ->shouldBeCalled();

        $this
            ->setUser($user)
            ->send(['notification' => true])
            ->shouldReturn(true);
    }


    function it_should_return_false_during_send_if_not_on_testnet(User $user)
    {
        $user->getEthWallet()
            ->shouldBeCalled()
            ->willReturn('0xTEST');

        $user->getEthIncentive()
            ->shouldBeCalled()
            ->willReturn('');

        $this->config->get('blockchain')
            ->shouldBeCalled()
            ->willReturn([
                'testnet' => false,
                'incentive_wallet_pkey' => '0xWALLETPKEY',
                'incentive_wallet_address' => '0xWALLET',

            ]);

        $this->eth->sendRawTransaction(Argument::any())
            ->shouldNotBeCalled();

        $user->setEthIncentive(Argument::any())
            ->shouldNotBeCalled();

        $user->save()
            ->shouldNotBeCalled();

        $this
            ->setUser($user)
            ->send(['notification' => false])
            ->shouldReturn(false);
    }

    function it_should_return_false_during_send_if_no_address(User $user)
    {
        $user->getEthWallet()
            ->shouldBeCalled()
            ->willReturn('');

        $this->eth->sendRawTransaction(Argument::any())
            ->shouldNotBeCalled();

        $user->setEthIncentive(Argument::any())
            ->shouldNotBeCalled();

        $user->save()
            ->shouldNotBeCalled();

        $this
            ->setUser($user)
            ->send(['notification' => false])
            ->shouldReturn(false);
    }

    function it_should_return_false_during_send_if_already_sent(User $user)
    {
        $user->getEthWallet()
            ->willReturn('0xTEST');

        $user->getEthIncentive()
            ->shouldBeCalled()
            ->willReturn('0xTX');

        $this->eth->sendRawTransaction(Argument::any())
            ->shouldNotBeCalled();

        $user->setEthIncentive(Argument::any())
            ->shouldNotBeCalled();

        $user->save()
            ->shouldNotBeCalled();

        $this
            ->setUser($user)
            ->send(['notification' => false])
            ->shouldReturn(false);
    }
}
