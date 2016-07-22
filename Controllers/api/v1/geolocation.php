<?php
/**
 * Minds Geolocation API
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class geolocation implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    public function get($pages)
    {
        $config = Core\Di\Di::_()->get('Config');
        $googleConfig = $config->get('google');

        $url = "https://nominatim.openstreetmap.org/search.php?q=" . urlencode($_GET['q']) . "&format=json&addressdetails=1";
        $url = "http://open.mapquestapi.com/nominatim/v1/search.php?key=ohEcFAArFVNvzTlwGQS5C9XGkAZ4iW9p&format=json&q=" . urlencode($_GET['q']) . "&addressdetails=1";
        $url = "https://maps.googleapis.com/maps/api/geocode/json?key={$googleConfig['geolocation']}&address=" . urlencode($_GET['q']);


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
                                 
        $data = json_decode($output, true);

        $results = [];
        foreach ($data['results'] as $k => $item) {
            foreach ($item['address_components'] as $addr_line) {
                if ($addr_line['types'][0] == "locality" || $addr_line['types'][0] == "postal_town") {
                    $results[$k]['address']['city'] = $addr_line['long_name'];
                }
                if ($addr_line['types'][0] == "administrative_area_level_2" || $addr_line['types'][0] == "administrative_area_level_1") {
                    $results[$k]['address']['state'] = $addr_line['long_name'];
                }
                if ($addr_line['types'][0] == 'country') {
                    if ($results[$k]['address']['state']) {
                        $results[$k]['address']['state'] .= ", {$addr_line['long_name']}";
                    } else {
                        $results[$k]['address']['state'] = $addr_line['long_name'];
                    }
                }
            }
            if (!$results[$k]['address']['city']) {
                $results[$k]['address']['city'] = $results[$k]['address']['state'];
            }
            $results[$k]['lat'] = $item['geometry']['location']['lat'];
            $results[$k]['lon'] = $item['geometry']['location']['lng'];
        }


        switch ($pages[0]) {
            case 'list':
                return Factory::response(array('results'=>$results));
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
    
    public function post($pages)
    {
    }
    
    public function put($pages)
    {
    }
    
    public function delete($pages)
    {
    }
}
