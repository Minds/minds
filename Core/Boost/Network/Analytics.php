<?php
/**
 * Boost stats 
 */
namespace Minds\Core\Boost\Network;

use Minds\Common\Repository\Response;
use Minds\Core\Di\Di;
use Minds\Helpers;
use Minds\Core\Data\ElasticSearch\Prepared;

class Analytics 
{
    /** @var Client $es */
    protected $es;

    /** @var string $type */
    protected $type;

    public function __construct($es = null)
    {
        $this->es = $es ?: Di::_()->get('Database\ElasticSearch');
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getReview()
    {
        $body = [
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'term' => [
                                'bid_type' => 'tokens',
                            ],
                        ],
                    ],
                    'must_not' => [
                        [
                            'exists' => [
                                'field' => '@rejected',
                            ],
                        ],
                        [
                            'exists' => [
                                'field' => '@revoked',
                            ],
                        ],
                        [
                            'exists' => [
                                'field' => '@reviewed',
                            ],
                        ],
                    ],
                ],
            ],
            'aggs' => [
                'oldest' => [
                    'min' => [
                        'field' => '@timestamp',
                    ],
                ],
            ],
        ];

        if ($this->type ) {
            $body['query']['bool']['must'][] = [
                'term' => [
                    'type' => $this->type,
                ],
            ];
        }

        $prepared = new Prepared\Search();
        $prepared->query([
            'index' => 'minds-boost',
            'type' => '_doc',
            'body' => $body,
        ]);
        
        $result = $this->es->request($prepared);
        
        return [
            'oldest' => $result['aggregations']['oldest']['value'],
            'count' => $result['hits']['total']
        ];
    }

    public function getReviewCount()
    {
        return $this->getReview()['count'];
    }

    public function getReviewBacklog()
    {
        return (time() - ($this->getReview()['oldest'] / 1000)) / (60 * 60);
    }

    public function getApproved()
    {
        $body = [
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'term' => [
                                'bid_type' => 'tokens',
                            ],
                        ],
                        [
                            'exists' => [
                                'field' => '@reviewed',
                            ],
                        ],
                    ],
                    'must_not' => [
                        [
                            'exists' => [
                                'field' => '@completed',
                            ],
                        ],
                        [
                            'exists' => [
                                'field' => '@revoked',
                            ],
                        ],
                        [
                            'exists' => [
                                'field' => '@rejected',
                            ],
                        ],
                    ],
                ],
            ],
            'aggs' => [
                'oldest' => [
                    'min' => [
                        'field' => '@timestamp',
                    ],
                ],
            ],
        ];

        $prepared = new Prepared\Search();
        $prepared->query([
            'index' => 'minds-boost',
            'type' => '_doc',
            'body' => $body,
            'size' => 10000,
        ]);

        $result = $this->es->request($prepared);
        return [
            'docs' => $result['hits']['hits'],
            'oldest' => $result['aggregations']['oldest']['value'],
            'count' => $result['hits']['total'],
        ];
    }

    public function getApprovedCount()
    {
        return $this->getApproved()['count'];
    }

    public function getApprovedBacklog()
    {
        return (time() - ($this->getApproved()['oldest'] / 1000)) / (60 * 60);   
    }

    public function getImpressions()
    {
         $total = 0;
         foreach ($this->getApproved()['docs'] as $doc) {
             $total += $doc['_source']['impressions'];
         }
         return $total;
    }

    public function getImpressionsMet()
    {
        $met = 0;
        //foreach ($this->getApproved()['docs'] as $doc) {
        //    $met += Helpers\Counters::get((string) $doc['_id'], "boost_impressions", false);
        //}
        return $met;
    }
    
}
