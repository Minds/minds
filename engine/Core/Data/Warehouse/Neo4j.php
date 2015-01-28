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
        switch($slugs[0]){
	        case 'users':
                $this->syncUsers();
	        break;
            case 'subscriptions':
		        error_log('Sorry, please transfer users first..');    
	        break;
            case 'videos':
                $this->syncVideos();
            break;
            default:
                $this->syncUsers();
	            $this->syncVideos();
	    }
	
        
        
        return $this;
    } 

    /**
     * Sync users, with their subscriptions
     */
    public function syncUsers(){
        $subscriptions = new \Minds\Core\Data\Call('friends');
        $prepared = new Prepared\Common();
        $offset = '';
        while(true){
            error_log("Syncing 250 users from $offset");
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
    }

    /**
     * sync videos
     * @return void
     */
    public function syncVideos(){
        $prepared = new Prepared\Common();
        //transfer over videos
        $offset = "";
        while(true){
            error_log("Syncing 250 videos from $offset");
            $videos = core\entities::get(array('subtype'=>'video', 'offset'=>$offset, 'limit'=>250));
            if(!is_array($videos) || end($videos)->guid == $offset)
                break;
            $offset = end($videos)->guid;
            $this->client->request($prepared->createBulkObjects($videos, 'video'));
            error_log("Imported videos");
        }
    }

    /**
     * sync users
     * @param array (optional) $users
     * @return void
     */
    public function syncThumbs($users = NULL){
        $indexes = new Core\Data\Call('entities_by_time');
        if(!$users && !is_array($users)){
            while(true){

            }
        }
    
        foreach($users as $user){
            $thumbs_up = array();
        }

    }
}   
