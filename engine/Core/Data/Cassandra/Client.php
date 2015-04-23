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
            'servers' => $CONFIG->cassandra->cql_servers,
        ), $options);

        $this->cassandra = new CassandraLibrary\Connection($options['servers'], $options['keyspace']);
    }
    
    public function request(Interfaces\PreparedInterface $request){
        $cql = $request->build();
        
        $prepared = $this->cassandra->prepare($cql['string']);
        $statement = $this->cassandra->executeAsync($prepared['id'], CassandraLibrary\Request\Request::strictTypeValues($cql['values'], $prepared['metadata']['columns']), \Cassandra\Request\Request::CONSISTENCY_ONE, array('names_for_values'=>true));
        
        $response = $statement->getResponse();
        
        if($response instanceof \Cassandra\Response\Error)
            throw new \Exception($response->getData());

        if($response->getData() && $cql['values'])
               return $response->fetchAll();
        else
            return true; //assume true because of no exception
        
        return $response;
    }

    public function batchRequest($requests = array()){

        $batchRequest = new CassandraLibrary\Request\Batch(CassandraLibrary\Request\Batch::TYPE_COUNTER, CassandraLibrary\Request\Request::CONSISTENCY_ONE);

        foreach($requests as $request){
            $cql = $request;
            $prepared = $this->cassandra->prepare($cql['string']);
            $batchRequest->appendQueryId($prepared['id'], CassandraLibrary\Request\Request::strictTypeValues($cql['values'], $prepared['metadata']['columns']));
        }
       
        $response = $this->cassandra->syncRequest($batchRequest);
        //return $response->fetchAll();
        
    }
    
}    
