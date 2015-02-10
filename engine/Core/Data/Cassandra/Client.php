<?php
/**
 * Cassandra client
 */
namespace Minds\Core\Data\Cassandra;

use Cassandra as CassandraLibrary;
use Minds\Core\Data\Interfaces;

class Client implements Interfaces\ClientInterface{
    
    private $cassandra;
    private $prepared;
    
    public function __construct(array $options = array()){
        global $CONFIG;
        $options = array_merge(array(
            'keyspace' => $CONFIG->cassandra->keyspace,
            'servers' => $CONFIG->cassandra->servers,
        ), $options);

        $this->cassandra = new CassandraLibrary\Connection($data_config['servers'], $options['keyspace']);
    }
    
    public function request(Interfaces\PreparedInterface $request){
        $cql = $request->build();
        
        $prepared = $this->cassandra->prepare($cql['string']);
        $statement = $this->cassandra->executeAsync($prepared['id'], CassandraLibrary\Request\Request::strictTypeValues($cql['prepared_data'], $prepared['metadata']['columns']), \Cassandra\Request\Request::CONSISTENCY_ONE);
        
        $response = $statement->getResponse();
        
        if($response instanceof \Cassandra\Response\Error)
            throw new \Exception($response->getData());

        if($response->getData())
               return $response->fetchAll();
        else
            return true; //assume true because of no exception
        
        return $response;
    }
    
}    
