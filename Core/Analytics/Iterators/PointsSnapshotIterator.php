<?php
namespace Minds\Core\Analytics\Iterators;

use Minds\Core;
use Minds\Core\Entities;
use Minds\Core\Data;
use Minds\Core\Analytics\Timestamps;

/**
 * Iterator that loops through all signups after a set period
 */
class PointsSnapshotIterator implements \Iterator
{
    private $cursor = -1;
    private $period = 0;


    private $limit = 400;
    private $token = "";
    private $offset = "";
    private $data = [];

    private $valid = true;

    /** @var Data\Cassandra\Client */
    private $db;

    /** @var Core\EntitiesBuilder */
    private $entities;

    private $position;

    public function __construct($db = null, $entities = null)
    {
        $this->db = $db ?: Core\Di\Di::_()->get('Database\Cassandra\Cql');
        $this->entities = $entities ?: Core\Di\Di::_()->get('EntitiesBuilder');
        $this->position = 0;
    }

    /**
     * Sets the period to cycle through
     * @param string $period
     */
    public function setPeriod($period = null)
    {
        $this->period = $period;
        $this->getUsers();
    }

    public function setOffset($offset = '')
    {
        $this->offset = $offset;
        $this->getUsers();
    }
    

    /**
     * Fetch all the users who signed up in a certain period
     * @return array
     */
    protected function getUsers()
    {
        $timestamps = array_reverse(Timestamps::span($this->period+1, 'day'));
        $prepared = new Data\Cassandra\Prepared\Custom;
        $prepared->query("SELECT * from entities_by_time where key='points:snapshot' and column1>?", [
            (string) $this->offset
        ]);
        $prepared->setOpts([
            'page_size' => $this->limit,
            'paging_state_token' => $this->token
        ]);

        $rows = $this->db->request($prepared);
        if (!$rows) {
           $this->valid = false;
           return;
        }

        $this->token = $rows->pagingStateToken();

        $guids = [];
        foreach ($rows as $row) {
            $guids[$row['column1']] = $row['value'];
        }

        $this->valid = true;
        $users = $this->entities->get(['guids' => array_keys($guids) ]);
        
        $pushed = 0;
        foreach ($users as $user) {
            if ($user->time_created < $timestamps[$this->period]) {
                $user->points_snapshot = $guids[$user->guid];
                array_push($this->data, $user);
                $pushed++;
            }
        }

        if ($rows->isLastPage()) { 
            //$this->valid = false;
            //return;
        } 

        if (!$pushed) {
            error_log("no users past period " . date('d-m-Y', end($users)->time_created));
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
