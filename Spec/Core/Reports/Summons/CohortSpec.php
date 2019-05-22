<?php

namespace Spec\Minds\Core\Reports\Summons;

use Minds\Core\Channels\Subscriptions;
use Minds\Core\Reports\Summons\Cohort;
use Minds\Core\Reports\Summons\Pool;
use Minds\Core\Reports\Summons\Repository;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CohortSpec extends ObjectBehavior
{
    /** @var Repository */
    protected $repository;

    /** @var Pool */
    protected $pool;

    /** @var Subscriptions */
    protected $subscriptions;

    function let(
        Repository $repository,
        Pool $pool,
        Subscriptions $subscriptions
    )
    {
        $this->beConstructedWith($repository, $pool, $subscriptions, 20, 3);
        $this->repository = $repository;
        $this->pool = $pool;
        $this->subscriptions = $subscriptions;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Cohort::class);
    }

    function it_should_pick()
    {
        $this->subscriptions->setUser(Argument::that(function (User $user) {
            return $user->get('guid') == 1000;
        }))
            ->shouldBeCalled()
            ->willReturn($this->subscriptions);

        // 1st iteration

        $this->pool->getList([
            'active_threshold' => 100,
            'platform' => 'browser',
            'for' => 1000,
            'except' => [ 1001, 1002 ],
            'except_hashes' => [ '0303456', '0800888AFIP', '555KLA' ],
            'include_only' => null,
            'validated' => true,
            'size' => 20,
            'page' => 0,
            'max_pages' => 3,
        ])
            ->shouldBeCalled()
            ->willReturn([ 1010 ]);

        $this->subscriptions->hasSubscription(1010)
            ->shouldBeCalled()
            ->willReturn(false);

        // 2nd iteration

        $this->pool->getList([
            'active_threshold' => 100,
            'platform' => 'browser',
            'for' => 1000,
            'except' => [ 1001, 1002 ],
            'except_hashes' => [ '0303456', '0800888AFIP', '555KLA' ],
            'include_only' => null,
            'validated' => true,
            'size' => 20,
            'page' => 1,
            'max_pages' => 3,
        ])
            ->shouldBeCalled()
            ->willReturn([ 1011 ]);

        $this->subscriptions->hasSubscription(1011)
            ->shouldBeCalled()
            ->willReturn(false);

        // 3rd iteration

        $this->pool->getList([
            'active_threshold' => 100,
            'platform' => 'browser',
            'for' => 1000,
            'except' => [ 1001, 1002 ],
            'except_hashes' => [ '0303456', '0800888AFIP', '555KLA' ],
            'include_only' => null,
            'validated' => true,
            'size' => 20,
            'page' => 2,
            'max_pages' => 3,
        ])
            ->shouldBeCalled()
            ->willReturn([ 1012, 1013, 1014 ]);

        $this->subscriptions->hasSubscription(1012)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->subscriptions->hasSubscription(1013)
            ->shouldBeCalled()
            ->willReturn(false);

        $this->subscriptions->hasSubscription(1014)
            ->shouldNotBeCalled();

        //

        $this
            ->pick([
                'size' => 3,
                'for' => 1000,
                'except' => [ 1001, 1002 ],
                'except_hashes' => [ '0303456', '0800888AFIP', '555KLA' ],
                'include_only' => null,
                'active_threshold' => 100,
            ])
            ->shouldReturn([ 1010, 1011, 1013 ]);
    }
}
