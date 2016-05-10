<?php
namespace Minds\Core\Analytics\Iterators;

use Minds\Core;
use Minds\Core\Entities;
use Minds\Core\Data;
use Minds\Core\Analytics\Timestamps;

class SignupsIterator implements \Iterator
{

    private $cursor = -1;
    private $item;

    private $limit = 200;
    private $offset = "";
    private $data = [];

    private $valid = true;

    public function __construct($db = null) {
        $this->db = $db ?: new Data\Call('entities_by_time');
        $this->position = 0;
    }

    public function setPeriod($period = NULL)
    {
        $this->period = $period;
        $this->getUsers();
    }

    protected function getUsers(){
        //$this->cursor = -1;
        //$this->item = null;

        $timestamps = array_reverse(Timestamps::span(30, 'day'));

        $guids = $this->db->getRow("analytics:signup:day:{$timestamps[$this->period]}", ['limit' => $this->limit, 'offset'=> $this->offset]);
        $guids = array_keys($guids);
        if($this->offset){
            array_shift($guids);
        }

        if(empty($guids)){
            $this->valid = false;
            return;
        }
        $this->valid = true;
        $users = Entities::get(['guids' => $guids]);

        foreach($users as $user){
            array_push($this->data, $user);
        }

        if($this->offset == end($users)->guid){
            $this->valid = false;
            return;
        }

        $this->offset = end($users)->guid;
    }

    public function rewind() {
        if ($this->cursor >= 0){
            $this->getUsers();
        }
        $this->next();
    }

    public function current() {
        return $this->data[$this->cursor];
    }

    public function key() {
        return $this->cursor;
    }

    public function next() {
        $this->cursor++;
        if(!isset($this->data[$this->cursor])){
            $this->getUsers();
        }        
    }

    public function valid() {
        return $this->valid && isset($this->data[$this->cursor]);
    }

}
