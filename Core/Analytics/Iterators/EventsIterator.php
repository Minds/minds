<?php
namespace Minds\Core\Analytics\Iterators;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Entities;
use Minds\Core\Data;
use Minds\Core\Analytics\Timestamps;

/**
 * Iterator that loops through all signups after a set period
 */
class EventsIterator implements \Iterator
{
    private $cursor = -1;

    private $item;

    private $limit = 10000;
    private $offset = "";
    private $data = [];

    private $type;
    private $distinct;
    private $body;
    private $terms = [];

    private $valid = true;


    private $elastic;
    private $index;
    private $position;

    public function __construct($elastic = null, $index = null)
    {
        $this->elastic = $elastic ?: Di::_()->get('Database\ElasticSearch');
        $this->index = $index ?: Di::_()->get('Config')->elasticsearch['metrics_index'] . '-*';
        $this->position = 0;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function setTerms($terms)
    {
        $this->terms = $terms;
        return $this;
    }

    /**
     * Sets the period to cycle through
     * @param string $period
     */
    public function setPeriod($period = null)
    {
        $this->period = $period;
    }

    /**
     * Fetch all the users who signed up in a certain period
     * @return array
     */
    protected function getList()
    {

        $body = [
            'query' => [
                'range' => [
                    '@timestamp' => [
                        'gte' => $this->period * 1000
                        ]
                    ]
            ]
        ];

        if ($this->terms) {
            foreach ($this->terms as $term) {
                $body['aggs'][$term] = [
                    'terms' => [
                        'field' => $term,
                        'size' => $this->limit
                    ]
                ];
            }
        }

        
        $prepared = new Core\Data\ElasticSearch\Prepared\Search();
        $prepared->query([
            'body' => $body,
            'index' => $this->index,
            'type' => $this->type,
            'size' => $this->limit,
            'from' => (int) $this->offset,
            'client' => [
                'timeout' => 2,
                'connect_timeout' => 1
            ] 
        ]);

        $result = $this->elastic->request($prepared);

        if ($this->terms) {
            foreach ($this->terms as $term) {
                foreach ($result['aggregations'][$term]['buckets'] as $item) {
                    $this->data[] = $item['key'];
                }
            }
        }

        $this->offset = count($this->data);
    }

    /**
     * Rewind the array cursor
     * @return null
     */
    public function rewind()
    {
        if ($this->cursor >= 0) {
            $this->getList();
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
        if (!isset($this->data[$this->cursor]) && !($this->data && $this->terms)) {
            $this->getList();
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
