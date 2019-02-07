<?php

namespace Minds\Core\Analytics\UserStates;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data;

/*
* Iterator that loops through users and counts their action.active entries for the past N days
* All times adjusted to midnight to span the entire day
* Takes a reference day (eg today) and bucket sums user activity back N days
*/

class ActiveUsersIterator implements \Iterator
{
    private $cursor = -1;
    private $period = 0;
    private $item;
    private $limit = 400;
    private $partitions = 200;
    private $page = -1;
    private $data = [];
    private $active;
    private $valid = true;

    public function __construct($client = null)
    {
        $this->client = $client ?: Di::_()->get('Database\ElasticSearch');
        $this->position = 0;
        $this->referenceDate = strtotime('midnight');
        $this->rangeOffset = 7;
    }

    //Sets the last day for the iterator (ie, today)
    public function setReferenceDate($referenceDate)
    {
        $this->referenceDate = $referenceDate;

        return $this;
    }

    //Sets the number of days to look backwards
    public function setRangeOffset($rangeOffset)
    {
        $this->rangeOffset = $rangeOffset;

        return $this;
    }

    //Builds up a sub aggregate that counts the days for a bucket with the same name
    private function buildBucketCountAggregation($name)
    {
        return [
            'sum_bucket' => [
                'buckets_path' => "$name-bucket>_count",
            ],
        ];
    }

    //Builds up a sub aggregate that splits a user's activity into days
    private function buildBucketAggregation($name, $dayOffset)
    {
        $toOffset = $dayOffset - 1;
        //Set times to midnight of the current day until midnight of the next day(end of day);

        $from = strtotime("-$dayOffset day", $this->referenceDate);
        $to = strtotime("-$toOffset day", $this->referenceDate);

        return [
            'date_range' => [
                'field' => '@timestamp',
                'ranges' => [
                    [
                        'from' => $from * 1000, //eg 2019-01-24 00:00:00
                        'to' => $to * 1000, //eg 2019-01-25 00:00:00
                    ],
                ],
            ],
        ];
    }

    public function get()
    {
        if ($this->page++ >= $this->partitions - 1) {
            $this->valid = false;

            return;
        }

        //Set the range for the entire query day - offset to day + 1
        $from = strtotime("-$this->rangeOffset day", $this->referenceDate);
        $to = strtotime('+1 day', $this->referenceDate);

        $bucketAggregations = [];
        //for the range of (reference day ) - offset (midnight) to (reference day) + 1 offset (midnight the next day)
        foreach (range(0, $this->rangeOffset) as $dayOffset) {
            $bucketAggregations["day-$dayOffset-bucket"] = $this->buildBucketAggregation("day-$dayOffset", $dayOffset);
            $bucketAggregations["day-$dayOffset"] = $this->buildBucketCountAggregation("day-$dayOffset");
        }

        $must = [
            ['match_phrase' => [
                'action.keyword' => [
                    'query' => 'active',
                ],
            ]],
            ['range' => [
                  '@timestamp' => [
                    'from' => $from * 1000, //midnight of the first day
                    'to' => $to * 1000, //midnight of the last day
                    'format' => 'epoch_millis',
                  ],
            ]],
        ];

        //split up users by user guid
        $aggs = [
            'users' => [
                'terms' => [
                    'field' => 'user_guid.keyword',
                    'size' => 5000,
                    'include' => [
                        'partition' => $this->page,
                        'num_partitions' => $this->partitions,
                    ],
                ],
                'aggs' => $bucketAggregations,
            ],
        ];

        $query = [
            'index' => 'minds-metrics-*',
            'size' => '0',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => $must,
                    ],
                ],
                'aggs' => $aggs,
            ],
        ];

        $prepared = new Core\Data\ElasticSearch\Prepared\Search();
        $prepared->query($query);

        try {
            $result = $this->client->request($prepared);
        } catch (\Exception $e) {
            error_log($e);

            return false;
        }

        if (!$result || $result['hits']['total'] == 0) {
            return false;
        }

        //Cook down the verbose elastic search into just the data we need
        foreach ($result['aggregations']['users']['buckets'] as $userActivityByDay) {
            $userActivityBuckets = (new UserActivityBuckets())
                ->setUserGuid($userActivityByDay['key'])
                ->setReferenceDateMs($this->referenceDate * 1000);

            $days = [];
            foreach (range(0, $this->rangeOffset) as $dayOffset) {
                $days[$dayOffset] = [
                    'reference_date' => $userActivityByDay["day-$dayOffset-bucket"]['buckets'][0]['from'],
                    'count' => $userActivityByDay["day-$dayOffset"]['value'],
                ];
            }

            $userActivityBuckets->setActiveDaysBuckets($days);
            $this->data[] = $userActivityBuckets;
        }
        if ($this->cursor >= count($this->data)) {
            $this->get();
        }
    }

    /**
     * Rewind the array cursor.
     */
    public function rewind()
    {
        if ($this->cursor >= 0) {
            $this->get();
        }
        $this->next();
    }

    /**
     * Get the current cursor's data.
     *
     * @return mixed
     */
    public function current()
    {
        return $this->data[$this->cursor];
    }

    /**
     * Get cursor's key.
     *
     * @return mixed
     */
    public function key()
    {
        return $this->cursor;
    }

    /**
     * Goes to the next cursor.
     */
    public function next()
    {
        ++$this->cursor;
        if (!isset($this->data[$this->cursor])) {
            $this->get();
        }
    }

    /**
     * Checks if the cursor is valid.
     *
     * @return bool
     */
    public function valid()
    {
        return $this->valid && isset($this->data[$this->cursor]);
    }
}
