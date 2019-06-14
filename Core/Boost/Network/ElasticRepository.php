<?php
/**
 * Elasticsearch repository for Boost
 */
namespace Minds\Core\Boost\Network;

use Minds\Common\Repository\Response;
use Minds\Core\Di\Di;
use Minds\Core\Data\ElasticSearch\Prepared;
use Minds\Core\Util\BigNumber;

class ElasticRepository
{
    /** @var Client $es */
    protected $es;

    public function __construct($es = null)
    {
        $this->es = $es ?: Di::_()->get('Database\ElasticSearch');
    }

    /**
     * Return a list of boosts
     * @param array $opts
     * @return Response
     */
    public function getList($opts = [])
    {
        $opts = array_merge([
            'rating' => 3,
            'token' => 0,
            'offset' => null,
        ], $opts);
        
        $must = [];
        $must_not = [];
        $sort = [ '@timestamp' => 'asc' ];

        $must[] = [
            'term' => [
                'bid_type' => 'tokens',
            ],
        ];

        $must[] = [
            'term' => [
                'type' => $opts['type'],
            ],
        ];

        if ($opts['offset']) {
            $must[] = [
                'range' => [
                    '@timestamp' => [
                        'gt' => $opts['offset'],
                    ],
                ],
            ];
        }

        if ($opts['entity_guid']) {
            $must[] = [
                'term' => [
                    'entity_guid' => $opts['entity_guid']
                ]
            ];
        }

        if ($opts['state'] === 'approved') {
            $must[] = [
                'exists' => [
                    'field' => '@reviewed',
                ],
            ];
            $must[] = [
                'range' => [
                    'rating' => [
                        'lte' => $opts['rating'],
                    ],
                ],
            ];
        }

        if ($opts['state'] === 'review') {
            $must_not[] = [
                'exists' => [
                    'field' => '@reviewed',
                ],
            ];
            $sort = ['@timestamp' => 'asc'];
        }

        if ($opts['state'] === 'approved' || $opts['state'] === 'review') {
            $must_not[] = [
                'exists' => [
                    'field' => '@completed',
                ],
            ];
            $must_not[] = [
                'exists' => [
                    'field' => '@rejected',
                ],
            ];
            $must_not[] = [
                'exists' => [
                    'field' => '@revoked',
                ],
            ];
        }

        $body = [
            'query' => [
                'bool' => [
                    'must' => $must,
                    'must_not' => $must_not, 
                ],
            ],
            'sort' => $sort,
        ];

        $prepared = new Prepared\Search();
        $prepared->query([
            'index' => 'minds-boost',
            'type' => '_doc',
            'body' => $body,
            'size' => $opts['limit'],
            'from' => (int) $opts['token'],
        ]);

        $result = $this->es->request($prepared);
        
        $response = new Response;

        $offset = 0;
        foreach ($result['hits']['hits'] as $doc) {
            $boost = new Boost();
            $boost
                ->setGuid($doc['_id'])
                ->setEntityGuid($doc['_source']['entity_guid'])
                ->setOwnerGuid($doc['_source']['owner_guid'])
                ->setCreatedTimestamp($doc['_source']['@timestamp'])
                ->setReviewedTimestamp($doc['_source']['@reviewed'] ?? null)
                ->setRevokedTimestamp($doc['_source']['@revoked'] ?? null)
                ->setRejectedTimestamp($doc['_source']['@rejected'] ?? null)
                ->setCompletedTimestamp($doc['_source']['@completed'] ?? null)
                ->setPriority($doc['_source']['priority'] ?? false)
                ->setType($doc['_source']['type'])
                ->setRating($doc['_source']['rating'])
                ->setImpressions($doc['_source']['impressions'])
                ->setImpressionsMet($doc['_source']['impressions_met'])
                ->setBid($doc['_source']['bid'])
                ->setBidType($doc['_source']['bid_type']);
            $offset = $boost->getCreatedTimestamp();
            $response[] = $boost;
        }

        $response->setPagingToken($offset);
        return $response;
    }

    /**
     * Return a single boost via urn
     * @param string $urn
     * @return Boost
     */
    public function get($urn)
    {
        return $this->getList([])[0];
    }

    /**
     * Add a boost
     * @param Boost $boost
     * @return bool
     * @throws \Exception
     */
    public function add($boost)
    {
        $body = [
            'doc' => [
                '@timestamp' => $boost->getCreatedTimestamp(),
                'bid' => $boost->getBidType() === 'tokens' ?
                    (string) BigNumber::fromPlain($boost->getBid(), 18)->toDouble() : $boost->getBid(),
                'bid_type' => $boost->getBidType(),
                'entity_guid' => $boost->getEntityGuid(),
                'impressions' => $boost->getImpressions(),
                'owner_guid' => $boost->getOwnerGuid(),
                'rating' => $boost->getRating(),
                'type' => $boost->getType(),
                'priority' => (bool) $boost->isPriority(),
            ],
            'doc_as_upsert' => true,
        ];

        if ($boost->getBidType() === 'tokens') {
            $body['doc']['token_method'] = (strpos($boost->getTransactionId(), '0x', 0) === 0) 
                ? 'onchain' : 'offchain';
        }

        if ($boost->getImpressionsMet()) {
            $body['doc']['impressions_met'] = $boost->getImpressionsMet();
        }

        if ($boost->getCompletedTimestamp()) {
            $body['doc']['@completed'] = $boost->getCompletedTimestamp();
        }

        if ($boost->getReviewedTimestamp()) {
            $body['doc']['@reviewed'] = $boost->getReviewedTimestamp();
        }

        if ($boost->getRevokedTimestamp()) {
            $body['doc']['@revoked'] = $boost->getRevokedTimestamp();
        }

        if ($boost->getRejectedTimestamp()) {
            $body['doc']['@rejected'] = $boost->getRejectedTimestamp();
        }

        $prepared = new Prepared\Update();
        $prepared->query([
            'index' => 'minds-boost',
            'type' => '_doc',
            'body' => $body,
            'id' => $boost->getGuid(),
        ]);

        return (bool) $this->es->request($prepared);
    }

    /**
     * Update a boost
     * @param Boost $boost
     * @return bool
     * @throws \Exception
     */
    public function update($boost, $fields = [])
    {
        return $this->add($boost);
    }

    /**
     * void
     */
    public function delete($boost)
    {
    }

}

