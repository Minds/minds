<?php

namespace Spec\Minds\Core\Trending;

use Cassandra\Varint;
use Minds\Core\Data\Cassandra\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Spec\Minds\Mocks\Cassandra\Rows;
use Minds\Core\Data\Cassandra\Prepared\Custom;

class RepositorySpec extends ObjectBehavior
{
    protected $_client;

    function let(Client $client)
    {
        $this->beConstructedWith($client);

        $this->_client = $client;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Trending\Repository');
    }

    function it_should_get_a_list_of_trending_entities_guids(Client $client)
    {
        $rows = new Rows([
            [ 'type' => 'newsfeed', 'place' => 0, 'guid' => 123 ],
            [ 'type' => 'network', 'place' => 1, 'guid' => 456 ],
        ], '');

        $client->request(Argument::type(Custom::class))
            ->shouldBeCalled()
            ->willReturn($rows);

        $this->beConstructedWith($client);

        $return = $this->getList(['type'=>'newsfeed']);
        $return->shouldHaveCount(2);
        $return->shouldHaveKeyWithValue('guids', [ "123", "456" ]);
    }

    function it_should_store_a_list_of_trending_entities(Client $client)
    {
        $client->batchRequest(Argument::that(function ($requests) {
            if (!is_array($requests)) {
                return false;
            }

            return true;
        }), 1)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->beConstructedWith($client);

        $this->add('newsfeed', [123, 456]);
    }
}
