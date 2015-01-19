<?php
/**
 * Neo4j client
 */
namespace Minds\Core\Data\Neo4j;

use Minds\Core\Data\Interfaces;

class Client implements Interfaces\ClientInterface{
    
    private $neo4j;
    private $prepared;
    
    public function __construct(array $options = array()){
        global $CONFIG;
        $this->neo4j = new \Everyman\Neo4j\Client(isset($CONFIG['neo4j_server']) ? $CONFIG['neo4j_server'] : NULL);
        
    }
    
    public function setPrepared(Interfaces\PreparedInterface $prepared){
        $this->prepared = $prepared;
        return $this;
    }
        
    public function request(Interfaces\PreparedInterface $request){
        $build = $request->build();
        $query = new \Everyman\Neo4j\Cypher\Query($this->neo4j, $build['string'], $build['values']);
        return $query->getResultSet();
    }
    
}    
