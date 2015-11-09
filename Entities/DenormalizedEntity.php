<?php
/**
 * Minds Denormalized Entity
 */
namespace Minds\Entities;

use Minds\Core;
use Minds\Core\Data;
use Minds\Helpers;
use Minds\Traits;

class DenormalizedEntity
{

    use Traits\Entity;

    protected $db;
    protected $rowKey;
    protected $guid;
    protected $exportableDefaults = [];

    public function __construct($db = NULL)
    {
        $this->db = $db ?: new Data\Call('entities_by_time');
    }

    public function setRowKey($key)
    {
        $this->rowKey = $key;
        return $this;
    }

    public function getRowKey()
    {
        return $this->rowKey;
    }

    /**
     * Load from guid
     * @param guid
     * @return $this
     * @throws Exception
     */
    public function loadFromGuid($guid)
    {
        $row = $this->db->getRow($this->rowKey, ['offset' => $guid, 'limit'=>1]);
        if(!$row || !isset($row[$guid]))
            throw new \Exception("Entity not found");
        return $this->loadFromArray($row[$guid]);
    }

    /**
     * Load an entity from an array
     * @param array
     * @return $this
     */
    public function loadFromArray($array)
    {
        $array = is_array($array) ? $array : json_decode($array, true);
        foreach($array as $key => $value){
            if(Helpers\Validation::isJson($value)) //json_decode should handle this, not sure it's needed
                $value = json_decode($value, true);

            $method = Helpers\Entities::buildSetter($key);
            if(method_exists($this, $method))
                $this->$method($value);
            elseif(property_exists($this, $key))
                $this->$key = $value;
        }
		//if($this->useCache())
		//	cache_entity($this);
        return $this;
    }

    /**
     * Save the denormalized entity to the database
     * @param array $data
     * @return boolean
     */
    protected function saveToDb($data)
    {
        return (bool) $this->db->insert($this->rowKey, [$this->guid => json_encode($data)]);
    }

    /**
     * Delete the denormalized entity
     */
    public function delete(){
        return (bool) $this->db->removeAttributes($this->rowKey, [$this->guid]);
    }

    /**
     * Export the entity
     * @param array $keys
     * @return array
     */
    public function export($keys = []){
        $keys = array_merge($this->exportableDefaults, $keys);
        $export = [];
        foreach($keys as $key){
            $method = Helpers\Entities::buildGetter($key);
            if(method_exists($this, $method))
                $export[$key] = $this->$method();
            elseif(property_exists($this, $key))
                $export[$key] = $this->$key;
        }
        return $export;
    }

}
