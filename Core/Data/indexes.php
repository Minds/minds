<?php
/**
 * The indexes helper function
 *
 */

namespace Minds\Core\Data;

use Minds\Core;

class indexes
{
    private $db;
    private $namespace = '';

    public function __construct($namespace = null)
    {
        $this->db = new Call('entities_by_time');

        if ($namespace) {
            $this->setNamespace($namespace);
        }
    }

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace . ':';
    }

    public function set($name, array $guids = array())
    {
        return $this->insert($this->namespace.$name, $guids);
    }

        /**
     * Get from the index (returns uuids)
     *
     * @param string/int $key_id - the ID for the row to return
     * @param array $options - limit, offset, reversed
     */
    public function get($key_id, array $options = array('limit'=>12, 'offset'=>'','reversed'=>true))
    {
        try {
            return $this->db->getRow($this->namespace.$key_id, $options);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function remove($key, array $guids = array())
    {
        $this->db->removeAttributes($this->namespace.$key, $guids);
    }

    /**
     * Pass other functions back to Call (its a hack)
     * @param $name
     * @param $args
     * @return mixed
     */
    public function __call($name, $args) { 
        return $this->db->$name(...$args);
    }

    /**
     * Static methods
     */
     public static function fetch($key, array $options = array('limit'=>12, 'offset'=>'','reversed'=>true))
     {
         $db = new Call('entities_by_time');
         try {
             return $db->getRow($key, $options);
         } catch (\Exception $e) {
             return false;
         }
     }
}
