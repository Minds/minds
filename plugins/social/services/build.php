<?php
/**
 * Social
 */

namespace minds\plugin\social\services;

use Minds\Components;
use Minds\Core;

class build extends Components\Plugin
{
    public static function build($service, $params = array())
    {
        $class_name = "minds\\plugin\\social\\services\\$service";
        if (!class_exists($class_name)) {
            throw new \Exception('Service does not exists');
        }
        
        $class = new $class_name($params);
        if (isset($params['access_token'])) {
            $class->access_token = $params['access_token'];
        }
        return $class;
    }
}
