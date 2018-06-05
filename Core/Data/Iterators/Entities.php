<?php

namespace Minds\Core\Data\Iterators;

use Cassandra\Rows;
use Minds\Core;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;

class Entities implements \Iterator
{
    /** @var Client */
    protected $db;

    /** @var int */
    protected $fetchLimit = 200;

    /** @var string */
    protected $offset = '';

    /** @var bool */
    protected $forcedOffset = false;

    /** @var array */
    protected $data = [];

    /** @var int */
    protected $i;

    /** @var bool */
    protected $valid = true;

    /** @var int */
    protected $cursor = -1;

    /** @var string */
    protected $type;

    /** @var bool */
    protected $wasIteratingLastPage;

    public function __construct($db = null)
    {
        $this->db = Core\Di\Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * @param int $fetchLimit
     * @return Entities
     */
    public function setFetchLimit($fetchLimit)
    {
        $this->fetchLimit = $fetchLimit;
        return $this;
    }

    /**
     * @param string $offset
     * @return $this
     */
    public function setOffset($offset = '')
    {
        $this->forcedOffset = true;
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return string
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param string $type
     * @return Entities
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getI()
    {
        return $this->i;
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function getData()
    {
        if (!$this->type) {
            throw new \Exception('Missing type filter');
        }

        if (!isset($this->offset) || $this->wasIteratingLastPage) {
            $this->valid = false;
            return;
        }

        $this->data = [];
        $this->cursor = 0;

        $template = 'SELECT * FROM entities where column1 = ? and value = ? GROUP BY key ALLOW FILTERING';
        $values = ['type', (string)$this->type];

        $query = new Custom();
        $query->query($template, $values);

        $query->setOpts([
            'page_size' => (int)$this->fetchLimit,
            'paging_state_token' => base64_decode($this->offset),
        ]);

        try {
            /** @var Rows $result */
            $result = $this->db->request($query);
        } catch (\Exception $e) {
            error_log($e);

            usleep(500000);
            $this->getData();

            return;
        }

        if (!$result) {
            $this->valid = false;
            return;
        }

        $this->forcedOffset = false;
        $this->offset = base64_encode($result->pagingStateToken());
        $this->valid = true;

        foreach ($result as $row) {
            if ($row['value'] == $this->type) {
                $single = $this->getSingle($row['key']);

                if ($single) {
                    $this->data[] = $single;
                }
            }
        }

        if ($result->isLastPage()) {
            $this->wasIteratingLastPage = true;
        }
    }

    private function getSingle($guid)
    {
        $template = "SELECT * FROM entities where key = ?";
        $values = [ $guid ];

        $query = new Custom();
        $query->query($template, $values);

        try {
            return $this->db->request($query, true);
        } catch (\Exception $e) {
            error_log($e);
        }

        return null;
    }

    /**
     * Rewind the array cursor
     * @throws \Exception
     */
    public function rewind()
    {
        if (!$this->forcedOffset) {
            $this->offset = '';
        }

        $this->wasIteratingLastPage = false;
        $this->i = 0;
        $this->getData();
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
     * @throws \Exception
     */
    public function next()
    {
        $this->cursor++;
        $this->i++;

        if (!isset($this->data[$this->cursor])) {
            $this->getData();
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

