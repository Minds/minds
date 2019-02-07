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

class UserStateIterator implements \Iterator
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
    }

    //Sets the last day for the iterator (ie, today)
    public function setReferenceDate($referenceDate)
    {
        $this->referenceDate = $referenceDate;

        return $this;
    }

    public function get()
    {
        if ($this->page++ >= $this->partitions - 1) {
            $this->valid = false;

            return;
        }

        //Set the range for the entire query day - offset to day + 1
        $from = strtotime('-1 day', $this->referenceDate);
        $to = $this->referenceDate;

        $must = [
            ['range' => [
                  'reference_date' => [
                    'gte' => $from * 1000, //midnight of the first day
                    'lte' => $to * 1000, //midnight of the last day
                    'format' => 'epoch_millis',
                  ],
            ]],
        ];

        //split up users by user guid
        $aggs = [
            'user_state' => [
                'terms' => [
                    'field' => 'user_guid',
                    'size' => 5000,
                    'include' => [
                        'partition' => $this->page,
                        'num_partitions' => $this->partitions,
                    ],
                ],
                'aggs' => [
                    'unique_state' => [
                        'cardinality' => [
                            'field' => 'state.keyword',
                        ],
                    ],
                    'latest_state' => [
                        'top_hits' => [
                            'docvalue_fields' => ['state.keyword'],
                            'size' => 2,
                            'sort' => [
                                'reference_date' => [
                                    'order' => 'desc',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $query = [
            'index' => 'minds-kite',
            'size' => '2',
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

        if ($result && $result['aggregations']['user_state']['buckets']) {
            $document = $result['aggregations']['user_state']['buckets'][0]['latest_state']['hits']['hits'][0];
            if ($result['aggregations']['user_state']['buckets'][0]['unique_state']['value'] == 2) {
                //Fire off state changes
                $previousDocument = $result['aggregations']['user_state']['buckets'][0]['latest_state']['hits']['hits'][1];
                $userState = (new UserState())
                    ->setUserGuid($document['_source']['user_guid'])
                    ->setReferenceDateMs($document['_source']['reference_date'])
                    ->setState($document['_source']['state'])
                    ->setPreviousState($previousDocument['_source']['state'])
                    ->setActivityPercentage($document['_source']['activity_percentage']);
                $this->data[] = $userState;
            } elseif ($result['aggregations']['user_state']['buckets'][0]['doc_count'] == 1) {
                //Fire off single states (new user, resurrected or a gap)
                $userState = (new UserState())
                    ->setUserGuid($document['_source']['user_guid'])
                    ->setReferenceDateMs($document['_source']['reference_date'])
                    ->setState($document['_source']['state'])
                    ->setActivityPercentage($document['_source']['activity_percentage']);
                $this->data[] = $userState;
            }
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
