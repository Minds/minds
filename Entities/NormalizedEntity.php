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

    protected $db;
    protected $indexDB;
    protected $guid;
    private $indexes = [];
    protected $exportableDefaults = [];

    public function __construct($db = null, $indexDb = null)
    {
        $this->db = $db ?: new Data\Call('entities');
        $this->indexDb = $indexDb ?: Core\Di\Di::_()->get('Cassandra\Thrift\Indexes');
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
     * Gets `guid`
     * @return mixed
     */
    public function getGuid()
    {
        if(!$this->guid){
            $this->guid = Core\Guid::build();
        }
        return (string) $this->guid;
    }

    /**
     * Feature
     */
    public function feature()
    {
        //@todo check if the entity is allowed to be featured
        $this->featured_id = Core\Guid::build();

    		$this->indexDb->set("$this->type:featured", [ $this->featured_id => $this->getGUID() ]);
        if($this->subtype){
    		    $this->indexDb->set("$this->type:$this->subtype:featured", [ $this->featured_id => $this->getGUID() ]);
        }

    		$this->featured = 1;
    		$this->save();

    		return $this->featured_id;
    }

    /**
     * Un-Feature
     */
    public function unFeature()
    {
        if($this->featured_id){
          //supports legacy imports
          $this->indexDb->remove("$this->type:featured", [ $this->featured_id ]);
          $this->indexDb->remove("$this->type:$this->subtype:featured", [ $this->featured_id ]);
          $this->featured_id = null;
        }

        $this->featured = 0;
        $this->save();

        $this->db->removeAttributes($this->guid, [ 'featured_id' ]);

        return true;
    }

    /**
     * Auto set/get hanlders
     */
    public function __call($name, array $args = [])
    {
        if(strpos($name, 'set', 0) === 0){
            $attribute = str_replace('set', '', $name);
            $attribute = lcfirst($attribute);
            $this->$attribute = $args[0];
            return $this;
        }
        if(strpos($name, 'get', 0) === 0){
            $attribute = str_replace('get', '', $name);
            $attribute = lcfirst($attribute);
            return $this->$attribute;
        }
        return $this;
    }

    /**
     * Export the entity
     * @param array $keys
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
