<?php

namespace minds\plugin\social\pages;

use Minds\Core;
use Minds\Interfaces;
use minds\plugin\social\services;

class redirect extends core\page implements Interfaces\page
{
        
    /**
     * Get requests
     */
    public function get($pages)
    {
        if (!isset($pages[0])) {
            return false;
        }

        if ($_REQUEST['access_token']) {
            $_SESSION['user'] = core\Session::getLoggedinUser(); //hate this hack..
        }

        try {
            $service = services\build::build($pages[0]);
        } catch (\Exception $e) {
            return false;
        }
        
        $service->authorizeCallback();
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
