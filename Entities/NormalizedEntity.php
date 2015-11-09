<?php
/**
 * Minds Normalized Entity
 */
namespace Minds\Entities;

use Minds\Core;
use Minds\Core\Data;
use Minds\Helpers;
use Minds\Traits;

class NormalizedEntity
{

    use Traits\Entity;

    private $db;
    private $indexDB;
    private $guid;
    private $indexes = [];

    public function __construct($db = NULL, $indexDB = NULL){
        $this->db = $db ?: new Data\Call('entities');
        $this->indexDB = $indexDB ?: new Data\Call('entities_by_time');
    }

    /**
     * Load from guid
     * @param guid
     * @return $this
     * @throws Exception
     */
    public function loadFromGuid($guid)
    {
        $row = $this->db->getRow($guid);
        if(!$row)
            throw new \Exception("Entity not found");
        return $this->loadFromArray($row);
    }

    /**
     * Load an entity from an array
     * @param array
     * @return $this
     */
    public function loadFromArray($array)
    {
        foreach($array as $key => $value){
            if(Helpers\Validation::isJson($value))
                $value = json_decode($value, true);

            if(property_exists($this, $key))
                $this->$key = $value;
        }

		//if($this->useCache())
		//	cache_entity($this);
        return $this;
    }

    /**
     * Save the normalized entity to the database
     * @param array $data
     * @return boolean
     */
    protected function saveToDb($data)
    {
        foreach($data as $k => $v){
            if(is_null($v)) continue;
            if(is_array($v)) $v = json_encode($v);
            $data[$k] = $v;
        }
        return (bool) $this->db->insert($this->getGuid(), $data);
    }

    /**
     * Save to indexes
     * @return void
     */
    protected function saveToIndex()
    {
        foreach($this->indexes as $index)
            $this->indexDB->insert($index, [$this->getGuid() => $this->getGuid()]);
    }

}
