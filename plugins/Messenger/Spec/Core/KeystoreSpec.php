<?php

namespace Messenger\Spec\Minds\Plugin\Messenger\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Entities\User;

class KeystoreSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Plugin\Messenger\Core\Keystore');
    }

    public function it_should_return_a_private_key(User $user)
    {
        $this->setUser($user);
        $user->get('plugin:user_setting:gatherings:privatekey')->willReturn('got-private-key');
        $this->getPrivateKey()->shouldReturn('got-private-key');
    }

    public function it_should_return_a_public_key(User $user)
    {
        $this->setUser($user);
        $user->get('plugin:user_setting:gatherings:publickey')->willReturn('got-public-key');
        $this->getPublicKey()->shouldReturn('got-public-key');
    }

    public function it_should_set_a_public_key(User $user)
    {
        $this->setUser($user);
        $user->set('plugin:user_setting:gatherings:publickey', 'here-is-a-public-key')->shouldBeCalled();
        $this->setPublicKey('here-is-a-public-key')->shouldReturn($this);
    }

    public function it_should_set_a_private_key(User $user)
    {
        $this->setUser($user);
        $user->set('plugin:user_setting:gatherings:privatekey', 'here-is-a-private-key')->shouldBeCalled();
        $this->setPrivateKey('here-is-a-private-key')->shouldReturn($this);
    }
}
