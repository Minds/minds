<?php
namespace Minds\Core\Rewards\Contributions;

use Minds\Core;
use Minds\Core\Entities;
use Minds\Core\Data;
use Minds\Core\Data\ElasticSearch;
use Minds\Core\Analytics\Timestamps;

/**
 * Iterator that loops through all signups after a set period
 */
class UsersIterator implements \Iterator
{
    private $cursor = -1;
    private $period = 0;

    private $item;

    private $from;
    private $to;

    private $limit = 400;
    private $partitions = 200;

    private $page = -1;
    private $data = [];
    private $action;

    private $valid = true;

    public function __construct($client = null)
    {
        $this->client = $client ?: Core\Di\Di::_()->get('Database\ElasticSearch');
        $this->position = 0;

        $this->from = strtotime('-24 hours') * 1000;
        $this->to = time() * 1000;
    }

    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }    

    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * Fetch all the users who signed up in a certain period
     * @return array
     */
    protected function getUsers()
    {
        if ($this->page++ >= $this->partitions - 1) {
            $this->valid = false;
            return;
        }

        $field = 'entity_owner_guid.keyword';

        $bool = [
            'must' => [
                [
                    'exists' => [
                        'field' => 'user_phone_number_hash',
                     ],
                ],
                [
                    'range' => [
                        '@timestamp' => [
                            'gte' => $this->from,
                            'lte' => $this->to,
                            'format' =>  'epoch_millis'
                        ]
                    ]
                ]
            ]
        ];

        if ($this->action == 'subscribe') {
            $bool['filter'] = [
                'terms' => [
                    'action' => 'subscribe'
                ]
            ];
            $field = 'entity_guid.keyword';
        }

        if ($this->action == 'active') {
            $bool['must'][] =
                [
                    'match_all' => (object) [],
                    ];
            $bool['must'][] =
                [
                    'match_phrase' => [
                        'action' => [
                            'query' => 'vote:up'
                        ]
                    ]
                ];
            $field = 'user_guid.keyword';
        }

        $query = [
            'index' => 'minds-metrics-*',
            'type' => 'action',
            'size' => 0, //we want just the aggregates
            'body' => [
                'query' => [
                    'bool' => $bool
                ],
                'aggs' => [
                    'counts' => [
                        'terms' => [ 
                            'field' => $field,
                            'size' => 5000, //5000 * 200 pages = 1,000,000 result
                            'include' => [
                                'partition' => $this->page,
                                'num_partitions' => $this->partitions
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $prepared = new ElasticSearch\Prepared\Search();
        $prepared->query($query);
        
        try {
            $result = $this->client->request($prepared);
        } catch (\Exception $e) {
            var_dump($e); exit;
            return false;
        }
        
        foreach ($result['aggregations']['counts']['buckets'] as $count) {
            if ($count['key'] == 0) {
                continue;
            } 
            array_push($this->data, $count['key']);
        }

        if ($this->cursor >= count($this->data)) {
            $this->getUsers();
        }

    }

    /**
     * Rewind the array cursor
     * @return null
     */
    public function rewind()
    {
        if ($this->cursor >= 0) {
            $this->getUsers();
        }
        $this->next();
    }

    /**
     * Get the current cursor's data
     * @return mixed
     */
    public function current()
    {
        return $this->data[$this->cursor];
    }

    /**
     * Get cursor's key
     * @return mixed
     */
    public function key()
    {
        return $this->cursor;
    }

    /**
     * Goes to the next cursor
     * @return null
     */
    public function next()
    {
        $this->cursor++;
        if (!isset($this->data[$this->cursor])) {
            $this->getUsers();
        }
    }

    /**
     * Checks if the cursor is valid
     * @return bool
     */
    public function valid()
    {
        return $this->valid && isset($this->data[$this->cursor]);
    }
}

