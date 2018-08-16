<?php

namespace Spec\Minds\Core\Blockchain;

use Minds\Core\Blockchain\Preregistrations;
use Minds\Core\Data\Cassandra\Client;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PreregistrationsSpec extends ObjectBehavior
{
    /** @var Client */
    private $cql;

    function let(Client $cql)
    {
        $this->beConstructedWith($cql);

        $this->cql = $cql;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Preregistrations::class);
    }

    function it_should_register(User $user)
    {
        $user->get('guid')
            ->shouldBeCalled()
            ->willReturn('123');

        $this->cql->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] = "INSERT INTO entities_by_time (key, column1, value) VALUES (?, ?, ?)"
                && $built['values'][0] === 'blockchain:preregistrations'
                && $built['values'][1] === '123';
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->register($user)->shouldReturn(true);
    }

    function it_should_fail_to_register_if_no_user(User $user)
    {
        $this->shouldThrow(new \Exception('User is required'))->during('register', [$user]);
    }

    function it_should_check_if_a_user_is_registered(User $user)
    {
        $user->get('guid')
            ->shouldBeCalled()
            ->willReturn('123');

        $this->cql->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] = "SELECT COUNT(*) from entities_by_time WHERE key = ? AND column1 = ?"
                && $built['values'][0] === 'blockchain:preregistrations'
                && $built['values'][1] === '123';
        }))
            ->shouldBeCalled()
            ->willReturn([
                [
                    'count' => 3
                ]
            ]);

        $this->isRegistered($user)->shouldReturn(true);
    }

    function it_should_fail_to_check_if_a_user_is_registered_if_no_user(User $user)
    {
        $this->shouldThrow(new \Exception('User is required'))->during('isRegistered', [$user]);
    }

    function it_should_fail_to_check_if_a_user_is_registered_if_theres_a_cassandra_error(User $user)
    {
        $user->get('guid')
            ->shouldBeCalled()
            ->willReturn('123');

        $this->cql->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] = "SELECT COUNT(*) from entities_by_time WHERE key = ? AND column1 = ?"
                && $built['values'][0] === 'blockchain:preregistrations'
                && $built['values'][1] === '123';
        }))
            ->shouldBeCalled()
            ->willReturn(false);

        $this->shouldThrow(new \Exception('Error getting count'))->during('isRegistered', [$user]);
    }
}
