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
    	
        $servers = isset($CONFIG->neo4j_servers) ?  $CONFIG->neo4j_servers : array('default'=>array('address'=>'localhost', 'port'=>7474, 'password' => ''));

        $builder = NeoClient\ClientBuilder::create();

        foreach($servers as $id => $config){
            $builder->addConnection($id,'http', $config['address'], isset($config['port']) ? $config['port'] : 7474, true, 'neo4j', $config['password']);
            if(isset($config['master']) && $config['master'])
                $builder->setMasterConnection($id);
            if(!isset($config['salve']) || $config['slave'])
                $builder->setSlaveConnection($id);
        }

        $this->neo4j = $builder->registerExtension('geo', 'Minds\Core\Data\Neo4j\Extensions\Geo')
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

    public function client($command){
         return $this->neo4j;
    }

}    
