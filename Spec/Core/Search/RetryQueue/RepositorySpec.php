<?php

namespace Spec\Minds\Core\Search\RetryQueue;

use Exception;
use Minds\Common\Repository\Response;
use Minds\Core\Data\Cassandra\Client as CassandraClient;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Search\RetryQueue\Repository;
use Minds\Core\Search\RetryQueue\RetryQueueEntry;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Spec\Minds\Mocks\Cassandra\Rows;

class RepositorySpec extends ObjectBehavior
{
    /** @var CassandraClient */
    protected $db;

    function let(
        CassandraClient $db
    )
    {
        $this->beConstructedWith($db);

        $this->db = $db;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_get_a_list()
    {
        $this->db->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();

            return $query['string'] == 'SELECT * FROM search_dispatcher_queue' &&
                $query['values'] === [];
        }))
            ->shouldBeCalled()
            ->willReturn(new Rows([], '1a2b3c4d5e6f7890'));

        $this
            ->getList([])
            ->shouldReturnAnInstanceOf(Response::class);
    }

    function it_should_get_by_a_list_by_urn()
    {
        $this->db->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();

            return $query['string'] == 'SELECT * FROM search_dispatcher_queue WHERE entity_urn = ?' &&
                $query['values'] === ['urn:test:123456'];
        }))
            ->shouldBeCalled()
            ->willReturn(new Rows([
                [
                    'entity_urn' => 'urn:test:123456',
                    'last_retry' => new \Cassandra\Timestamp(1562182542),
                    'retries' => 3,
                ]
            ], '1a2b3c4d5e6f7890'));

        $this
            ->getList([
                'entity_urn' => 'urn:test:123456',
            ])
            ->shouldReturnAnInstanceOf(Response::class);
    }

    function it_should_add(RetryQueueEntry $retryQueueEntry)
    {
        $retryQueueEntry->getEntityUrn()
            ->shouldBeCalled()
            ->willReturn('urn:test:123456');

        $retryQueueEntry->getLastRetry()
            ->shouldBeCalled()
            ->willReturn(1562182542);

        $retryQueueEntry->getRetries()
            ->shouldBeCalled()
            ->willReturn(3);

        $this->db->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();

            return stripos($query['string'], 'INSERT INTO search_dispatcher_queue') === 0;
        }), true)
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->add($retryQueueEntry)
            ->shouldNotReturn(false);
    }

    function it_should_throw_during_add_if_no_entity_urn(RetryQueueEntry $retryQueueEntry)
    {
        $retryQueueEntry->getEntityUrn()
            ->shouldBeCalled()
            ->willReturn(null);

        $this->db->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(new Exception('Missing URN'))
            ->duringAdd($retryQueueEntry);
    }

    function it_should_delete(RetryQueueEntry $retryQueueEntry)
    {
        $retryQueueEntry->getEntityUrn()
            ->shouldBeCalled()
            ->willReturn('urn:test:123456');

        $this->db->request(Argument::that(function (Custom $prepared) {
            $query = $prepared->build();

            return stripos($query['string'], 'DELETE FROM search_dispatcher_queue') === 0;
        }), true)
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->delete($retryQueueEntry)
            ->shouldNotReturn(false);
    }

    function it_should_throw_during_delete_if_no_entity_urn(RetryQueueEntry $retryQueueEntry)
    {
        $retryQueueEntry->getEntityUrn()
            ->shouldBeCalled()
            ->willReturn(null);

        $this->db->request(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->shouldThrow(new Exception('Missing URN'))
            ->duringDelete($retryQueueEntry);
    }
}
