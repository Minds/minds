<?php


namespace Minds\Core\Analytics\Iterators;

use Minds\Core;
use Minds\Core\Data;
use Minds\Core\Data\Cassandra;

/**
 * Iterator that loops through all signups after a set period
 */
class UsersWithFacebookIterator  implements \Iterator
{
    private $cursor = -1;
    private $period = 0;
    private $limit = 400;
    private $token = "";
    private $offset = "";
    private $data = [];
    private $valid = true;

    /** @var Cassandra\Client $db */
    protected $db;
    /** @var Core\EntitiesBuilder */
    protected $entitiesBuilder;

    protected $position;

    public function __construct($db = null, $entitiesBuilder = null)
    {
        $this->db = $db ?: Core\Di\Di::_()->get('Database\Cassandra\Cql');
        $this->entitiesBuilder = $entitiesBuilder ?: Core\Di\Di::_()->get('EntitiesBuilder');
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
     * Fetch all the users who signup through facebook
     */
    protected function getUsers()
    {
        $query = new Data\Cassandra\Prepared\Custom;
        $query->query("SELECT key FROM entities WHERE column1='fb_uuid' ALLOW FILTERING", []);
        $query->setOpts([
            'page_size' => $this->limit,
            'paging_state_token' => $this->token
        ]);
        $rows = $this->db->request($query);
        if (!$rows) {
            $this->valid = false;
            return;
        }
        $this->token = $rows->pagingStateToken();
        $guids = [];
        foreach ($rows as $row) {
            $guids[] = $row['key'];
        }
        $this->valid = true;
        $users = $this->entitiesBuilder->get(['guids' => $guids ]);
        $this->data = array_merge($this->data, $users);

        if ($rows->isLastPage()) {
            //$this->valid = false;
            //return;
        }
        if (!count($guids)) {
            error_log("no users with facebook login" . date('d-m-Y', end($users)->time_created));
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