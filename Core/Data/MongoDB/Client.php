<?php
/**
 * MongoDB client
 */
namespace Minds\Core\Data\MongoDB;

use Minds\Core\Data\Interfaces;
use MongoDB\Client as MongoClient;

class Client implements Interfaces\ClientInterface
{
    private $mongodb;
    private $prepared;
    private $db_name = 'minds';

    public function __construct(array $options = [])
    {
        global $CONFIG;

        if (!class_exists('\MongoDB\Driver\Manager')) {
            throw new \Exception("MongoDB Driver is not installed");
        }

        if (!class_exists('\MongoDB\Client')) {
            throw new \Exception("MongoDB Client is not installed");
        }

        $servers = isset($CONFIG->mongodb_servers) ?  $CONFIG->mongodb_servers : ['127.0.0.1'];
        $servers = implode(',', $servers);
        $options = [];
        $driverOptions = [];

        if (isset($CONFIG->mongodb_db)) {
            $this->db_name = $CONFIG->mongodb_db;
        }

        if (isset($CONFIG->mongodb_options) && is_array($CONFIG->mongodb_options)) {
            $options = $CONFIG->mongodb_options;
        }

        if (isset($CONFIG->mongodb_driver_options) && is_array($CONFIG->mongodb_driver_options)) {
            $driverOptions = $CONFIG->mongodb_driver_options;
        }

        try {
            if (!$this->mongodb) {
                $this->mongodb = new MongoClient("mongodb://{$servers}/", $options, $driverOptions);
            }
        } catch (\Exception $e) {
            error_log("MongoDB Connection [" . get_class($e) . "]: {$e->getMessage()}");
        }
    }

    public function client()
    {
        return $this->mongodb;
    }

    /**
     * Insert into MongoDB
     * @param string $table
     * @param array $data
     * @return string $_id
     */
    public function insert($table, array $data = [])
    {
        if (!$this->mongodb) {
            error_log("MongoDB Insert: No connection");
            return false;
        }

        try {
            return $this->mongodb
                ->selectCollection($this->db_name, $table)
                ->insertOne($data);
        } catch (\Exception $e) {
            error_log("MongoDB Insert [" . get_class($e) . "]: {$e->getMessage()}");
        }

        return false;
    }

    /**
     * Update MongoDB
     *
     * @param string $table
     * @param array $query
     * @param array $data
     * @return string $_id
     */
    public function update($table, array $query = [], array $data = [])
    {
        if (!$this->mongodb) {
            error_log("MongoDB Update: No connection");
            return false;
        }

        try {
            return $this->mongodb
                ->selectCollection($this->db_name, $table)
                ->updateMany($query, [ '$set' => $data ], [ 'upsert' => true ]);
        } catch (\Exception $e) {
            error_log("MongoDB Update [" . get_class($e) . "]: {$e->getMessage()}");
        }

        return false;
    }

    /**
     * Find records from MongoDB
     * @param string $table
     * @param array $query
     * @return array of result
     */
    public function find($table, array $query = [], array $options = [])
    {
        if (!$this->mongodb) {
            error_log("MongoDB Find: No connection");
            return false;
        }

        try {
            $options = array_merge($options, []);

            return $this->mongodb
                ->selectCollection($this->db_name, $table)
                ->find($query, $options);
        } catch (\exception $e) {
            error_log("MongoDB Find [" . get_class($e) . "]: {$e->getMessage()}");
        }

        return false;
    }

    /**
     * Remove a record from MongoDB
     * @param string $table
     * @param array $query
     * @return boolean
     */
    public function remove($table, array $query = [])
    {
        if (!$this->mongodb) {
            error_log("MongoDB Remove: No connection");
            return false;
        }

        return $this->mongodb
            ->selectCollection($this->db_name, $table)
            ->deleteMany($query);
    }

    /**
     * Count from MongoDB
     * @param string $table
     * @param array $query
     * @return array of result
     */
    public function count($table, array $query = [])
    {
        if (!$this->mongodb) {
            error_log("MongoDB Count: No connection");
            return false;
        }

        return $this->mongodb
            ->selectCollection($this->db_name, $table)
            ->count($query);
    }
}
