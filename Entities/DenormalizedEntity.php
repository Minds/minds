<?php
namespace Minds\Entities;

use Minds\Core;
use Minds\Core\Data;
use Minds\Helpers;
use Minds\Traits;

/**
 * Denormalized Entity
 */
class DenormalizedEntity
{
    use Traits\Entity;

    protected $db;
    protected $rowKey;
    protected $guid;
    protected $exportableDefaults = [];

    public function __construct($db = null)
    {
        $this->db = $db ?: new Data\Call('entities_by_time');
    }

    /**
     * Set this entity's database store
     * @param object $db
     */
    public function setDb($db)
    {
        $this->db = $db;
        return $this;
    }

    /**
     * Set this entity's row key
     * @param string $key
     */
    public function setRowKey($key)
    {
        $this->rowKey = $key;
        return $this;
    }

    /**
     * Get this entity's row key
     * @return string|null
     */
    public function getRowKey()
    {
        return $this->rowKey;
    }

    /**
     * Load entity data from a GUID
     * @param  $guid
     * @return $this
     * @throws \Exception
     */
    public function loadFromGuid($guid)
    {
        $row = $this->db->getRow($this->rowKey, ['offset' => $guid, 'limit'=>1]);
        if (!$row || !isset($row[$guid])) {
            throw new \Exception("Entity not found");
        }

        $this->guid = $guid;

        return $this->loadFromArray($row[$guid]);
    }

    /**
     * Load entity data from an array
     * @param  $array
     * @return $this
     */
    public function loadFromArray($array)
    {
        $array = is_array($array) ? $array : json_decode($array, true);
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
     * Save the denormalized entity to the database
     * @param  array $data
     * @return bool
     */
    protected function saveToDb($data)
    {
        return (bool) $this->db->insert($this->rowKey, [$this->guid => json_encode($data)]);
    }

    /**
     * Delete the denormalized entity
     * @return bool
     */
    public function delete()
    {
        return (bool) $this->db->removeAttributes($this->rowKey, [$this->guid]);
    }

    /**
     * Magic getter.
     * @return string|void
     */
    public function __get($name)
    {
        if ($name == 'guid') {
            return $this->getGuid();
        }
    }

    /**
     * Export the entity onto an array
     * @param  array $keys
     * @return array
     */
    public function export(array $keys = [])
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
