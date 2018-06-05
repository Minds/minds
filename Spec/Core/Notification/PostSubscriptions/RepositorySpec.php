<?php

namespace Spec\Minds\Core\Notification\PostSubscriptions;

use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Notification\PostSubscriptions\PostSubscription;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
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
        $this->shouldHaveType('Minds\Core\Notification\PostSubscriptions\Repository');
    }

    function it_should_get_rows()
    {
        $rows = new \stdClass();

        $preparedMatch = function (Custom $prepared) {
            $query = $prepared->build();
            $cql = $query['string'];
            $values = $query['values'];
            $opts = $prepared->getOpts();

            return (
                stripos($cql, 'select * from post_subscriptions') === 0 &&
                stripos($cql, 'entity_guid = ?') > 0 &&
                stripos($cql, 'user_guid = ?') > 0 &&
                $values[0]->value() == 5000 &&
                $values[1]->value() == 1000 &&
                $opts['page_size'] === 10 &&
                $opts['paging_state_token'] === 'phpspec'
            );
        };

        $this->db->request(Argument::that($preparedMatch))
            ->shouldBeCalled()
            ->willReturn($rows);

        $this
            ->getRows([
                'entity_guid' => 5000,
                'user_guid' => 1000,
                'offset' => base64_encode('phpspec'),
                'limit' => 10
            ])
            ->shouldReturn($rows);
    }

    function it_should_get_list()
    {
        $preparedMatch = function (Custom $prepared) {
            $query = $prepared->build();
            $cql = $query['string'];
            $values = $query['values'];
            $opts = $prepared->getOpts();

            return (
                stripos($cql, 'select * from post_subscriptions') === 0 &&
                stripos($cql, 'entity_guid = ?') > 0 &&
                stripos($cql, 'user_guid = ?') > 0 &&
                $values[0]->value() == 5000 &&
                $values[1]->value() == 1000 &&
                $opts['page_size'] === 10 &&
                $opts['paging_state_token'] === 'phpspec'
            );
        };

        $this->db->request(Argument::that($preparedMatch))
            ->shouldBeCalled()
            ->willReturn([
                [
                    'entity_guid' => new \Mock(5000),
                    'user_guid' => new \Mock(1000),
                    'following' => 'true',
                ],
                [
                    'entity_guid' => new \Mock(5000),
                    'user_guid' => new \Mock(1000),
                    'following' => 'true',
                ]
            ]);

        $this
            ->getList([
                'entity_guid' => 5000,
                'user_guid' => 1000,
                'offset' => base64_encode('phpspec'),
                'limit' => 10
            ])
            ->shouldBeACountableIteratorOf(2, PostSubscription::class);
    }

    function it_should_get()
    {
        $preparedMatch = function (Custom $prepared) {
            $query = $prepared->build();
            $cql = $query['string'];
            $values = $query['values'];
            $opts = $prepared->getOpts();

            return (
                stripos($cql, 'select * from post_subscriptions') === 0 &&
                stripos($cql, 'entity_guid = ?') > 0 &&
                stripos($cql, 'user_guid = ?') > 0 &&
                $values[0]->value() == 5000 &&
                $values[1]->value() == 1000 &&
                $opts['page_size'] === 1
            );
        };

        $this->db->request(Argument::that($preparedMatch))
            ->shouldBeCalled()
            ->willReturn([
                [
                    'entity_guid' => new \Mock(5000),
                    'user_guid' => new \Mock(1000),
                    'following' => 'true',
                ],
                [
                    'entity_guid' => new \Mock(5000),
                    'user_guid' => new \Mock(1000),
                    'following' => 'true',
                ]
            ]);

        $this
            ->get(5000, 1000)
            ->shouldReturnAnInstanceOf(PostSubscription::class);
    }

    function it_should_get_null_if_not_exists()
    {
        $preparedMatch = function (Custom $prepared) {
            $query = $prepared->build();
            $cql = $query['string'];
            $values = $query['values'];
            $opts = $prepared->getOpts();

            return (
                stripos($cql, 'select * from post_subscriptions') === 0 &&
                stripos($cql, 'entity_guid = ?') > 0 &&
                stripos($cql, 'user_guid = ?') > 0 &&
                $values[0]->value() == 5000 &&
                $values[1]->value() == 1000 &&
                $opts['page_size'] === 1
            );
        };

        $this->db->request(Argument::that($preparedMatch))
            ->shouldBeCalled()
            ->willReturn([]);

        $this
            ->get(5000, 1000)
            ->shouldReturn(null);
    }

    function it_should_add()
    {
        $preparedMatch = function (Custom $prepared) {
            $query = $prepared->build();
            $cql = $query['string'];
            $values = $query['values'];

            return (
                stripos($cql, 'insert into post_subscriptions') === 0 &&
                stripos($cql, 'if not exists') === false &&
                $values[0]->value() == 5000 &&
                $values[1]->value() == 1000 &&
                $values[2] === true
            );
        };

        $this->db->request(Argument::that($preparedMatch))
            ->shouldBeCalled()
            ->willReturn(true);

        $postSubscription = new PostSubscription();
        $postSubscription
            ->setEntityGuid(5000)
            ->setUserGuid(1000)
            ->setFollowing(true);

        $this
            ->add($postSubscription)
            ->shouldReturn(true);
    }

    function it_should_add_if_not_exists()
    {
        $preparedMatch = function (Custom $prepared) {
            $query = $prepared->build();
            $cql = $query['string'];
            $values = $query['values'];

            return (
                stripos($cql, 'insert into post_subscriptions') === 0 &&
                stripos($cql, 'if not exists') !== false &&
                $values[0]->value() == 5000 &&
                $values[1]->value() == 1000 &&
                $values[2] === true
            );
        };

        $this->db->request(Argument::that($preparedMatch))
            ->shouldBeCalled()
            ->willReturn(true);

        $postSubscription = new PostSubscription();
        $postSubscription
            ->setEntityGuid(5000)
            ->setUserGuid(1000)
            ->setFollowing(true);

        $this
            ->add($postSubscription, true)
            ->shouldReturn(true);
    }

    function it_should_delete()
    {
        $preparedMatch = function (Custom $prepared) {
            $query = $prepared->build();
            $cql = $query['string'];
            $values = $query['values'];

            return (
                stripos($cql, 'delete from post_subscriptions') === 0 &&
                $values[0]->value() == 5000 &&
                $values[1]->value() == 1000
            );
        };

        $this->db->request(Argument::that($preparedMatch))
            ->shouldBeCalled()
            ->willReturn(true);

        $postSubscription = new PostSubscription();
        $postSubscription
            ->setEntityGuid(5000)
            ->setUserGuid(1000);

        $this
            ->delete($postSubscription)
            ->shouldReturn(true);
    }

    function getMatchers()
    {
        $matchers = [];

        $matchers['beACountableIteratorOf'] = function ($subject, $count, $class) {
            if ($count !== null && count($subject) !== $count) {
                throw new FailureException("Subject should be a countable iterator of $count elements");
            }

            $validTypes = true;

            foreach ($subject as $element) {
                if (!($element instanceof $class)) {
                    $validTypes = false;
                    break;
                }
            }

            if (!$validTypes) {
                throw new FailureException("Subject should be a countable iterator of {$class}");
            }

            return true;
        };

        return $matchers;
    }
}
