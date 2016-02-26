<?php
/**
 * Cassandra user index to guid wrapper
 */
namespace Minds\Core\Data\Cassandra\Thrift;

class Relationships
{
    protected $db;
    protected $guid;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Sets the working GUID
     * @param string $guid
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * Creates a relationship between working GUID and another GUID
     * @param  string $rel
     * @param  string $guid
     * @return boolean
     */
    public function create($rel, $guid)
    {
        if (!$this->guid) {
            throw new \Exception('Source guid must not be empty');
        }

        if (!$rel) {
            throw new \Exception('Relationship must not be empty');
        }

        if (!$guid) {
            throw new \Exception('Guid must not be empty');
        }

        $result = $this->db->insert("{$this->guid}:{$rel}", [ $guid => time() ]);

        if (!$result) {
            return false;
        }

        $result = $this->db->insert("{$guid}:{$rel}:inverted", [ $this->guid => time() ]);

        return (bool) $result;
    }

    /**
     * Gets GUIDs for a certain relationship for working GUID
     * @param  string $rel
     * @param  array $opts
     * @return mixed
     */
    public function get($rel, array $opts = [])
    {
        $opts = array_merge([
            'inverse' => false
        ], $opts);

        if (!$this->guid) {
            throw new \Exception('Source guid must not be empty');
        }

        if (!$rel) {
            throw new \Exception('Relationship must not be empty');
        }

        $inverse_keyword = $opts['inverse'] ? ':inverted' : '';

        $rows = $this->db->getRow("{$this->guid}:{$rel}{$inverse_keyword}", $opts);

        if (!$rows) {
            return false;
        }

        return array_keys($rows);
    }

    /**
     * Removes a relationship between working GUID and another GUID
     * @param  string $rel
     * @param  string $guid
     * @return boolean
     */
    public function remove($rel, $guid)
    {
        if (!$this->guid) {
            throw new \Exception('Source guid must not be empty');
        }

        if (!$rel) {
            throw new \Exception('Relationship must not be empty');
        }

        if (!$guid) {
            throw new \Exception('Guid must not be empty');
        }

        $result = $this->db->removeAttributes("{$this->guid}:{$rel}", [ $guid ]);

        if ($result === false) {
            return false;
        }

        $result = $this->db->removeAttributes("{$guid}:{$rel}:inverted", [ $this->guid ]);

        return $result !== false;
    }

    /**
     * Checks if a relationship between working GUID and another GUID exists
     * @param  string $rel
     * @param  string $guid
     * @return boolean
     */
    public function check($rel, $guid)
    {
        if (!$this->guid) {
            throw new \Exception('Source guid must not be empty');
        }

        if (!$rel) {
            throw new \Exception('Relationship must not be empty');
        }

        if (!$guid) {
            throw new \Exception('Guid must not be empty');
        }

        $result = $this->db->getRow("{$this->guid}:{$rel}", [
            'offset' => $guid,
            'limit' => 1
        ]);

        if (isset($result[$guid])) {
            return true;
        }

        $result = $this->db->getRow("{$guid}:{$rel}:inverted", [
            'offset' => $this->guid,
            'limit' => 1
        ]);

        return isset($result[$this->guid]);
    }

    /**
     * Counts the GUIDs on a working GUID's relationship
     * @param  string  $rel
     * @param  boolean $inverse
     * @return integer
     */
    public function count($rel, $inverse = false)
    {
        if (!$this->guid) {
            throw new \Exception('Source guid must not be empty');
        }

        if (!$rel) {
            throw new \Exception('Relationship must not be empty');
        }

        $inverse_keyword = $inverse ? ':inverted' : '';

        return $this->db->countRow("{$this->guid}:{$rel}{$inverse_keyword}");
    }

    /**
     * Readable shortcut for ->count(guid, true)
     * @param  string $rel
     * @return integer
     */
    public function countInverse($rel)
    {
        return $this->count($rel, true);
    }

    /**
     * Destroys a relationship
     * @param  string  $rel
     * @return boolean
     */
    public function destroy($rel, array $opts = [])
    {
        $opts = array_merge([
            'inverse' => false
        ], $opts);

        $this_inverse_keyword = $opts['inverse'] ? ':inverted' : '';
        $rel_inverse_keyword = $opts['inverse'] ? '' : ':inverted';

        $offset = '';

        while (true) {

            $guids = $this->db->getRow("{$this->guid}:{$rel}{$this_inverse_keyword}", [
                'limit' => 500,
                'offset' => $offset
            ]);

            if (!$guids) {
                break;
            }

            $guids = array_keys($guids);

            if ($offset) {
                array_shift($guids);
            }

            if (!$guids) {
                break;
            }

            if ($guids[0] == $offset) {
                break;
            }

            $offset = end($guids);

            foreach ($guids as $guid) {
                $this->db->removeRow("{$guid}:{$rel}{$rel_inverse_keyword}");
            }

            if (!$offset) {
                break;
            }

        }
        return $this->db->removeRow("{$this->guid}:{$rel}{$this_inverse_keyword}");
    }
}
