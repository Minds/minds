<?php
/**
 * Neo4j data warehouse
 */
 
namespace Minds\Core\Data\Warehouse;

use Minds\Core;
use Minds\Core\Data\Interfaces;
use Minds\Core\Data\Neo4j\Prepared;

class Neo4j implements Interfaces\WarehouseJobInterface{

    private $client;
 
    /**
     * Run the job
     * @return void
     */
    public function run(array $slugs = array()){
        $this->client = \Minds\Core\Data\Client::build('neo4j');
        switch($slugs[0]){
            case 'sync':
                array_shift($slugs);
                $this->sync($slugs);
                break;
        }
    }
    
    /**
     * Syncronise the data
     */
    public function sync($slugs = array()){
        $prepared = new Prepared\Subscriptions();
        
        $subscriptions = new \Minds\Core\Data\Call('friends');
        
        //transfer over all user
        $offset = '';
        while(true){
            $users = core\entities::get(array('type'=>'user', 'offset'=>$offset, 'limit'=>50));
            if(!is_array($users) || end($users)->guid == $offset)
                break;
            $offset = end($users)->guid;
            $this->client->request($prepared->createBulkUsers($users));
            
            
            $guids = array();
            foreach($users as $user){
                $guids[] = $user->guid;
            }
            $this->client->request($prepared->createBulkSubscriptions($subscriptions->getRows($guids)));
            break;
            exit;
        }
        
        
        
        return $this;
    } 
}   