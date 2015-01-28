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
        $this->client = \Minds\Core\Data\Client::build('Neo4j');
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
        $prepared = new Prepared\Common();
        
        $subscriptions = new \Minds\Core\Data\Call('friends');
        
        //transfer over all user
        $offset = '';
        while(true){
            error_log("Syncing 50 entities from $offset");
	    $users = core\entities::get(array('type'=>'user', 'offset'=>$offset, 'limit'=>250));
            if(!is_array($users) || end($users)->guid == $offset)
                break;
            $offset = end($users)->guid;
            $this->client->request($prepared->createBulkUsers($users));
            error_log("Imported users");
            $guids = array();
            foreach($users as $user){
                $guids[] = $user->guid;
            }
            $this->client->request($prepared->createBulkSubscriptions($subscriptions->getRows($guids)));
	    error_log("Imported subscriptions");
           // break;
           // exit;
        }
        
        
        
        return $this;
    } 
}   
