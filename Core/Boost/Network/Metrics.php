<?php

namespace Minds\Core\Boost\Network;

use Minds\Core\Boost\Repository;
use Minds\Core\Data;
use Minds\Core\Di\Di;
use Minds\Entities\Boost\Network;
use Minds\Helpers;

class Metrics
{
    protected $mongo;
    protected $type;

    public function __construct(Data\Interfaces\ClientInterface $mongo = null)
    {
        $this->mongo = $mongo ?: Data\Client::build('MongoDB');
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = strtolower($type);
        return $this;
    }

    /**
     * Increments impressions to a given boost
     * @param Network $boost
     * @return int updated boost impressions count
     */

    public function incrementViews($boost)
    {
        //increment impression counter
        Helpers\Counters::increment((string) $boost->getGuid(), "boost_impressions", 1);
        //get the current impressions count for this boost
        Helpers\Counters::increment(0, "boost_impressions", 1);

        $count = Helpers\Counters::get((string) $boost->getGuid(), "boost_impressions", false);

        if ($boost->getMongoId()) {
            $count += Helpers\Counters::get((string) $boost->getMongoId(), "boost_impressions", false);
        }

        return $count;
    }

    public function getBacklogCount($userGuid = null)
    {
        $query = [
            'state' => 'approved',
            'type' => $this->type,
        ];
        if ($userGuid) {
            $match['owner_guid'] = $userGuid;
        }
        return (int) $this->mongo->count('boost', $query);
    }

    public function getPriorityBacklogCount()
    {
        return (int) $this->mongo->count('boost', [
            'state' => 'approved',
            'type' => $this->type,
            'priority' => [
                '$exists' => true,
                '$gt' => 0
            ],
        ]);
    }

    public function getBacklogImpressionsSum()
    {
        $result = $this->mongo->aggregate('boost', [
            [
                '$match' => [
                    'state' => 'approved',
                    'type' => $this->type
                ]
            ],
            [
                '$group' => [
                    '_id' => null,
                    'total' => ['$sum' => '$impressions']
                ]
            ]
        ]);

        return reset($result)->total ?: 0;
    }

    public function getAvgApprovalTime()
    {
        $result = $this->mongo->aggregate('boost', [
            [
                '$match' => [
                    'state' => 'approved',
                    'type' => $this->type,
                    'createdAt' => ['$ne' => null],
                    'approvedAt' => ['$ne' => null]
                ]
            ],
            [
                '$project' => [
                    'diff' => [
                        '$subtract' => ['$approvedAt', '$createdAt']
                    ]
                ]
            ],
            [
                '$group' => [
                    '_id' => null,
                    'count' => ['$sum' => 1],
                    'diffSum' => ['$sum' => '$diff']
                ]
            ]
        ]);

        $totals = reset($result);

        return ($totals->diffSum ?: 0) / ($totals->count ?: 1);
    }
}
