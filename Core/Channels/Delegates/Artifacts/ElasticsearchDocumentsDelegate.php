<?php
/**
 * ElasticsearchDocumentsDelegate.
 *
 * @author emi
 */

namespace Minds\Core\Channels\Delegates\Artifacts;

use Minds\Core\Channels\Snapshots\Repository;
use Minds\Core\Config;
use Minds\Core\Data\ElasticSearch\Client as ElasticsearchClient;
use Minds\Core\Di\Di;

class ElasticsearchDocumentsDelegate implements ArtifactsDelegateInterface
{
    /** @var Repository */
    protected $repository;

    /** @var Config */
    protected $config;

    /** @var ElasticsearchClient */
    protected $elasticsearch;

    /**
     * ElasticsearchDocumentsDelegate constructor.
     * @param Repository $repository
     * @param Config $config
     * @param ElasticsearchClient $elasticsearch
     */
    public function __construct(
        $repository = null,
        $config = null,
        $elasticsearch = null
    )
    {
        $this->repository = $repository ?: new Repository();
        $this->config = $config ?: Di::_()->get('Config');
        $this->elasticsearch = $elasticsearch ?: Di::_()->get('Database\ElasticSearch');
    }

    /**
     * @param string|int $userGuid
     * @return bool
     */
    public function snapshot($userGuid)
    {
        return true;
    }

    /**
     * @param string|int $userGuid
     * @return bool
     * @throws \Exception
     */
    public function restore($userGuid)
    {
        $body = [
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'match' => [
                                'owner_guid' => (string) $userGuid,
                            ]
                        ],
                    ],
                ],
            ],
            'script' => [
                'inline' => 'ctx._source.deleted = false;',
                'lang' => 'painless',
            ],
        ];

        $query = [
            'index' => $this->config->get('elasticsearch')['index'],
            'body' => $body,
        ];

        $this->elasticsearch->getClient()->updateByQuery($query);

        return true;
    }

    /**
     * @param string|int $userGuid
     * @return bool
     */
    public function hide($userGuid)
    {
        $body = [
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'match' => [
                                'owner_guid' => (string) $userGuid,
                            ]
                        ],
                    ],
                ],
            ],
            'script' => [
                'inline' => 'ctx._source.deleted = true;',
                'lang' => 'painless',
            ],
        ];

        $query = [
            'index' => $this->config->get('elasticsearch')['index'],
            'body' => $body,
        ];

        $this->elasticsearch->getClient()->updateByQuery($query);

        return true;
    }

    /**
     * @param string|int $userGuid
     * @return bool
     */
    public function delete($userGuid)
    {
        $params = [
            'index' => $this->config->get('elasticsearch')['index'],
            'type' => 'user',
            'id' => $userGuid
        ];

        $this->elasticsearch->getClient()->delete($params);

        $body = [
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'match' => [
                                'owner_guid' => (string) $userGuid,
                            ]
                        ],
                    ],
                ],
            ],
        ];

        $query = [
            'index' => $this->config->get('elasticsearch')['index'],
            'body' => $body,
        ];

        $this->elasticsearch->getClient()->deleteByQuery($query);

        return true;
    }
}
