<?php
/**
 * Minds Geolocation API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1;

use Minds\Core;
use minds\entities;
use minds\interfaces;
use Minds\Api\Factory;

class geolocation implements interfaces\api, interfaces\ApiIgnorePam{

    public function get($pages){
        
        $url = "http://nominatim.openstreetmap.org/search.php?q=" . urlencode($_GET['q']) . "&format=json&addressdetails=1";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $output = curl_exec($ch); 
        curl_close($ch);  
        
        $data = json_decode($output, true);
        var_dump($data[0]);
        
        $city = isset($data[0]['address']['city']) ? $data[0]['address']['city'] : $data[0]['address']['town'];
        $coorinates = $data[0]['lat'] . ',' . $data[0]['lon'];

        return Factory::response(array(
            'city' => $city,
            'coorinates' => $coorinates
        ));
    }
    
    public function post($pages){
    }
    
    public function put($pages){
    }
    
    public function delete($pages){
    }
    
}
        
