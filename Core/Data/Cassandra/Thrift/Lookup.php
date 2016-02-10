<?php
/**
 * Cassandra user index to guid wrapper
 */
namespace Minds\Core\Data\Cassandra\Thrift;

class Lookup
{
    use Namespaced;
    
    protected $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    /**
     * Appends a new lookup entry column
     * @param string $name
     * @param mixed  $values
     */
    public function set($name, $values)
    {
        if (!$name) {
            throw new \Exception('Missing lookup name');
        }
        
        if (!is_array($values)) {
            $values = [ $values ];
        }
        
        return $this->db->insert($this->buildNamespace($name), $values);
    }
    
    /**
     * Retrieves a lookup entry columns
     * @param  string $name
     * @param  array $opts
     * @return mixed
     */
    public function get($name, array $opts = [])
    {
        $opts = array_merge([
            // TODO: [emi] default options
        ], $opts);
        
        if (!$name) {
            throw new \Exception('Missing lookup name');
        }
        
        try {
            return $this->db->getRow($this->buildNamespace($name), $opts);
        } catch (\Exception $e) {
            error_log('Lookup->get(): ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Destroy lookup entry
     * @param  string $name
     * @return boolean
     */
    public function remove($name)
    {
        if (!$name) {
            throw new \Exception('Missing lookup name');
        }
        
        $this->db->removeRow($this->buildNamespace($name));
        
        return true;
    }
    
    /**
     * Removes a lookup entry column
     * @param  string $name
     * @param  string $column
     * @return boolean
     */
    public function removeColumn($name, $column)
    {
        if (!$name) {
            throw new \Exception('Missing lookup name');
        }
        
        if (!$column) {
            return false;
        }
        
        $this->db->removeAttributes($this->buildNamespace($name), [ $column ]);
        
        return true;
    }
}
