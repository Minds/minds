<?php

namespace Spec\Minds\Core\Channels\Delegates\Artifacts;

use Minds\Core\Channels\Delegates\Artifacts\UserEntitiesDelegate;
use Minds\Core\Channels\Snapshots\Repository;
use Minds\Core\Data\Cassandra\Client as CassandraClient;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Data\Cassandra\Scroll as CassandraScroll;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserEntitiesDelegateSpec extends ObjectBehavior
{
    /** @var Repository */
    protected $repository;

    /** @var CassandraClient */
    protected $db;

    /** @var CassandraScroll */
    protected $scroll;

    function let(
        Repository $repository,
        CassandraClient $db,
        CassandraScroll $scroll
    )
    {
        $this->beConstructedWith($repository, $db, $scroll);
        $this->repository = $repository;
        $this->db = $db;
        $this->scroll = $scroll;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UserEntitiesDelegate::class);
    }

    function it_should_snapshot()
    {
        $this
            ->snapshot(1000)
            ->shouldReturn(true);
    }

    function it_should_restore()
    {
        foreach (UserEntitiesDelegate::TABLE_KEYS as $i => $tableKey) {
            $this->scroll->request(Argument::that(function (Custom $prepared) use ($tableKey) {
                $query = $prepared->build();

                return stripos($query['string'], 'select * from entities_by_time') === 0 &&
                    $query['values'] === [sprintf($tableKey, 1000)];
            }))
                ->shouldBeCalled()
                ->willReturn([
                    ['key' => sprintf($tableKey, 1000), 'column1' => (string) (5000 + $i), 'value' => '1000000'],
                ]);

            $this->db->request(Argument::that(function (Custom $prepared) use ($i, $tableKey) {
                $query = $prepared->build();

                return stripos($query['string'], 'delete from entities') === 0 &&
                    $query['values'] === [(string) (5000 + $i), 'deleted'];
            }), true)
                ->shouldBeCalled()
                ->willReturn(true);
        }

        $this
            ->restore(1000)
            ->shouldReturn(true);    }

    function it_should_hide()
    {
        foreach (UserEntitiesDelegate::TABLE_KEYS as $i => $tableKey) {
            $this->scroll->request(Argument::that(function (Custom $prepared) use ($tableKey) {
                $query = $prepared->build();

                return stripos($query['string'], 'select * from entities_by_time') === 0 &&
                    $query['values'] === [sprintf($tableKey, 1000)];
            }))
                ->shouldBeCalled()
                ->willReturn([
                    ['key' => sprintf($tableKey, 1000), 'column1' => (string) (5000 + $i), 'value' => '1000000'],
                ]);

            $this->db->request(Argument::that(function (Custom $prepared) use ($i, $tableKey) {
                $query = $prepared->build();

                return stripos($query['string'], 'insert into entities') === 0 &&
                    $query['values'] === [(string) (5000 + $i), 'deleted', '1'];
            }), true)
                ->shouldBeCalled()
                ->willReturn(true);
        }

        $this
            ->hide(1000)
            ->shouldReturn(true);
    }

    function it_should_delete()
    {
        foreach (UserEntitiesDelegate::TABLE_KEYS as $i => $tableKey) {
            $this->scroll->request(Argument::that(function (Custom $prepared) use ($tableKey) {
                $query = $prepared->build();

                return stripos($query['string'], 'select * from entities_by_time') === 0 &&
                    $query['values'] === [sprintf($tableKey, 1000)];
            }))
                ->shouldBeCalled()
                ->willReturn([
                    ['key' => sprintf($tableKey, 1000), 'column1' => (string) (5000 + $i), 'value' => '1000000'],
                ]);

            $this->db->request(Argument::that(function (Custom $prepared) use ($i, $tableKey) {
                $query = $prepared->build();

                return stripos($query['string'], 'delete from entities') === 0 &&
                    $query['values'] === [(string) (5000 + $i)];
            }), true)
                ->shouldBeCalled()
                ->willReturn(true);

            $this->db->request(Argument::that(function (Custom $prepared) use ($tableKey) {
                $query = $prepared->build();

                return stripos($query['string'], 'delete from entities_by_time') === 0 &&
                    $query['values'] === [sprintf($tableKey, 1000)];
            }), true)
                ->shouldBeCalled()
                ->willReturn(true);
        }

        $this
            ->delete(1000)
            ->shouldReturn(true);
    }
}
