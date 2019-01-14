<?php

namespace Spec\Minds\Core\VideoChat\Leases;

use Minds\Core\VideoChat\Leases\Repository;
use Minds\Core\VideoChat\Leases\VideoChatLease;
use Minds\Core\Data\Cassandra\Client;
use Spec\Minds\Mocks\Cassandra\Rows;
use Cassandra\Varint;
use Cassandra\Timestamp;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{

    private $db;

    function let(Client $db)
    {
        $this->beConstructedWith($db);
        $this->db = $db;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_add_lease_to_the_database()
    {
        $lease = new VideoChatLease();
        $lease->setKey('testkey')
            ->setSecret('secret')
            ->setHolderGuid(123)
            ->setLastRefreshed(time());

        $this->db->request(Argument::that(function($prepared) {
                $query = $prepared->build();
                return $query['values'][0] === 'testkey'
                    && $query['values'][1] === 'secret'
                    && $query['values'][2]->value() == 123
                    && $query['values'][3]->time() == time();
            }))
            ->shouldBeCalled()
            ->willReturn(true);
        
        $this->add($lease)->shouldBe(true);
    }

    function it_should_return_a_single_lease_by_key()
    {
        $this->db->request(Argument::any())
            ->shouldBeCalled()
            ->willReturn(new Rows([
                [
                    'key' => 'testkey',
                    'secret' => 'secret',
                    'holder_guid' => new Varint(123),
                    'last_refreshed' => new Timestamp(time()),
                ],
            ], 'paging'));
        $lease = $this->get('testkey');
        $lease->getKey()->shouldReturn('testkey');
        $lease->getSecret()->shouldReturn('secret');
        $lease->getHolderGuid()->shouldReturn(123);
        $lease->getLastRefreshed()->shouldReturn(time());
    }

}
