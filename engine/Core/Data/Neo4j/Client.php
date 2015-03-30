<?php
/**
 * Neo4j client
 */
namespace Minds\Core\Data\Neo4j;

use Neoxygen\NeoClient;
use Minds\Core\Data\Interfaces;

class Client implements Interfaces\ClientInterface{
    
    private $neo4j;
    private $prepared;
    
    public function __construct(array $options = array()){
        global $CONFIG;
        //$this->neo4j = new \Everyman\Neo4j\Client(isset($CONFIG->neo4j_server) ? $CONFIG->neo4j_server : NULL);
    	$this->neo4j = NeoClient\ClientBuilder::create()
    				->addConnection('default','http',isset($CONFIG->neo4j_server) ? $CONFIG->neo4j_server : 'localhost',7474, true, 'neo4j', 'Avitas@201')
                    ->setAutoFormatResponse(true)
                    ->setDefaultTimeout(20)
    				->build();
    }
    
    public function setPrepared(Interfaces\PreparedInterface $prepared){
        $this->prepared = $prepared;
        return $this;
    }
        
    public function request(Interfaces\PreparedInterface $request){
        $build = $request->build();
        $response = $this->neo4j->sendCypherQuery($build['string'], $build['values']);
        return $response;
    }
    
}    
