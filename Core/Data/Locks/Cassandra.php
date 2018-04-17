<?php


namespace Minds\Core\Data\Locks;


use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;

class Cassandra
{
    /** @var Client */
    protected $db;

    protected $key;
    protected $ttl;

    public function __construct($db = null)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Cql');
    }

    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    public function setTTL($ttl)
    {
        $this->ttl = $ttl;
        return $this;
    }

    public function isLocked() {
        if (!isset($this->key)) {
            throw new KeyNotSetupException();
        }

        $query = new Custom();

        $query->query('SELECT * from locks where key = ?', [$this->key]);
        $result = $this->db->request($query);
        return isset($result[0]);
    }

    public function lock()
    {
        if (!isset($this->key)) {
            throw new KeyNotSetupException();
        }
        $template = 'INSERT INTO locks(key) values(?) IF NOT EXISTS';
        $values = [$this->key];

        if (isset($this->ttl)) {
            $template .= ' USING TTL ?';
            $values[] = $this->ttl;
        }
        $query = new Custom();

        $query->query($template, $values);
        $result = $this->db->request($query);

        if (!$result) {
            throw new LockFailedException();
        }
        return $result;
    }

    public function unlock()
    {
        if (!isset($this->key)) {
            throw new KeyNotSetupException();
        }
        $query = new Custom();
        $query->query('DELETE FROM locks where key = ? IF EXISTS', [
            $this->key
        ]);

        $result = $this->db->request($query);
        return $result;
    }
}
