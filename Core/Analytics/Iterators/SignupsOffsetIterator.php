<?php
namespace Minds\Core\Analytics\Iterators;

use Minds\Core;
use Minds\Core\Entities;
use Minds\Core\Data;
use Minds\Core\Analytics\Timestamps;

/**
 * Iterator that loops through all signups after a set period
 */
class SignupsOffsetIterator implements \Iterator
{
    private $cursor = -1;

    private $item;

    private $limit = 400;
    private $offset = "";
    private $data = [];

    private $valid = true;

    public function __construct($db = null)
    {
        $this->db = $db ?: new Data\Call('entities_by_time');
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

    /**
     * Fetch all the users who signed up in a certain period
     * @return array
     */
    protected function getUsers()
    {
        $timestamps = array_reverse(Timestamps::span($this->period+1, 'day'));

        $guids = $this->db->getRow("user", ['limit' => $this->limit, 'offset'=> $this->offset]);
        $guids = array_keys($guids);

        if ($this->offset) {
            array_shift($guids);
        }

        if (empty($guids)) {
            $this->valid = false;
            return;
        }
        $this->valid = true;
        $users = Entities::get(['guids' => $guids]);

        $pushed = 0;
        foreach ($users as $user) {
            if ($user->time_created < $timestamps[$this->period]) {
                array_push($this->data, $user);
                $pushed++;
            }
        }

        if ($this->offset == end($users)->guid) {
            $this->valid = false;
            return;
        }

        $this->offset = end($users)->guid;
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
