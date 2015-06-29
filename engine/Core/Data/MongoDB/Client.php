<?php
/**
 * MongoDB client
 */
namespace Minds\Core\Data\MongoDB;

use Minds\Core\Data\Interfaces;
use MongoClient;
use MongoCollection;
use MongoId;

class Client implements Interfaces\ClientInterface{
    
    private $mongodb;
    private $prepared;
    private $db_name = 'minds';
    
    public function __construct(array $options = array()){
        global $CONFIG;

        if(!class_exists('\MongoClient'))
            throw new \Exception("Mongo is not installed");
    	
        $servers = isset($CONFIG->mongodb_servers) ?  $CONFIG->mongodb_servers : array('127.0.0.1');
        $servers = implode(',', $servers);
        
        if(isset($CONFIG->mongodb_db)){
            $this->db_name = $CONFIG->mongodb_db;
        }
        
        try{
            if(!$this->mongodb)
                $this->mongodb = new MongoClient($servers);
        } catch(\Exception $e){
            error_log("MongoDB Connection: " . $e->getMessage());
        }
    }

    public function client(){
        
    }
    
    /**
     * Insert into MongoDB
     * @param string $table
     * @param array $data
     * @return string $_id
     */    
    public function insert($table, $data = array()){
        if(!$this->mongodb)
            return false;
        try{
            $collection = new MongoCollection($this->mongodb->selectDB($this->db_name), $table);
            return $collection->insert($data);
        } catch (\Exception $e){
           error_log("MongoDB Insert: " . $e->getMessage());
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
    public function update($table, $query = array(), $data = array()){
        if(!$this->mongodb)
            return false;
        try{
            $collection = new MongoCollection($this->mongodb->selectDB($this->db_name), $table);
            if(isset($query['_id']))
                $query['_id'] = new MongoId($query['_id']);

            return $collection->update($query, array('$set' => $data), array("upsert" => true));
        }catch(\Exception $e){
            error_log("MongoDB Update: " . $e->getMessage());
        }
        return false;
    }

    /**
     * Find records from MongoDB
     * @param string $table
     * @param array $query
     * @return array of result
     */
    public function find($table, $query = array(), $projections = array()){
        if(!$this->mongodb)
            return false;
        try{
            $collection = new MongoCollection($this->mongodb->selectDB($this->db_name), $table);
            if(isset($query['_id']) && isset($query['_id']['$gt']))
                $query['_id']['$gt'] = new MongoId($query['_id']['$gt']);
            elseif(isset($query['_id']) && is_string($query['_id']))
                $query['_id'] = new MongoId($query['_id']);

            $projections = array_merge($projections, array());
            return $collection->find($query, $projections);
        }catch(\exception $e){
           error_log("MongoDB Find: " . $e->getMessage());
        }
        return false;
    }

    /**
     * Remove a record from MongoDB
     * @param string $table
     * @param array $query
     * @return boolean
     */
    public function remove($table, $query = array()){
        if(!$this->mongodb)
            return false;
         $collection = new MongoCollection($this->mongodb->selectDB($this->db_name), $table);
         if(isset($query['_id']))
            $query['_id'] = new MongoId($query['_id']);
         return $collection->remove($query);
    }    

    /**
     * Count from MongoDB
     * @param string $table
     * @param array $query
     * @return array of result
     */
    public function count($table, $query = array()){
        if(!$this->mongodb)
            return false;
        $collection = new MongoCollection($this->mongodb->selectDB($this->db_name), $table);
        return $collection->count($query);
    }
}    
