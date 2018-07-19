<?php
/**
 * API for enabling/disabling cookies
 */
namespace Minds\Controllers\api\v2;

use Minds\Api\Factory;
use Minds\Common\Cookie;
use Minds\Core\Config;
use Minds\Core\Session;
use Minds\Interfaces;

class cookies implements Interfaces\Api
{

    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        return $this->delete($pages);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        if (Session::isLoggedin()) {
            return Factory::response([
                'status' => 'failed',
                  'message' => "You can't disable cookies if you're logged in!"
            ]);
        }
        
        $cookie = new Cookie();
        $cookie
            ->setName('disable_cookies')
            ->setValue(1)
            ->setExpire(0)
            ->setPath('/')
            //->setDomain(Config::_()->get('site_url'))
            ->setHttpOnly(false)
            ->create();

        foreach ($_COOKIE as $key => $value) {
            $cookie = new Cookie();
            $cookie
                ->setName($key)
                ->setvValue('')
                ->setExpire(time() - 3600)
                ->setPath('/')
                ->create(); 
        }
        
        return Factory::response(['status' => 'success']);
    }
    
}

