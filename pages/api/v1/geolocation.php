<?php
/**
 * Minds Geolocation API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class geolocation implements Interfaces\api, Interfaces\ApiIgnorePam{

    public function get($pages){

        $url = "http://nominatim.openstreetmap.org/search.php?q=" . urlencode($_GET['q']) . "&format=json&addressdetails=1";
                 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $output = curl_exec($ch); 
        curl_close($ch);  
                                 
        $data = json_decode($output, true);

        switch($pages[0]){
            case 'list':
                return Factory::response(array('results'=>$data));
                break;
            default:
                $city = isset($data[0]['address']['city']) ? $data[0]['address']['city'] : $data[0]['address']['town'];
                $coorinates = $data[0]['lat'] . ',' . $data[0]['lon'];

                return Factory::response(array(
                    'city' => $city,
                    'coordinates' => $coorinates
                ));
        }
    }
    
    public function post($pages){
    }
    
    public function put($pages){
    }
    
    public function delete($pages){
    }
    
}
        
