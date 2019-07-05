<?php

namespace Spec\Minds\Core\Channels\Snapshots;

use Minds\Core\Channels\Snapshots\Repository;
use Minds\Core\Channels\Snapshots\Snapshot;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    /** @var Client */
    protected $db;

    function let(
        Client $db
    )
    {
        $this->beConstructedWith($db);
        $this->db = $db;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_get_all()
    {
        $this->db->request(Argument::that(function (Custom $prepared) {
            $statement = $prepared->build();

            // Varint conversion
            $statement['values'][0] = $statement['values'][0]->toInt();

            return stripos($statement['string'], 'select * from user_snapshots') !== false &&
                $statement['values'] === [1000];
        }))
            ->shouldBeCalled()
            ->willReturn([
                ['user_guid' => 1000, 'type' => 'test', 'key' => '1', 'json_data' => '{}'],
                ['user_guid' => 1000, 'type' => 'test', 'key' => '2', 'json_data' => '{}'],
            ]);

        $this
            ->getList([
                'user_guid' => 1000,
            ])
            ->shouldBeATraversableOf(2, Snapshot::class);
    }

    function it_should_get_list()
    {
        $this->db->request(Argument::that(function (Custom $prepared) {
            $statement = $prepared->build();

            return stripos($statement['string'], 'select * from user_snapshots') !== false;
        }))
            ->shouldBeCalled()
            ->willReturn([
                ['user_guid' => 1000, 'type' => 'test', 'key' => '1', 'json_data' => '{}'],
                ['user_guid' => 1000, 'type' => 'test', 'key' => '2', 'json_data' => '{}'],
            ]);

        $this
            ->getList([
                'user_guid' => 1000,
                'type' => 'test',
            ])
            ->shouldBeATraversableOf(2, Snapshot::class);
    }

    function it_should_add(Snapshot $snapshot)
    {
        $snapshot->getUserGuid()
            ->shouldBeCalled()
            ->willReturn(1000);

        $snapshot->getType()
            ->shouldBeCalled()
            ->willReturn('test');

        $snapshot->getKey()
            ->shouldBeCalled()
            ->willReturn('1');

        $snapshot->getJsonData(true)
            ->shouldBeCalled()
            ->willReturn('{}');

        $this->db->request(Argument::that(function (Custom $prepared) {
            $statement = $prepared->build();

            // Varint conversion
            $statement['values'][0] = $statement['values'][0]->toInt();

            return stripos($statement['string'], 'insert into user_snapshots') !== false &&
                $statement['values'] === [1000, 'test', '1', '{}'];
        }), true)
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->add($snapshot)
            ->shouldReturn(true);
    }

    function it_should_delete_all()
    {
        $this->db->request(Argument::that(function (Custom $prepared) {
            $statement = $prepared->build();

            // Varint conversion
            $statement['values'][0] = $statement['values'][0]->toInt();

            return stripos($statement['string'], 'delete from user_snapshots') !== false &&
                $statement['values'] === [1000];
        }), true)
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->deleteAll(1000)
            ->shouldReturn(true);
    }

    function it_should_delete_all_of_type()
    {
        $this->db->request(Argument::that(function (Custom $prepared) {
            $statement = $prepared->build();

            // Varint conversion
            $statement['values'][0] = $statement['values'][0]->toInt();

            return stripos($statement['string'], 'delete from user_snapshots') !== false &&
                $statement['values'] === [1000, 'test'];
        }), true)
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->deleteAll(1000, 'test')
            ->shouldReturn(true);
    }

    public function getMatchers()
    {
        $matchers = [];

        $matchers['beATraversableOf'] = function ($subject, $count, $class) {
            if (!$subject instanceof \Traversable) {
                throw new FailureException('Return value should be instance of \Traversable');
            }

            $subjectArray = iterator_to_array($subject);

            if ($count !== null && count($subjectArray) !== $count) {
                throw new FailureException("Subject should be a traversable of $count $class");
            }

            $validTypes = true;

            foreach ($subjectArray as $element) {
                if (!($element instanceof $class)) {
                    $validTypes = false;
                    break;
                }
            }

            if (!$validTypes) {
                throw new FailureException("Subject should be a traversable of {$class}");
            }

            return true;
        };

        return $matchers;
    }
}
