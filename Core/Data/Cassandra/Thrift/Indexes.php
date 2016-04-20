<?php
/**
 * Cassandra entities by time wrapper
 */
namespace Minds\Core\Data\Cassandra\Thrift;

class Indexes
{
    use Namespaced;

    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Appends a new index entry GUID
     * @param string $name
     * @param array  $guids
     */
    public function set($name, array $guids = [])
    {
        if (!$name) {
            throw new \Exception('Missing index name');
        }

        return $this->db->insert($this->buildNamespace($name), $guids);
    }

    /**
     * Retrieves an index entry GUIDs
     * @param  string $name
     * @param  array $opts
     * @return mixed
     */
    public function get($name, array $opts = [])
    {
        $opts = array_merge([
            'limit' => 12,
            'offset' => '',
            'reversed' => true
        ], $opts);

        if (!$name) {
            throw new \Exception('Missing index name');
        }

        try {
            return $this->db->getRow($this->buildNamespace($name), $opts);
        } catch (\Exception $e) {
            error_log('Indexes->get(): ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Counts index entry GUIDs
     * @param  string $name
     * @return number
     */
    public function count($name)
    {
        if (!$name) {
            throw new \Exception('Missing index name');
        }

        try {
            return (int) $this->db->countRow($this->buildNamespace($name));
        } catch (\Exception $e) {
            error_log('Indexes->count(): ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Removes a index entry GUIDs
     * @param  string $name
     * @param  array $guids
     * @return boolean
     */
    public function remove($name, array $guids = [])
    {
        if (!$name) {
            throw new \Exception('Missing index name');
        }

        if (!$guids) {
            return false;
        }

        $this->db->removeAttributes($this->buildNamespace($name), $guids);

        return true;
    }
}
