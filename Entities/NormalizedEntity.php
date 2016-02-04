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
    protected $exportableDefaults = [];

    public function __construct($db = null, $indexDB = null)
    {
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
        if (!$row) {
            throw new \Exception("Entity not found");
        }
        return $this->loadFromArray($row);
    }

    /**
     * Load an entity from an array
     * @param array
     * @return $this
     */
    public function loadFromArray($array)
    {
        foreach ($array as $key => $value) {
            if (Helpers\Validation::isJson($value)) { //json_decode should handle this, not sure it's needed
                $value = json_decode($value, true);
            }

            $method = Helpers\Entities::buildSetter($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            } elseif (property_exists($this, $key)) {
                $this->$key = $value;
            }
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
        foreach ($data as $k => $v) {
            if (is_null($v)) {
                unset($data[$k]);
                continue;
            }
            if (is_array($v)) {
                $v = json_encode($v);
            }
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
        foreach ($this->indexes as $index) {
            $this->indexDB->insert($index, [$this->getGuid() => $this->getGuid()]);
        }
    }
    
    /**
     * Export the entity
     * @param array $keys
     * @return array
     */
    public function export($keys = [])
    {
        $keys = array_merge($this->exportableDefaults, $keys);
        $export = [];
        foreach ($keys as $key) {
            $method = Helpers\Entities::buildGetter($key);
            if (method_exists($this, $method)) {
                $export[$key] = $this->$method();
            } elseif (property_exists($this, $key)) {
                $export[$key] = $this->$key;
            }

            if (is_object($export[$key]) && method_exists($export[$key], 'export')) {
                $export[$key] = $export[$key]->export();
            }
        }
        return $export;
    }
}
