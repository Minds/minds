<?php

namespace Spec\Minds\Core\Channels\Delegates\Artifacts;

use Elasticsearch\Client as ElasticsearchNativeClient;
use Minds\Core\Channels\Delegates\Artifacts\ElasticsearchDocumentsDelegate;
use Minds\Core\Channels\Snapshots\Repository;
use Minds\Core\Channels\Snapshots\Snapshot;
use Minds\Core\Config;
use Minds\Core\Data\ElasticSearch\Client as ElasticsearchClient;
use Minds\Core\Data\ElasticSearch\Prepared\Search as PreparedSearch;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ElasticsearchDocumentsDelegateSpec extends ObjectBehavior
{
    /** @var Repository */
    protected $repository;

    /** @var Config */
    protected $config;

    /** @var ElasticsearchClient */
    protected $elasticsearch;

    /** @var ElasticsearchNativeClient */
    protected $esNativeClient;

    function let(
        Repository $repository,
        Config $config,
        ElasticsearchClient $elasticsearch,
        ElasticsearchNativeClient $esNativeClient
    )
    {
        $this->beConstructedWith($repository, $config, $elasticsearch);

        $this->repository = $repository;
        $this->config = $config;
        $this->elasticsearch = $elasticsearch;

        $this->elasticsearch->getClient()
            ->willReturn($esNativeClient);

        $this->esNativeClient = $esNativeClient;
    }


    function it_is_initializable()
    {
        $this->shouldHaveType(ElasticsearchDocumentsDelegate::class);
    }

    function it_should_snapshot()
    {
        $this
            ->snapshot(1000)
            ->shouldReturn(true);
    }

    function it_should_restore()
    {
        $this->config->get('elasticsearch')
            ->shouldBeCalled()
            ->willReturn(['index' => 'phpspec']);

        $this->elasticsearch->getClient()
            ->shouldBeCalled()
            ->willReturn($this->esNativeClient);

        $this->esNativeClient->updateByQuery(Argument::that(function ($query) {
            return ($query['body']['query']['bool']['must'][0]['match']['owner_guid'] ?? null) === '1000';
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->restore(1000)
            ->shouldReturn(true);

    }

    function it_should_hide()
    {
        $this->config->get('elasticsearch')
            ->shouldBeCalled()
            ->willReturn(['index' => 'phpspec']);

        $this->elasticsearch->getClient()
            ->shouldBeCalled()
            ->willReturn($this->esNativeClient);

        $this->esNativeClient->updateByQuery(Argument::that(function ($query) {
            return ($query['body']['query']['bool']['must'][0]['match']['owner_guid'] ?? null) === '1000';
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->hide(1000)
            ->shouldReturn(true);

    }

    function it_should_delete()
    {
        $this->config->get('elasticsearch')
            ->shouldBeCalled()
            ->willReturn(['index' => 'phpspec']);

        $this->esNativeClient->delete([
            'index' => 'phpspec',
            'type' => 'user',
            'id' => 1000,
        ])
            ->shouldBeCalled()
            ->willReturn(true);

        $this->esNativeClient->deleteByQuery(Argument::that(function ($query) {
            return ($query['body']['query']['bool']['must'][0]['match']['owner_guid'] ?? null) === '1000';
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->delete(1000)
            ->shouldReturn(true);
    }
}
