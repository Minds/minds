<?php

namespace Spec\Minds\Core\Search;

use Minds\Core\Data\ElasticSearch\Client;
use Minds\Core\Di\Di;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProvisionerSpec extends ObjectBehavior
{
    protected $_client;
    protected $_index = 'phpspec';

    function let(
        Client $client
    ) {
        $this->_client = $client;

        $this->beConstructedWith($client, $this->_index);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Search\Provisioner');
    }

    function it_should_set_up(
        \Elasticsearch\Client $esClient,
        \Elasticsearch\Namespaces\IndicesNamespace $indicesNamespace
    )
    {
        $this->_client->getClient()
            ->shouldBeCalled()
            ->willReturn($esClient);

        $esClient->indices()
            ->shouldBeCalled()
            ->willReturn($indicesNamespace);

        $indicesNamespace->putMapping(Argument::withEntry('type', 'activity'))
            ->shouldBeCalled()
            ->willReturn(null);

        $indicesNamespace->putMapping(Argument::withEntry('type', 'group'))
            ->shouldBeCalled()
            ->willReturn(null);

        $indicesNamespace->putMapping(Argument::withEntry('type', 'object:blog'))
            ->shouldBeCalled()
            ->willReturn(null);

        $indicesNamespace->putMapping(Argument::withEntry('type', 'object:image'))
            ->shouldBeCalled()
            ->willReturn(null);

        $indicesNamespace->putMapping(Argument::withEntry('type', 'object:video'))
            ->shouldBeCalled()
            ->willReturn(null);

        $indicesNamespace->putMapping(Argument::withEntry('type', 'user'))
            ->shouldBeCalled()
            ->willReturn(null);

        $this->setUp();
    }
}
