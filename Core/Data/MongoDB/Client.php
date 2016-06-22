<?php
/**
 * MongoDB client
 */
namespace Minds\Core\Data\MongoDB;

use Minds\Core\Data\Interfaces;
use MongoDB\BSON\ObjectID;
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
            $result = $this->mongodb
                ->selectCollection($this->db_name, $table)
                ->insertOne($data);

            return $result->getInsertedId();
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

        $query = $this->parseQueryObjectIds($query);

        try {
            $result = $this->mongodb
                ->selectCollection($this->db_name, $table)
                ->updateOne($query, [ '$set' => $data ], [ 'upsert' => true ]);

            return $result->getMatchedCount();
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

        $query = $this->parseQueryObjectIds($query);

        try {
            $options = array_merge($options, []);

            if (isset($options['limit'])) {
                $options['limit'] = intval($options['limit']);
            }

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

        $query = $this->parseQueryObjectIds($query);

        $result = $this->mongodb
            ->selectCollection($this->db_name, $table)
            ->deleteOne($query);

        return $result->getDeletedCount();
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

        $query = $this->parseQueryObjectIds($query);

        return $this->mongodb
            ->selectCollection($this->db_name, $table)
            ->count($query);
    }

    /**
     * Extracts the timestamp of a document from its time_created or _id fields
     * @param mixed $document
     * @return int
     */
    public function getDocumentTimestamp($document)
    {
        if (isset($document['time_created'])) {
            return intval($document['time_created']);
        } elseif (isset($document['_id']) && $document['_id'] instanceof ObjectId) {
            $oid = (string) $document['_id'];
            return base_convert(substr($oid, 0, 8), 16, 10);
        }

        return 0;
    }

    /**
     * Tries to create an ObjectId on _id query fields
     * @param array $query
     * @return array of modified $query
     */
    public function parseQueryObjectIds($query)
    {
        if (!isset($query['_id'])) {
            return $query;
        }

        if (is_string($query['_id']) && $query['_id']) {
            try {
                $query['_id'] = new ObjectId($query['_id']);
            } catch (\Exception $e) { /* No conversion */ }
        } elseif (is_array($query['_id'])) {
            array_walk($query['_id'], function(&$value) {
                if (is_string($value) && $value) {
                    try {
                        $value = new ObjectId($value);
                    } catch (\Exception $e) { /* No conversion */ }
                }
            });
        }

        return $query;
    }
}
