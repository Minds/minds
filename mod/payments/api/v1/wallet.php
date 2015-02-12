<?php
/**
 * Minds Wallet API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\plugin\payments\api\v1;

use Minds\Core;
use minds\interfaces;
use minds\api\factory;

class wallet implements interfaces\api{

    /**
     * Returns the wallet info
     * @param array $pages
     * 
     * API:: /v1/wallet/:slug
     */      
    public function get($pages){
        
        $response = array();
        
        switch($pages[0]){
            
            case "count":
                $db=new Core\Data\Call('entities');
                $slice = $db->getRow(Core\session::getLoggedinUser()->guid, array("offset"=>"points_count", "limit"=>1));
                $count = isset($slice['points_count']) ? (int) $slice['points_count'] : 0;
                
                $satoshi_rate = 1;//@todo make this configurable for admins
                $satoshi = $count * $satoshi_rate;
                $btc = ($satoshi / 1000000000);
            
                $response['count'] = $count;
                $response['satoshi'] = $satoshi;
                $response['btc'] = sprintf('%.9f', $btc);
                $response['usd'] = round($count / 10000, 2);
                break;
                
            case "transactions":
                $entities = Core\entities::get(array('subtype'=>'points_transaction', 'owner_guid'=> Core\session::getLoggedinUser()->guid, 'limit'=>isset($_GET['limit']) ? $_GET['limit'] : 12, 'offset'=>isset($_GET['offset']) ? $_GET['offset'] : ""));
                $response['transactions'] = factory::exportable($entities);
                $response['load-next'] = end($entities)->guid;
                break;
                
        }
    
        return factory::response($response);
        
    }
    
    public function post($pages){
        
        switch($pages[0]){
            case "qoute":
                $ex_rate = 0.001;
                $points = $_POST['points'];
                $usd = $ex_rate * $points;
                return factory::response(array('usd'=>$usd));
                break;
            case "charge":
                
                $ex_rate = 0.001;
                $points = $_POST['points'];
                $usd = $ex_rate * $points; 
                
                $card = new \Minds\plugin\payments\entities\card();
                $card_obj = $card->create(array(
                    'type' => $_POST['type'],
                    'number' => (int) str_replace(' ', '', $_POST['number']),
                    'month' => $_POST['month'],
                    'year' => $_POST['year'],
                    'sec' => $_POST['sec'],
                    'name' => $_POST['name'],
                    'name2' => $_POST['name2']
                    ));

                $response['id'] = \Minds\plugin\payments\start::createPayment("$points purchase", $usd, $card->card_id);
                break;
        }
        

        return factory::response($response);
    }
    
    public function put($pages){
        
        return factory::response(array());
        
    }
    
    public function delete($pages){
        
        return factory::response(array());
        
    }
    
}
        
