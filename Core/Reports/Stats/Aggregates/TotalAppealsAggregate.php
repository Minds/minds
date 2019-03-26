<?php
namespace Minds\Core\Reports\Stats\Aggregates;

use Minds\Core\Di\Di;
use Minds\Core\Data\ElasticSearch\Prepared;

class TotalAppealsAggregate implements ModerationStatsAggregateInterface
{
    /** @var Client $es */
    private $es;

    public function __construct($es = null)
    {
        $this->es = $es ?: Di::_()->get('Database\ElasticSearch');
    }

    /**
     * @return init
     */
    public function get(): int
    {
        $body = [
            'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'range' => [
                                    '@appeal_timestamp' => [
                                        'gte' => 1550669969578,
                                        'lte' => 1553261969578,
                                        'format' => 'epoch_millis',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
        ];

        $query = [
            'index' => 'minds-moderation',
            'body' => $body,
            'size' => 0,
        ];

        $prepared = new Prepared\Search();
        $prepared->query($query);
        $result = $this->es->request($prepared);
        
        return $result['hits']['total'];
    }

}
